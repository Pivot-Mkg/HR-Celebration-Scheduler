<?php

namespace App\Services;

use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class BirthdayService
{
    public function getTodaysBirthdays(?Carbon $date = null): Collection
    {
        $today = $date ?? Carbon::today();

        return Employee::active()
            ->whereNotNull('date_of_birth')
            ->get()
            ->filter(fn (Employee $e) => $e->date_of_birth->month === $today->month
                && $e->date_of_birth->day === $today->day)
            ->values();
    }

    public function isBirthdayToday(Employee $employee, ?Carbon $date = null): bool
    {
        if (!$employee->date_of_birth) {
            return false;
        }
        $today = $date ?? Carbon::today();
        return $employee->date_of_birth->month === $today->month
            && $employee->date_of_birth->day === $today->day;
    }
}
