<?php

namespace Tests\Feature;

use App\Models\Conference;
use App\Models\ConferenceSetting;
use App\Models\ConferenceRegistrationType;
use App\Models\Participant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ParticipantDashboardTest extends TestCase
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

        return $conference;
    }

    private function createRegistrationType(
        Conference $conference,
        string $category
    ): ConferenceRegistrationType {
        return ConferenceRegistrationType::create([
            'conference_id' => $conference->id,
            'name' => $category === 'presenter'
                ? 'Presenter Bhamada'
                : 'Peserta Seminar Umum',
            'code' => $category === 'presenter'
                ? 'presenter_bhamada'
                : 'peserta_seminar_umum',
            'category' => $category,
            'payment_timing' => 'immediate',
            'fee' => $category === 'presenter' ? 250000 : 75000,
            'included_papers' => $category === 'presenter' ? 1 : 0,
            'additional_paper_fee' => 0,
            'currency' => 'IDR',
            'description' => 'Test registration type.',
            'benefits' => 'Test benefits.',
            'is_active' => true,
            'sort_order' => 1,
        ]);
    }

    private function createParticipant(
        Conference $conference,
        ConferenceRegistrationType $registrationType,
        User $user,
        string $registrationStatus = 'confirmed',
        string $attendanceType = 'online'
    ): Participant {
        return Participant::create([
            'user_id' => $user->id,
            'conference_id' => $conference->id,
            'registration_type_id' => $registrationType->id,
            'registration_number' => 'REG-' . $user->id,
            'full_name' => $user->name,
            'email' => $user->email,
            'phone' => '081234567890',
            'institution' => 'Test University',
            'country' => 'Indonesia',
            'city' => 'Slawi',
            'attendance_type' => $attendanceType,
            'participant_type' => $registrationType->category,
            'registration_status' => $registrationStatus,
            'registered_at' => now(),
        ]);
    }

    public function test_confirmed_seminar_dashboard_does_not_show_submission_actions(): void
    {
        $conference = $this->createOpenConference();

        $registrationType = $this->createRegistrationType(
            $conference,
            'participant'
        );

        $user = User::factory()->create([
            'role' => 'participant',
            'status' => 'active',
        ]);

        $this->createParticipant(
            $conference,
            $registrationType,
            $user
        );

        $response = $this
            ->actingAs($user)
            ->get(route('participant.dashboard'));

        $response
            ->assertSuccessful()
            ->assertSeeText('Conference Schedule')
            ->assertDontSeeText('My Submissions')
            ->assertDontSeeText('Submit Your Paper')
            ->assertDontSeeText('Recent Submissions');
    }

    public function test_confirmed_presenter_dashboard_keeps_submission_flow(): void
    {
        $conference = $this->createOpenConference();

        $registrationType = $this->createRegistrationType(
            $conference,
            'presenter'
        );

        $user = User::factory()->create([
            'role' => 'participant',
            'status' => 'active',
        ]);

        $this->createParticipant(
            $conference,
            $registrationType,
            $user
        );

        $response = $this
            ->actingAs($user)
            ->get(route('participant.dashboard'));

        $response
            ->assertSuccessful()
            ->assertSeeText('My Submissions')
            ->assertSeeText('Submit Your Paper')
            ->assertDontSeeText('Conference Schedule');
    }
}
