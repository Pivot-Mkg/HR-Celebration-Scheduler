<?php

namespace Tests\Unit;

use App\Models\EmailLog;
use App\Models\EmailTemplate;
use App\Models\Employee;
use App\Services\AnniversaryService;
use App\Services\BirthdayService;
use App\Services\CelebrationProcessor;
use App\Services\EmailService;
use App\Services\TemplateRenderer;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class CelebrationProcessorTest extends TestCase
{
    use RefreshDatabase;

    private CelebrationProcessor $processor;

    protected function setUp(): void
    {
        parent::setUp();
        $this->processor = app(CelebrationProcessor::class);
    }

    private function makeDefaultTemplate(string $eventType): EmailTemplate
    {
        return EmailTemplate::factory()->create([
            'event_type' => $eventType,
            'is_active'  => true,
            'is_default' => true,
            'subject'    => 'Happy ' . ucfirst($eventType) . ', {{ employee_name }}!',
            'body_html'  => '<p>Dear {{ employee_name }}</p>',
        ]);
    }

    public function test_dry_run_does_not_send_emails(): void
    {
        Mail::fake();
        $today = Carbon::create(2026, 9, 24);
        $this->makeDefaultTemplate('birthday');

        Employee::factory()->create([
            'date_of_birth' => '1990-09-24',
            'status'        => 'active',
        ]);

        $results = $this->processor->process($today, dryRun: true);

        Mail::assertNothingSent();
        $this->assertEquals(0, $results['sent']);
        $this->assertEquals(1, count(array_filter($results['details'], fn($d) => $d['status'] === 'would_send')));
    }

    public function test_birthday_is_processed(): void
    {
        Mail::fake();
        $today = Carbon::create(2026, 9, 24);
        $this->makeDefaultTemplate('birthday');

        $employee = Employee::factory()->create(['date_of_birth' => '1990-09-24', 'status' => 'active']);

        $results = $this->processor->process($today, dryRun: false);

        $this->assertEquals(1, $results['sent']);
        $this->assertDatabaseHas('email_logs', [
            'employee_id' => $employee->id,
            'event_type'  => 'birthday',
            'status'      => 'sent',
        ]);
    }

    public function test_anniversary_is_processed(): void
    {
        Mail::fake();
        $today = Carbon::create(2026, 9, 24);
        $this->makeDefaultTemplate('anniversary');

        $employee = Employee::factory()->create(['date_of_joining' => '2022-09-24', 'status' => 'active']);

        $results = $this->processor->process($today, dryRun: false);

        $this->assertEquals(1, $results['sent']);
        $this->assertDatabaseHas('email_logs', [
            'employee_id' => $employee->id,
            'event_type'  => 'anniversary',
            'status'      => 'sent',
        ]);
    }

    public function test_both_events_processed_for_same_employee(): void
    {
        Mail::fake();
        $today = Carbon::create(2026, 9, 24);
        $this->makeDefaultTemplate('birthday');
        $this->makeDefaultTemplate('anniversary');

        $employee = Employee::factory()->create([
            'date_of_birth'   => '1990-09-24',
            'date_of_joining' => '2022-09-24',
            'status'          => 'active',
        ]);

        $results = $this->processor->process($today, dryRun: false);

        $this->assertEquals(2, $results['sent']);
        $this->assertDatabaseHas('email_logs', ['employee_id' => $employee->id, 'event_type' => 'birthday',  'status' => 'sent']);
        $this->assertDatabaseHas('email_logs', ['employee_id' => $employee->id, 'event_type' => 'anniversary','status' => 'sent']);
    }

    public function test_inactive_employee_not_processed(): void
    {
        Mail::fake();
        $today = Carbon::create(2026, 9, 24);
        $this->makeDefaultTemplate('birthday');

        Employee::factory()->create(['date_of_birth' => '1990-09-24', 'status' => 'inactive']);

        $results = $this->processor->process($today);
        $this->assertEquals(0, $results['birthdays']);
    }

    public function test_duplicate_protection_skips_second_run(): void
    {
        Mail::fake();
        $today = Carbon::create(2026, 9, 24);
        $this->makeDefaultTemplate('birthday');

        Employee::factory()->create(['date_of_birth' => '1990-09-24', 'status' => 'active']);

        $results1 = $this->processor->process($today);
        $results2 = $this->processor->process($today);

        $this->assertEquals(1, $results1['sent']);
        $this->assertEquals(0, $results2['sent']);
        $this->assertEquals(1, $results2['skipped']);
    }

    public function test_no_template_results_in_skip(): void
    {
        Mail::fake();
        $today = Carbon::create(2026, 9, 24);

        Employee::factory()->create(['date_of_birth' => '1990-09-24', 'status' => 'active']);

        $results = $this->processor->process($today);
        $this->assertEquals(0, $results['sent']);
        $this->assertEquals(1, $results['skipped']);
    }

    public function test_failed_email_does_not_stop_processing(): void
    {
        Mail::fake();
        Mail::shouldReceive('to')->andThrow(new \RuntimeException('SMTP down'));

        $today = Carbon::create(2026, 9, 24);
        $this->makeDefaultTemplate('birthday');

        Employee::factory()->create(['date_of_birth' => '1990-09-24', 'status' => 'active', 'email' => 'e1@test.com', 'employee_code' => 'F001']);
        Employee::factory()->create(['date_of_birth' => '1990-09-24', 'status' => 'active', 'email' => 'e2@test.com', 'employee_code' => 'F002']);

        // Processor should not throw, just log failures
        $results = $this->processor->process($today);
        $this->assertEquals(2, $results['failed']);
    }

    public function test_no_events_returns_empty_details(): void
    {
        Mail::fake();
        $today = Carbon::create(2026, 9, 24);
        // No employees at all
        $results = $this->processor->process($today);
        $this->assertEmpty($results['details']);
    }
}
