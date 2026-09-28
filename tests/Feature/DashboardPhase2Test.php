<?php

namespace Tests\Feature;

use App\Models\EmailLog;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardPhase2Test extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_dashboard_shows_today_sent_count(): void
    {
        EmailLog::factory()->create(['status' => 'sent',   'event_date' => now()->toDateString(), 'sent_at' => now()]);
        EmailLog::factory()->create(['status' => 'failed', 'event_date' => now()->toDateString()]);

        $this->actingAs($this->user)->get('/dashboard')->assertStatus(200)->assertSee('1');
    }

    public function test_dashboard_shows_upcoming_events(): void
    {
        Employee::factory()->create([
            'employee_name' => 'Alice Upcoming',
            'date_of_birth' => now()->addDays(3)->format('Y-m-d'),
            'status'        => 'active',
        ]);

        $this->actingAs($this->user)
             ->get('/dashboard')
             ->assertStatus(200)
             ->assertSee('Alice Upcoming');
    }

    public function test_dashboard_quick_actions_present(): void
    {
        $this->actingAs($this->user)
             ->get('/dashboard')
             ->assertStatus(200)
             ->assertSee('Run Scheduler');
    }
}
