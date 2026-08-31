<?php

namespace Tests\Unit;

use App\Mail\PaymentVerifiedMail;
use App\Mail\SubmissionStatusMail;
use App\Models\Payment;
use App\Models\Submission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class MailTest extends TestCase
{
    use RefreshDatabase;

    private int $conferenceId;

    private int $participantId;

    private int $paymentMethodId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->conferenceId = DB::table('conferences')->insertGetId([
            'name' => 'ICON 2026',
            'short_name' => 'ICON',
            'year' => 2026,
            'theme' => 'Innovation and Technology',
            'venue' => 'Test Venue',
            'city' => 'Semarang',
            'country' => 'Indonesia',
            'start_date' => '2026-11-01',
            'end_date' => '2026-11-01',
            'status' => 'registration_open',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->paymentMethodId = DB::table(
            'conference_payment_methods'
        )->insertGetId([
            'conference_id' => $this->conferenceId,
            'type' => 'bank_transfer',
            'name' => 'Test Bank',
            'provider' => 'Test Bank',
            'account_number' => '1234567890',
            'account_name' => 'ICON 2026',
            'currency' => 'IDR',
            'instructions' => 'Test payment instructions.',
            'qr_code_file' => null,
            'is_active' => true,
            'sort_order' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->participantId = DB::table('participants')->insertGetId([
            'user_id' => null,
            'conference_id' => $this->conferenceId,
            'registration_type_id' => null,
            'registration_number' => 'MAIL-TEST-001',
            'full_name' => 'Mail Test Participant',
            'email' => 'mail-test@example.com',
            'phone' => null,
            'institution' => 'Test University',
            'department' => 'Informatics',
            'country' => 'Indonesia',
            'city' => 'Semarang',
            'participant_type' => 'regular',
            'attendance_type' => 'offline',
            'registration_status' => 'confirmed',
            'notes' => null,
            'registered_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function test_payment_verified_mail_can_be_rendered(): void
    {
        $paymentId = DB::table('payments')->insertGetId([
            'participant_id' => $this->participantId,
            'payment_method_id' => $this->paymentMethodId,
            'payment_code' => 'PAY-MAIL-001',
            'amount' => 250000,
            'proof_file' => null,
            'status' => 'verified',
            'notes' => null,
            'paid_at' => now(),
            'verified_at' => now(),
            'verified_by' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $payment = Payment::findOrFail($paymentId);

        $mail = new PaymentVerifiedMail($payment);

        $rendered = $mail->render();

        $this->assertStringContainsString(
            'Payment Verified',
            $rendered
        );

        $this->assertStringContainsString(
            'PAY-MAIL-001',
            $rendered
        );

        $this->assertStringContainsString(
            '250.000',
            $rendered
        );

        $this->assertStringContainsString(
            'Mail Test Participant',
            $rendered
        );
    }

    public function test_submission_status_mail_can_be_rendered(): void
    {
        $topicId = DB::table('topics')->insertGetId([
            'conference_id' => $this->conferenceId,
            'name' => 'Test Topic',
            'description' => null,
            'icon' => null,
            'color' => 'primary',
            'sort_order' => 0,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $submissionId = DB::table('submissions')->insertGetId([
            'conference_id' => $this->conferenceId,
            'participant_id' => $this->participantId,
            'topic_id' => $topicId,
            'submission_code' => 'ICON26-MAIL001',
            'title' => 'Test Submission',
            'abstract' => 'Test abstract.',
            'keywords' => 'test, mail',
            'paper_file' => 'test/paper.pdf',
            'revised_file' => null,
            'camera_ready_file' => null,
            'camera_ready_correction_reason' => null,
            'status' => 'accepted',
            'presentation_type' => null,
            'presentation_mode' => null,
            'presenter_author_id' => null,
            'presentation_completed' => false,
            'submitted_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $submission = Submission::findOrFail($submissionId);

        $message =
            'Congratulations! Your submission has been accepted.';

        $mail = new SubmissionStatusMail(
            $submission,
            $message
        );

        $rendered = $mail->render();

        $this->assertStringContainsString(
            'Submission Status Update',
            $rendered
        );

        $this->assertStringContainsString(
            'ICON26-MAIL001',
            $rendered
        );

        $this->assertStringContainsString(
            'Test Submission',
            $rendered
        );

        $this->assertStringContainsString(
            'accepted',
            strtolower($rendered)
        );

        $this->assertStringContainsString(
            $message,
            $rendered
        );
    }
}
