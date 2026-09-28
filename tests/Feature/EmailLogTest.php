<?php

namespace Tests\Feature;

use App\Models\EmailLog;
use App\Models\EmailTemplate;
use App\Models\Employee;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmailLogTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_logs_page_is_accessible(): void
    {
        $this->actingAs($this->user)->get('/logs')->assertStatus(200);
    }

    public function test_log_can_be_viewed(): void
    {
        $log = EmailLog::factory()->create();
        $this->actingAs($this->user)->get("/logs/{$log->id}")->assertStatus(200);
    }

    public function test_logs_filter_by_status(): void
    {
        $sentLog   = EmailLog::factory()->create(['status' => 'sent',   'to_email' => 'sent@example.com',   'event_date' => now()->toDateString()]);
        $failedLog = EmailLog::factory()->create(['status' => 'failed', 'to_email' => 'failed@example.com', 'event_date' => now()->subDays(2)->toDateString()]);

        $response = $this->actingAs($this->user)->get('/logs?status=sent');
        $response->assertStatus(200)
                 ->assertSee('sent@example.com')
                 ->assertDontSee('failed@example.com');
    }

    public function test_logs_filter_by_event_type(): void
    {
        EmailLog::factory()->create(['event_type' => 'birthday',    'event_date' => now()->toDateString()]);
        EmailLog::factory()->create(['event_type' => 'anniversary', 'event_date' => now()->addDay()->toDateString()]);

        $response = $this->actingAs($this->user)->get('/logs?event_type=birthday');
        $response->assertStatus(200);
    }

    public function test_already_sent_check(): void
    {
        $employee = Employee::factory()->create();
        EmailLog::factory()->create([
            'employee_id' => $employee->id,
            'event_type'  => 'birthday',
            'event_date'  => '2026-09-24',
            'status'      => 'sent',
        ]);

        $this->assertTrue(EmailLog::alreadySent($employee->id, 'birthday', '2026-09-24'));
        $this->assertFalse(EmailLog::alreadySent($employee->id, 'anniversary', '2026-09-24'));
        $this->assertFalse(EmailLog::alreadySent($employee->id, 'birthday', '2026-09-25'));
    }

    public function test_unauthenticated_cannot_view_logs(): void
    {
        $this->get('/logs')->assertRedirect('/login');
    }
}
