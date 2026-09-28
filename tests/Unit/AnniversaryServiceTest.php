<?php

namespace Tests\Unit;

use App\Models\Employee;
use App\Services\AnniversaryService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnniversaryServiceTest extends TestCase
{
    use RefreshDatabase;

    private AnniversaryService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new AnniversaryService();
    }

    public function test_anniversary_match_on_same_month_day(): void
    {
        $today = Carbon::create(2026, 9, 24);
        $employee = Employee::factory()->create([
            'date_of_joining' => Carbon::create(2022, 9, 24)->format('Y-m-d'),
            'status' => 'active',
        ]);

        $result = $this->service->getTodaysAnniversaries($today);
        $this->assertTrue($result->contains('id', $employee->id));
    }

    public function test_anniversary_no_match_on_different_day(): void
    {
        $today = Carbon::create(2026, 9, 25);
        $employee = Employee::factory()->create([
            'date_of_joining' => Carbon::create(2022, 9, 24)->format('Y-m-d'),
            'status' => 'active',
        ]);

        $result = $this->service->getTodaysAnniversaries($today);
        $this->assertFalse($result->contains('id', $employee->id));
    }

    public function test_inactive_employee_excluded_from_anniversaries(): void
    {
        $today = Carbon::create(2026, 9, 24);
        $employee = Employee::factory()->create([
            'date_of_joining' => Carbon::create(2022, 9, 24)->format('Y-m-d'),
            'status' => 'inactive',
        ]);

        $result = $this->service->getTodaysAnniversaries($today);
        $this->assertFalse($result->contains('id', $employee->id));
    }

    public function test_completed_years_calculated_correctly(): void
    {
        $today = Carbon::create(2026, 9, 24);
        $employee = Employee::factory()->create([
            'date_of_joining' => Carbon::create(2022, 9, 24)->format('Y-m-d'),
        ]);

        $years = $this->service->getCompletedYears($employee, $today);
        $this->assertEquals(4, $years);
    }

    public function test_completed_years_single_year(): void
    {
        $today = Carbon::create(2026, 9, 24);
        $employee = Employee::factory()->create([
            'date_of_joining' => Carbon::create(2025, 9, 24)->format('Y-m-d'),
        ]);

        $years = $this->service->getCompletedYears($employee, $today);
        $this->assertEquals(1, $years);
    }

    public function test_joining_date_same_year_not_counted_as_anniversary(): void
    {
        $today = Carbon::create(2026, 9, 24);
        $employee = Employee::factory()->create([
            'date_of_joining' => Carbon::create(2026, 9, 24)->format('Y-m-d'),
            'status' => 'active',
        ]);

        $result = $this->service->getTodaysAnniversaries($today);
        $this->assertFalse($result->contains('id', $employee->id));
    }

    public function test_both_birthday_and_anniversary_detected_independently(): void
    {
        $today = Carbon::create(2026, 9, 24);

        $employee = Employee::factory()->create([
            'date_of_birth'   => Carbon::create(1995, 9, 24)->format('Y-m-d'),
            'date_of_joining' => Carbon::create(2022, 9, 24)->format('Y-m-d'),
            'status'          => 'active',
        ]);

        $birthdays     = app(\App\Services\BirthdayService::class)->getTodaysBirthdays($today);
        $anniversaries = $this->service->getTodaysAnniversaries($today);

        $this->assertTrue($birthdays->contains('id', $employee->id));
        $this->assertTrue($anniversaries->contains('id', $employee->id));
    }
}
