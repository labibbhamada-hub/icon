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

    private ConferenceRegistrationType $registrationType;

    private ConferencePaymentMethod $paymentMethod;

    private Topic $topic;

    protected function setUp(): void
    {
        parent::setUp();

        $this->conference = Conference::create([
            'name' => 'ICON 2026',
            'short_name' => 'ICON',
            'year' => 2026,
            'theme' => 'Innovation and Technology',
            'country' => 'Indonesia',
            'start_date' => '2026-11-01',
            'end_date' => '2026-11-01',
            'status' => 'registration_open',
        ]);

        $this->registrationType = ConferenceRegistrationType::create([
            'conference_id' => $this->conference->id,
            'name' => 'Author / Presenter',
            'code' => 'presenter',
            'category' => 'presenter',
            'payment_timing' => 'after_acceptance',
            'fee' => 0,
            'included_papers' => 1,
            'additional_paper_fee' => 150000,
            'currency' => 'IDR',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        ConferencePresentationPrice::create([
            'registration_type_id' => $this->registrationType->id,
            'presentation_type' => 'oral',
            'fee' => 350000,
            'currency' => 'IDR',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        ConferencePresentationPrice::create([
            'registration_type_id' => $this->registrationType->id,
            'presentation_type' => 'poster',
            'fee' => 250000,
            'currency' => 'IDR',
            'is_active' => true,
            'sort_order' => 2,
        ]);

        $this->paymentMethod = ConferencePaymentMethod::create([
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

        $this->topic = Topic::create([
            'conference_id' => $this->conference->id,
            'name' => 'Artificial Intelligence',
            'description' => 'AI topic for testing.',
            'is_active' => true,
            'sort_order' => 0,
        ]);
    }

    private function createParticipant(): Participant
    {
        return Participant::create([
            'user_id' => null,
            'conference_id' => $this->conference->id,
            'registration_type_id' => $this->registrationType->id,
            'presentation_type' => null,
            'registration_number' => 'TEST-' . fake()->unique()->numerify('######'),
            'full_name' => 'Test Participant',
            'email' => 'test@example.com',
            'phone' => '+628123456789',
            'institution' => 'Test University',
            'department' => 'Informatics',
            'country' => 'Indonesia',
            'city' => 'Semarang',
            'participant_type' => 'regular',
            'attendance_type' => 'online',
            'registration_status' => 'confirmed',
            'registered_at' => now(),
        ]);
    }

    private function createSubmission(
        Participant $participant,
        string $presentationType,
        int $idOffset = 0
    ): Submission {
        return Submission::create([
            'conference_id' => $this->conference->id,
            'participant_id' => $participant->id,
            'topic_id' => $this->topic->id,
            'submission_code' => 'TEST-SUB-' .
                strtoupper(
                    substr(
                        md5(
                            $participant->id .
                                '-' .
                                $presentationType .
                                '-' .
                                $idOffset
                        ),
                        0,
                        8
                    )
                ),
            'title' => 'Test Submission ' . ($idOffset + 1),
            'abstract' => 'Test abstract.',
            'keywords' => 'test, conference, technology',
            'paper_file' => 'test/paper.pdf',
            'status' => 'accepted',
            'presentation_type' => $presentationType,
            'presentation_mode' => 'online',
            'presentation_completed' => true,
        ]);
    }

    public function test_presenter_oral_price_is_used_as_base_fee(): void
    {
        $participant = $this->createParticipant();

        $this->createSubmission(
            $participant,
            'oral'
        );

        $service = app(PaymentCalculationService::class);

        $result = $service->calculate($participant->fresh());

        $this->assertSame(
            'oral',
            $result['presentation_type']
        );

        $this->assertSame(
            350000.0,
            $result['presentation_fee']
        );

        $this->assertSame(
            350000.0,
            $result['base_fee']
        );

        $this->assertSame(
            350000.0,
            $result['total_amount']
        );

        $this->assertSame(
            0.0,
            $result['additional_amount']
        );
    }

    public function test_presenter_poster_price_is_used_as_base_fee(): void
    {
        $participant = $this->createParticipant();

        $this->createSubmission(
            $participant,
            'poster'
        );

        $service = app(PaymentCalculationService::class);

        $result = $service->calculate($participant->fresh());

        $this->assertSame(
            'poster',
            $result['presentation_type']
        );

        $this->assertSame(
            250000.0,
            $result['presentation_fee']
        );

        $this->assertSame(
            250000.0,
            $result['base_fee']
        );

        $this->assertSame(
            250000.0,
            $result['total_amount']
        );
    }

    public function test_additional_accepted_papers_are_charged_correctly(): void
    {
        $participant = $this->createParticipant();

        $this->createSubmission(
            $participant,
            'oral',
            0
        );

        $this->createSubmission(
            $participant,
            'oral',
            1
        );

        $this->createSubmission(
            $participant,
            'oral',
            2
        );

        $service = app(PaymentCalculationService::class);

        $result = $service->calculate($participant->fresh());

        $this->assertSame(
            3,
            $result['accepted_papers']
        );

        $this->assertSame(
            2,
            $result['additional_papers']
        );

        $this->assertSame(
            300000.0,
            $result['additional_amount']
        );

        $this->assertSame(
            650000.0,
            $result['total_amount']
        );
    }

    public function test_verified_payment_reduces_outstanding_amount(): void
    {
        $participant = $this->createParticipant();

        $this->createSubmission(
            $participant,
            'poster'
        );

        Payment::create([
            'participant_id' => $participant->id,
            'payment_method_id' => $this->paymentMethod->id,
            'payment_code' => 'PAY-TEST-' . strtoupper(
                substr(
                    md5(
                        $participant->id . '-verified'
                    ),
                    0,
                    8
                )
            ),
            'amount' => 250000,
            'status' => 'verified',
            'paid_at' => now(),
            'verified_at' => now(),
        ]);

        $service = app(PaymentCalculationService::class);

        $result = $service->calculate($participant->fresh());

        $this->assertSame(
            250000.0,
            $result['total_amount']
        );

        $this->assertSame(
            250000.0,
            $result['verified_payment_amount']
        );

        $this->assertEquals(
            0.0,
            $result['outstanding_amount']
        );
    }

    public function test_outstanding_amount_never_becomes_negative(): void
    {
        $participant = $this->createParticipant();

        $this->createSubmission(
            $participant,
            'poster'
        );

        Payment::create([
            'participant_id' => $participant->id,
            'payment_method_id' => $this->paymentMethod->id,
            'payment_code' => 'PAY-TEST-' . strtoupper(
                substr(
                    md5(
                        $participant->id . '-overpayment'
                    ),
                    0,
                    8
                )
            ),
            'amount' => 300000,
            'status' => 'verified',
            'paid_at' => now(),
            'verified_at' => now(),
        ]);

        $service = app(PaymentCalculationService::class);

        $result = $service->calculate($participant->fresh());

        $this->assertSame(
            250000.0,
            $result['total_amount']
        );

        $this->assertSame(
            300000.0,
            $result['verified_payment_amount']
        );

        $this->assertEquals(
            0.0,
            $result['outstanding_amount']
        );
    }
}
