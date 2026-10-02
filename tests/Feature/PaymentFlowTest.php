<?php

namespace Tests\Feature;

use App\Models\Conference;
use App\Models\ConferenceAttendanceOption;
use App\Models\ConferencePaymentMethod;
use App\Models\ConferenceRegistrationType;
use App\Models\ConferenceSetting;
use App\Models\Participant;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PaymentFlowTest extends TestCase
{
    use RefreshDatabase;

    private function createOpenConference(): Conference
    {
        $conference = Conference::create([
            'name' => 'Test Conference',
            'short_name' => 'TEST',
            'year' => 2026,
            'theme' => 'Test Theme',
            'venue' => 'Online Conference',
            'city' => 'Slawi',
            'country' => 'Indonesia',
            'start_date' => '2026-12-20',
            'end_date' => '2026-12-20',
            'abstract_deadline' => '2026-12-10',
            'fullpaper_deadline' => '2026-12-15',
            'registration_deadline' => '2026-12-31',
            'status' => 'registration_open',
        ]);

        ConferenceSetting::create([
            'conference_id' => $conference->id,
            'is_active' => true,
            'registration_enabled' => true,
            'submission_enabled' => true,
            'payment_enabled' => true,
            'review_enabled' => true,
            'certificate_enabled' => true,
            'published' => true,
            'maintenance_mode' => false,
            'review_mode' => 'open',
        ]);

        ConferenceAttendanceOption::create([
            'conference_id' => $conference->id,
            'type' => 'online',
            'sort_order' => 1,
        ]);

        return $conference;
    }

    private function createPresenterRegistrationType(
        Conference $conference
    ): ConferenceRegistrationType {
        return ConferenceRegistrationType::create([
            'conference_id' => $conference->id,
            'name' => 'Presenter Bhamada',
            'code' => 'presenter_bhamada',
            'category' => 'presenter',
            'payment_timing' => 'immediate',
            'fee' => 250000,
            'included_papers' => 1,
            'additional_paper_fee' => 0,
            'currency' => 'IDR',
            'description' => 'Test presenter registration type.',
            'benefits' => 'Test presenter benefits.',
            'is_active' => true,
            'sort_order' => 1,
        ]);
    }

    private function createPaymentMethod(
        Conference $conference
    ): ConferencePaymentMethod {
        return ConferencePaymentMethod::create([
            'conference_id' => $conference->id,
            'type' => 'bank_transfer',
            'name' => 'Bank Transfer',
            'provider' => 'BRI',
            'account_number' => '1234567890',
            'account_name' => 'Test Conference',
            'currency' => 'IDR',
            'instructions' => 'Transfer to the test account.',
            'is_active' => true,
            'sort_order' => 1,
        ]);
    }

    private function createPresenterParticipant(
        Conference $conference,
        ConferenceRegistrationType $registrationType,
        User $user
    ): Participant {
        return Participant::create([
            'user_id' => $user->id,
            'conference_id' => $conference->id,
            'registration_type_id' => $registrationType->id,
            'registration_number' => 'REG-' . $user->id,
            'full_name' => $user->name,
            'email' => $user->email,
            'country' => 'Indonesia',
            'attendance_type' => 'online',
            'participant_type' => 'presenter',
            'registration_status' => 'pending',
            'registered_at' => now(),
        ]);
    }

    public function test_presenter_can_submit_payment_proof(): void
    {
        Storage::fake('local');

        $conference = $this->createOpenConference();

        $registrationType = $this->createPresenterRegistrationType(
            $conference
        );

        $paymentMethod = $this->createPaymentMethod(
            $conference
        );

        $user = User::factory()->create([
            'role' => 'participant',
            'status' => 'active',
        ]);

        $participant = $this->createPresenterParticipant(
            $conference,
            $registrationType,
            $user
        );

        $proofFile = UploadedFile::fake()->create(
            'payment-proof.pdf',
            100,
            'application/pdf'
        );

        $response = $this
            ->actingAs($user)
            ->post(
                route('participant.payments.store'),
                [
                    'participant_id' => $participant->id,
                    'payment_method_id' => $paymentMethod->id,
                    'proof_file' => $proofFile,
                    'paid_at' => now()->format('Y-m-d H:i:s'),
                    'notes' => 'Test payment.',
                ]
            );

        $response->assertRedirect(
            route('participant.payments.index')
        );

        $this->assertDatabaseHas('payments', [
            'participant_id' => $participant->id,
            'payment_method_id' => $paymentMethod->id,
            'amount' => 250000,
            'status' => 'pending',
        ]);

        $participant->refresh();

        $this->assertSame(
            'pending',
            $participant->registration_status
        );
    }

    public function test_admin_can_verify_payment_and_confirm_registration(): void
    {
        Mail::fake();
        Queue::fake();

        $conference = $this->createOpenConference();

        $registrationType = $this->createPresenterRegistrationType(
            $conference
        );

        $paymentMethod = $this->createPaymentMethod(
            $conference
        );

        $user = User::factory()->create([
            'role' => 'participant',
            'status' => 'active',
        ]);

        $participant = $this->createPresenterParticipant(
            $conference,
            $registrationType,
            $user
        );

        $payment = Payment::create([
            'participant_id' => $participant->id,
            'payment_method_id' => $paymentMethod->id,
            'payment_code' => 'PAY-TEST-VERIFY',
            'amount' => 250000,
            'proof_file' => 'payments/proofs/test-proof.pdf',
            'status' => 'pending',
            'paid_at' => now(),
        ]);

        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);

        $response = $this
            ->actingAs($admin)
            ->patch(
                route('admin.payments.verify', $payment)
            );

        $response->assertRedirect(
            route('admin.payments.show', $payment)
        );

        $payment->refresh();
        $participant->refresh();

        $this->assertSame(
            'verified',
            $payment->status
        );

        $this->assertNotNull(
            $payment->verified_at
        );

        $this->assertSame(
            $admin->id,
            $payment->verified_by
        );

        $this->assertSame(
            'confirmed',
            $participant->registration_status
        );
    }

    public function test_rejected_payment_keeps_registration_pending(): void
    {
        $conference = $this->createOpenConference();

        $registrationType = $this->createPresenterRegistrationType(
            $conference
        );

        $paymentMethod = $this->createPaymentMethod(
            $conference
        );

        $user = User::factory()->create([
            'role' => 'participant',
            'status' => 'active',
        ]);

        $participant = $this->createPresenterParticipant(
            $conference,
            $registrationType,
            $user
        );

        $payment = Payment::create([
            'participant_id' => $participant->id,
            'payment_method_id' => $paymentMethod->id,
            'payment_code' => 'PAY-TEST-REJECT',
            'amount' => 250000,
            'proof_file' => 'payments/proofs/test-reject.pdf',
            'status' => 'pending',
            'paid_at' => now(),
        ]);

        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);

        $response = $this
            ->actingAs($admin)
            ->patch(
                route('admin.payments.reject', $payment)
            );

        $response->assertRedirect(
            route('admin.payments.show', $payment)
        );

        $payment->refresh();
        $participant->refresh();

        $this->assertSame(
            'rejected',
            $payment->status
        );

        $this->assertSame(
            'pending',
            $participant->registration_status
        );
    }

    public function test_participant_cannot_submit_payment_for_another_participant(): void
    {
        Storage::fake('local');

        $conference = $this->createOpenConference();

        $registrationType = $this->createPresenterRegistrationType(
            $conference
        );

        $paymentMethod = $this->createPaymentMethod(
            $conference
        );

        $userA = User::factory()->create([
            'role' => 'participant',
            'status' => 'active',
        ]);

        $userB = User::factory()->create([
            'role' => 'participant',
            'status' => 'active',
        ]);

        $participantA = $this->createPresenterParticipant(
            $conference,
            $registrationType,
            $userA
        );

        $participantB = $this->createPresenterParticipant(
            $conference,
            $registrationType,
            $userB
        );

        $response = $this
            ->actingAs($userA)
            ->post(
                route('participant.payments.store'),
                [
                    'participant_id' => $participantB->id,
                    'payment_method_id' => $paymentMethod->id,
                    'proof_file' => UploadedFile::fake()->create(
                        'payment-proof.pdf',
                        100,
                        'application/pdf'
                    ),
                    'paid_at' => now()->format('Y-m-d H:i:s'),
                    'notes' => 'Unauthorized payment attempt.',
                ]
            );

        $response->assertSessionHasErrors([
            'participant_id',
        ]);

        $this->assertDatabaseMissing('payments', [
            'participant_id' => $participantB->id,
        ]);

        $this->assertDatabaseCount(
            'payments',
            0
        );

        $this->assertNotSame(
            $participantA->id,
            $participantB->id
        );
    }

    public function test_participant_payment_history_only_shows_owned_payments(): void
    {
        $conference = $this->createOpenConference();

        $registrationType = $this->createPresenterRegistrationType(
            $conference
        );

        $paymentMethod = $this->createPaymentMethod(
            $conference
        );

        $userA = User::factory()->create([
            'role' => 'participant',
            'status' => 'active',
        ]);

        $userB = User::factory()->create([
            'role' => 'participant',
            'status' => 'active',
        ]);

        $participantA = $this->createPresenterParticipant(
            $conference,
            $registrationType,
            $userA
        );

        $participantB = $this->createPresenterParticipant(
            $conference,
            $registrationType,
            $userB
        );

        $paymentA = Payment::create([
            'participant_id' => $participantA->id,
            'payment_method_id' => $paymentMethod->id,
            'payment_code' => 'PAY-USER-A',
            'amount' => 250000,
            'proof_file' => 'payments/proofs/a.pdf',
            'status' => 'pending',
            'paid_at' => now(),
        ]);

        $paymentB = Payment::create([
            'participant_id' => $participantB->id,
            'payment_method_id' => $paymentMethod->id,
            'payment_code' => 'PAY-USER-B',
            'amount' => 250000,
            'proof_file' => 'payments/proofs/b.pdf',
            'status' => 'pending',
            'paid_at' => now(),
        ]);

        $response = $this
            ->actingAs($userA)
            ->get(
                route('participant.payments.index')
            );

        $response->assertSuccessful();

        $response->assertViewHas(
            'payments',
            function ($payments) use ($paymentA, $paymentB) {
                return $payments->count() === 1
                    && $payments->first()->id === $paymentA->id
                    && !$payments->contains(
                        'id',
                        $paymentB->id
                    );
            }
        );
    }

    public function test_payment_method_from_another_conference_cannot_be_used(): void
    {
        Storage::fake('local');

        $conferenceA = $this->createOpenConference();

        $conferenceB = $this->createOpenConference();

        $registrationTypeA =
            $this->createPresenterRegistrationType(
                $conferenceA
            );

        $paymentMethodA =
            $this->createPaymentMethod(
                $conferenceA
            );

        $paymentMethodB =
            $this->createPaymentMethod(
                $conferenceB
            );

        $user = User::factory()->create([
            'role' => 'participant',
            'status' => 'active',
        ]);

        $participant = $this->createPresenterParticipant(
            $conferenceA,
            $registrationTypeA,
            $user
        );

        $response = $this
            ->actingAs($user)
            ->post(
                route('participant.payments.store'),
                [
                    'participant_id' => $participant->id,
                    'payment_method_id' => $paymentMethodB->id,
                    'proof_file' => UploadedFile::fake()->create(
                        'payment-proof.pdf',
                        100,
                        'application/pdf'
                    ),
                    'paid_at' => now()->format('Y-m-d H:i:s'),
                    'notes' => 'Wrong conference payment method.',
                ]
            );

        $response->assertSessionHasErrors([
            'payment_method_id',
        ]);

        $this->assertDatabaseCount(
            'payments',
            0
        );

        $this->assertNotSame(
            $paymentMethodA->id,
            $paymentMethodB->id
        );
    }
}
