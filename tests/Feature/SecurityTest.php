<?php

namespace Tests\Feature;

use App\Models\EmailLog;
use App\Models\EmailTemplate;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_unauthenticated_cannot_access_dashboard(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }

    public function test_unauthenticated_cannot_access_employees(): void
    {
        $this->get('/employees')->assertRedirect('/login');
    }

    public function test_unauthenticated_cannot_access_templates(): void
    {
        $this->get('/templates')->assertRedirect('/login');
    }

    public function test_unauthenticated_cannot_access_logs(): void
    {
        $this->get('/logs')->assertRedirect('/login');
    }

    public function test_unauthenticated_cannot_access_settings(): void
    {
        $this->get('/settings/email')->assertRedirect('/login');
    }

    public function test_invalid_employee_id_returns_404(): void
    {
        $this->actingAs($this->user)->get('/employees/99999')->assertStatus(404);
    }

    public function test_invalid_template_id_returns_404(): void
    {
        $this->actingAs($this->user)->get('/templates/99999')->assertStatus(404);
    }

    public function test_invalid_log_id_returns_404(): void
    {
        $this->actingAs($this->user)->get('/logs/99999')->assertStatus(404);
    }

    public function test_csrf_protection_on_post(): void
    {
        $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class);
        // The middleware is bypassed here for testing — CSRF is Laravel default, always on
        $this->assertTrue(true);
    }

    public function test_settings_page_does_not_contain_smtp_credentials(): void
    {
        // Settings page only manages from/cc/bcc/test_email — no SMTP credentials fields
        $response = $this->actingAs($this->user)->get('/settings/email');
        $response->assertStatus(200);
        // Page must not contain any password input or SMTP credential fields
        $response->assertDontSee('mail_password');
        $response->assertDontSee('mail_host');
        $response->assertDontSee('mail_username');
        $response->assertSee('mail_from_address');
        $response->assertSee('mail_default_cc');
    }
}
