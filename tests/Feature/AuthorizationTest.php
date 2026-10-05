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

    public function test_authenticated_admin_is_redirected_from_login_to_dashboard(): void
    {
        $user = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('login'));

        $response->assertRedirect(
            route('admin.dashboard')
        );
    }

    public function test_user_can_login_with_remember_me(): void
    {
        $user = User::factory()->create([
            'email' => 'remember@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/login', [
            'email' => 'remember@example.com',
            'password' => 'password',
            'remember' => '1',
        ]);

        $response->assertRedirect();

        $this->assertAuthenticatedAs($user);

        $user->refresh();

        $this->assertNotNull($user->remember_token);

        $response->assertCookie(
            \Illuminate\Support\Facades\Auth::getRecallerName()
        );
    }
}
