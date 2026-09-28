<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_is_accessible(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_unauthenticated_user_cannot_access_employees(): void
    {
        $this->get('/employees')->assertRedirect('/login');
    }

    public function test_unauthenticated_user_cannot_access_dashboard(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }

    public function test_authenticated_user_can_access_dashboard(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->get('/dashboard')->assertStatus(200);
    }

    public function test_authenticated_user_can_access_employees(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->get('/employees')->assertStatus(200);
    }

    public function test_valid_login_redirects_to_dashboard(): void
    {
        $user = User::factory()->create(['password' => bcrypt('password')]);

        $this->post('/login', ['email' => $user->email, 'password' => 'password'])
             ->assertRedirect('/dashboard');
    }

    public function test_invalid_login_returns_errors(): void
    {
        $this->post('/login', ['email' => 'wrong@example.com', 'password' => 'wrong'])
             ->assertSessionHasErrors('email');
    }

    public function test_logout_works(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->post('/logout')->assertRedirect('/');
    }
}
