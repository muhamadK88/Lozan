<?php

namespace Tests\Feature;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_is_only_available_on_the_admin_host(): void
    {
        $this->get('http://admin.localhost/login')->assertOk();
        $this->get('http://localhost/login')->assertNotFound();
    }

    public function test_admin_can_login_with_valid_credentials(): void
    {
        $admin = Admin::factory()->create([
            'email' => 'admin@lozan.com',
            'password' => 'secret-password',
        ]);

        $response = $this->post('http://admin.localhost/login', [
            'email' => 'admin@lozan.com',
            'password' => 'secret-password',
        ]);

        $response->assertRedirect('http://admin.localhost/dashboard');
        $this->assertAuthenticatedAs($admin, 'admin');
    }

    public function test_invalid_credentials_are_rejected_with_a_generic_message(): void
    {
        Admin::factory()->create([
            'email' => 'admin@lozan.com',
            'password' => 'secret-password',
        ]);

        $response = $this
            ->from('http://admin.localhost/login')
            ->post('http://admin.localhost/login', [
                'email' => 'admin@lozan.com',
                'password' => 'wrong-password',
            ]);

        $response->assertRedirect('http://admin.localhost/login');
        $response->assertSessionHasErrors('email');
        $this->assertGuest('admin');
    }

    public function test_admin_dashboard_cannot_be_reached_from_the_public_host(): void
    {
        $admin = Admin::factory()->create();

        $this
            ->actingAs($admin, 'admin')
            ->get('http://localhost/dashboard')
            ->assertNotFound();
    }

    public function test_login_attempts_are_rate_limited(): void
    {
        Admin::factory()->create([
            'email' => 'admin@lozan.com',
            'password' => 'secret-password',
        ]);

        for ($attempt = 0; $attempt < 5; $attempt++) {
            $this
                ->post('http://admin.localhost/login', [
                    'email' => 'admin@lozan.com',
                    'password' => 'wrong-password',
                ])
                ->assertRedirect();
        }

        $this
            ->post('http://admin.localhost/login', [
                'email' => 'admin@lozan.com',
                'password' => 'wrong-password',
            ])
            ->assertStatus(429);
    }

    public function test_an_authenticated_admin_is_redirected_away_from_the_login_page(): void
    {
        $admin = Admin::factory()->create();

        $this
            ->actingAs($admin, 'admin')
            ->get('http://admin.localhost/login')
            ->assertRedirect('http://admin.localhost/dashboard');
    }
}
