<?php

namespace Tests\Feature;

use App\Models\Certificate;
use App\Models\Conference;
use App\Models\ConferenceAttendance;
use App\Models\ConferenceAttendanceOption;
use App\Models\ConferenceRegistrationType;
use App\Models\ConferenceSetting;
use App\Models\Participant;
use App\Models\Review;
use App\Models\Reviewer;
use App\Models\Submission;
use App\Models\SubmissionAuthor;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CertificateControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
    }

    private function createConference(): Conference
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

    private function createAdmin(): User
    {
        return User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);
    }

    private function createParticipant(
        Conference $conference,
        ?ConferenceRegistrationType $registrationType = null,
        string $participantType = 'regular'
    ): Participant {
        $user = User::factory()->create([
            'role' => 'participant',
            'status' => 'active',
        ]);

        return Participant::create([
            'user_id' => $user->id,
            'conference_id' => $conference->id,
            'registration_type_id' => $registrationType?->id,
            'registration_number' => 'REG-' . $user->id,
            'title_prefix' => null,
            'full_name' => $user->name,
            'title_suffix' => null,
            'email' => $user->email,
            'orcid' => null,
            'phone' => '081234567890',
            'institution' => 'Test University',
            'department' => 'Test Department',
            'country' => 'Indonesia',
            'city' => 'Slawi',
            'participant_type' => $participantType,
            'attendance_type' => 'online',
            'registration_status' => 'confirmed',
            'registered_at' => now(),
        ]);
    }

    private function createPresenterRegistrationType(
        Conference $conference
    ): ConferenceRegistrationType {
        return ConferenceRegistrationType::create([
            'conference_id' => $conference->id,
            'name' => 'Presenter',
            'code' => 'PRESENTER-TEST-' . uniqid(),
            'category' => 'presenter',
            'fee' => 250000,
            'currency' => 'IDR',
            'included_papers' => 1,
            'additional_paper_fee' => 0,
            'payment_timing' => 'immediate',
            'is_active' => true,
            'sort_order' => 1,
        ]);
    }

    private function createTopic(
        Conference $conference
    ): Topic {
        return Topic::create([
            'conference_id' => $conference->id,
            'name' => 'Test Topic',
            'description' => 'Test topic description.',
            'icon' => 'bi-cpu',
            'color' => 'primary',
            'sort_order' => 1,
            'is_active' => true,
        ]);
    }

    private function createPublishedSubmission(
        Conference $conference,
        Participant $participant
    ): Submission {
        $topic = $this->createTopic($conference);

        $submission = Submission::create([
            'conference_id' => $conference->id,
            'participant_id' => $participant->id,
            'topic_id' => $topic->id,
            'submission_code' => 'TEST-' . uniqid(),
            'title' => 'Test Published Submission',
            'abstract' => 'Test abstract.',
            'keywords' => 'test, submission',
            'submission_stage' => 'full_paper',
            'status' => 'published',
            'submitted_at' => now(),
        ]);

        $author = SubmissionAuthor::create([
            'submission_id' => $submission->id,
            'name' => $participant->full_name,
            'email' => $participant->email,
            'institution' => $participant->institution,
            'is_corresponding' => true,
            'sort_order' => 1,
        ]);

        $submission->presenter_author_id = $author->id;
        $submission->save();

        return $submission->fresh();
    }

    private function createVerifiedAttendance(
        Conference $conference,
        Participant $participant
    ): ConferenceAttendance {
        $admin = $this->createAdmin();

        return ConferenceAttendance::create([
            'conference_id' => $conference->id,
            'participant_id' => $participant->id,
            'attendance_status' => 'verified',
            'checked_in_at' => now(),
            'verified_at' => now(),
            'verified_by' => $admin->id,
        ]);
    }

    public function test_participant_certificate_is_rejected_without_verified_attendance(): void
    {
        $conference = $this->createConference();
        $participant = $this->createParticipant($conference);
        $admin = $this->createAdmin();

        $response = $this
            ->actingAs($admin)
            ->post(
                route('admin.certificates.store'),
                [
                    'participant_id' => $participant->id,
                    'type' => 'participant',
                ]
            );

        $response
            ->assertRedirect()
            ->assertSessionHas(
                'error',
                'Conference attendance must be verified.'
            );

        $this->assertDatabaseMissing(
            'certificates',
            [
                'participant_id' => $participant->id,
                'conference_id' => $conference->id,
                'type' => 'participant',
            ]
        );
    }

    public function test_participant_certificate_is_generated_when_eligible(): void
    {
        $conference = $this->createConference();
        $participant = $this->createParticipant($conference);

        $this->createVerifiedAttendance(
            $conference,
            $participant
        );

        $admin = $this->createAdmin();

        $response = $this
            ->actingAs($admin)
            ->post(
                route('admin.certificates.store'),
                [
                    'participant_id' => $participant->id,
                    'type' => 'participant',
                ]
            );

        $response->assertRedirect();

        $this->assertDatabaseHas(
            'certificates',
            [
                'participant_id' => $participant->id,
                'conference_id' => $conference->id,
                'submission_id' => null,
                'type' => 'participant',
            ]
        );

        $certificate = Certificate::query()
            ->where('participant_id', $participant->id)
            ->where('conference_id', $conference->id)
            ->where('type', 'participant')
            ->firstOrFail();

        $this->assertNotNull(
            $certificate->file_path
        );

        Storage::disk('public')->assertExists(
            $certificate->file_path
        );
    }

    public function test_presenter_certificate_is_rejected_without_verified_attendance(): void
    {
        $conference = $this->createConference();

        $registrationType = $this->createPresenterRegistrationType(
            $conference
        );

        $participant = $this->createParticipant(
            $conference,
            $registrationType,
            'presenter'
        );

        $submission = $this->createPublishedSubmission(
            $conference,
            $participant
        );

        $admin = $this->createAdmin();

        $response = $this
            ->actingAs($admin)
            ->post(
                route('admin.certificates.store'),
                [
                    'participant_id' => $participant->id,
                    'type' => 'presenter',
                    'submission_id' => $submission->id,
                ]
            );

        $response
            ->assertRedirect()
            ->assertSessionHas(
                'error',
                'Conference attendance must be verified.'
            );

        $this->assertDatabaseMissing(
            'certificates',
            [
                'participant_id' => $participant->id,
                'conference_id' => $conference->id,
                'submission_id' => $submission->id,
                'type' => 'presenter',
            ]
        );
    }

    public function test_presenter_certificate_is_generated_when_eligible(): void
    {
        $conference = $this->createConference();

        $registrationType = $this->createPresenterRegistrationType(
            $conference
        );

        $participant = $this->createParticipant(
            $conference,
            $registrationType,
            'presenter'
        );

        $submission = $this->createPublishedSubmission(
            $conference,
            $participant
        );

        $this->createVerifiedAttendance(
            $conference,
            $participant
        );

        $admin = $this->createAdmin();

        $response = $this
            ->actingAs($admin)
            ->post(
                route('admin.certificates.store'),
                [
                    'participant_id' => $participant->id,
                    'type' => 'presenter',
                    'submission_id' => $submission->id,
                ]
            );

        $response->assertRedirect();

        $this->assertDatabaseHas(
            'certificates',
            [
                'participant_id' => $participant->id,
                'conference_id' => $conference->id,
                'submission_id' => $submission->id,
                'type' => 'presenter',
            ]
        );

        $certificate = Certificate::query()
            ->where('participant_id', $participant->id)
            ->where('conference_id', $conference->id)
            ->where('submission_id', $submission->id)
            ->where('type', 'presenter')
            ->firstOrFail();

        $this->assertNotNull(
            $certificate->file_path
        );

        Storage::disk('public')->assertExists(
            $certificate->file_path
        );
    }

    public function test_reviewer_certificate_is_rejected_when_review_is_incomplete(): void
    {
        $conference = $this->createConference();

        $user = User::factory()->create([
            'role' => 'reviewer',
            'status' => 'active',
        ]);

        $participant = Participant::create([
            'user_id' => $user->id,
            'conference_id' => $conference->id,
            'registration_number' => 'REG-REVIEWER-' . $user->id,
            'full_name' => $user->name,
            'email' => $user->email,
            'phone' => '081234567890',
            'institution' => 'Test University',
            'department' => 'Test Department',
            'country' => 'Indonesia',
            'city' => 'Slawi',
            'participant_type' => 'regular',
            'attendance_type' => 'online',
            'registration_status' => 'confirmed',
            'registered_at' => now(),
        ]);

        $reviewer = Reviewer::create([
            'conference_id' => $conference->id,
            'user_id' => $user->id,
            'expertise' => 'Computer Science',
            'institution' => 'Test University',
            'bio' => 'Test reviewer.',
            'is_active' => true,
        ]);

        $submission = $this->createPublishedSubmission(
            $conference,
            $participant
        );

        Review::create([
            'submission_id' => $submission->id,
            'reviewer_id' => $reviewer->id,
            'review_stage' => 'full_paper',
            'review_round' => 1,
            'score' => null,
            'comment' => null,
            'recommendation' => null,
            'reviewed_at' => null,
        ]);

        $admin = $this->createAdmin();

        $response = $this
            ->actingAs($admin)
            ->post(
                route('admin.certificates.store'),
                [
                    'participant_id' => $participant->id,
                    'type' => 'reviewer',
                ]
            );

        $response
            ->assertRedirect()
            ->assertSessionHas(
                'error',
                'All reviews assigned to the reviewer must be completed.'
            );

        $this->assertDatabaseMissing(
            'certificates',
            [
                'participant_id' => $participant->id,
                'conference_id' => $conference->id,
                'submission_id' => null,
                'type' => 'reviewer',
            ]
        );
    }

    public function test_submission_id_is_rejected_for_non_presenter_certificate(): void
    {
        $conference = $this->createConference();
        $participant = $this->createParticipant($conference);

        $submission = $this->createPublishedSubmission(
            $conference,
            $participant
        );

        $admin = $this->createAdmin();

        $response = $this
            ->actingAs($admin)
            ->post(
                route('admin.certificates.store'),
                [
                    'participant_id' => $participant->id,
                    'type' => 'participant',
                    'submission_id' => $submission->id,
                ]
            );

        $response
            ->assertRedirect()
            ->assertSessionHas(
                'error',
                'A submission can only be linked to a presenter certificate.'
            );

        $this->assertDatabaseMissing(
            'certificates',
            [
                'participant_id' => $participant->id,
                'conference_id' => $conference->id,
                'type' => 'participant',
            ]
        );
    }

    public function test_speaker_certificate_is_generated_when_eligible(): void
    {
        $conference = $this->createConference();

        $participant = $this->createParticipant(
            $conference,
            null,
            'speaker'
        );

        $this->createVerifiedAttendance(
            $conference,
            $participant
        );

        $admin = $this->createAdmin();

        $response = $this
            ->actingAs($admin)
            ->post(
                route('admin.certificates.store'),
                [
                    'participant_id' => $participant->id,
                    'type' => 'speaker',
                ]
            );

        $response->assertRedirect();

        $this->assertDatabaseHas(
            'certificates',
            [
                'participant_id' => $participant->id,
                'conference_id' => $conference->id,
                'submission_id' => null,
                'type' => 'speaker',
            ]
        );

        $certificate = Certificate::query()
            ->where('participant_id', $participant->id)
            ->where('conference_id', $conference->id)
            ->whereNull('submission_id')
            ->where('type', 'speaker')
            ->firstOrFail();

        $this->assertNotNull(
            $certificate->file_path
        );

        Storage::disk('public')->assertExists(
            $certificate->file_path
        );
    }

    public function test_committee_certificate_is_generated_when_eligible(): void
    {
        $conference = $this->createConference();

        $participant = $this->createParticipant(
            $conference,
            null,
            'committee'
        );

        $this->createVerifiedAttendance(
            $conference,
            $participant
        );

        $admin = $this->createAdmin();

        $response = $this
            ->actingAs($admin)
            ->post(
                route('admin.certificates.store'),
                [
                    'participant_id' => $participant->id,
                    'type' => 'committee',
                ]
            );

        $response->assertRedirect();

        $this->assertDatabaseHas(
            'certificates',
            [
                'participant_id' => $participant->id,
                'conference_id' => $conference->id,
                'submission_id' => null,
                'type' => 'committee',
            ]
        );

        $certificate = Certificate::query()
            ->where('participant_id', $participant->id)
            ->where('conference_id', $conference->id)
            ->whereNull('submission_id')
            ->where('type', 'committee')
            ->firstOrFail();

        $this->assertNotNull(
            $certificate->file_path
        );

        Storage::disk('public')->assertExists(
            $certificate->file_path
        );
    }

    public function test_reviewer_certificate_is_generated_when_all_reviews_are_completed(): void
    {
        $conference = $this->createConference();

        $user = User::factory()->create([
            'role' => 'reviewer',
            'status' => 'active',
        ]);

        $participant = Participant::create([
            'user_id' => $user->id,
            'conference_id' => $conference->id,
            'registration_number' => 'REG-REVIEWER-' . $user->id,
            'full_name' => $user->name,
            'email' => $user->email,
            'phone' => '081234567890',
            'institution' => 'Test University',
            'department' => 'Test Department',
            'country' => 'Indonesia',
            'city' => 'Slawi',
            'participant_type' => 'regular',
            'attendance_type' => 'online',
            'registration_status' => 'confirmed',
            'registered_at' => now(),
        ]);

        $reviewer = Reviewer::create([
            'conference_id' => $conference->id,
            'user_id' => $user->id,
            'expertise' => 'Computer Science',
            'institution' => 'Test University',
            'bio' => 'Test reviewer.',
            'is_active' => true,
        ]);

        $submissionParticipant = $this->createParticipant(
            $conference
        );

        $submission = $this->createPublishedSubmission(
            $conference,
            $submissionParticipant
        );

        Review::create([
            'submission_id' => $submission->id,
            'reviewer_id' => $reviewer->id,
            'review_stage' => 'full_paper',
            'review_round' => 1,
            'score' => 85,
            'comment' => 'Good paper.',
            'recommendation' => 'accept',
            'reviewed_at' => now(),
        ]);

        $admin = $this->createAdmin();

        $response = $this
            ->actingAs($admin)
            ->post(
                route('admin.certificates.store'),
                [
                    'participant_id' => $participant->id,
                    'type' => 'reviewer',
                ]
            );

        $response->assertRedirect();

        $this->assertDatabaseHas(
            'certificates',
            [
                'participant_id' => $participant->id,
                'conference_id' => $conference->id,
                'submission_id' => null,
                'type' => 'reviewer',
            ]
        );

        $certificate = Certificate::query()
            ->where('participant_id', $participant->id)
            ->where('conference_id', $conference->id)
            ->whereNull('submission_id')
            ->where('type', 'reviewer')
            ->firstOrFail();

        $this->assertNotNull(
            $certificate->file_path
        );

        Storage::disk('public')->assertExists(
            $certificate->file_path
        );
    }
}
