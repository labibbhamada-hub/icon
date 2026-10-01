<?php

namespace Tests\Feature;

use App\Models\Conference;
use App\Models\ConferenceWhatsappGroup;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConferenceWhatsappGroupTest extends TestCase
{
    use RefreshDatabase;

    private function createAdmin(): User
    {
        return User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);
    }

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

    private function whatsappPayload(
        Conference $conference,
        string $audience
    ): array {
        return [
            'conference_id' => $conference->id,
            'audience' => $audience,
            'title' => $audience === 'presenter'
                ? 'Test Presenter WhatsApp Group'
                : 'Test Seminar WhatsApp Group',
            'group_url' => 'https://chat.whatsapp.com/test-' . $audience,
            'description' => 'Test WhatsApp group description.',
            'is_active' => 1,
        ];
    }

    public function test_admin_can_open_whatsapp_group_create_page(): void
    {
        $admin = $this->createAdmin();

        $response = $this
            ->actingAs($admin)
            ->get(route('admin.conference-whatsapp-groups.create'));

        $response
            ->assertSuccessful()
            ->assertSeeText('Create WhatsApp Group')
            ->assertSeeText('Audience')
            ->assertSeeText('Presenter')
            ->assertSeeText('Seminar');
    }

    public function test_admin_can_create_presenter_whatsapp_group(): void
    {
        $admin = $this->createAdmin();
        $conference = $this->createConference();

        $response = $this
            ->actingAs($admin)
            ->post(
                route('admin.conference-whatsapp-groups.store'),
                $this->whatsappPayload($conference, 'presenter')
            );

        $response
            ->assertRedirect(
                route('admin.conference-whatsapp-groups.index')
            )
            ->assertSessionHas(
                'success',
                'WhatsApp group created successfully.'
            );

        $this->assertDatabaseHas('conference_whatsapp_groups', [
            'conference_id' => $conference->id,
            'audience' => 'presenter',
            'title' => 'Test Presenter WhatsApp Group',
            'is_active' => true,
        ]);
    }

    public function test_admin_can_create_seminar_whatsapp_group_for_same_conference(): void
    {
        $admin = $this->createAdmin();
        $conference = $this->createConference();

        ConferenceWhatsappGroup::create([
            'conference_id' => $conference->id,
            'audience' => 'presenter',
            'title' => 'Existing Presenter Group',
            'group_url' => 'https://chat.whatsapp.com/existing-presenter',
            'description' => 'Existing presenter group.',
            'is_active' => true,
        ]);

        $response = $this
            ->actingAs($admin)
            ->post(
                route('admin.conference-whatsapp-groups.store'),
                $this->whatsappPayload($conference, 'seminar')
            );

        $response
            ->assertRedirect(
                route('admin.conference-whatsapp-groups.index')
            )
            ->assertSessionHas(
                'success',
                'WhatsApp group created successfully.'
            );

        $this->assertDatabaseHas('conference_whatsapp_groups', [
            'conference_id' => $conference->id,
            'audience' => 'presenter',
            'title' => 'Existing Presenter Group',
        ]);

        $this->assertDatabaseHas('conference_whatsapp_groups', [
            'conference_id' => $conference->id,
            'audience' => 'seminar',
            'title' => 'Test Seminar WhatsApp Group',
        ]);

        $this->assertDatabaseCount(
            'conference_whatsapp_groups',
            2
        );
    }

    public function test_duplicate_conference_and_audience_is_rejected(): void
    {
        $admin = $this->createAdmin();
        $conference = $this->createConference();

        ConferenceWhatsappGroup::create([
            'conference_id' => $conference->id,
            'audience' => 'seminar',
            'title' => 'Existing Seminar Group',
            'group_url' => 'https://chat.whatsapp.com/existing-seminar',
            'description' => 'Existing seminar group.',
            'is_active' => true,
        ]);

        $response = $this
            ->actingAs($admin)
            ->from(
                route('admin.conference-whatsapp-groups.create')
            )
            ->post(
                route('admin.conference-whatsapp-groups.store'),
                $this->whatsappPayload($conference, 'seminar')
            );

        $response
            ->assertRedirect(
                route('admin.conference-whatsapp-groups.create')
            )
            ->assertSessionHasErrors([
                'audience' =>
                'This conference already has a WhatsApp group for the selected audience.',
            ]);

        $this->assertDatabaseCount(
            'conference_whatsapp_groups',
            1
        );
    }
}
