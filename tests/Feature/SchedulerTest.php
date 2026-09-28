<?php

namespace Tests\Feature;

use App\Models\EmailTemplate;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SchedulerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        Mail::fake();
    }

    public function test_scheduler_page_accessible(): void
    {
        $this->actingAs($this->user)->get('/scheduler')->assertStatus(200);
    }

    public function test_scheduler_run_returns_results_view(): void
    {
        $this->actingAs($this->user)
             ->post('/scheduler/run')
             ->assertStatus(200)
             ->assertSee('Scheduler completed');
    }

    public function test_dry_run_does_not_send_emails(): void
    {
        EmailTemplate::factory()->create(['event_type' => 'birthday', 'is_active' => true, 'is_default' => true]);
        Employee::factory()->create(['date_of_birth' => now()->format('Y-m-d'), 'status' => 'active']);

        $this->actingAs($this->user)
             ->post('/scheduler/run', ['dry_run' => '1'])
             ->assertStatus(200)
             ->assertSee('DRY RUN');

        Mail::assertNothingSent();
    }

    public function test_scheduler_processes_birthday(): void
    {
        EmailTemplate::factory()->create(['event_type' => 'birthday', 'is_active' => true, 'is_default' => true, 'subject' => 'BD {{ employee_name }}', 'body_html' => '<p>BD</p>']);
        $employee = Employee::factory()->create([
            'date_of_birth' => now()->format('Y-m-d'),
            'status'        => 'active',
        ]);

        $this->actingAs($this->user)->post('/scheduler/run')->assertStatus(200);

        $this->assertDatabaseHas('email_logs', [
            'employee_id' => $employee->id,
            'event_type'  => 'birthday',
            'status'      => 'sent',
        ]);
    }

    public function test_unauthenticated_user_cannot_run_scheduler(): void
    {
        $this->post('/scheduler/run')->assertRedirect('/login');
    }
}
