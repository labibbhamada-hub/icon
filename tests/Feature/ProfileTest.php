<?php

namespace Tests\Feature;

use App\Models\Conference;
use App\Models\ConferenceAttendanceOption;
use App\Models\ConferenceSetting;
use App\Models\Participant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
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

        ConferenceAttendanceOption::create([
            'conference_id' => $conference->id,
            'type' => 'online',
            'sort_order' => 1,
        ]);

        return $conference;
    }

    private function createParticipant(
        User $user,
        Conference $conference
    ): Participant {
        return Participant::create([
            'user_id' => $user->id,
            'conference_id' => $conference->id,
            'registration_number' => 'TEST-' . $user->id,
            'title_prefix' => 'Dr.',
            'full_name' => 'Budi Santoso',
            'title_suffix' => 'S.Kom.',
            'orcid' => '0009-0001-2345-6789',
            'email' => $user->email,
            'phone' => '081234567890',
            'institution' => 'Test University',
            'department' => 'Test Department',
            'country' => 'Indonesia',
            'city' => 'Slawi',
            'attendance_type' => 'online',
            'registration_status' => 'pending',
        ]);
    }

    public function test_participant_can_open_profile(): void
    {
        $user = User::factory()->create([
            'role' => 'participant',
            'status' => 'active',
        ]);

        $conference = $this->createConference();

        $this->createParticipant(
            $user,
            $conference
        );

        $response = $this
            ->actingAs($user)
            ->get(route('participant.profile.edit'));

        $response->assertStatus(200);
    }

    public function test_participant_can_update_profile(): void
    {
        $user = User::factory()->create([
            'role' => 'participant',
            'status' => 'active',
        ]);

        $conference = $this->createConference();

        $participant = $this->createParticipant(
            $user,
            $conference
        );

        $response = $this
            ->actingAs($user)
            ->put(route('participant.profile.update'), [
                'title_prefix' => 'Prof. Dr.',
                'full_name' => 'Budi Santoso Updated',
                'title_suffix' => 'S.Kom., M.Kom.',
                'orcid' => '0000-0002-1825-0097',
                'phone' => '+62 81234567890',
                'institution' => 'Universitas Bhamada Slawi',
                'department' => 'Fakultas Teknologi Informasi',
                'country' => 'Indonesia',
                'city' => 'Tegal',
            ]);

        $response
            ->assertRedirect(
                route('participant.profile.edit')
            )
            ->assertSessionHas(
                'success',
                'Profile updated successfully.'
            );

        $participant->refresh();

        $this->assertSame(
            'Prof. Dr.',
            $participant->title_prefix
        );

        $this->assertSame(
            'Budi Santoso Updated',
            $participant->full_name
        );

        $this->assertSame(
            'S.Kom., M.Kom.',
            $participant->title_suffix
        );

        $this->assertSame(
            '0000-0002-1825-0097',
            $participant->orcid
        );

        $this->assertSame(
            '+62 81234567890',
            $participant->phone
        );

        $this->assertSame(
            'Universitas Bhamada Slawi',
            $participant->institution
        );

        $this->assertSame(
            'Fakultas Teknologi Informasi',
            $participant->department
        );

        $this->assertSame(
            'Indonesia',
            $participant->country
        );

        $this->assertSame(
            'Tegal',
            $participant->city
        );
    }

    public function test_participant_cannot_update_profile_with_invalid_orcid(): void
    {
        $user = User::factory()->create([
            'role' => 'participant',
            'status' => 'active',
        ]);

        $conference = $this->createConference();

        $this->createParticipant(
            $user,
            $conference
        );

        $response = $this
            ->actingAs($user)
            ->from(route('participant.profile.edit'))
            ->put(route('participant.profile.update'), [
                'title_prefix' => 'Dr.',
                'full_name' => 'Budi Santoso',
                'title_suffix' => 'S.Kom.',
                'orcid' => '00001234567890',
                'phone' => null,
                'institution' => null,
                'department' => null,
                'country' => 'Indonesia',
                'city' => null,
            ]);

        $response
            ->assertRedirect(
                route('participant.profile.edit')
            )
            ->assertSessionHasErrors('orcid');
    }

    public function test_user_without_participant_is_redirected_to_registration(): void
    {
        $user = User::factory()->create([
            'role' => 'participant',
            'status' => 'active',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('participant.profile.edit'));

        $response
            ->assertRedirect(
                route('participant.registration.create')
            )
            ->assertSessionHas('info');
    }
}
