<?php

namespace Tests\Feature;

use App\Models\Conference;
use App\Models\ConferenceSetting;
use App\Models\ConferenceRegistrationType;
use App\Models\ConferenceWhatsappGroup;
use App\Models\Participant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ParticipantWhatsappGroupTest extends TestCase
{
    use RefreshDatabase;

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

        return $conference;
    }

    private function createRegistrationType(
        Conference $conference,
        string $category
    ): ConferenceRegistrationType {
        return ConferenceRegistrationType::create([
            'conference_id' => $conference->id,
            'name' => $category === 'presenter'
                ? 'Test Presenter'
                : 'Test Seminar',
            'code' => strtoupper($category) . '_TEST',
            'category' => $category,
            'payment_timing' => 'immediate',
            'fee' => 10000,
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
        User $user,
        Conference $conference,
        ConferenceRegistrationType $registrationType,
        string $registrationStatus = 'confirmed',
        string $attendanceType = 'online',
        ?string $registrationNumber = null
    ): Participant {
        return Participant::create([
            'user_id' => $user->id,
            'conference_id' => $conference->id,
            'registration_type_id' => $registrationType->id,
            'registration_number' => $registrationNumber
                ?? 'TEST-' . $user->id . '-' . $registrationType->id,
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

    private function createWhatsappGroup(
        Conference $conference,
        string $audience,
        string $title
    ): ConferenceWhatsappGroup {
        return ConferenceWhatsappGroup::create([
            'conference_id' => $conference->id,
            'audience' => $audience,
            'title' => $title,
            'group_url' => 'https://chat.whatsapp.com/test-' . $audience,
            'description' => 'Test WhatsApp group.',
            'is_active' => true,
        ]);
    }

    public function test_confirmed_presenter_receives_presenter_whatsapp_group(): void
    {
        $conference = $this->createConference();

        $registrationType = $this->createRegistrationType(
            $conference,
            'presenter'
        );

        $this->createWhatsappGroup(
            $conference,
            'presenter',
            'Presenter WhatsApp Group'
        );

        $user = User::factory()->create([
            'role' => 'participant',
            'status' => 'active',
        ]);

        $this->createParticipant(
            $user,
            $conference,
            $registrationType
        );

        $response = $this
            ->actingAs($user)
            ->get(route('participant.dashboard'));

        $response
            ->assertStatus(200)
            ->assertSeeText('WhatsApp Group')
            ->assertSeeText('Presenter WhatsApp Group');
    }

    public function test_confirmed_seminar_receives_seminar_whatsapp_group(): void
    {
        $conference = $this->createConference();

        $registrationType = $this->createRegistrationType(
            $conference,
            'participant'
        );

        $this->createWhatsappGroup(
            $conference,
            'seminar',
            'Seminar WhatsApp Group'
        );

        $user = User::factory()->create([
            'role' => 'participant',
            'status' => 'active',
        ]);

        $this->createParticipant(
            $user,
            $conference,
            $registrationType
        );

        $response = $this
            ->actingAs($user)
            ->get(route('participant.dashboard'));

        $response
            ->assertStatus(200)
            ->assertSeeText('WhatsApp Group')
            ->assertSeeText('Seminar WhatsApp Group');
    }

    public function test_pending_seminar_does_not_receive_whatsapp_group(): void
    {
        $conference = $this->createConference();

        $registrationType = $this->createRegistrationType(
            $conference,
            'participant'
        );

        $this->createWhatsappGroup(
            $conference,
            'seminar',
            'Seminar WhatsApp Group'
        );

        $user = User::factory()->create([
            'role' => 'participant',
            'status' => 'active',
        ]);

        $this->createParticipant(
            $user,
            $conference,
            $registrationType,
            'pending',
            'online'
        );

        $response = $this
            ->actingAs($user)
            ->get(route('participant.dashboard'));

        $response
            ->assertStatus(200)
            ->assertDontSeeText('Seminar WhatsApp Group');
    }

    public function test_confirmed_offline_seminar_does_not_receive_whatsapp_group(): void
    {
        $conference = $this->createConference();

        $registrationType = $this->createRegistrationType(
            $conference,
            'participant'
        );

        $this->createWhatsappGroup(
            $conference,
            'seminar',
            'Seminar WhatsApp Group'
        );

        $user = User::factory()->create([
            'role' => 'participant',
            'status' => 'active',
        ]);

        $this->createParticipant(
            $user,
            $conference,
            $registrationType,
            'confirmed',
            'offline'
        );

        $response = $this
            ->actingAs($user)
            ->get(route('participant.dashboard'));

        $response
            ->assertStatus(200)
            ->assertDontSeeText('Seminar WhatsApp Group');
    }

    public function test_seminar_participant_does_not_receive_presenter_whatsapp_group(): void
    {
        $conference = $this->createConference();

        $registrationType = $this->createRegistrationType(
            $conference,
            'participant'
        );

        $this->createWhatsappGroup(
            $conference,
            'presenter',
            'Presenter WhatsApp Group'
        );

        $this->createWhatsappGroup(
            $conference,
            'seminar',
            'Seminar WhatsApp Group'
        );

        $user = User::factory()->create([
            'role' => 'participant',
            'status' => 'active',
        ]);

        $this->createParticipant(
            $user,
            $conference,
            $registrationType
        );

        $response = $this
            ->actingAs($user)
            ->get(route('participant.dashboard'));

        $response
            ->assertStatus(200)
            ->assertSeeText('Seminar WhatsApp Group')
            ->assertDontSeeText('Presenter WhatsApp Group');
    }
}
