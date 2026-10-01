<?php

namespace Tests\Feature;

use App\Models\Conference;
use App\Models\ConferenceAttendance;
use App\Models\ConferenceAttendanceOption;
use App\Models\ConferencePaymentMethod;
use App\Models\ConferenceRegistrationType;
use App\Models\ConferenceSetting;
use App\Models\Participant;
use App\Models\Payment;
use App\Models\Review;
use App\Models\Reviewer;
use App\Models\Submission;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SubmissionTest extends TestCase
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

    private function createParticipant(
        Conference $conference
    ): Participant {
        $user = User::factory()->create([
            'role' => 'participant',
            'status' => 'active',
        ]);

        return Participant::create([
            'user_id' => $user->id,
            'conference_id' => $conference->id,
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
            'code' => 'PRESENTER-TEST',
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

    private function createSeminarRegistrationType(
        Conference $conference
    ): ConferenceRegistrationType {
        return ConferenceRegistrationType::create([
            'conference_id' => $conference->id,
            'name' => 'Seminar',
            'code' => 'SEMINAR-TEST',
            'category' => 'participant',
            'fee' => 100000,
            'currency' => 'IDR',
            'included_papers' => 0,
            'additional_paper_fee' => 0,
            'payment_timing' => 'immediate',
            'is_active' => true,
            'sort_order' => 2,
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
        Topic $topic,
        array $attributes = []
    ): Submission {
        return Submission::create(array_merge([
            'conference_id' => $conference->id,
            'participant_id' => $participant->id,
            'topic_id' => $topic->id,
            'submission_code' => 'TEST-' . uniqid(),
            'title' => 'Test Submission',
            'abstract' => 'Test abstract.',
            'keywords' => 'test, submission',
            'submission_stage' => 'abstract',
            'status' => 'submitted',
            'submitted_at' => now(),
        ], $attributes));
    }

    private function createReviewer(
        Conference $conference
    ): Reviewer {
        $user = User::factory()->create([
            'role' => 'reviewer',
            'status' => 'active',
        ]);

        return Reviewer::create([
            'user_id' => $user->id,
            'conference_id' => $conference->id,
            'institution' => 'Test University',
            'is_active' => true,
        ]);
    }

    public function test_submission_can_store_author_metadata(): void
    {
        $conference = $this->createOpenConference();

        $participant = $this->createParticipant(
            $conference
        );

        $topic = $this->createTopic(
            $conference
        );

        $submission = Submission::create([
            'conference_id' => $conference->id,
            'participant_id' => $participant->id,
            'topic_id' => $topic->id,
            'submission_code' => 'ICON26-TEST0001',
            'title' => 'Test Submission',
            'abstract' => 'Test abstract.',
            'keywords' => 'test, submission',
            'submission_stage' => 'full_paper',
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        $submission->authors()->createMany([
            [
                'title_prefix' => 'Prof. Dr.',
                'name' => 'Budi Santoso',
                'title_suffix' => 'S.Kom., M.Kom.',
                'email' => 'budi@example.com',
                'orcid' => '0000-0002-1825-0097',
                'institution' => 'Universitas Bhamada Slawi',
                'department' => 'Fakultas Teknologi Informasi',
                'is_corresponding' => true,
                'sort_order' => 1,
            ],
            [
                'title_prefix' => 'Dr.',
                'name' => 'Siti Aminah',
                'title_suffix' => 'S.Farm., M.Farm.',
                'email' => 'siti@example.com',
                'orcid' => '0009-0001-2345-6789',
                'institution' => 'Universitas Bhamada Slawi',
                'department' => 'Fakultas Farmasi',
                'is_corresponding' => false,
                'sort_order' => 2,
            ],
        ]);

        $submission->load('authors');

        $this->assertCount(
            2,
            $submission->authors
        );

        $this->assertDatabaseHas('submission_authors', [
            'submission_id' => $submission->id,
            'title_prefix' => 'Prof. Dr.',
            'name' => 'Budi Santoso',
            'title_suffix' => 'S.Kom., M.Kom.',
            'orcid' => '0000-0002-1825-0097',
            'is_corresponding' => 1,
            'sort_order' => 1,
        ]);

        $this->assertDatabaseHas('submission_authors', [
            'submission_id' => $submission->id,
            'title_prefix' => 'Dr.',
            'name' => 'Siti Aminah',
            'title_suffix' => 'S.Farm., M.Farm.',
            'orcid' => '0009-0001-2345-6789',
            'is_corresponding' => 0,
            'sort_order' => 2,
        ]);

        $this->assertTrue(
            $submission->authors
                ->first()
                ->is_corresponding
        );

        $this->assertFalse(
            $submission->authors
                ->last()
                ->is_corresponding
        );
    }

    public function test_admin_can_create_submission_with_author_metadata(): void
    {
        Storage::fake('local');

        $conference = $this->createOpenConference();

        $participant = $this->createParticipant(
            $conference
        );

        $topic = $this->createTopic(
            $conference
        );

        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);

        $response = $this
            ->actingAs($admin)
            ->post(
                route('admin.submissions.store'),
                [
                    'conference_id' => $conference->id,
                    'participant_id' => $participant->id,
                    'topic_id' => $topic->id,
                    'title' => 'Test HTTP Submission',
                    'abstract' => 'Test abstract for HTTP submission.',
                    'keywords' => 'test, http, submission',
                    'paper_file' => UploadedFile::fake()->create(
                        'test-paper.pdf',
                        100,
                        'application/pdf'
                    ),
                    'status' => 'submitted',
                    'submitted_at' => now()->format(
                        'Y-m-d\TH:i'
                    ),

                    'authors' => [
                        [
                            'title_prefix' => 'Prof. Dr.',
                            'name' => 'Budi Santoso',
                            'title_suffix' => 'S.Kom., M.Kom.',
                            'orcid' => '0000-0002-1825-0097',
                            'email' => 'budi@example.com',
                            'institution' => 'Universitas Bhamada Slawi',
                            'department' => 'Fakultas Teknologi Informasi',
                            'is_corresponding' => 1,
                            'sort_order' => 1,
                        ],
                        [
                            'title_prefix' => 'Dr.',
                            'name' => 'Siti Aminah',
                            'title_suffix' => 'S.Farm., M.Farm.',
                            'orcid' => '0009-0001-2345-6789',
                            'email' => 'siti@example.com',
                            'institution' => 'Universitas Bhamada Slawi',
                            'department' => 'Fakultas Farmasi',
                            'is_corresponding' => 0,
                            'sort_order' => 2,
                        ],
                    ],
                ]
            );

        $response->assertRedirect(
            route('admin.submissions.index')
        );

        $this->assertDatabaseHas(
            'submissions',
            [
                'conference_id' => $conference->id,
                'participant_id' => $participant->id,
                'topic_id' => $topic->id,
                'title' => 'Test HTTP Submission',
                'status' => 'submitted',
            ]
        );

        $submission = Submission::latest()->first();

        $this->assertDatabaseHas('submission_authors', [
            'submission_id' => $submission->id,
            'title_prefix' => 'Prof. Dr.',
            'name' => 'Budi Santoso',
            'title_suffix' => 'S.Kom., M.Kom.',
            'orcid' => '0000-0002-1825-0097',
            'is_corresponding' => 1,
            'sort_order' => 1,
        ]);

        $this->assertDatabaseHas('submission_authors', [
            'submission_id' => $submission->id,
            'title_prefix' => 'Dr.',
            'name' => 'Siti Aminah',
            'title_suffix' => 'S.Farm., M.Farm.',
            'orcid' => '0009-0001-2345-6789',
            'is_corresponding' => 0,
            'sort_order' => 2,
        ]);
    }

    public function test_reviewer_can_review_same_submission_in_different_stages(): void
    {
        $conference = $this->createOpenConference();

        $participant = $this->createParticipant(
            $conference
        );

        $topic = $this->createTopic(
            $conference
        );

        $submission = $this->createSubmission(
            $conference,
            $participant,
            $topic
        );

        $reviewer = $this->createReviewer(
            $conference
        );

        Review::create([
            'submission_id' => $submission->id,
            'reviewer_id' => $reviewer->id,
            'review_stage' => 'abstract',
            'review_round' => 1,
            'score' => 80,
            'comment' => 'Good abstract.',
            'recommendation' => 'accept',
            'reviewed_at' => now(),
        ]);

        $submission->update([
            'submission_stage' => 'full_paper',
            'status' => 'submitted',
        ]);

        Review::create([
            'submission_id' => $submission->id,
            'reviewer_id' => $reviewer->id,
            'review_stage' => 'full_paper',
            'review_round' => 1,
            'score' => 85,
            'comment' => 'Good full paper.',
            'recommendation' => 'accept',
            'reviewed_at' => now(),
        ]);

        $this->assertDatabaseCount(
            'reviews',
            2
        );

        $this->assertDatabaseHas('reviews', [
            'submission_id' => $submission->id,
            'reviewer_id' => $reviewer->id,
            'review_stage' => 'abstract',
            'review_round' => 1,
        ]);

        $this->assertDatabaseHas('reviews', [
            'submission_id' => $submission->id,
            'reviewer_id' => $reviewer->id,
            'review_stage' => 'full_paper',
            'review_round' => 1,
        ]);
    }

    public function test_same_reviewer_cannot_be_assigned_twice_in_same_stage_and_round(): void
    {
        $conference = $this->createOpenConference();

        $participant = $this->createParticipant(
            $conference
        );

        $topic = $this->createTopic(
            $conference
        );

        $submission = $this->createSubmission(
            $conference,
            $participant,
            $topic
        );

        $reviewer = $this->createReviewer(
            $conference
        );

        Review::create([
            'submission_id' => $submission->id,
            'reviewer_id' => $reviewer->id,
            'review_stage' => 'abstract',
            'review_round' => 1,
        ]);

        $this->expectException(
            \Illuminate\Database\QueryException::class
        );

        Review::create([
            'submission_id' => $submission->id,
            'reviewer_id' => $reviewer->id,
            'review_stage' => 'abstract',
            'review_round' => 1,
        ]);
    }

    public function test_submission_remains_under_review_until_all_reviewers_complete(): void
    {
        Mail::fake();

        $conference = $this->createOpenConference();
        $participant = $this->createParticipant($conference);
        $topic = $this->createTopic($conference);

        $submission = $this->createSubmission(
            $conference,
            $participant,
            $topic,
            [
                'submission_stage' => 'abstract',
                'status' => 'under_review',
            ]
        );

        $reviewer1 = $this->createReviewer($conference);
        $reviewer2 = $this->createReviewer($conference);

        $review1 = Review::create([
            'submission_id' => $submission->id,
            'reviewer_id' => $reviewer1->id,
            'review_stage' => 'abstract',
            'review_round' => 1,
            'score' => null,
            'comment' => null,
            'recommendation' => null,
            'reviewed_at' => null,
        ]);

        $review2 = Review::create([
            'submission_id' => $submission->id,
            'reviewer_id' => $reviewer2->id,
            'review_stage' => 'abstract',
            'review_round' => 1,
            'score' => null,
            'comment' => null,
            'recommendation' => null,
            'reviewed_at' => null,
        ]);

        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);

        $this
            ->actingAs($admin)
            ->put(route('admin.reviews.update', $review1), [
                'score' => 85,
                'comment' => 'Good abstract.',
                'recommendation' => 'accept',
            ])
            ->assertRedirect();

        $this->assertEquals(
            'under_review',
            $submission->fresh()->status
        );

        $this->assertDatabaseHas('reviews', [
            'id' => $review1->id,
            'recommendation' => 'accept',
        ]);

        $this->assertDatabaseHas('reviews', [
            'id' => $review2->id,
            'reviewed_at' => null,
        ]);
    }

    public function test_submission_is_accepted_when_all_reviewers_accept(): void
    {
        Mail::fake();

        $conference = $this->createOpenConference();
        $participant = $this->createParticipant($conference);
        $topic = $this->createTopic($conference);

        $submission = $this->createSubmission(
            $conference,
            $participant,
            $topic,
            [
                'submission_stage' => 'abstract',
                'status' => 'under_review',
            ]
        );

        $reviewer1 = $this->createReviewer($conference);
        $reviewer2 = $this->createReviewer($conference);

        $review1 = Review::create([
            'submission_id' => $submission->id,
            'reviewer_id' => $reviewer1->id,
            'review_stage' => 'abstract',
            'review_round' => 1,
            'score' => null,
            'comment' => null,
            'recommendation' => null,
            'reviewed_at' => null,
        ]);

        $review2 = Review::create([
            'submission_id' => $submission->id,
            'reviewer_id' => $reviewer2->id,
            'review_stage' => 'abstract',
            'review_round' => 1,
            'score' => null,
            'comment' => null,
            'recommendation' => null,
            'reviewed_at' => null,
        ]);

        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);

        $this
            ->actingAs($admin)
            ->put(route('admin.reviews.update', $review1), [
                'score' => 85,
                'comment' => 'Good abstract.',
                'recommendation' => 'accept',
            ])
            ->assertRedirect();

        $this->assertEquals(
            'under_review',
            $submission->fresh()->status
        );

        $this
            ->actingAs($admin)
            ->put(route('admin.reviews.update', $review2), [
                'score' => 90,
                'comment' => 'Excellent abstract.',
                'recommendation' => 'accept',
            ])
            ->assertRedirect();

        $this->assertEquals(
            'accepted',
            $submission->fresh()->status
        );

        $this->assertDatabaseHas('reviews', [
            'id' => $review1->id,
            'recommendation' => 'accept',
        ]);

        $this->assertDatabaseHas('reviews', [
            'id' => $review2->id,
            'recommendation' => 'accept',
        ]);
    }

    public function test_submission_requires_revision_when_reviewer_recommends_minor_revision(): void
    {
        Mail::fake();

        $conference = $this->createOpenConference();
        $participant = $this->createParticipant($conference);
        $topic = $this->createTopic($conference);

        $submission = $this->createSubmission(
            $conference,
            $participant,
            $topic,
            [
                'submission_stage' => 'abstract',
                'status' => 'under_review',
            ]
        );

        $reviewer1 = $this->createReviewer($conference);
        $reviewer2 = $this->createReviewer($conference);

        $review1 = Review::create([
            'submission_id' => $submission->id,
            'reviewer_id' => $reviewer1->id,
            'review_stage' => 'abstract',
            'review_round' => 1,
            'score' => null,
            'comment' => null,
            'recommendation' => null,
            'reviewed_at' => null,
        ]);

        $review2 = Review::create([
            'submission_id' => $submission->id,
            'reviewer_id' => $reviewer2->id,
            'review_stage' => 'abstract',
            'review_round' => 1,
            'score' => null,
            'comment' => null,
            'recommendation' => null,
            'reviewed_at' => null,
        ]);

        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);

        $this
            ->actingAs($admin)
            ->put(route('admin.reviews.update', $review1), [
                'score' => 88,
                'comment' => 'Good paper.',
                'recommendation' => 'accept',
            ])
            ->assertRedirect();

        $this
            ->actingAs($admin)
            ->put(route('admin.reviews.update', $review2), [
                'score' => 75,
                'comment' => 'Some improvements are needed.',
                'recommendation' => 'minor_revision',
            ])
            ->assertRedirect();

        $this->assertEquals(
            'revision',
            $submission->fresh()->status
        );
    }

    public function test_submission_is_rejected_when_a_reviewer_rejects(): void
    {
        Mail::fake();

        $conference = $this->createOpenConference();
        $participant = $this->createParticipant($conference);
        $topic = $this->createTopic($conference);

        $submission = $this->createSubmission(
            $conference,
            $participant,
            $topic,
            [
                'submission_stage' => 'abstract',
                'status' => 'under_review',
            ]
        );

        $reviewer1 = $this->createReviewer($conference);
        $reviewer2 = $this->createReviewer($conference);

        $review1 = Review::create([
            'submission_id' => $submission->id,
            'reviewer_id' => $reviewer1->id,
            'review_stage' => 'abstract',
            'review_round' => 1,
            'score' => null,
            'comment' => null,
            'recommendation' => null,
            'reviewed_at' => null,
        ]);

        $review2 = Review::create([
            'submission_id' => $submission->id,
            'reviewer_id' => $reviewer2->id,
            'review_stage' => 'abstract',
            'review_round' => 1,
            'score' => null,
            'comment' => null,
            'recommendation' => null,
            'reviewed_at' => null,
        ]);

        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);

        $this
            ->actingAs($admin)
            ->put(route('admin.reviews.update', $review1), [
                'score' => 85,
                'comment' => 'Good paper.',
                'recommendation' => 'accept',
            ])
            ->assertRedirect();

        $this
            ->actingAs($admin)
            ->put(route('admin.reviews.update', $review2), [
                'score' => 30,
                'comment' => 'The paper does not meet the requirements.',
                'recommendation' => 'reject',
            ])
            ->assertRedirect();

        $this->assertEquals(
            'rejected',
            $submission->fresh()->status
        );
    }

    public function test_upload_revision_creates_next_review_round_with_previous_reviewers(): void
    {
        Storage::fake('local');

        $conference = $this->createOpenConference();

        $participant = $this->createParticipant(
            $conference
        );

        $registrationType =
            $this->createPresenterRegistrationType(
                $conference
            );

        $participant->update([
            'registration_type_id' =>
            $registrationType->id,

            'participant_type' =>
            'presenter',
        ]);

        $topic = $this->createTopic(
            $conference
        );

        $participantUser = User::findOrFail($participant->user_id);

        // Route participant mewajibkan email terverifikasi.
        $participantUser->markEmailAsVerified();

        $submission = $this->createSubmission(
            $conference,
            $participant,
            $topic,
            [
                'submission_stage' => 'full_paper',
                'status' => 'revision',
                'revised_file' => null,
            ]
        );

        $reviewer1 = $this->createReviewer($conference);
        $reviewer2 = $this->createReviewer($conference);

        Review::create([
            'submission_id' => $submission->id,
            'reviewer_id' => $reviewer1->id,
            'review_stage' => 'full_paper',
            'review_round' => 1,
            'score' => 70,
            'comment' => 'Needs improvement.',
            'recommendation' => 'major_revision',
            'reviewed_at' => now(),
        ]);

        Review::create([
            'submission_id' => $submission->id,
            'reviewer_id' => $reviewer2->id,
            'review_stage' => 'full_paper',
            'review_round' => 1,
            'score' => 75,
            'comment' => 'Minor improvements required.',
            'recommendation' => 'minor_revision',
            'reviewed_at' => now(),
        ]);

        $response = $this
            ->actingAs($participantUser)
            ->post(
                route(
                    'participant.submissions.revision.upload',
                    $submission
                ),
                [
                    'revised_file' => UploadedFile::fake()->create(
                        'revised-paper.pdf',
                        100,
                        'application/pdf'
                    ),
                ]
            );

        $response->assertRedirect(
            route(
                'participant.submissions.show',
                $submission
            )
        );

        $this->assertEquals(
            'under_review',
            $submission->fresh()->status
        );

        $this->assertDatabaseHas('reviews', [
            'submission_id' => $submission->id,
            'reviewer_id' => $reviewer1->id,
            'review_stage' => 'full_paper',
            'review_round' => 2,
            'score' => null,
            'comment' => null,
            'recommendation' => null,
            'reviewed_at' => null,
        ]);

        $this->assertDatabaseHas('reviews', [
            'submission_id' => $submission->id,
            'reviewer_id' => $reviewer2->id,
            'review_stage' => 'full_paper',
            'review_round' => 2,
            'score' => null,
            'comment' => null,
            'recommendation' => null,
            'reviewed_at' => null,
        ]);

        $this->assertDatabaseCount('reviews', 4);

        Storage::disk('local')->assertExists(
            $submission->fresh()->revised_file
        );
    }

    public function test_second_revision_creates_round_three_without_duplicating_previous_round(): void
    {
        Storage::fake('local');
        Mail::fake();

        $conference = $this->createOpenConference();

        $participant = $this->createParticipant(
            $conference
        );

        $registrationType =
            $this->createPresenterRegistrationType(
                $conference
            );

        $participant->update([
            'registration_type_id' =>
            $registrationType->id,

            'participant_type' =>
            'presenter',
        ]);

        $topic = $this->createTopic(
            $conference
        );

        $participantUser = User::findOrFail(
            $participant->user_id
        );

        $participantUser->markEmailAsVerified();

        $submission = $this->createSubmission(
            $conference,
            $participant,
            $topic,
            [
                'submission_stage' => 'full_paper',
                'status' => 'revision',
                'revised_file' => null,
            ]
        );

        $reviewer1 = $this->createReviewer($conference);
        $reviewer2 = $this->createReviewer($conference);

        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);

        /*
    |--------------------------------------------------------------------------
    | ROUND 1
    |--------------------------------------------------------------------------
    */

        Review::create([
            'submission_id' => $submission->id,
            'reviewer_id' => $reviewer1->id,
            'review_stage' => 'full_paper',
            'review_round' => 1,
            'score' => 70,
            'comment' => 'Needs major revision.',
            'recommendation' => 'major_revision',
            'reviewed_at' => now(),
        ]);

        Review::create([
            'submission_id' => $submission->id,
            'reviewer_id' => $reviewer2->id,
            'review_stage' => 'full_paper',
            'review_round' => 1,
            'score' => 75,
            'comment' => 'Needs revision.',
            'recommendation' => 'minor_revision',
            'reviewed_at' => now(),
        ]);

        /*
    |--------------------------------------------------------------------------
    | PARTICIPANT UPLOAD REVISION → ROUND 2
    |--------------------------------------------------------------------------
    */

        $this
            ->actingAs($participantUser)
            ->post(
                route(
                    'participant.submissions.revision.upload',
                    $submission
                ),
                [
                    'revised_file' => UploadedFile::fake()->create(
                        'revision-round-2.pdf',
                        100,
                        'application/pdf'
                    ),
                ]
            )
            ->assertRedirect();

        $this->assertEquals(
            'under_review',
            $submission->fresh()->status
        );

        /*
    |--------------------------------------------------------------------------
    | ROUND 2
    |--------------------------------------------------------------------------
    */

        $round2Reviewer1 = Review::where(
            'submission_id',
            $submission->id
        )
            ->where(
                'reviewer_id',
                $reviewer1->id
            )
            ->where(
                'review_stage',
                'full_paper'
            )
            ->where(
                'review_round',
                2
            )
            ->firstOrFail();

        $round2Reviewer2 = Review::where(
            'submission_id',
            $submission->id
        )
            ->where(
                'reviewer_id',
                $reviewer2->id
            )
            ->where(
                'review_stage',
                'full_paper'
            )
            ->where(
                'review_round',
                2
            )
            ->firstOrFail();

        /*
    |--------------------------------------------------------------------------
    | Reviewer 1 → ACCEPT
    |--------------------------------------------------------------------------
    */

        $this
            ->actingAs($admin)
            ->put(
                route(
                    'admin.reviews.update',
                    $round2Reviewer1
                ),
                [
                    'score' => 85,
                    'comment' => 'Revision is acceptable.',
                    'recommendation' => 'accept',
                ]
            )
            ->assertRedirect();

        $this->assertEquals(
            'under_review',
            $submission->fresh()->status
        );

        /*
    |--------------------------------------------------------------------------
    | Reviewer 2 → MINOR REVISION
    |--------------------------------------------------------------------------
    */

        $this
            ->actingAs($admin)
            ->put(
                route(
                    'admin.reviews.update',
                    $round2Reviewer2
                ),
                [
                    'score' => 80,
                    'comment' => 'One final minor improvement is needed.',
                    'recommendation' => 'minor_revision',
                ]
            )
            ->assertRedirect();

        /*
    |--------------------------------------------------------------------------
    | Round 2 should now require revision
    |--------------------------------------------------------------------------
    */

        $this->assertEquals(
            'revision',
            $submission->fresh()->status
        );

        /*
    |--------------------------------------------------------------------------
    | PARTICIPANT UPLOAD REVISION AGAIN → ROUND 3
    |--------------------------------------------------------------------------
    */

        $this
            ->actingAs($participantUser)
            ->post(
                route(
                    'participant.submissions.revision.upload',
                    $submission
                ),
                [
                    'revised_file' => UploadedFile::fake()->create(
                        'revision-round-3.pdf',
                        100,
                        'application/pdf'
                    ),
                ]
            )
            ->assertRedirect();

        $submission->refresh();

        $this->assertEquals(
            'under_review',
            $submission->status
        );

        /*
    |--------------------------------------------------------------------------
    | Verify total reviews
    |--------------------------------------------------------------------------
    */

        $this->assertDatabaseCount(
            'reviews',
            6
        );

        /*
    |--------------------------------------------------------------------------
    | Reviewer 1 exists in rounds 1, 2, and 3
    |--------------------------------------------------------------------------
    */

        $this->assertDatabaseHas('reviews', [
            'submission_id' => $submission->id,
            'reviewer_id' => $reviewer1->id,
            'review_stage' => 'full_paper',
            'review_round' => 1,
        ]);

        $this->assertDatabaseHas('reviews', [
            'submission_id' => $submission->id,
            'reviewer_id' => $reviewer1->id,
            'review_stage' => 'full_paper',
            'review_round' => 2,
        ]);

        $this->assertDatabaseHas('reviews', [
            'submission_id' => $submission->id,
            'reviewer_id' => $reviewer1->id,
            'review_stage' => 'full_paper',
            'review_round' => 3,
            'score' => null,
            'comment' => null,
            'recommendation' => null,
            'reviewed_at' => null,
        ]);

        /*
    |--------------------------------------------------------------------------
    | Reviewer 2 exists in rounds 1, 2, and 3
    |--------------------------------------------------------------------------
    */

        $this->assertDatabaseHas('reviews', [
            'submission_id' => $submission->id,
            'reviewer_id' => $reviewer2->id,
            'review_stage' => 'full_paper',
            'review_round' => 1,
        ]);

        $this->assertDatabaseHas('reviews', [
            'submission_id' => $submission->id,
            'reviewer_id' => $reviewer2->id,
            'review_stage' => 'full_paper',
            'review_round' => 2,
            'recommendation' => 'minor_revision',
        ]);

        $this->assertDatabaseHas('reviews', [
            'submission_id' => $submission->id,
            'reviewer_id' => $reviewer2->id,
            'review_stage' => 'full_paper',
            'review_round' => 3,
            'score' => null,
            'comment' => null,
            'recommendation' => null,
            'reviewed_at' => null,
        ]);
    }

    public function test_same_reviewer_can_be_assigned_again_in_next_revision_round(): void
    {
        $conference = $this->createOpenConference();
        $participant = $this->createParticipant($conference);
        $topic = $this->createTopic($conference);

        $submission = $this->createSubmission(
            $conference,
            $participant,
            $topic,
            [
                'submission_stage' => 'full_paper',
                'status' => 'revision',
            ]
        );

        $reviewer = $this->createReviewer($conference);

        /*
        |--------------------------------------------------------------------------
        | Round 1
        |--------------------------------------------------------------------------
        */

        Review::create([
            'submission_id' => $submission->id,
            'reviewer_id' => $reviewer->id,
            'review_stage' => 'full_paper',
            'review_round' => 1,
            'score' => 70,
            'comment' => 'Needs revision.',
            'recommendation' => 'major_revision',
            'reviewed_at' => now(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Round 2
        |--------------------------------------------------------------------------
        */

        Review::create([
            'submission_id' => $submission->id,
            'reviewer_id' => $reviewer->id,
            'review_stage' => 'full_paper',
            'review_round' => 2,
            'score' => null,
            'comment' => null,
            'recommendation' => null,
            'reviewed_at' => null,
        ]);

        $this->assertDatabaseCount('reviews', 2);

        $this->assertDatabaseHas('reviews', [
            'submission_id' => $submission->id,
            'reviewer_id' => $reviewer->id,
            'review_stage' => 'full_paper',
            'review_round' => 1,
            'recommendation' => 'major_revision',
        ]);

        $this->assertDatabaseHas('reviews', [
            'submission_id' => $submission->id,
            'reviewer_id' => $reviewer->id,
            'review_stage' => 'full_paper',
            'review_round' => 2,
            'recommendation' => null,
        ]);
    }

    public function test_admin_can_request_camera_ready_correction_and_participant_can_resubmit(): void
    {
        Storage::fake('local');
        Mail::fake();
        Queue::fake();

        $conference = $this->createOpenConference();

        $registrationType = ConferenceRegistrationType::create([
            'conference_id' => $conference->id,
            'name' => 'Presenter',
            'code' => 'PRESENTER-TEST',
            'category' => 'presenter',
            'fee' => 250000,
            'currency' => 'IDR',
            'included_papers' => 1,
            'additional_paper_fee' => 0,
            'payment_timing' => 'immediate',
            'is_active' => true,
        ]);

        $participant = $this->createParticipant($conference);

        $participant->update([
            'registration_type_id' => $registrationType->id,
        ]);

        $topic = $this->createTopic($conference);

        $participant->user->markEmailAsVerified();

        $submission = $this->createSubmission(
            $conference,
            $participant,
            $topic,
            [
                'submission_stage' => 'full_paper',
                'status' => 'camera_ready',
                'video_url' => 'https://drive.google.com/file/d/test-video-id/view',
                'camera_ready_file' => 'submissions/camera-ready/old-camera-ready.pdf',
            ]
        );

        $author = \App\Models\SubmissionAuthor::create([
            'submission_id' => $submission->id,
            'name' => 'Presenter Test',
            'email' => $participant->email,
            'institution' => 'Test University',
            'is_corresponding' => true,
            'sort_order' => 1,
        ]);

        $submission->update([
            'presenter_author_id' => $author->id,
        ]);

        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);

        $paymentMethod = ConferencePaymentMethod::create([
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

        Payment::create([
            'participant_id' => $participant->id,
            'payment_method_id' => $paymentMethod->id,
            'payment_code' => 'PAY-TEST-CAMERA-READY',
            'amount' => 250000,
            'proof_file' => 'payments/proofs/test-camera-ready.pdf',
            'status' => 'verified',
            'paid_at' => now(),
            'verified_at' => now(),
            'verified_by' => $admin->id,
        ]);

        Storage::disk('local')->put(
            'submissions/camera-ready/old-camera-ready.pdf',
            'OLD CAMERA READY'
        );

        $this->actingAs($admin)
            ->patch(
                route(
                    'admin.submissions.camera-ready.correction',
                    $submission
                ),
                [
                    'correction_reason' =>
                    'Please correct the author affiliation and update Figure 2.',
                ]
            )
            ->assertRedirect(
                route('admin.submissions.show', $submission)
            );

        Queue::assertPushed(
            \App\Jobs\SendCameraReadyCorrectionWhatsApp::class
        );

        $submission->refresh();

        $this->assertSame(
            'accepted',
            $submission->status
        );

        $this->assertSame(
            'revision',
            $submission->camera_ready_status
        );

        $this->assertSame(
            'Please correct the author affiliation and update Figure 2.',
            $submission->camera_ready_correction_reason
        );

        Mail::assertQueued(
            \App\Mail\SubmissionStatusMail::class
        );

        $newFile = UploadedFile::fake()->create(
            'camera-ready-v2.pdf',
            200,
            'application/pdf'
        );

        $this->actingAs($participant->user)
            ->post(
                route(
                    'participant.submissions.camera-ready.upload',
                    $submission
                ),
                [
                    'camera_ready_file' => $newFile,
                ]
            )
            ->assertRedirect(
                route('participant.submissions.show', $submission)
            );

        $submission->refresh();

        $this->assertSame(
            'camera_ready',
            $submission->status
        );

        $this->assertSame(
            'submitted',
            $submission->camera_ready_status
        );

        $this->assertNull(
            $submission->camera_ready_correction_reason
        );

        $this->assertNotSame(
            'submissions/camera-ready/old-camera-ready.pdf',
            $submission->camera_ready_file
        );

        Storage::disk('local')->assertMissing(
            'submissions/camera-ready/old-camera-ready.pdf'
        );

        Storage::disk('local')->assertExists(
            $submission->camera_ready_file
        );
    }

    public function test_admin_can_approve_camera_ready_and_set_approved_status(): void
    {
        Storage::fake('local');
        Storage::fake('public');
        Mail::fake();
        Queue::fake();

        $conference = $this->createOpenConference();

        $registrationType =
            $this->createPresenterRegistrationType(
                $conference
            );

        $participant =
            $this->createParticipant(
                $conference
            );

        $participant->update([
            'registration_type_id' => $registrationType->id,
            'participant_type' => 'presenter',
        ]);

        $topic =
            $this->createTopic(
                $conference
            );

        $submission =
            $this->createSubmission(
                $conference,
                $participant,
                $topic,
                [
                    'submission_stage' => 'full_paper',
                    'status' => 'camera_ready',
                    'video_url' =>
                    'https://drive.google.com/file/d/test-video-id/view',
                    'video_submitted_at' => now(),
                    'camera_ready_file' =>
                    'submissions/camera-ready/test-camera-ready.pdf',
                ]
            );

        $submission->camera_ready_status = 'submitted';
        $submission->save();

        $author = \App\Models\SubmissionAuthor::create([
            'submission_id' => $submission->id,
            'name' => $participant->full_name,
            'email' => $participant->email,
            'institution' => $participant->institution,
            'is_corresponding' => true,
            'sort_order' => 1,
        ]);

        $submission->update([
            'presenter_author_id' => $author->id,
        ]);

        Storage::disk('local')->put(
            'submissions/camera-ready/test-camera-ready.pdf',
            'TEST CAMERA READY'
        );

        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);

        ConferenceAttendance::create([
            'conference_id' => $conference->id,
            'participant_id' => $participant->id,
            'attendance_status' => 'verified',
            'checked_in_at' => now(),
            'verified_at' => now(),
            'verified_by' => $admin->id,
        ]);

        $this->actingAs($admin)
            ->patch(
                route(
                    'admin.submissions.camera-ready.approve',
                    $submission
                )
            )
            ->assertRedirect(
                route(
                    'admin.submissions.show',
                    $submission
                )
            );

        $submission->refresh();

        $this->assertSame(
            'published',
            $submission->status
        );

        $this->assertSame(
            'approved',
            $submission->camera_ready_status
        );
    }

    public function test_admin_cannot_publish_without_presentation_video(): void
    {
        Storage::fake('local');

        $conference = $this->createOpenConference();

        $registrationType =
            $this->createPresenterRegistrationType(
                $conference
            );

        $participant =
            $this->createParticipant(
                $conference
            );

        $participant->update([
            'registration_type_id' => $registrationType->id,
            'participant_type' => 'presenter',
        ]);

        $topic =
            $this->createTopic(
                $conference
            );

        $submission =
            $this->createSubmission(
                $conference,
                $participant,
                $topic,
                [
                    'submission_stage' => 'full_paper',
                    'status' => 'camera_ready',
                    'camera_ready_file' =>
                    'submissions/camera-ready/test-no-video.pdf',
                ]
            );

        $submission->camera_ready_status = 'submitted';
        $submission->save();

        $author = \App\Models\SubmissionAuthor::create([
            'submission_id' => $submission->id,
            'name' => $participant->full_name,
            'email' => $participant->email,
            'institution' => $participant->institution,
            'is_corresponding' => true,
            'sort_order' => 1,
        ]);

        $submission->presenter_author_id = $author->id;
        $submission->save();

        Storage::disk('local')->put(
            'submissions/camera-ready/test-no-video.pdf',
            'TEST CAMERA READY'
        );

        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);

        \App\Models\ConferenceAttendance::create([
            'conference_id' => $conference->id,
            'participant_id' => $participant->id,
            'attendance_status' => 'verified',
            'checked_in_at' => now(),
            'verified_at' => now(),
            'verified_by' => $admin->id,
        ]);

        $response = $this
            ->actingAs($admin)
            ->patch(
                route(
                    'admin.submissions.camera-ready.approve',
                    $submission
                )
            );

        $response->assertRedirect();

        $response->assertSessionHas(
            'error',
            'Cannot publish this submission: The presentation video has not been submitted.'
        );

        $submission->refresh();

        $this->assertSame(
            'camera_ready',
            $submission->status
        );

        $this->assertSame(
            'submitted',
            $submission->camera_ready_status
        );
    }

    public function test_admin_cannot_publish_without_verified_attendance(): void
    {
        Storage::fake('local');

        $conference = $this->createOpenConference();

        $registrationType =
            $this->createPresenterRegistrationType(
                $conference
            );

        $participant =
            $this->createParticipant(
                $conference
            );

        $participant->update([
            'registration_type_id' => $registrationType->id,
            'participant_type' => 'presenter',
        ]);

        $topic =
            $this->createTopic(
                $conference
            );

        $submission =
            $this->createSubmission(
                $conference,
                $participant,
                $topic,
                [
                    'submission_stage' => 'full_paper',
                    'status' => 'camera_ready',
                    'camera_ready_file' =>
                    'submissions/camera-ready/test-no-attendance.pdf',
                    'video_url' =>
                    'https://drive.google.com/file/d/test-video-id/view',
                    'video_submitted_at' => now(),
                ]
            );

        $submission->camera_ready_status = 'submitted';
        $submission->save();

        $author = \App\Models\SubmissionAuthor::create([
            'submission_id' => $submission->id,
            'name' => $participant->full_name,
            'email' => $participant->email,
            'institution' => $participant->institution,
            'is_corresponding' => true,
            'sort_order' => 1,
        ]);

        $submission->presenter_author_id = $author->id;
        $submission->save();

        Storage::disk('local')->put(
            'submissions/camera-ready/test-no-attendance.pdf',
            'TEST CAMERA READY'
        );

        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);

        $response = $this
            ->actingAs($admin)
            ->patch(
                route(
                    'admin.submissions.camera-ready.approve',
                    $submission
                )
            );

        $response->assertRedirect();

        $response->assertSessionHas(
            'error',
            'Cannot publish this submission: Conference attendance has not been verified.'
        );

        $submission->refresh();

        $this->assertSame(
            'camera_ready',
            $submission->status
        );

        $this->assertSame(
            'submitted',
            $submission->camera_ready_status
        );
    }

    public function test_admin_cannot_create_published_submission_through_standard_form(): void
    {
        $conference = $this->createOpenConference();

        $registrationType =
            $this->createPresenterRegistrationType(
                $conference
            );

        $participant =
            $this->createParticipant(
                $conference
            );

        $participant->update([
            'registration_type_id' => $registrationType->id,
            'participant_type' => 'presenter',
        ]);

        $topic =
            $this->createTopic(
                $conference
            );

        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);

        $response = $this
            ->actingAs($admin)
            ->post(
                route('admin.submissions.store'),
                [
                    'conference_id' => $conference->id,
                    'participant_id' => $participant->id,
                    'topic_id' => $topic->id,
                    'title' => 'Published Bypass Test',
                    'abstract' => 'Test abstract.',
                    'keywords' => 'test, publication',
                    'status' => 'published',
                    'authors' => [
                        [
                            'name' => 'Test Presenter',
                            'email' => $participant->email,
                            'institution' => 'Test University',
                            'department' => 'Test Department',
                            'is_corresponding' => true,
                            'sort_order' => 1,
                        ],
                    ],
                ]
            );

        $response->assertSessionHasErrors([
            'status',
        ]);

        $this->assertDatabaseMissing('submissions', [
            'title' => 'Published Bypass Test',
        ]);
    }

    public function test_admin_cannot_update_submission_to_published_through_standard_form(): void
    {
        $conference = $this->createOpenConference();

        $registrationType =
            $this->createPresenterRegistrationType(
                $conference
            );

        $participant =
            $this->createParticipant(
                $conference
            );

        $participant->update([
            'registration_type_id' => $registrationType->id,
            'participant_type' => 'presenter',
        ]);

        $topic =
            $this->createTopic(
                $conference
            );

        $submission =
            $this->createSubmission(
                $conference,
                $participant,
                $topic,
                [
                    'submission_stage' => 'full_paper',
                    'status' => 'camera_ready',
                ]
            );

        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);

        $response = $this
            ->actingAs($admin)
            ->put(
                route(
                    'admin.submissions.update',
                    $submission
                ),
                [
                    'conference_id' => $conference->id,
                    'participant_id' => $participant->id,
                    'topic_id' => $topic->id,
                    'title' => $submission->title,
                    'abstract' => $submission->abstract,
                    'keywords' => $submission->keywords,
                    'status' => 'published',
                    'authors' => [
                        [
                            'name' => 'Test Presenter',
                            'email' => $participant->email,
                            'institution' => 'Test University',
                            'department' => 'Test Department',
                            'is_corresponding' => true,
                            'sort_order' => 1,
                        ],
                    ],
                ]
            );

        $response->assertSessionHasErrors([
            'status',
        ]);

        $submission->refresh();

        $this->assertSame(
            'camera_ready',
            $submission->status
        );
    }

    public function test_published_submission_cannot_be_edited_through_standard_form(): void
    {
        $conference = $this->createOpenConference();

        $registrationType =
            $this->createPresenterRegistrationType(
                $conference
            );

        $participant =
            $this->createParticipant(
                $conference
            );

        $participant->update([
            'registration_type_id' => $registrationType->id,
            'participant_type' => 'presenter',
        ]);

        $topic =
            $this->createTopic(
                $conference
            );

        $submission =
            $this->createSubmission(
                $conference,
                $participant,
                $topic,
                [
                    'submission_stage' => 'full_paper',
                    'status' => 'published',
                ]
            );

        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);

        $response = $this
            ->actingAs($admin)
            ->put(
                route(
                    'admin.submissions.update',
                    $submission
                ),
                [
                    'conference_id' => $conference->id,
                    'participant_id' => $participant->id,
                    'topic_id' => $topic->id,
                    'title' => 'Should Not Change',
                    'abstract' => $submission->abstract,
                    'keywords' => $submission->keywords,
                    'status' => 'accepted',
                    'authors' => [
                        [
                            'name' => 'Test Presenter',
                            'email' => $participant->email,
                            'institution' => 'Test University',
                            'department' => 'Test Department',
                            'is_corresponding' => true,
                            'sort_order' => 1,
                        ],
                    ],
                ]
            );

        $response->assertRedirect(
            route(
                'admin.submissions.show',
                $submission
            )
        );

        $response->assertSessionHas(
            'error',
            'Published submissions cannot be edited through the standard submission form.'
        );

        $submission->refresh();

        $this->assertSame(
            'published',
            $submission->status
        );
    }

    public function test_pending_presenter_cannot_open_abstract_submission_form(): void
    {
        $conference = $this->createOpenConference();

        $user = User::factory()->create([
            'role' => 'participant',
            'status' => 'active',
        ]);

        $registrationType = ConferenceRegistrationType::create([
            'conference_id' => $conference->id,
            'name' => 'Presenter',
            'code' => 'PRESENTER-TEST',
            'category' => 'presenter',
            'payment_timing' => 'immediate',
            'fee' => 0,
            'currency' => 'IDR',
            'included_papers' => 1,
            'additional_paper_fee' => 0,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $participant = Participant::create([
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

        $response = $this
            ->actingAs($user)
            ->get(route('participant.submissions.create'));

        $response
            ->assertRedirect(route('participant.submissions.index'))
            ->assertSessionHas(
                'error',
                'You do not have a conference registration currently open for abstract submission.'
            );

        $this->assertDatabaseCount('submissions', 0);
    }

    public function test_pending_presenter_cannot_submit_abstract(): void
    {
        $conference = $this->createOpenConference();

        $user = User::factory()->create([
            'role' => 'participant',
            'status' => 'active',
        ]);

        $registrationType = ConferenceRegistrationType::create([
            'conference_id' => $conference->id,
            'name' => 'Presenter',
            'code' => 'PRESENTER-TEST',
            'category' => 'presenter',
            'payment_timing' => 'immediate',
            'fee' => 0,
            'currency' => 'IDR',
            'included_papers' => 1,
            'additional_paper_fee' => 0,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $participant = Participant::create([
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

        $topic = $this->createTopic($conference);

        $response = $this
            ->actingAs($user)
            ->post(
                route('participant.submissions.store'),
                [
                    'participant_id' => $participant->id,
                    'topic_id' => $topic->id,
                    'title' => 'Pending Presenter Test',
                    'abstract' => 'This abstract must not be submitted while registration is pending.',
                    'keywords' => 'test, pending, presenter',
                    'authors' => [
                        [
                            'name' => $user->name,
                            'email' => $user->email,
                            'institution' => 'Test University',
                            'department' => 'Test Department',
                            'is_corresponding' => true,
                            'sort_order' => 1,
                        ],
                    ],
                ]
            );

        $response
            ->assertRedirect()
            ->assertSessionHasErrors([
                'participant_id',
            ]);

        $this->assertDatabaseCount('submissions', 0);
    }

    public function test_pending_participant_cannot_open_full_paper_submission(): void
    {
        $user = User::factory()->create();

        $conference = $this->createOpenConference();

        $registrationType = $this->createPresenterRegistrationType(
            $conference
        );

        $participant = Participant::create([
            'user_id' => $user->id,
            'conference_id' => $conference->id,
            'registration_type_id' => $registrationType->id,
            'registration_number' => 'REG-' . $user->id,
            'full_name' => $user->name,
            'email' => $user->email,
            'country' => 'Indonesia',
            'city' => 'Slawi',
            'institution' => 'Test University',
            'department' => 'Test Department',
            'attendance_type' => 'online',
            'participant_type' => 'presenter',
            'registration_status' => 'pending',
            'registered_at' => now(),
        ]);

        $topic = $this->createTopic($conference);

        $submission = $this->createSubmission(
            $conference,
            $participant,
            $topic,
            [
                'submission_stage' => 'abstract',
                'status' => 'accepted',
            ]
        );

        $this->actingAs($user)
            ->get(route('participant.submissions.full-paper', $submission))
            ->assertNotFound();
    }

    public function test_pending_participant_cannot_upload_full_paper(): void
    {
        Storage::fake('local');

        $user = User::factory()->create();

        $conference = $this->createOpenConference();

        $registrationType = $this->createPresenterRegistrationType(
            $conference
        );

        $participant = Participant::create([
            'user_id' => $user->id,
            'conference_id' => $conference->id,
            'registration_type_id' => $registrationType->id,
            'registration_number' => 'REG-' . $user->id,
            'full_name' => $user->name,
            'email' => $user->email,
            'country' => 'Indonesia',
            'city' => 'Slawi',
            'institution' => 'Test University',
            'department' => 'Test Department',
            'attendance_type' => 'online',
            'participant_type' => 'presenter',
            'registration_status' => 'pending',
            'registered_at' => now(),
        ]);

        $topic = $this->createTopic($conference);

        $submission = $this->createSubmission(
            $conference,
            $participant,
            $topic,
            [
                'submission_stage' => 'abstract',
                'status' => 'accepted',
            ]
        );

        $file = UploadedFile::fake()->create(
            'paper.pdf',
            500,
            'application/pdf'
        );

        $this->actingAs($user)
            ->post(
                route(
                    'participant.submissions.full-paper.upload',
                    $submission
                ),
                [
                    'paper_file' => $file,
                ]
            )
            ->assertNotFound();
    }

    public function test_pending_presenter_cannot_open_video_submission_form(): void
    {
        $conference = $this->createOpenConference();

        $user = User::factory()->create();

        $participant = Participant::create([
            'user_id' => $user->id,
            'conference_id' => $conference->id,
            'registration_type_id' => $this->createPresenterRegistrationType($conference)->id,
            'registration_number' => 'REG-PENDING-VIDEO',
            'full_name' => 'Pending Presenter',
            'email' => $user->email,
            'institution' => 'Test Institution',
            'participant_type' => 'presenter',
            'attendance_type' => 'online',
            'registration_status' => 'pending',
            'registered_at' => now(),
        ]);

        $topic = $this->createTopic($conference);

        $submission = $this->createSubmission(
            $conference,
            $participant,
            $topic,
            [
                'submission_stage' => 'full_paper',
                'status' => 'accepted',
            ]
        );

        $this->actingAs($user)
            ->get(
                route(
                    'participant.submissions.video.edit',
                    $submission
                )
            )
            ->assertNotFound();
    }

    public function test_pending_presenter_cannot_update_video_submission(): void
    {
        $conference = $this->createOpenConference();

        $user = User::factory()->create();

        $registrationType =
            $this->createPresenterRegistrationType($conference);

        $participant = Participant::create([
            'user_id' => $user->id,
            'conference_id' => $conference->id,
            'registration_type_id' => $registrationType->id,
            'registration_number' => 'REG-PENDING-VIDEO-UPDATE',
            'full_name' => 'Pending Presenter',
            'email' => $user->email,
            'institution' => 'Test Institution',
            'participant_type' => 'presenter',
            'attendance_type' => 'online',
            'registration_status' => 'pending',
            'registered_at' => now(),
        ]);

        $topic = $this->createTopic($conference);

        $submission = $this->createSubmission(
            $conference,
            $participant,
            $topic,
            [
                'submission_stage' => 'full_paper',
                'status' => 'accepted',
            ]
        );

        $author = \App\Models\SubmissionAuthor::create([
            'submission_id' => $submission->id,
            'name' => 'Pending Presenter',
            'email' => $user->email,
            'institution' => 'Test University',
            'is_corresponding' => true,
            'sort_order' => 1,
        ]);

        $this->actingAs($user)
            ->put(
                route(
                    'participant.submissions.video.update',
                    $submission
                ),
                [
                    'presenter_author_id' => $author->id,
                    'video_url' =>
                    'https://drive.google.com/file/d/test-video-id/view',
                ]
            )
            ->assertNotFound();
    }

    public function test_presenter_can_submit_video_link_with_selected_presenter(): void
    {
        $conference = $this->createOpenConference();

        $user = User::factory()->create();

        $registrationType =
            $this->createPresenterRegistrationType($conference);

        $participant = Participant::create([
            'user_id' => $user->id,
            'conference_id' => $conference->id,
            'registration_type_id' => $registrationType->id,
            'registration_number' => 'REG-VIDEO-SUBMISSION',
            'full_name' => 'Presenter Test',
            'email' => $user->email,
            'institution' => 'Test Institution',
            'participant_type' => 'presenter',
            'attendance_type' => 'online',
            'registration_status' => 'confirmed',
            'registered_at' => now(),
        ]);

        $topic = $this->createTopic($conference);

        $submission = $this->createSubmission(
            $conference,
            $participant,
            $topic,
            [
                'submission_stage' => 'full_paper',
                'status' => 'accepted',
            ]
        );

        $author = \App\Models\SubmissionAuthor::create([
            'submission_id' => $submission->id,
            'name' => 'Presenter Test',
            'email' => $participant->email,
            'institution' => 'Test University',
            'is_corresponding' => true,
            'sort_order' => 1,
        ]);

        $videoUrl =
            'https://drive.google.com/file/d/test-video-id/view';

        $this->actingAs($user)
            ->put(
                route(
                    'participant.submissions.video.update',
                    $submission
                ),
                [
                    'presenter_author_id' => $author->id,
                    'video_url' => $videoUrl,
                ]
            )
            ->assertRedirect(
                route(
                    'participant.submissions.show',
                    $submission
                )
            );

        $submission->refresh();

        $this->assertSame(
            $author->id,
            $submission->presenter_author_id
        );

        $this->assertSame(
            $videoUrl,
            $submission->video_url
        );

        $this->assertNotNull(
            $submission->video_submitted_at
        );
    }

    public function test_video_submission_rejects_non_google_drive_url(): void
    {
        $conference = $this->createOpenConference();

        $user = User::factory()->create();

        $registrationType =
            $this->createPresenterRegistrationType($conference);

        $participant = Participant::create([
            'user_id' => $user->id,
            'conference_id' => $conference->id,
            'registration_type_id' => $registrationType->id,
            'registration_number' => 'REG-VIDEO-INVALID-URL',
            'full_name' => 'Presenter Test',
            'email' => $user->email,
            'institution' => 'Test Institution',
            'participant_type' => 'presenter',
            'attendance_type' => 'online',
            'registration_status' => 'confirmed',
            'registered_at' => now(),
        ]);

        $topic = $this->createTopic($conference);

        $submission = $this->createSubmission(
            $conference,
            $participant,
            $topic,
            [
                'submission_stage' => 'full_paper',
                'status' => 'accepted',
            ]
        );

        $author = \App\Models\SubmissionAuthor::create([
            'submission_id' => $submission->id,
            'name' => 'Presenter Test',
            'email' => $participant->email,
            'institution' => 'Test University',
            'is_corresponding' => true,
            'sort_order' => 1,
        ]);

        $this->actingAs($user)
            ->put(
                route(
                    'participant.submissions.video.update',
                    $submission
                ),
                [
                    'presenter_author_id' => $author->id,
                    'video_url' => 'https://example.com/video.mp4',
                ]
            )
            ->assertSessionHasErrors([
                'video_url',
            ]);

        $submission->refresh();

        $this->assertNull(
            $submission->video_url
        );

        $this->assertNull(
            $submission->video_submitted_at
        );
    }

    public function test_video_submission_rejects_presenter_from_another_submission(): void
    {
        $conference = $this->createOpenConference();

        $user = User::factory()->create();

        $registrationType =
            $this->createPresenterRegistrationType($conference);

        $participant = Participant::create([
            'user_id' => $user->id,
            'conference_id' => $conference->id,
            'registration_type_id' => $registrationType->id,
            'registration_number' => 'REG-VIDEO-WRONG-PRESENTER',
            'full_name' => 'Presenter Test',
            'email' => $user->email,
            'institution' => 'Test Institution',
            'participant_type' => 'presenter',
            'attendance_type' => 'online',
            'registration_status' => 'confirmed',
            'registered_at' => now(),
        ]);

        $otherUser = User::factory()->create();

        $otherParticipant = Participant::create([
            'user_id' => $otherUser->id,
            'conference_id' => $conference->id,
            'registration_type_id' => $registrationType->id,
            'registration_number' => 'REG-VIDEO-OTHER-PARTICIPANT',
            'full_name' => 'Other Presenter Test',
            'email' => $otherUser->email,
            'institution' => 'Other Test Institution',
            'participant_type' => 'presenter',
            'attendance_type' => 'online',
            'registration_status' => 'confirmed',
            'registered_at' => now(),
        ]);

        $topic = $this->createTopic($conference);

        $submission = $this->createSubmission(
            $conference,
            $participant,
            $topic,
            [
                'submission_stage' => 'full_paper',
                'status' => 'accepted',
            ]
        );

        $otherSubmission = $this->createSubmission(
            $conference,
            $otherParticipant,
            $topic,
            [
                'submission_stage' => 'full_paper',
                'status' => 'accepted',
            ]
        );

        $author = \App\Models\SubmissionAuthor::create([
            'submission_id' => $otherSubmission->id,
            'name' => 'Presenter From Another Paper',
            'email' => $otherParticipant->email,
            'institution' => 'Test University',
            'is_corresponding' => true,
            'sort_order' => 1,
        ]);

        $this->actingAs($user)
            ->put(
                route(
                    'participant.submissions.video.update',
                    $submission
                ),
                [
                    'presenter_author_id' => $author->id,
                    'video_url' =>
                    'https://drive.google.com/file/d/test-video-id/view',
                ]
            )
            ->assertSessionHasErrors([
                'presenter_author_id',
            ]);

        $submission->refresh();

        $this->assertNull(
            $submission->video_url
        );

        $this->assertNull(
            $submission->video_submitted_at
        );

        $this->assertNull(
            $submission->presenter_author_id
        );
    }

    public function test_admin_cannot_publish_using_verified_attendance_of_another_participant(): void
    {
        Storage::fake('local');

        $conference = $this->createOpenConference();

        $registrationType =
            $this->createPresenterRegistrationType(
                $conference
            );

        $participant =
            $this->createParticipant(
                $conference
            );

        $participant->update([
            'registration_type_id' => $registrationType->id,
            'participant_type' => 'presenter',
        ]);

        $otherParticipant =
            $this->createParticipant(
                $conference
            );

        $otherParticipant->update([
            'registration_type_id' => $registrationType->id,
            'participant_type' => 'presenter',
        ]);

        $topic =
            $this->createTopic(
                $conference
            );

        $submission =
            $this->createSubmission(
                $conference,
                $participant,
                $topic,
                [
                    'submission_stage' => 'full_paper',
                    'status' => 'camera_ready',
                    'camera_ready_file' =>
                    'submissions/camera-ready/test-wrong-attendance.pdf',
                    'video_url' =>
                    'https://drive.google.com/file/d/test-video-id/view',
                    'video_submitted_at' => now(),
                ]
            );

        $submission->camera_ready_status = 'submitted';
        $submission->save();

        $author = \App\Models\SubmissionAuthor::create([
            'submission_id' => $submission->id,
            'name' => $participant->full_name,
            'email' => $participant->email,
            'institution' => $participant->institution,
            'is_corresponding' => true,
            'sort_order' => 1,
        ]);

        $submission->presenter_author_id = $author->id;
        $submission->save();

        Storage::disk('local')->put(
            'submissions/camera-ready/test-wrong-attendance.pdf',
            'TEST CAMERA READY'
        );

        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);

        ConferenceAttendance::create([
            'conference_id' => $conference->id,
            'participant_id' => $otherParticipant->id,
            'attendance_status' => 'verified',
            'checked_in_at' => now(),
            'verified_at' => now(),
            'verified_by' => $admin->id,
        ]);

        $response = $this
            ->actingAs($admin)
            ->patch(
                route(
                    'admin.submissions.camera-ready.approve',
                    $submission
                )
            );

        $response->assertRedirect();

        $response->assertSessionHas(
            'error',
            'Cannot publish this submission: Conference attendance has not been verified.'
        );

        $submission->refresh();

        $this->assertSame(
            'camera_ready',
            $submission->status
        );

        $this->assertSame(
            'submitted',
            $submission->camera_ready_status
        );
    }

    public function test_admin_submission_detail_shows_publication_requirements_when_not_eligible(): void
    {
        Storage::fake('local');

        $conference = $this->createOpenConference();

        $registrationType =
            $this->createPresenterRegistrationType(
                $conference
            );

        $participant =
            $this->createParticipant(
                $conference
            );

        $participant->update([
            'registration_type_id' => $registrationType->id,
            'participant_type' => 'presenter',
        ]);

        $topic =
            $this->createTopic($conference);

        $submission =
            $this->createSubmission(
                $conference,
                $participant,
                $topic,
                [
                    'submission_stage' => 'full_paper',
                    'status' => 'camera_ready',
                    'camera_ready_file' =>
                    'submissions/camera-ready/test-ui.pdf',
                    'video_url' =>
                    'https://drive.google.com/file/d/test-video-id/view',
                    'video_submitted_at' => now(),
                ]
            );

        $submission->camera_ready_status = 'submitted';
        $submission->save();

        $author = \App\Models\SubmissionAuthor::create([
            'submission_id' => $submission->id,
            'name' => $participant->full_name,
            'email' => $participant->email,
            'institution' => $participant->institution,
            'is_corresponding' => true,
            'sort_order' => 1,
        ]);

        $submission->presenter_author_id = $author->id;
        $submission->save();

        Storage::disk('local')->put(
            'submissions/camera-ready/test-ui.pdf',
            'TEST CAMERA READY'
        );

        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);

        $response =
            $this->actingAs($admin)
            ->get(
                route(
                    'admin.submissions.show',
                    $submission
                )
            );

        $response->assertOk();

        $response->assertSee(
            'Publication Eligibility'
        );

        $response->assertSee(
            'Conference Attendance'
        );

        $response->assertSee(
            'Conference attendance has not been verified.'
        );

        $response->assertSee(
            'Publication is not available yet.'
        );

        $response->assertSee(
            'Publication requirements are not yet satisfied.',
            false
        );
    }

    public function test_admin_submission_detail_shows_publication_ready_when_eligible(): void
    {
        Storage::fake('local');

        $conference = $this->createOpenConference();

        $registrationType =
            $this->createPresenterRegistrationType(
                $conference
            );

        $participant =
            $this->createParticipant(
                $conference
            );

        $participant->update([
            'registration_type_id' => $registrationType->id,
            'participant_type' => 'presenter',
        ]);

        $topic =
            $this->createTopic($conference);

        $submission =
            $this->createSubmission(
                $conference,
                $participant,
                $topic,
                [
                    'submission_stage' => 'full_paper',
                    'status' => 'camera_ready',
                    'camera_ready_file' =>
                    'submissions/camera-ready/test-ui-ready.pdf',
                    'video_url' =>
                    'https://drive.google.com/file/d/test-video-id/view',
                    'video_submitted_at' => now(),
                ]
            );

        $submission->camera_ready_status = 'submitted';
        $submission->save();

        $author = \App\Models\SubmissionAuthor::create([
            'submission_id' => $submission->id,
            'name' => $participant->full_name,
            'email' => $participant->email,
            'institution' => $participant->institution,
            'is_corresponding' => true,
            'sort_order' => 1,
        ]);

        $submission->presenter_author_id = $author->id;
        $submission->save();

        Storage::disk('local')->put(
            'submissions/camera-ready/test-ui-ready.pdf',
            'TEST CAMERA READY'
        );

        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);

        ConferenceAttendance::create([
            'conference_id' => $conference->id,
            'participant_id' => $participant->id,
            'attendance_status' => 'verified',
            'checked_in_at' => now(),
            'verified_at' => now(),
            'verified_by' => $admin->id,
        ]);

        $response =
            $this->actingAs($admin)
            ->get(
                route(
                    'admin.submissions.show',
                    $submission
                )
            );

        $response->assertOk();

        $response->assertSee(
            'Publication Eligibility'
        );

        $response->assertSee(
            'This submission is eligible for publication.'
        );

        $response->assertSee(
            'Approve & Publish',
            false
        );

        $response->assertSee(
            'Camera Ready'
        );

        $response->assertSee(
            'Presentation Video'
        );

        $response->assertSee(
            'Conference Attendance'
        );
    }

    public function test_confirmed_seminar_cannot_access_submission_workflow(): void
    {
        $conference = $this->createOpenConference();

        $seminarRegistrationType =
            $this->createSeminarRegistrationType($conference);

        $participant = $this->createParticipant(
            $conference
        );

        $participant->update([
            'registration_type_id' =>
            $seminarRegistrationType->id,
            'participant_type' => 'participant',
            'registration_status' => 'confirmed',
        ]);

        $topic = $this->createTopic(
            $conference
        );

        $submission = $this->createSubmission(
            $conference,
            $participant,
            $topic
        );

        $this->actingAs($participant->user)
            ->get(
                route(
                    'participant.submissions.index'
                )
            )
            ->assertForbidden();

        $this->actingAs($participant->user)
            ->get(
                route(
                    'participant.submissions.create'
                )
            )
            ->assertForbidden();

        $this->actingAs($participant->user)
            ->get(
                route(
                    'participant.submissions.show',
                    $submission
                )
            )
            ->assertForbidden();
    }

    public function test_confirmed_seminar_cannot_store_submission(): void
    {
        $conference = $this->createOpenConference();

        $seminarRegistrationType =
            $this->createSeminarRegistrationType($conference);

        $participant = $this->createParticipant(
            $conference
        );

        $participant->update([
            'registration_type_id' =>
            $seminarRegistrationType->id,
            'participant_type' => 'participant',
            'registration_status' => 'confirmed',
        ]);

        $topic = $this->createTopic(
            $conference
        );

        $response = $this->actingAs(
            $participant->user
        )
            ->post(
                route(
                    'participant.submissions.store'
                ),
                [
                    'participant_id' =>
                    $participant->id,

                    'topic_id' =>
                    $topic->id,

                    'title' =>
                    'Seminar Must Not Submit',

                    'abstract' =>
                    'This submission must be rejected because the registration is for seminar participation.',

                    'keywords' =>
                    'seminar, participant, security',

                    'authors' => [
                        [
                            'name' =>
                            $participant->full_name,

                            'email' =>
                            $participant->email,

                            'institution' =>
                            $participant->institution,

                            'department' =>
                            $participant->department,

                            'is_corresponding' =>
                            true,

                            'sort_order' =>
                            1,
                        ],
                    ],
                ]
            );

        $response->assertForbidden();

        $this->assertDatabaseCount(
            'submissions',
            0
        );
    }
}
