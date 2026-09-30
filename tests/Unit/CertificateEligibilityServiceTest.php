<?php

namespace Tests\Unit;

use App\Models\Conference;
use App\Models\ConferenceAttendance;
use App\Models\Participant;
use App\Models\Reviewer;
use App\Models\Review;
use App\Models\Submission;
use App\Models\Topic;
use App\Models\User;
use App\Services\CertificateEligibilityService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CertificateEligibilityServiceTest extends TestCase
{
    use RefreshDatabase;

    private CertificateEligibilityService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service =
            app(CertificateEligibilityService::class);
    }

    public function test_confirmed_participant_with_verified_attendance_is_eligible(): void
    {
        $conference =
            $this->createConference();

        $participant =
            $this->createParticipant(
                $conference,
                [
                    'participant_type' => 'regular',
                    'registration_status' => 'confirmed',
                ]
            );

        $this->verifyAttendance(
            $conference,
            $participant
        );

        $result =
            $this->service->evaluate(
                $participant,
                'participant'
            );

        $this->assertTrue(
            $result['eligible']
        );

        $this->assertSame(
            [],
            $result['reasons']
        );
    }

    public function test_participant_without_verified_attendance_is_not_eligible(): void
    {
        $conference =
            $this->createConference();

        $participant =
            $this->createParticipant(
                $conference,
                [
                    'participant_type' => 'regular',
                    'registration_status' => 'confirmed',
                ]
            );

        $result =
            $this->service->evaluate(
                $participant,
                'participant'
            );

        $this->assertFalse(
            $result['eligible']
        );

        $this->assertContains(
            'Conference attendance must be verified.',
            $result['reasons']
        );
    }

    public function test_presenter_requires_published_submission_and_verified_attendance(): void
    {
        $conference =
            $this->createConference();

        $participant =
            $this->createParticipant(
                $conference,
                [
                    'participant_type' => 'presenter',
                    'registration_status' => 'confirmed',
                ]
            );

        $topic =
            Topic::create([
                'conference_id' => $conference->id,
                'name' => 'Artificial Intelligence',
                'description' => 'Test topic.',
                'is_active' => true,
                'sort_order' => 1,
            ]);

        $submission =
            Submission::create([
                'conference_id' => $conference->id,
                'participant_id' => $participant->id,
                'topic_id' => $topic->id,
                'submission_code' => 'TEST-' . uniqid(),
                'title' => 'Published Test Paper',
                'abstract' => 'Test abstract.',
                'keywords' => 'test, certificate',
                'paper_file' => 'submissions/papers/test.pdf',
                'status' => 'published',
                'submitted_at' => now(),
            ]);

        $this->verifyAttendance(
            $conference,
            $participant
        );

        $result =
            $this->service->evaluate(
                $participant,
                'presenter',
                $submission
            );

        $this->assertTrue(
            $result['eligible']
        );
    }

    public function test_speaker_requires_confirmed_registration_and_verified_attendance(): void
    {
        $conference =
            $this->createConference();

        $participant =
            $this->createParticipant(
                $conference,
                [
                    'participant_type' => 'speaker',
                    'registration_status' => 'confirmed',
                ]
            );

        $this->verifyAttendance(
            $conference,
            $participant
        );

        $result =
            $this->service->evaluate(
                $participant,
                'speaker'
            );

        $this->assertTrue(
            $result['eligible']
        );
    }

    public function test_committee_requires_confirmed_registration_and_verified_attendance(): void
    {
        $conference =
            $this->createConference();

        $participant =
            $this->createParticipant(
                $conference,
                [
                    'participant_type' => 'committee',
                    'registration_status' => 'confirmed',
                ]
            );

        $this->verifyAttendance(
            $conference,
            $participant
        );

        $result =
            $this->service->evaluate(
                $participant,
                'committee'
            );

        $this->assertTrue(
            $result['eligible']
        );
    }

    public function test_reviewer_is_eligible_when_all_assigned_reviews_are_completed(): void
    {
        $conference =
            $this->createConference();

        $user =
            User::factory()->create([
                'role' => 'reviewer',
                'status' => 'active',
            ]);

        $participant =
            $this->createParticipant(
                $conference,
                [
                    'user_id' => $user->id,
                    'participant_type' => 'regular',
                    'registration_status' => 'confirmed',
                ]
            );

        $reviewer =
            Reviewer::create([
                'conference_id' => $conference->id,
                'user_id' => $user->id,
                'expertise' => 'Artificial Intelligence',
                'institution' => 'Test University',
                'bio' => 'Test reviewer.',
                'is_active' => true,
            ]);

        $topic =
            Topic::create([
                'conference_id' => $conference->id,
                'name' => 'AI',
                'description' => 'Test topic.',
                'is_active' => true,
                'sort_order' => 1,
            ]);

        $submission =
            Submission::create([
                'conference_id' => $conference->id,
                'participant_id' => $participant->id,
                'topic_id' => $topic->id,
                'submission_code' => 'REVIEWER-' . uniqid(),
                'title' => 'Reviewer Test Paper',
                'abstract' => 'Test abstract.',
                'keywords' => 'reviewer',
                'paper_file' => 'submissions/papers/reviewer.pdf',
                'status' => 'submitted',
                'submitted_at' => now(),
            ]);

        Review::create([
            'submission_id' => $submission->id,
            'reviewer_id' => $reviewer->id,
            'review_stage' => 'abstract',
            'review_round' => 1,
            'score' => 85,
            'comment' => 'Good paper.',
            'recommendation' => 'accept',
            'reviewed_at' => now(),
        ]);

        $result =
            $this->service->evaluate(
                $participant,
                'reviewer'
            );

        $this->assertTrue(
            $result['eligible']
        );

        $this->assertTrue(
            $result['checks']['reviewer_assignment']
        );

        $this->assertTrue(
            $result['checks']['reviews_completed']
        );
    }

    public function test_reviewer_with_incomplete_review_is_not_eligible(): void
    {
        $conference =
            $this->createConference();

        $user =
            User::factory()->create([
                'role' => 'reviewer',
                'status' => 'active',
            ]);

        $participant =
            $this->createParticipant(
                $conference,
                [
                    'user_id' => $user->id,
                    'participant_type' => 'regular',
                    'registration_status' => 'confirmed',
                ]
            );

        $reviewer =
            Reviewer::create([
                'conference_id' => $conference->id,
                'user_id' => $user->id,
                'expertise' => 'Information Systems',
                'institution' => 'Test University',
                'bio' => 'Test reviewer.',
                'is_active' => true,
            ]);

        $topic =
            Topic::create([
                'conference_id' => $conference->id,
                'name' => 'Information Systems',
                'description' => 'Test topic.',
                'is_active' => true,
                'sort_order' => 1,
            ]);

        $submission =
            Submission::create([
                'conference_id' => $conference->id,
                'participant_id' => $participant->id,
                'topic_id' => $topic->id,
                'submission_code' => 'REVIEWER-INCOMPLETE-' . uniqid(),
                'title' => 'Incomplete Review Paper',
                'abstract' => 'Test abstract.',
                'keywords' => 'review',
                'paper_file' => 'submissions/papers/reviewer-incomplete.pdf',
                'status' => 'submitted',
                'submitted_at' => now(),
            ]);

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

        $result =
            $this->service->evaluate(
                $participant,
                'reviewer'
            );

        $this->assertFalse(
            $result['eligible']
        );

        $this->assertContains(
            'All reviews assigned to the reviewer must be completed.',
            $result['reasons']
        );
    }

    private function createConference(): Conference
    {
        return Conference::create([
            'name' => 'ICON Test Conference',
            'short_name' => 'ICONTEST',
            'year' => 2026,
            'theme' => 'Test Conference',
            'country' => 'Indonesia',
            'start_date' => '2026-08-27',
            'end_date' => '2026-08-27',
            'status' => 'submission_open',
        ]);
    }

    private function createParticipant(
        Conference $conference,
        array $overrides = []
    ): Participant {
        $user =
            array_key_exists('user_id', $overrides)
            && $overrides['user_id']
            ? User::find(
                $overrides['user_id']
            )
            : User::factory()->create([
                'role' => 'participant',
                'status' => 'active',
            ]);

        return Participant::create(
            array_merge([
                'user_id' => $user?->id,
                'conference_id' => $conference->id,
                'registration_type_id' => null,
                'registration_number' =>
                'REG-' . strtoupper(uniqid()),
                'full_name' => $user?->name ?? 'Test Participant',
                'email' => $user?->email ?? 'test@example.com',
                'country' => 'Indonesia',
                'attendance_type' => 'online',
                'participant_type' => 'regular',
                'registration_status' => 'confirmed',
                'registered_at' => now(),
            ], $overrides)
        );
    }

    private function verifyAttendance(
        Conference $conference,
        Participant $participant
    ): void {
        ConferenceAttendance::create([
            'conference_id' => $conference->id,
            'participant_id' => $participant->id,
            'checked_in_at' => now(),
            'attendance_status' => 'verified',
            'verified_at' => now(),
        ]);
    }
}
