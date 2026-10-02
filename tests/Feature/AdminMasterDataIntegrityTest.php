<?php

namespace Tests\Feature;

use App\Models\Conference;
use App\Models\ConferenceRegistrationType;
use App\Models\Certificate;
use App\Models\ConferenceAttendance;
use App\Models\ConferencePaymentMethod;
use App\Models\Participant;
use App\Models\Payment;
use App\Models\Reviewer;
use App\Models\Review;
use App\Models\Submission;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminMasterDataIntegrityTest extends TestCase
{
    use RefreshDatabase;

    private function createAdmin(): User
    {
        return User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
    }

    private function createConference(
        string $name = 'Test Conference',
        int $year = 2026
    ): Conference {
        return Conference::create([
            'name' => $name,
            'short_name' => 'TEST' . $year,
            'year' => $year,
            'theme' => 'Test Theme',
            'venue' => 'Online Conference',
            'city' => 'Slawi',
            'country' => 'Indonesia',
            'start_date' => $year . '-12-20',
            'end_date' => $year . '-12-20',
            'abstract_deadline' => $year . '-12-10',
            'fullpaper_deadline' => $year . '-12-15',
            'registration_deadline' => $year . '-12-31',
            'status' => 'registration_open',
        ]);
    }

    private function createRegistrationType(
        Conference $conference,
        string $category = 'participant',
        string $code = 'regular'
    ): ConferenceRegistrationType {
        return ConferenceRegistrationType::create([
            'conference_id' => $conference->id,
            'name' => ucfirst($category) . ' Registration',
            'code' => $code,
            'category' => $category,
            'fee' => 100000,
            'included_papers' => $category === 'presenter' ? 1 : 0,
            'additional_paper_fee' => 0,
            'payment_timing' => 'immediate',
            'currency' => 'IDR',
            'description' => 'Test registration type.',
            'benefits' => 'Test benefit.',
            'is_active' => true,
            'sort_order' => 1,
        ]);
    }

    private function createParticipant(
        Conference $conference,
        ConferenceRegistrationType $registrationType,
        ?User $user = null
    ): Participant {
        $user ??= User::factory()->create([
            'role' => 'participant',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        return Participant::create([
            'user_id' => $user->id,
            'conference_id' => $conference->id,
            'registration_type_id' => $registrationType->id,
            'registration_number' => 'REG-' . uniqid(),
            'full_name' => 'Test Participant',
            'email' => $user->email,
            'phone' => '081234567890',
            'institution' => 'Test University',
            'department' => 'Test Department',
            'country' => 'Indonesia',
            'city' => 'Slawi',
            'participant_type' => 'regular',
            'attendance_type' => 'online',
            'registration_status' => 'confirmed',
            'notes' => null,
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
        return Submission::create([
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
        ]);
    }

    private function createReviewer(
        Conference $conference
    ): Reviewer {
        $user = User::factory()->create([
            'role' => 'reviewer',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        return Reviewer::create([
            'user_id' => $user->id,
            'conference_id' => $conference->id,
            'institution' => 'Test University',
            'is_active' => true,
        ]);
    }

    private function reviewerUpdatePayload(
        Reviewer $reviewer,
        Conference $conference
    ): array {
        return [
            'conference_id' => $conference->id,
            'user_id' => $reviewer->user_id,
            'expertise' => 'Information Technology',
            'institution' => 'Test University',
            'bio' => 'Test reviewer biography.',
            'is_active' => true,
        ];
    }

    private function registrationTypeUpdatePayload(
        ConferenceRegistrationType $registrationType,
        Conference $conference,
        ?string $category = null
    ): array {
        return [
            'conference_id' => $conference->id,
            'name' => $registrationType->name,
            'code' => $registrationType->code,
            'category' => $category ?? $registrationType->category,
            'fee' => $registrationType->fee,
            'included_papers' => $registrationType->included_papers,
            'additional_paper_fee' => $registrationType->additional_paper_fee,
            'payment_timing' => $registrationType->payment_timing,
            'currency' => $registrationType->currency,
            'description' => $registrationType->description,
            'benefits' => $registrationType->benefits,
            'is_active' => $registrationType->is_active,
            'sort_order' => $registrationType->sort_order,
        ];
    }

    private function participantUpdatePayload(
        Participant $participant,
        Conference $conference,
        ConferenceRegistrationType $registrationType
    ): array {
        return [
            'conference_id' => $conference->id,
            'registration_type_id' => $registrationType->id,
            'registration_number' => $participant->registration_number,
            'full_name' => $participant->full_name,
            'email' => $participant->email,
            'phone' => $participant->phone,
            'institution' => $participant->institution,
            'department' => $participant->department,
            'country' => $participant->country,
            'city' => $participant->city,
            'participant_type' => $participant->participant_type,
            'attendance_type' => $participant->attendance_type,
            'registration_status' => $participant->registration_status,
            'notes' => $participant->notes,
            'registered_at' => $participant->registered_at,
        ];
    }

    public function test_clean_participant_can_be_moved_to_another_conference(): void
    {
        $this->actingAs($this->createAdmin());

        $sourceConference = $this->createConference(
            'Source Conference',
            2026
        );

        $targetConference = $this->createConference(
            'Target Conference',
            2027
        );

        $sourceType = $this->createRegistrationType(
            $sourceConference,
            'participant',
            'source_regular'
        );

        $targetType = $this->createRegistrationType(
            $targetConference,
            'participant',
            'target_regular'
        );

        $participant = $this->createParticipant(
            $sourceConference,
            $sourceType
        );

        $response = $this->put(
            route(
                'admin.participants.update',
                $participant
            ),
            $this->participantUpdatePayload(
                $participant,
                $targetConference,
                $targetType
            )
        );

        $response->assertRedirect(
            route('admin.participants.index')
        );

        $this->assertDatabaseHas('participants', [
            'id' => $participant->id,
            'conference_id' => $targetConference->id,
            'registration_type_id' => $targetType->id,
        ]);
    }

    public function test_participant_with_submission_cannot_be_moved_to_another_conference(): void
    {
        $this->actingAs($this->createAdmin());

        $sourceConference = $this->createConference(
            'Source Conference',
            2026
        );

        $targetConference = $this->createConference(
            'Target Conference',
            2027
        );

        $sourceType = $this->createRegistrationType(
            $sourceConference,
            'participant',
            'source_regular'
        );

        $targetType = $this->createRegistrationType(
            $targetConference,
            'participant',
            'target_regular'
        );

        $participant = $this->createParticipant(
            $sourceConference,
            $sourceType
        );

        $topic = $this->createTopic($sourceConference);

        $this->createSubmission(
            $sourceConference,
            $participant,
            $topic
        );

        $response = $this->put(
            route(
                'admin.participants.update',
                $participant
            ),
            $this->participantUpdatePayload(
                $participant,
                $targetConference,
                $targetType
            )
        );

        $response->assertSessionHas(
            'error',
            'Participant cannot be moved to another conference because related records already exist.'
        );

        $this->assertDatabaseHas('participants', [
            'id' => $participant->id,
            'conference_id' => $sourceConference->id,
            'registration_type_id' => $sourceType->id,
        ]);
    }

    public function test_reviewer_user_must_have_reviewer_role(): void
    {
        $this->actingAs($this->createAdmin());

        $conference = $this->createConference();

        $user = User::factory()->create([
            'role' => 'participant',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $response = $this->post(
            route('admin.reviewers.store'),
            [
                'conference_id' => $conference->id,
                'user_id' => $user->id,
                'expertise' => 'Information Technology',
                'institution' => 'Test University',
                'bio' => 'Test reviewer biography.',
                'is_active' => true,
            ]
        );

        $response->assertSessionHasErrors([
            'user_id',
        ]);

        $this->assertDatabaseMissing('reviewers', [
            'user_id' => $user->id,
            'conference_id' => $conference->id,
        ]);
    }

    public function test_clean_reviewer_can_be_moved_to_another_conference(): void
    {
        $this->actingAs($this->createAdmin());

        $sourceConference = $this->createConference(
            'Source Conference',
            2026
        );

        $targetConference = $this->createConference(
            'Target Conference',
            2027
        );

        $reviewer = $this->createReviewer($sourceConference);

        $response = $this->put(
            route(
                'admin.reviewers.update',
                $reviewer
            ),
            $this->reviewerUpdatePayload(
                $reviewer,
                $targetConference
            )
        );

        $response->assertRedirect(
            route('admin.reviewers.index')
        );

        $this->assertDatabaseHas('reviewers', [
            'id' => $reviewer->id,
            'conference_id' => $targetConference->id,
        ]);
    }

    public function test_reviewer_with_reviews_cannot_be_moved_to_another_conference(): void
    {
        $this->actingAs($this->createAdmin());

        $sourceConference = $this->createConference(
            'Source Conference',
            2026
        );

        $targetConference = $this->createConference(
            'Target Conference',
            2027
        );

        $registrationType = $this->createRegistrationType(
            $sourceConference,
            'participant',
            'source_regular'
        );

        $participant = $this->createParticipant(
            $sourceConference,
            $registrationType
        );

        $topic = $this->createTopic($sourceConference);

        $submission = $this->createSubmission(
            $sourceConference,
            $participant,
            $topic
        );

        $reviewer = $this->createReviewer($sourceConference);

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

        $response = $this->put(
            route(
                'admin.reviewers.update',
                $reviewer
            ),
            $this->reviewerUpdatePayload(
                $reviewer,
                $targetConference
            )
        );

        $response->assertSessionHas(
            'error',
            'Reviewer cannot be moved to another conference because review records already exist.'
        );

        $this->assertDatabaseHas('reviewers', [
            'id' => $reviewer->id,
            'conference_id' => $sourceConference->id,
        ]);
    }

    public function test_clean_registration_type_can_be_moved_to_another_conference(): void
    {
        $this->actingAs($this->createAdmin());

        $sourceConference = $this->createConference(
            'Source Conference',
            2026
        );

        $targetConference = $this->createConference(
            'Target Conference',
            2027
        );

        $registrationType = $this->createRegistrationType(
            $sourceConference,
            'participant',
            'source_regular'
        );

        $targetRegistrationTypeCode = 'source_regular_target';

        $response = $this->put(
            route(
                'admin.registration-types.update',
                $registrationType
            ),
            array_merge(
                $this->registrationTypeUpdatePayload(
                    $registrationType,
                    $targetConference
                ),
                [
                    'code' => $targetRegistrationTypeCode,
                ]
            )
        );

        $response->assertRedirect(
            route('admin.registration-types.index')
        );

        $this->assertDatabaseHas(
            'conference_registration_types',
            [
                'id' => $registrationType->id,
                'conference_id' => $targetConference->id,
            ]
        );
    }

    public function test_used_registration_type_cannot_be_moved_to_another_conference(): void
    {
        $this->actingAs($this->createAdmin());

        $sourceConference = $this->createConference(
            'Source Conference',
            2026
        );

        $targetConference = $this->createConference(
            'Target Conference',
            2027
        );

        $registrationType = $this->createRegistrationType(
            $sourceConference,
            'participant',
            'source_regular'
        );

        $targetType = $this->createRegistrationType(
            $targetConference,
            'participant',
            'target_regular'
        );

        $this->createParticipant(
            $sourceConference,
            $registrationType
        );

        $response = $this->put(
            route(
                'admin.registration-types.update',
                $registrationType
            ),
            array_merge(
                $this->registrationTypeUpdatePayload(
                    $registrationType,
                    $targetConference
                ),
                [
                    'code' => $targetType->code . '_2',
                ]
            )
        );

        $response->assertSessionHas(
            'error',
            'Registration type conference and category cannot be changed because it is already used by participants.'
        );

        $this->assertDatabaseHas(
            'conference_registration_types',
            [
                'id' => $registrationType->id,
                'conference_id' => $sourceConference->id,
                'category' => 'participant',
            ]
        );
    }

    public function test_used_registration_type_cannot_change_category(): void
    {
        $this->actingAs($this->createAdmin());

        $conference = $this->createConference();

        $registrationType = $this->createRegistrationType(
            $conference,
            'participant',
            'participant_regular'
        );

        $this->createParticipant(
            $conference,
            $registrationType
        );

        $response = $this->put(
            route(
                'admin.registration-types.update',
                $registrationType
            ),
            $this->registrationTypeUpdatePayload(
                $registrationType,
                $conference,
                'presenter'
            )
        );

        $response->assertSessionHas(
            'error',
            'Registration type conference and category cannot be changed because it is already used by participants.'
        );

        $this->assertDatabaseHas(
            'conference_registration_types',
            [
                'id' => $registrationType->id,
                'conference_id' => $conference->id,
                'category' => 'participant',
            ]
        );
    }

    public function test_used_registration_type_can_still_update_fee(): void
    {
        $this->actingAs($this->createAdmin());

        $conference = $this->createConference();

        $registrationType = $this->createRegistrationType(
            $conference,
            'participant',
            'participant_regular'
        );

        $this->createParticipant(
            $conference,
            $registrationType
        );

        $response = $this->put(
            route(
                'admin.registration-types.update',
                $registrationType
            ),
            array_merge(
                $this->registrationTypeUpdatePayload(
                    $registrationType,
                    $conference
                ),
                [
                    'fee' => 150000,
                ]
            )
        );

        $response->assertRedirect(
            route('admin.registration-types.index')
        );

        $this->assertDatabaseHas(
            'conference_registration_types',
            [
                'id' => $registrationType->id,
                'conference_id' => $conference->id,
                'fee' => 150000,
            ]
        );
    }

    public function test_participant_with_payment_cannot_be_moved_to_another_conference(): void
    {
        $this->actingAs($this->createAdmin());

        $sourceConference = $this->createConference(
            'Source Conference',
            2026
        );

        $targetConference = $this->createConference(
            'Target Conference',
            2027
        );

        $sourceType = $this->createRegistrationType(
            $sourceConference,
            'participant',
            'source_regular'
        );

        $targetType = $this->createRegistrationType(
            $targetConference,
            'participant',
            'target_regular'
        );

        $participant = $this->createParticipant(
            $sourceConference,
            $sourceType
        );

        $paymentMethod = ConferencePaymentMethod::create([
            'conference_id' => $sourceConference->id,
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
            'payment_code' => 'PAY-TEST-' . uniqid(),
            'amount' => 100000,
            'proof_file' => 'payments/proofs/test-proof.pdf',
            'status' => 'pending',
            'paid_at' => now(),
        ]);

        $response = $this->put(
            route(
                'admin.participants.update',
                $participant
            ),
            $this->participantUpdatePayload(
                $participant,
                $targetConference,
                $targetType
            )
        );

        $response->assertSessionHas(
            'error',
            'Participant cannot be moved to another conference because related records already exist.'
        );

        $this->assertDatabaseHas('participants', [
            'id' => $participant->id,
            'conference_id' => $sourceConference->id,
            'registration_type_id' => $sourceType->id,
        ]);
    }

    public function test_participant_with_attendance_cannot_be_moved_to_another_conference(): void
    {
        $this->actingAs($this->createAdmin());

        $sourceConference = $this->createConference(
            'Source Conference',
            2026
        );

        $targetConference = $this->createConference(
            'Target Conference',
            2027
        );

        $sourceType = $this->createRegistrationType(
            $sourceConference,
            'participant',
            'source_regular'
        );

        $targetType = $this->createRegistrationType(
            $targetConference,
            'participant',
            'target_regular'
        );

        $participant = $this->createParticipant(
            $sourceConference,
            $sourceType
        );

        ConferenceAttendance::create([
            'conference_id' => $sourceConference->id,
            'participant_id' => $participant->id,
            'attendance_status' => 'checked_in',
            'checked_in_at' => '2026-12-20 08:30:00',
        ]);

        $response = $this->put(
            route(
                'admin.participants.update',
                $participant
            ),
            $this->participantUpdatePayload(
                $participant,
                $targetConference,
                $targetType
            )
        );

        $response->assertSessionHas(
            'error',
            'Participant cannot be moved to another conference because related records already exist.'
        );

        $this->assertDatabaseHas('participants', [
            'id' => $participant->id,
            'conference_id' => $sourceConference->id,
            'registration_type_id' => $sourceType->id,
        ]);
    }

    public function test_participant_with_certificate_cannot_be_moved_to_another_conference(): void
    {
        $this->actingAs($this->createAdmin());

        $sourceConference = $this->createConference(
            'Source Conference',
            2026
        );

        $targetConference = $this->createConference(
            'Target Conference',
            2027
        );

        $sourceType = $this->createRegistrationType(
            $sourceConference,
            'participant',
            'source_regular'
        );

        $targetType = $this->createRegistrationType(
            $targetConference,
            'participant',
            'target_regular'
        );

        $participant = $this->createParticipant(
            $sourceConference,
            $sourceType
        );

        Certificate::create([
            'participant_id' => $participant->id,
            'conference_id' => $sourceConference->id,
            'submission_id' => null,
            'certificate_number' => 'CERT-MOVE-TEST',
            'type' => 'participant',
            'file_path' => 'certificates/test-certificate.pdf',
            'issued_at' => now(),
        ]);

        $response = $this->put(
            route(
                'admin.participants.update',
                $participant
            ),
            $this->participantUpdatePayload(
                $participant,
                $targetConference,
                $targetType
            )
        );

        $response->assertSessionHas(
            'error',
            'Participant cannot be moved to another conference because related records already exist.'
        );

        $this->assertDatabaseHas('participants', [
            'id' => $participant->id,
            'conference_id' => $sourceConference->id,
            'registration_type_id' => $sourceType->id,
        ]);
    }

    public function test_empty_conference_can_be_deleted(): void
    {
        $this->actingAs($this->createAdmin());

        $conference = $this->createConference(
            'Empty Conference',
            2026
        );

        $response = $this->delete(
            route(
                'admin.conferences.destroy',
                $conference
            )
        );

        $response->assertRedirect(
            route('admin.conferences.index')
        );

        $this->assertDatabaseMissing('conferences', [
            'id' => $conference->id,
        ]);
    }

    public function test_conference_with_workflow_records_cannot_be_deleted(): void
    {
        $this->actingAs($this->createAdmin());

        $conference = $this->createConference(
            'Used Conference',
            2026
        );

        $registrationType = $this->createRegistrationType(
            $conference,
            'participant',
            'used_regular'
        );

        $participant = $this->createParticipant(
            $conference,
            $registrationType
        );

        $topic = $this->createTopic($conference);

        $this->createSubmission(
            $conference,
            $participant,
            $topic
        );

        $reviewer = $this->createReviewer($conference);

        Review::create([
            'submission_id' => $conference->submissions()->first()->id,
            'reviewer_id' => $reviewer->id,
            'review_stage' => 'abstract',
            'review_round' => 1,
            'score' => 80,
            'comment' => 'Good abstract.',
            'recommendation' => 'accept',
            'reviewed_at' => now(),
        ]);

        ConferenceAttendance::create([
            'conference_id' => $conference->id,
            'participant_id' => $participant->id,
            'attendance_status' => 'checked_in',
            'checked_in_at' => '2026-12-20 08:30:00',
        ]);

        Certificate::create([
            'participant_id' => $participant->id,
            'conference_id' => $conference->id,
            'submission_id' => null,
            'certificate_number' => 'CERT-DELETE-TEST',
            'type' => 'participant',
            'file_path' => null,
            'issued_at' => now(),
        ]);

        $response = $this->delete(
            route(
                'admin.conferences.destroy',
                $conference
            )
        );

        $response->assertSessionHas(
            'error',
            'Conference cannot be deleted because workflow records already exist.'
        );

        $this->assertDatabaseHas('conferences', [
            'id' => $conference->id,
        ]);

        $this->assertDatabaseHas('participants', [
            'id' => $participant->id,
            'conference_id' => $conference->id,
        ]);

        $this->assertDatabaseHas('reviewers', [
            'id' => $reviewer->id,
            'conference_id' => $conference->id,
        ]);

        $this->assertDatabaseHas('reviews', [
            'reviewer_id' => $reviewer->id,
        ]);

        $this->assertDatabaseHas('conference_attendances', [
            'participant_id' => $participant->id,
        ]);

        $this->assertDatabaseHas('certificates', [
            'participant_id' => $participant->id,
            'certificate_number' => 'CERT-DELETE-TEST',
        ]);
    }

    public function test_reviewer_without_reviews_can_be_deleted(): void
    {
        $this->actingAs($this->createAdmin());

        $conference = $this->createConference(
            'Reviewer Conference',
            2026
        );

        $reviewer = $this->createReviewer($conference);

        $response = $this->delete(
            route(
                'admin.reviewers.destroy',
                $reviewer
            )
        );

        $response->assertRedirect(
            route('admin.reviewers.index')
        );

        $this->assertDatabaseMissing('reviewers', [
            'id' => $reviewer->id,
        ]);
    }

    public function test_reviewer_with_reviews_cannot_be_deleted(): void
    {
        $this->actingAs($this->createAdmin());

        $conference = $this->createConference(
            'Reviewer Conference',
            2026
        );

        $registrationType = $this->createRegistrationType(
            $conference,
            'participant',
            'reviewer_delete_test'
        );

        $participant = $this->createParticipant(
            $conference,
            $registrationType
        );

        $topic = $this->createTopic($conference);

        $submission = $this->createSubmission(
            $conference,
            $participant,
            $topic
        );

        $reviewer = $this->createReviewer($conference);

        $review = Review::create([
            'submission_id' => $submission->id,
            'reviewer_id' => $reviewer->id,
            'review_stage' => 'abstract',
            'review_round' => 1,
            'score' => 80,
            'comment' => 'Good abstract.',
            'recommendation' => 'accept',
            'reviewed_at' => now(),
        ]);

        $response = $this->delete(
            route(
                'admin.reviewers.destroy',
                $reviewer
            )
        );

        $response->assertSessionHas(
            'error',
            'Reviewer cannot be deleted because review records already exist.'
        );

        $this->assertDatabaseHas('reviewers', [
            'id' => $reviewer->id,
        ]);

        $this->assertDatabaseHas('reviews', [
            'id' => $review->id,
            'reviewer_id' => $reviewer->id,
        ]);
    }

    public function test_topic_without_submissions_can_be_deleted(): void
    {
        $this->actingAs($this->createAdmin());

        $conference = $this->createConference(
            'Topic Conference',
            2026
        );

        $topic = $this->createTopic($conference);

        $response = $this->delete(
            route(
                'admin.topics.destroy',
                $topic
            )
        );

        $response->assertRedirect(
            route('admin.topics.index')
        );

        $this->assertDatabaseMissing('topics', [
            'id' => $topic->id,
        ]);
    }

    public function test_topic_with_submissions_cannot_be_deleted(): void
    {
        $this->actingAs($this->createAdmin());

        $conference = $this->createConference(
            'Topic Conference',
            2026
        );

        $registrationType = $this->createRegistrationType(
            $conference,
            'participant',
            'topic_delete_test'
        );

        $participant = $this->createParticipant(
            $conference,
            $registrationType
        );

        $topic = $this->createTopic($conference);

        $submission = $this->createSubmission(
            $conference,
            $participant,
            $topic
        );

        $response = $this->delete(
            route(
                'admin.topics.destroy',
                $topic
            )
        );

        $response->assertSessionHas(
            'error',
            'Topic cannot be deleted because it is already used by submissions.'
        );

        $this->assertDatabaseHas('topics', [
            'id' => $topic->id,
        ]);

        $this->assertDatabaseHas('submissions', [
            'id' => $submission->id,
            'topic_id' => $topic->id,
        ]);
    }
}
