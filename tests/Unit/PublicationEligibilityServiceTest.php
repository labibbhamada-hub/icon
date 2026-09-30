<?php

namespace Tests\Unit;

use App\Models\Conference;
use App\Models\ConferenceAttendance;
use App\Models\ConferenceRegistrationType;
use App\Models\Participant;
use App\Models\Submission;
use App\Models\SubmissionAuthor;
use App\Models\Topic;
use App\Models\User;
use App\Services\PublicationEligibilityService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PublicationEligibilityServiceTest extends TestCase
{
    use RefreshDatabase;

    private function createConference(): Conference
    {
        return Conference::create([
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

    private function createParticipant(
        Conference $conference,
        ConferenceRegistrationType $registrationType
    ): Participant {
        $user = User::factory()->create([
            'role' => 'participant',
            'status' => 'active',
        ]);

        return Participant::create([
            'user_id' => $user->id,
            'conference_id' => $conference->id,
            'registration_type_id' => $registrationType->id,
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
            'participant_type' => 'presenter',
            'attendance_type' => 'online',
            'registration_status' => 'confirmed',
            'registered_at' => now(),
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

    private function createSubmission(
        Conference $conference,
        Participant $participant,
        Topic $topic
    ): Submission {
        $submission = Submission::create([
            'conference_id' => $conference->id,
            'participant_id' => $participant->id,
            'topic_id' => $topic->id,
            'submission_code' => 'TEST-' . uniqid(),
            'title' => 'Test Submission',
            'abstract' => 'Test abstract.',
            'keywords' => 'test, submission',
            'submission_stage' => 'full_paper',
            'status' => 'camera_ready',
            'camera_ready_file' => 'submissions/camera-ready/test-paper.pdf',
            'video_url' => 'https://drive.google.com/file/d/test-video-id/view',
            'video_submitted_at' => now(),
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
        $submission->camera_ready_status = 'submitted';
        $submission->save();

        return $submission->fresh();
    }

    private function createVerifiedAttendance(
        Submission $submission
    ): ConferenceAttendance {
        return ConferenceAttendance::create([
            'conference_id' => $submission->conference_id,
            'participant_id' => $submission->participant_id,
            'attendance_status' => 'verified',
            'checked_in_at' => now(),
            'verified_at' => now(),
            'verified_by' => User::factory()->create([
                'role' => 'admin',
                'status' => 'active',
            ])->id,
        ]);
    }

    private function createEligibleSubmission(): Submission
    {
        Storage::fake('local');

        $conference = $this->createConference();

        $registrationType =
            $this->createPresenterRegistrationType(
                $conference
            );

        $participant =
            $this->createParticipant(
                $conference,
                $registrationType
            );

        $topic =
            $this->createTopic(
                $conference
            );

        $submission =
            $this->createSubmission(
                $conference,
                $participant,
                $topic
            );

        Storage::disk('local')->put(
            'submissions/camera-ready/test-paper.pdf',
            'TEST CAMERA READY'
        );

        $this->createVerifiedAttendance(
            $submission
        );

        return $submission;
    }

    public function test_submission_is_eligible_when_all_publication_requirements_are_met(): void
    {
        $submission =
            $this->createEligibleSubmission();

        $result =
            app(PublicationEligibilityService::class)
            ->evaluate($submission);

        $this->assertTrue(
            $result['eligible']
        );

        $this->assertTrue(
            $result['checks']['workflow']
        );

        $this->assertTrue(
            $result['checks']['camera_ready']
        );

        $this->assertTrue(
            $result['checks']['video']
        );

        $this->assertTrue(
            $result['checks']['attendance']
        );

        $this->assertTrue(
            $result['checks']['presenter']
        );

        $this->assertSame(
            [],
            $result['reasons']
        );
    }

    public function test_submission_is_not_eligible_when_video_is_missing(): void
    {
        $submission =
            $this->createEligibleSubmission();

        $submission->update([
            'video_url' => null,
            'video_submitted_at' => null,
        ]);

        $result =
            app(PublicationEligibilityService::class)
            ->evaluate($submission->fresh());

        $this->assertFalse(
            $result['eligible']
        );

        $this->assertFalse(
            $result['checks']['video']
        );

        $this->assertContains(
            'The presentation video has not been submitted.',
            $result['reasons']
        );
    }

    public function test_submission_is_not_eligible_when_attendance_is_not_verified(): void
    {
        $submission =
            $this->createEligibleSubmission();

        ConferenceAttendance::where(
            'participant_id',
            $submission->participant_id
        )->update([
            'attendance_status' => 'checked_in',
            'verified_at' => null,
            'verified_by' => null,
        ]);

        $result =
            app(PublicationEligibilityService::class)
            ->evaluate($submission->fresh());

        $this->assertFalse(
            $result['eligible']
        );

        $this->assertFalse(
            $result['checks']['attendance']
        );

        $this->assertContains(
            'Conference attendance has not been verified.',
            $result['reasons']
        );
    }

    public function test_verified_attendance_of_another_participant_does_not_make_submission_eligible(): void
    {
        $submission =
            $this->createEligibleSubmission();

        ConferenceAttendance::where(
            'participant_id',
            $submission->participant_id
        )->delete();

        $conference = $submission->conference;

        $registrationType =
            $this->createPresenterRegistrationType(
                $conference
            );

        $otherParticipant =
            $this->createParticipant(
                $conference,
                $registrationType
            );

        ConferenceAttendance::create([
            'conference_id' => $conference->id,
            'participant_id' => $otherParticipant->id,
            'attendance_status' => 'verified',
            'checked_in_at' => now(),
            'verified_at' => now(),
            'verified_by' => User::factory()->create([
                'role' => 'admin',
                'status' => 'active',
            ])->id,
        ]);

        $result =
            app(PublicationEligibilityService::class)
            ->evaluate($submission->fresh());

        $this->assertFalse(
            $result['eligible']
        );

        $this->assertFalse(
            $result['checks']['attendance']
        );
    }

    public function test_submission_is_not_eligible_when_camera_ready_is_not_ready(): void
    {
        $submission =
            $this->createEligibleSubmission();

        $submission->camera_ready_status = 'revision';
        $submission->save();

        $result =
            app(PublicationEligibilityService::class)
            ->evaluate($submission->fresh());

        $this->assertFalse(
            $result['eligible']
        );

        $this->assertFalse(
            $result['checks']['camera_ready']
        );

        $this->assertContains(
            'The camera-ready submission is not ready for publication approval.',
            $result['reasons']
        );
    }

    public function test_submission_is_not_eligible_when_workflow_status_is_not_camera_ready(): void
    {
        $submission =
            $this->createEligibleSubmission();

        $submission->status = 'accepted';
        $submission->save();

        $result =
            app(PublicationEligibilityService::class)
            ->evaluate($submission->fresh());

        $this->assertFalse(
            $result['eligible']
        );

        $this->assertFalse(
            $result['checks']['workflow']
        );

        $this->assertContains(
            'The submission must be in Camera Ready status.',
            $result['reasons']
        );
    }
}
