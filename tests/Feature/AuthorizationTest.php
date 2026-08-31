<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_admin_dashboard(): void
    {
        $response = $this->get('/admin/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_participant_cannot_access_admin_dashboard(): void
    {
        $user = User::factory()->create([
            'role' => 'participant',
            'status' => 'active',
        ]);

        $response = $this
            ->actingAs($user)
            ->get('/admin/dashboard');

        $response->assertForbidden();
    }

    public function test_reviewer_cannot_access_admin_dashboard(): void
    {
        $user = User::factory()->create([
            'role' => 'reviewer',
            'status' => 'active',
        ]);

        $response = $this
            ->actingAs($user)
            ->get('/admin/dashboard');

        $response->assertForbidden();
    }

    public function test_inactive_admin_cannot_access_admin_dashboard(): void
    {
        $user = User::factory()->create([
            'role' => 'admin',
            'status' => 'inactive',
        ]);

        $response = $this
            ->actingAs($user)
            ->get('/admin/dashboard');

        $response->assertForbidden();
    }

    public function test_active_admin_can_access_admin_dashboard(): void
    {
        $user = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);

        $response = $this
            ->actingAs($user)
            ->get('/admin/dashboard');

        $response->assertSuccessful();
    }
}
