<?php

namespace Tests\Unit;

use App\Models\Employee;
use App\Services\BirthdayService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BirthdayServiceTest extends TestCase
{
    use RefreshDatabase;

    private BirthdayService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new BirthdayService();
    }

    public function test_birthday_match_on_same_month_day(): void
    {
        $today = Carbon::create(2026, 9, 24);
        $employee = Employee::factory()->create([
            'date_of_birth' => Carbon::create(1995, 9, 24)->format('Y-m-d'),
            'status' => 'active',
        ]);

        $result = $this->service->getTodaysBirthdays($today);
        $this->assertTrue($result->contains('id', $employee->id));
    }

    public function test_birthday_no_match_on_different_day(): void
    {
        $today = Carbon::create(2026, 9, 25);
        $employee = Employee::factory()->create([
            'date_of_birth' => Carbon::create(1995, 9, 24)->format('Y-m-d'),
            'status' => 'active',
        ]);

        $result = $this->service->getTodaysBirthdays($today);
        $this->assertFalse($result->contains('id', $employee->id));
    }

    public function test_inactive_employee_excluded_from_birthdays(): void
    {
        $today = Carbon::create(2026, 9, 24);
        $employee = Employee::factory()->create([
            'date_of_birth' => Carbon::create(1995, 9, 24)->format('Y-m-d'),
            'status' => 'inactive',
        ]);

        $result = $this->service->getTodaysBirthdays($today);
        $this->assertFalse($result->contains('id', $employee->id));
    }

    public function test_multiple_birthdays_detected(): void
    {
        $today = Carbon::create(2026, 9, 24);

        $emp1 = Employee::factory()->create(['date_of_birth' => '1990-09-24', 'status' => 'active', 'email' => 'e1@test.com', 'employee_code' => 'T001']);
        $emp2 = Employee::factory()->create(['date_of_birth' => '1985-09-24', 'status' => 'active', 'email' => 'e2@test.com', 'employee_code' => 'T002']);
        $emp3 = Employee::factory()->create(['date_of_birth' => '1992-09-23', 'status' => 'active', 'email' => 'e3@test.com', 'employee_code' => 'T003']);

        $result = $this->service->getTodaysBirthdays($today);
        $this->assertTrue($result->contains('id', $emp1->id));
        $this->assertTrue($result->contains('id', $emp2->id));
        $this->assertFalse($result->contains('id', $emp3->id));
    }

    public function test_year_does_not_matter_for_birthday_match(): void
    {
        $today = Carbon::create(2026, 9, 24);
        $employee = Employee::factory()->create([
            'date_of_birth' => Carbon::create(1960, 9, 24)->format('Y-m-d'),
            'status' => 'active',
        ]);

        $result = $this->service->getTodaysBirthdays($today);
        $this->assertTrue($result->contains('id', $employee->id));
    }

    public function test_is_birthday_today_method(): void
    {
        $today = Carbon::create(2026, 9, 24);
        $employee = new Employee(['date_of_birth' => '1995-09-24', 'status' => 'active']);

        $this->assertTrue($this->service->isBirthdayToday($employee, $today));
    }

    public function test_is_not_birthday_today(): void
    {
        $today = Carbon::create(2026, 9, 24);
        $employee = new Employee(['date_of_birth' => '1995-09-23', 'status' => 'active']);

        $this->assertFalse($this->service->isBirthdayToday($employee, $today));
    }
}
