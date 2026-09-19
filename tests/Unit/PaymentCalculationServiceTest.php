<?php

namespace Tests\Unit;

use App\Models\Conference;
use App\Models\ConferencePaymentMethod;
use App\Models\ConferencePresentationPrice;
use App\Models\ConferenceRegistrationType;
use App\Models\Participant;
use App\Models\Payment;
use App\Models\Submission;
use App\Models\Topic;
use App\Services\PaymentCalculationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentCalculationServiceTest extends TestCase
{
    use RefreshDatabase;

    private Conference $conference;

    protected function setUp(): void
    {
        parent::setUp();

        $this->conference = Conference::create([
            'name' => 'ICON 2026',
            'short_name' => 'ICON',
            'year' => 2026,
            'theme' => 'Advancing Interdisciplinary Research and Innovation for Sustainable Development',
            'country' => 'Indonesia',
            'start_date' => '2026-08-27',
            'end_date' => '2026-08-27',
            'registration_deadline' => '2026-08-20',
            'status' => 'registration_open',
        ]);
    }

    private function createRegistrationType(
        string $name,
        string $code,
        string $category,
        float $fee,
        string $currency = 'IDR',
        string $paymentTiming = 'immediate'
    ): ConferenceRegistrationType {
        return ConferenceRegistrationType::create([
            'conference_id' => $this->conference->id,
            'name' => $name,
            'code' => $code,
            'category' => $category,
            'payment_timing' => $paymentTiming,
            'fee' => $fee,
            'included_papers' => 1,
            'additional_paper_fee' => 150000,
            'currency' => $currency,
            'is_active' => true,
            'sort_order' => 1,
        ]);
    }

    private function createParticipant(
        ConferenceRegistrationType $registrationType,
        string $status = 'pending'
    ): Participant {
        return Participant::create([
            'user_id' => null,
            'conference_id' => $this->conference->id,
            'registration_type_id' => $registrationType->id,
            'registration_number' => 'TEST-' . fake()->unique()->numerify('######'),
            'full_name' => 'Test Participant',
            'email' => fake()->unique()->safeEmail(),
            'phone' => '+628123456789',
            'institution' => 'Test University',
            'department' => 'Informatics',
            'country' => 'Indonesia',
            'city' => 'Slawi',
            'participant_type' => $registrationType->category === 'presenter'
                ? 'presenter'
                : 'regular',
            'attendance_type' => 'online',
            'registration_status' => $status,
            'registered_at' => now(),
        ]);
    }

    private function createAcceptedFullPaper(Participant $participant): Submission
    {
        $topic = Topic::create([
            'conference_id' => $this->conference->id,
            'name' => 'Artificial Intelligence',
            'description' => 'AI topic for testing.',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        return Submission::create([
            'conference_id' => $this->conference->id,
            'participant_id' => $participant->id,
            'topic_id' => $topic->id,
            'submission_code' => 'TEST-SUB-' . strtoupper(fake()->unique()->bothify('########')),
            'title' => 'Test Submission',
            'abstract' => 'Test abstract.',
            'keywords' => 'test, conference, technology',
            'paper_file' => 'test/paper.pdf',
            'submission_stage' => 'full_paper',
            'status' => 'accepted',
            'submitted_at' => now(),
        ]);
    }

    public function test_presenter_fee_uses_registration_type_fee(): void
    {
        $registrationType = $this->createRegistrationType(
            'Presenter Bhamada',
            'PRESENTER-BHAMADA',
            'presenter',
            250000
        );

        $participant = $this->createParticipant($registrationType);

        // Legacy Oral/Poster pricing must not affect the current calculation.
        ConferencePresentationPrice::create([
            'registration_type_id' => $registrationType->id,
            'presentation_type' => 'oral',
            'fee' => 350000,
            'currency' => 'IDR',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        ConferencePresentationPrice::create([
            'registration_type_id' => $registrationType->id,
            'presentation_type' => 'poster',
            'fee' => 250000,
            'currency' => 'IDR',
            'is_active' => true,
            'sort_order' => 2,
        ]);

        $result = app(PaymentCalculationService::class)
            ->calculate($participant->fresh());

        $this->assertSame(250000.0, $result['base_fee']);
        $this->assertSame(250000.0, $result['total_amount']);
        $this->assertSame('IDR', $result['currency']);
        $this->assertNull($result['presentation_type']);
        $this->assertNull($result['presentation_fee']);
    }

    public function test_presenter_luar_bhamada_fee_is_400000_idr(): void
    {
        $registrationType = $this->createRegistrationType(
            'Presenter Luar Bhamada',
            'PRESENTER-LUAR-BHAMADA',
            'presenter',
            400000
        );

        $participant = $this->createParticipant($registrationType);

        $result = app(PaymentCalculationService::class)
            ->calculate($participant);

        $this->assertSame(400000.0, $result['total_amount']);
        $this->assertSame('IDR', $result['currency']);
    }

    public function test_presenter_overseas_fee_is_60_usd(): void
    {
        $registrationType = $this->createRegistrationType(
            'Presenter Overseas',
            'PRESENTER-OVERSEAS',
            'presenter',
            60,
            'USD'
        );

        $participant = $this->createParticipant($registrationType);

        $result = app(PaymentCalculationService::class)
            ->calculate($participant);

        $this->assertSame(60.0, $result['base_fee']);
        $this->assertSame(60.0, $result['total_amount']);
        $this->assertSame('USD', $result['currency']);
    }

    public function test_seminar_umum_fee_is_75000_idr(): void
    {
        $registrationType = $this->createRegistrationType(
            'Peserta Seminar Umum',
            'SEMINAR-UMUM',
            'participant',
            75000
        );

        $participant = $this->createParticipant($registrationType);

        $result = app(PaymentCalculationService::class)
            ->calculate($participant);

        $this->assertSame(75000.0, $result['base_fee']);
        $this->assertSame(75000.0, $result['total_amount']);
        $this->assertSame('IDR', $result['currency']);
    }

    public function test_seminar_bhamada_fee_is_10000_idr(): void
    {
        $registrationType = $this->createRegistrationType(
            'Peserta Seminar Bhamada',
            'SEMINAR-BHAMADA',
            'participant',
            10000
        );

        $participant = $this->createParticipant($registrationType);

        $result = app(PaymentCalculationService::class)
            ->calculate($participant);

        $this->assertSame(10000.0, $result['total_amount']);
        $this->assertSame('IDR', $result['currency']);
    }

    public function test_additional_paper_fee_is_ignored_even_when_legacy_value_exists(): void
    {
        $registrationType = $this->createRegistrationType(
            'Presenter Bhamada',
            'PRESENTER-BHAMADA',
            'presenter',
            250000
        );

        $participant = $this->createParticipant($registrationType);

        $this->createAcceptedFullPaper($participant);
        $this->createAcceptedFullPaper($participant);

        $result = app(PaymentCalculationService::class)
            ->calculate($participant->fresh());

        $this->assertSame(2, $result['accepted_papers']);
        $this->assertSame(0, $result['additional_papers']);
        $this->assertSame(0.0, $result['additional_paper_fee']);
        $this->assertSame(0.0, $result['additional_amount']);
        $this->assertSame(250000.0, $result['total_amount']);
    }

    public function test_verified_payment_reduces_outstanding_amount(): void
    {
        $paymentMethod = ConferencePaymentMethod::create([
            'conference_id' => $this->conference->id,
            'type' => 'bank_transfer',
            'name' => 'Bank Transfer',
            'provider' => 'BRI',
            'account_number' => '1234567890',
            'account_name' => 'ICON 2026',
            'currency' => 'IDR',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        $registrationType = $this->createRegistrationType(
            'Presenter Bhamada',
            'PRESENTER-BHAMADA',
            'presenter',
            250000
        );

        $participant = $this->createParticipant($registrationType);

        Payment::create([
            'participant_id' => $participant->id,
            'payment_method_id' => $paymentMethod->id,
            'payment_code' => 'PAY-TEST-' . strtoupper(fake()->unique()->bothify('######')),
            'amount' => 250000,
            'status' => 'verified',
            'paid_at' => now(),
            'verified_at' => now(),
        ]);

        $result = app(PaymentCalculationService::class)
            ->calculate($participant->fresh());

        $this->assertSame(250000.0, $result['total_amount']);
        $this->assertSame(250000.0, $result['verified_payment_amount']);
        $this->assertSame(0.0, $result['outstanding_amount']);
    }

    public function test_outstanding_amount_never_becomes_negative(): void
    {
        $paymentMethod = ConferencePaymentMethod::create([
            'conference_id' => $this->conference->id,
            'type' => 'bank_transfer',
            'name' => 'Bank Transfer',
            'provider' => 'BRI',
            'account_number' => '1234567890',
            'account_name' => 'ICON 2026',
            'currency' => 'IDR',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        $registrationType = $this->createRegistrationType(
            'Peserta Seminar Umum',
            'SEMINAR-UMUM',
            'participant',
            75000
        );

        $participant = $this->createParticipant($registrationType);

        Payment::create([
            'participant_id' => $participant->id,
            'payment_method_id' => $paymentMethod->id,
            'payment_code' => 'PAY-TEST-' . strtoupper(fake()->unique()->bothify('######')),
            'amount' => 100000,
            'status' => 'verified',
            'paid_at' => now(),
            'verified_at' => now(),
        ]);

        $result = app(PaymentCalculationService::class)
            ->calculate($participant->fresh());

        $this->assertSame(75000.0, $result['total_amount']);
        $this->assertSame(100000.0, $result['verified_payment_amount']);
        $this->assertSame(0.0, $result['outstanding_amount']);
    }

    public function test_immediate_payment_registration_is_payable_while_pending(): void
    {
        $registrationType = $this->createRegistrationType(
            'Presenter Bhamada',
            'PRESENTER-BHAMADA',
            'presenter',
            250000
        );

        $participant = $this->createParticipant($registrationType, 'pending');

        $this->assertTrue(
            app(PaymentCalculationService::class)->canPay($participant)
        );
    }

    public function test_confirmed_registration_cannot_start_another_payment(): void
    {
        $registrationType = $this->createRegistrationType(
            'Presenter Bhamada',
            'PRESENTER-BHAMADA',
            'presenter',
            250000
        );

        $participant = $this->createParticipant($registrationType, 'confirmed');

        $this->assertFalse(
            app(PaymentCalculationService::class)->canPay($participant)
        );
    }

    public function test_after_acceptance_payment_timing_is_not_allowed_for_icon_2026(): void
    {
        $registrationType = $this->createRegistrationType(
            'Presenter Legacy',
            'PRESENTER-LEGACY',
            'presenter',
            250000,
            'IDR',
            'after_acceptance'
        );

        $participant = $this->createParticipant($registrationType, 'pending');

        $this->assertFalse(
            app(PaymentCalculationService::class)->canPay($participant)
        );
    }
}
