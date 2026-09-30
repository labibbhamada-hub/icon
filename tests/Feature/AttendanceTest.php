<?php

namespace Tests\Feature;

use App\Models\Conference;
use App\Models\ConferenceAttendance;
use App\Models\ConferenceAttendanceOption;
use App\Models\ConferenceRegistrationType;
use App\Models\ConferenceSetting;
use App\Models\Participant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
}
