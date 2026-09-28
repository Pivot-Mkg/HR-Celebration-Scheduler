<?php

namespace Tests\Unit;

use App\Mail\BirthdayMail;
use App\Mail\AnniversaryMail;
use App\Models\Employee;
use App\Models\Setting;
use App\Services\EmailService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class EmailServiceTest extends TestCase
{
    use RefreshDatabase;

    private EmailService $emailService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->emailService = new EmailService();
    }

    public function test_birthday_mail_has_correct_subject(): void
    {
        $employee = Employee::factory()->create(['employee_name' => 'Alice Test']);
        $config   = ['from_address' => 'hr@pivotmkg.com', 'from_name' => 'HR Team'];
        $mail     = new BirthdayMail($employee, $config);

        $this->assertStringContainsString('Alice Test', $mail->envelope()->subject);
        $this->assertStringContainsString('Birthday', $mail->envelope()->subject);
    }

    public function test_birthday_mail_from_address_comes_from_config(): void
    {
        $employee = Employee::factory()->create();
        $config   = ['from_address' => 'hr@pivotmkg.com', 'from_name' => 'HR Team'];
        $mail     = new BirthdayMail($employee, $config);

        $this->assertEquals('hr@pivotmkg.com', $mail->envelope()->from->address);
        $this->assertEquals('HR Team', $mail->envelope()->from->name);
    }

    public function test_anniversary_mail_has_correct_subject(): void
    {
        $employee = Employee::factory()->create([
            'employee_name'   => 'Bob Test',
            'date_of_joining' => Carbon::create(2022, 9, 24)->format('Y-m-d'),
        ]);
        $config = ['from_address' => 'hr@pivotmkg.com', 'from_name' => 'HR Team'];
        $mail   = new AnniversaryMail($employee, $config);

        $this->assertStringContainsString('Bob Test', $mail->envelope()->subject);
        $this->assertStringContainsString('Anniversary', $mail->envelope()->subject);
    }

    public function test_anniversary_mail_calculates_years(): void
    {
        $employee = Employee::factory()->create([
            'date_of_joining' => Carbon::create(2022, 9, 24)->format('Y-m-d'),
        ]);
        $config = ['from_address' => 'hr@pivotmkg.com', 'from_name' => 'HR Team'];
        $mail   = new AnniversaryMail($employee, $config);

        $this->assertGreaterThanOrEqual(1, $mail->completedYears);
    }

    public function test_email_service_sends_birthday_test_email(): void
    {
        Mail::fake();
        Setting::set('mail_from_address', 'hr@pivotmkg.com');

        $employee = Employee::factory()->create(['email' => 'employee@pivotmkg.com']);
        $result = $this->emailService->sendBirthdayEmail($employee, true, 'test@example.com');

        $this->assertTrue($result);
        Mail::assertSent(BirthdayMail::class);
    }

    public function test_email_service_sends_anniversary_test_email(): void
    {
        Mail::fake();
        Setting::set('mail_from_address', 'hr@pivotmkg.com');

        $employee = Employee::factory()->create(['email' => 'employee@pivotmkg.com']);
        $result = $this->emailService->sendAnniversaryEmail($employee, true, 'test@example.com');

        $this->assertTrue($result);
        Mail::assertSent(AnniversaryMail::class);
    }

    public function test_parse_addresses_with_valid_emails(): void
    {
        $result = $this->emailService->parseAddresses('hr@pivotmkg.com, manager@pivotmkg.com');
        $this->assertCount(2, $result);
        $this->assertContains('hr@pivotmkg.com', $result);
        $this->assertContains('manager@pivotmkg.com', $result);
    }

    public function test_parse_addresses_filters_invalid_emails(): void
    {
        $result = $this->emailService->parseAddresses('valid@example.com, not-an-email, another@example.com');
        $this->assertCount(2, $result);
    }

    public function test_validate_email_address(): void
    {
        $this->assertTrue($this->emailService->validateEmailAddress('valid@example.com'));
        $this->assertFalse($this->emailService->validateEmailAddress('invalid-email'));
    }

    public function test_birthday_email_sent_to_employee_not_from(): void
    {
        Mail::fake();

        $employee = Employee::factory()->create(['email' => 'employee@pivotmkg.com']);
        $this->emailService->sendBirthdayEmail($employee, true, 'test@example.com');

        Mail::assertSent(BirthdayMail::class, function ($mail) {
            return $mail->hasTo('test@example.com');
        });
    }

    public function test_settings_mail_config_loads(): void
    {
        Setting::set('mail_from_address', 'configured@pivotmkg.com');
        Setting::set('mail_from_name', 'Configured HR');
        Setting::set('mail_default_cc', 'cc@pivotmkg.com');

        $config = Setting::getMailConfig();
        $this->assertEquals('configured@pivotmkg.com', $config['from_address']);
        $this->assertEquals('Configured HR', $config['from_name']);
        $this->assertEquals('cc@pivotmkg.com', $config['cc']);
    }
}
