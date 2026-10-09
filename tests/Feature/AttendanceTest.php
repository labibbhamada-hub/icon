<?php

namespace Tests\Feature;

use App\Models\Conference;
use App\Models\ConferenceAttendance;
use App\Models\ConferenceAttendanceOption;
use App\Models\ConferenceRegistrationType;
use App\Models\ConferenceSetting;
use App\Models\ImportantDate;
use App\Models\Participant;
use App\Models\Submission;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AttendanceTest extends TestCase
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

        ImportantDate::create([
            'conference_id' => $conference->id,
            'title' => 'Test Conference',
            'type' => 'conference',
            'description' => 'Test conference event.',
            'date' => now()->toDateString(),
            'end_date' => now()->toDateString(),
            'sort_order' => 1,
            'is_active' => true,
        ]);

        return $conference;
    }

    private function createRegistrationType(
        Conference $conference
    ): ConferenceRegistrationType {
        return ConferenceRegistrationType::create([
            'conference_id' => $conference->id,
            'name' => 'Regular Participant',
            'code' => 'REGULAR',
            'category' => 'participant',
            'payment_timing' => 'immediate',
            'fee' => 100000,
            'included_papers' => 0,
            'additional_paper_fee' => 0,
            'currency' => 'IDR',
            'description' => 'Test registration type.',
            'benefits' => 'Test benefit.',
            'is_active' => true,
            'sort_order' => 1,
        ]);
    }

    private function createPresenterRegistrationType(
        Conference $conference
    ): ConferenceRegistrationType {
        return ConferenceRegistrationType::create([
            'conference_id' => $conference->id,
            'name' => 'Presenter',
            'code' => 'PRESENTER-' . uniqid(),
            'category' => 'presenter',
            'payment_timing' => 'immediate',
            'fee' => 500000,
            'included_papers' => 1,
            'additional_paper_fee' => 0,
            'currency' => 'IDR',
            'description' => 'Test presenter registration type.',
            'benefits' => 'Test presenter benefit.',
            'is_active' => true,
            'sort_order' => 1,
        ]);
    }

    private function createParticipant(
        Conference $conference,
        ConferenceRegistrationType $registrationType,
        User $user,
        string $status = 'confirmed'
    ): Participant {
        return Participant::create([
            'user_id' => $user->id,
            'conference_id' => $conference->id,
            'registration_type_id' => $registrationType->id,
            'registration_number' => 'REG-' . $user->id,
            'full_name' => $user->name,
            'email' => $user->email,
            'institution' => 'Test University',
            'country' => 'Indonesia',
            'city' => 'Slawi',
            'attendance_type' => 'online',
            'participant_type' => 'participant',
            'registration_status' => $status,
            'registered_at' => now(),
        ]);
    }

    private function createPresenterSubmission(
        Conference $conference,
        Participant $participant
    ): Submission {
        $topic = Topic::create([
            'conference_id' => $conference->id,
            'name' => 'Artificial Intelligence',
            'description' => 'Test topic.',
        ]);

        return Submission::create([
            'conference_id' => $conference->id,
            'participant_id' => $participant->id,
            'topic_id' => $topic->id,
            'submission_code' => 'TEST-PRESENTER-' . uniqid(),
            'title' => 'Test Presenter Submission',
            'abstract' => 'Test abstract.',
            'keywords' => 'test, presenter',
            'submission_stage' => 'full_paper',
            'status' => 'accepted',
            'video_url' =>
            'https://drive.google.com/file/d/test-video-' .
                uniqid() .
                '/view',
            'video_submitted_at' => now(),
            'submitted_at' => now(),
        ]);
    }

    public function test_confirmed_participant_can_check_in(): void
    {
        $conference = $this->createOpenConference();
        $registrationType = $this->createRegistrationType($conference);

        $user = User::factory()->create([
            'role' => 'participant',
            'status' => 'active',
        ]);

        $participant = $this->createParticipant(
            $conference,
            $registrationType,
            $user
        );

        $response = $this
            ->actingAs($user)
            ->post(
                route('participant.attendance.check-in', $participant)
            );

        $response->assertRedirect();

        $this->assertDatabaseHas('conference_attendances', [
            'conference_id' => $conference->id,
            'participant_id' => $participant->id,
            'attendance_status' => 'checked_in',
        ]);

        $attendance = ConferenceAttendance::where(
            'participant_id',
            $participant->id
        )->first();

        $this->assertNotNull($attendance);
        $this->assertNotNull($attendance->checked_in_at);
    }

    public function test_confirmed_presenter_check_in_generates_presenter_certificate(): void
    {
        Storage::fake('public');

        $conference = $this->createOpenConference();
        $registrationType = $this->createPresenterRegistrationType(
            $conference
        );

        $user = User::factory()->create([
            'role' => 'participant',
            'status' => 'active',
        ]);

        $participant = $this->createParticipant(
            $conference,
            $registrationType,
            $user
        );

        $participant->update([
            'participant_type' => 'presenter',
        ]);

        $submission = $this->createPresenterSubmission(
            $conference,
            $participant
        );

        $response = $this
            ->actingAs($user)
            ->post(
                route(
                    'participant.attendance.check-in',
                    $participant
                )
            );

        $response->assertRedirect();

        $this->assertDatabaseHas('conference_attendances', [
            'conference_id' => $conference->id,
            'participant_id' => $participant->id,
            'attendance_status' => 'checked_in',
        ]);

        $this->assertDatabaseHas('certificates', [
            'participant_id' => $participant->id,
            'conference_id' => $conference->id,
            'submission_id' => $submission->id,
            'type' => 'presenter',
        ]);

        $this->assertDatabaseMissing('certificates', [
            'participant_id' => $participant->id,
            'conference_id' => $conference->id,
            'submission_id' => null,
            'type' => 'participant',
        ]);
    }

    public function test_presenter_check_in_without_eligible_submission_does_not_generate_certificate(): void
    {
        Storage::fake('public');

        $conference = $this->createOpenConference();
        $registrationType = $this->createPresenterRegistrationType(
            $conference
        );

        $user = User::factory()->create([
            'role' => 'participant',
            'status' => 'active',
        ]);

        $participant = $this->createParticipant(
            $conference,
            $registrationType,
            $user
        );

        $participant->update([
            'participant_type' => 'presenter',
        ]);

        $response = $this
            ->actingAs($user)
            ->post(
                route(
                    'participant.attendance.check-in',
                    $participant
                )
            );

        $response->assertRedirect();

        $this->assertDatabaseHas('conference_attendances', [
            'conference_id' => $conference->id,
            'participant_id' => $participant->id,
            'attendance_status' => 'checked_in',
        ]);

        $this->assertDatabaseMissing('certificates', [
            'participant_id' => $participant->id,
            'conference_id' => $conference->id,
            'type' => 'presenter',
        ]);

        $this->assertDatabaseMissing('certificates', [
            'participant_id' => $participant->id,
            'conference_id' => $conference->id,
            'type' => 'participant',
        ]);
    }

    public function test_pending_participant_cannot_check_in(): void
    {
        $conference = $this->createOpenConference();
        $registrationType = $this->createRegistrationType($conference);

        $user = User::factory()->create([
            'role' => 'participant',
            'status' => 'active',
        ]);

        $participant = $this->createParticipant(
            $conference,
            $registrationType,
            $user,
            'pending'
        );

        $response = $this
            ->actingAs($user)
            ->post(
                route('participant.attendance.check-in', $participant)
            );

        $response->assertForbidden();

        $this->assertDatabaseMissing('conference_attendances', [
            'participant_id' => $participant->id,
        ]);
    }

    public function test_participant_cannot_check_in_another_participant(): void
    {
        $conference = $this->createOpenConference();
        $registrationType = $this->createRegistrationType($conference);

        $user = User::factory()->create([
            'role' => 'participant',
            'status' => 'active',
        ]);

        $otherUser = User::factory()->create([
            'role' => 'participant',
            'status' => 'active',
        ]);

        $participant = $this->createParticipant(
            $conference,
            $registrationType,
            $otherUser
        );

        $response = $this
            ->actingAs($user)
            ->post(
                route('participant.attendance.check-in', $participant)
            );

        $response->assertForbidden();

        $this->assertDatabaseMissing('conference_attendances', [
            'participant_id' => $participant->id,
        ]);
    }

    public function test_repeated_check_in_does_not_create_duplicate_attendance(): void
    {
        $conference = $this->createOpenConference();
        $registrationType = $this->createRegistrationType($conference);

        $user = User::factory()->create([
            'role' => 'participant',
            'status' => 'active',
        ]);

        $participant = $this->createParticipant(
            $conference,
            $registrationType,
            $user
        );

        $this
            ->actingAs($user)
            ->post(
                route('participant.attendance.check-in', $participant)
            );

        $attendance = ConferenceAttendance::where(
            'participant_id',
            $participant->id
        )->first();

        $this->assertNotNull($attendance);

        $attendance->update([
            'attendance_status' => 'verified',
            'verified_at' => now(),
        ]);

        $this
            ->actingAs($user)
            ->post(
                route('participant.attendance.check-in', $participant)
            )
            ->assertRedirect();

        $this->assertDatabaseCount('conference_attendances', 1);

        $attendance->refresh();

        $this->assertSame(
            'verified',
            $attendance->attendance_status
        );

        $this->assertNotNull($attendance->verified_at);
    }

    public function test_admin_can_manually_check_in_confirmed_participant(): void
    {
        $conference = $this->createOpenConference();
        $registrationType = $this->createRegistrationType($conference);

        $participantUser = User::factory()->create([
            'role' => 'participant',
            'status' => 'active',
        ]);

        $participant = $this->createParticipant(
            $conference,
            $registrationType,
            $participantUser
        );

        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);

        $checkedInAt = '2026-12-20 08:30:00';

        $response = $this
            ->actingAs($admin)
            ->patch(
                route(
                    'admin.attendance.manual-check-in',
                    $participant
                ),
                [
                    'checked_in_at' => $checkedInAt,
                    'verification_notes' =>
                    'Participant attended the conference but forgot to check in.',
                ]
            );

        $response->assertRedirect();

        $this->assertDatabaseHas('conference_attendances', [
            'conference_id' => $conference->id,
            'participant_id' => $participant->id,
            'attendance_status' => 'checked_in',
            'checked_in_at' => $checkedInAt,
            'verification_notes' =>
            'Participant attended the conference but forgot to check in.',
        ]);
    }

    public function test_admin_can_verify_checked_in_attendance(): void
    {
        $conference = $this->createOpenConference();
        $registrationType = $this->createRegistrationType($conference);

        $participantUser = User::factory()->create([
            'role' => 'participant',
            'status' => 'active',
        ]);

        $participant = $this->createParticipant(
            $conference,
            $registrationType,
            $participantUser
        );

        $attendance = ConferenceAttendance::create([
            'conference_id' => $conference->id,
            'participant_id' => $participant->id,
            'attendance_status' => 'checked_in',
            'checked_in_at' => '2026-12-20 08:30:00',
        ]);

        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);

        $response = $this
            ->actingAs($admin)
            ->patch(
                route(
                    'admin.attendance.verify',
                    $attendance
                )
            );

        $response->assertRedirect();

        $attendance->refresh();

        $this->assertSame(
            'verified',
            $attendance->attendance_status
        );

        $this->assertSame(
            $admin->id,
            $attendance->verified_by
        );

        $this->assertNotNull(
            $attendance->verified_at
        );

        $this->assertSame(
            '2026-12-20 08:30:00',
            $attendance->checked_in_at->format('Y-m-d H:i:s')
        );
    }

    public function test_admin_cannot_verify_attendance_that_has_not_been_checked_in(): void
    {
        $conference = $this->createOpenConference();
        $registrationType = $this->createRegistrationType($conference);

        $participantUser = User::factory()->create([
            'role' => 'participant',
            'status' => 'active',
        ]);

        $participant = $this->createParticipant(
            $conference,
            $registrationType,
            $participantUser
        );

        $attendance = ConferenceAttendance::create([
            'conference_id' => $conference->id,
            'participant_id' => $participant->id,
            'attendance_status' => 'not_checked_in',
        ]);

        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);

        $response = $this
            ->actingAs($admin)
            ->patch(
                route(
                    'admin.attendance.verify',
                    $attendance
                )
            );

        $response->assertForbidden();

        $attendance->refresh();

        $this->assertSame(
            'not_checked_in',
            $attendance->attendance_status
        );

        $this->assertNull(
            $attendance->verified_at
        );

        $this->assertNull(
            $attendance->verified_by
        );
    }

    public function test_admin_manual_check_in_requires_verification_notes(): void
    {
        $conference = $this->createOpenConference();
        $registrationType = $this->createRegistrationType($conference);

        $participantUser = User::factory()->create([
            'role' => 'participant',
            'status' => 'active',
        ]);

        $participant = $this->createParticipant(
            $conference,
            $registrationType,
            $participantUser
        );

        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);

        $response = $this
            ->actingAs($admin)
            ->patch(
                route(
                    'admin.attendance.manual-check-in',
                    $participant
                ),
                [
                    'checked_in_at' => '2026-12-20 08:30:00',
                ]
            );

        $response->assertSessionHasErrors([
            'verification_notes',
        ]);

        $this->assertDatabaseMissing(
            'conference_attendances',
            [
                'participant_id' => $participant->id,
            ]
        );
    }

    public function test_verified_attendance_cannot_be_manually_checked_in_again(): void
    {
        $conference = $this->createOpenConference();
        $registrationType = $this->createRegistrationType($conference);

        $participantUser = User::factory()->create([
            'role' => 'participant',
            'status' => 'active',
        ]);

        $participant = $this->createParticipant(
            $conference,
            $registrationType,
            $participantUser
        );

        $attendance = ConferenceAttendance::create([
            'conference_id' => $conference->id,
            'participant_id' => $participant->id,
            'attendance_status' => 'verified',
            'checked_in_at' => '2026-12-20 08:30:00',
            'verified_at' => '2026-12-20 09:00:00',
            'verified_by' => User::factory()->create([
                'role' => 'admin',
                'status' => 'active',
            ])->id,
        ]);

        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);

        $response = $this
            ->actingAs($admin)
            ->patch(
                route(
                    'admin.attendance.manual-check-in',
                    $participant
                ),
                [
                    'checked_in_at' => '2026-12-20 10:00:00',
                    'verification_notes' => 'Incorrect previous time.',
                ]
            );

        $response
            ->assertRedirect(route('admin.participants.show', $participant))
            ->assertSessionHas(
                'error',
                'Attendance has already been verified and cannot be checked in again.'
            );

        $attendance->refresh();

        $this->assertSame(
            'verified',
            $attendance->attendance_status
        );

        $this->assertSame(
            '2026-12-20 08:30:00',
            $attendance->checked_in_at->format('Y-m-d H:i:s')
        );
    }


    public function test_admin_cannot_manually_check_in_pending_participant(): void
    {
        $conference = $this->createOpenConference();
        $registrationType = $this->createRegistrationType($conference);

        $participantUser = User::factory()->create([
            'role' => 'participant',
            'status' => 'active',
        ]);

        $participant = $this->createParticipant(
            $conference,
            $registrationType,
            $participantUser,
            'pending'
        );

        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);

        $response = $this
            ->actingAs($admin)
            ->patch(
                route(
                    'admin.attendance.manual-check-in',
                    $participant
                ),
                [
                    'checked_in_at' => '2026-12-20 08:30:00',
                    'verification_notes' => 'QA pending registration.',
                ]
            );

        $response
            ->assertRedirect(route('admin.participants.show', $participant))
            ->assertSessionHas(
                'error',
                'Manual check-in is unavailable because the participant registration status is pending.'
            );

        $this->assertDatabaseMissing('conference_attendances', [
            'participant_id' => $participant->id,
        ]);
    }
}
