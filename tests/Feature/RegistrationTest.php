<?php

namespace Tests\Feature;

use App\Models\Conference;
use App\Models\ConferenceAttendanceOption;
use App\Models\ConferenceRegistrationType;
use App\Models\ConferenceSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    private function createOpenConference(
        string $registrationDeadline = '2026-12-31'
    ): Conference {
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
            'registration_deadline' => $registrationDeadline,
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
        Conference $conference,
        string $code = 'REGULAR'
    ): ConferenceRegistrationType {
        return ConferenceRegistrationType::create([
            'conference_id' => $conference->id,
            'name' => 'Regular Participant',
            'code' => $code,
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

    public function test_valid_registration_creates_participant(): void
    {
        $conference = $this->createOpenConference();
        $registrationType = $this->createRegistrationType($conference);

        $user = User::factory()->create([
            'role' => 'participant',
            'status' => 'active',
        ]);

        $response = $this
            ->actingAs($user)
            ->post('/participant/registration', [
                'conference_id' => $conference->id,
                'registration_type_id' => $registrationType->id,
                'phone' => '081234567890',
                'institution' => 'Test University',
                'department' => 'Test Department',
                'country' => 'Indonesia',
                'city' => 'Slawi',
                'attendance_type' => 'online',
            ]);

        $response->assertRedirect(
            route('participant.registration.index')
        );

        $this->assertDatabaseHas('participants', [
            'user_id' => $user->id,
            'conference_id' => $conference->id,
            'registration_type_id' => $registrationType->id,
            'country' => 'Indonesia',
            'attendance_type' => 'online',
            'registration_status' => 'pending',
            'full_name' => $user->name,
            'email' => $user->email,
        ]);
    }

    public function test_duplicate_registration_is_rejected(): void
    {
        $conference = $this->createOpenConference();
        $registrationType = $this->createRegistrationType($conference);

        $user = User::factory()->create([
            'role' => 'participant',
            'status' => 'active',
        ]);

        $payload = [
            'conference_id' => $conference->id,
            'registration_type_id' => $registrationType->id,
            'phone' => '081234567890',
            'institution' => 'Test University',
            'department' => 'Test Department',
            'country' => 'Indonesia',
            'city' => 'Slawi',
            'attendance_type' => 'online',
        ];

        $this
            ->actingAs($user)
            ->post('/participant/registration', $payload);

        $response = $this
            ->actingAs($user)
            ->post('/participant/registration', $payload);

        $response->assertRedirect();

        $response->assertSessionHas(
            'error',
            'You are already registered for this conference.'
        );

        $this->assertDatabaseCount('participants', 1);
    }

    public function test_registration_after_deadline_is_rejected(): void
    {
        Carbon::setTestNow('2026-08-27 12:00:00');

        try {
            $conference = $this->createOpenConference(
                '2026-08-26'
            );

            $registrationType = $this->createRegistrationType(
                $conference
            );

            $user = User::factory()->create([
                'role' => 'participant',
                'status' => 'active',
            ]);

            $response = $this
                ->actingAs($user)
                ->post('/participant/registration', [
                    'conference_id' => $conference->id,
                    'registration_type_id' => $registrationType->id,
                    'country' => 'Indonesia',
                    'attendance_type' => 'online',
                ]);

            $response->assertRedirect();

            $response->assertSessionHas(
                'error',
                'Conference registration is closed.'
            );

            $this->assertDatabaseMissing('participants', [
                'user_id' => $user->id,
                'conference_id' => $conference->id,
            ]);
        } finally {
            Carbon::setTestNow();
        }
    }

    public function test_registration_on_deadline_is_allowed(): void
    {
        Carbon::setTestNow('2026-08-26 12:00:00');

        try {
            $conference = $this->createOpenConference(
                '2026-08-26'
            );

            $registrationType = $this->createRegistrationType(
                $conference
            );

            $user = User::factory()->create([
                'role' => 'participant',
                'status' => 'active',
            ]);

            $response = $this
                ->actingAs($user)
                ->post('/participant/registration', [
                    'conference_id' => $conference->id,
                    'registration_type_id' => $registrationType->id,
                    'country' => 'Indonesia',
                    'attendance_type' => 'online',
                ]);

            $response->assertRedirect(
                route('participant.registration.index')
            );

            $this->assertDatabaseHas('participants', [
                'user_id' => $user->id,
                'conference_id' => $conference->id,
            ]);
        } finally {
            Carbon::setTestNow();
        }
    }

    public function test_registration_type_from_another_conference_is_rejected(): void
    {
        $conference = $this->createOpenConference();

        $anotherConference = $this->createOpenConference();

        $registrationType = $this->createRegistrationType(
            $anotherConference
        );

        $user = User::factory()->create([
            'role' => 'participant',
            'status' => 'active',
        ]);

        $response = $this
            ->actingAs($user)
            ->post('/participant/registration', [
                'conference_id' => $conference->id,
                'registration_type_id' => $registrationType->id,
                'country' => 'Indonesia',
                'attendance_type' => 'online',
            ]);

        $response->assertRedirect();

        $response->assertSessionHasErrors([
            'registration_type_id',
        ]);

        $this->assertDatabaseMissing('participants', [
            'user_id' => $user->id,
            'conference_id' => $conference->id,
        ]);
    }

    public function test_unavailable_attendance_type_is_rejected(): void
    {
        $conference = $this->createOpenConference();
        $registrationType = $this->createRegistrationType($conference);

        $user = User::factory()->create([
            'role' => 'participant',
            'status' => 'active',
        ]);

        $response = $this
            ->actingAs($user)
            ->post('/participant/registration', [
                'conference_id' => $conference->id,
                'registration_type_id' => $registrationType->id,
                'country' => 'Indonesia',
                'attendance_type' => 'offline',
            ]);

        $response->assertRedirect();

        $response->assertSessionHasErrors([
            'attendance_type',
        ]);

        $this->assertDatabaseMissing('participants', [
            'user_id' => $user->id,
            'conference_id' => $conference->id,
        ]);
    }
}
