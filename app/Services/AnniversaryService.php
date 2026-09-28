<?php

namespace App\Services;

use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class AnniversaryService
{
    public function getTodaysAnniversaries(?Carbon $date = null): Collection
    {
        $today = $date ?? Carbon::today();

        return Employee::active()
            ->whereNotNull('date_of_joining')
            ->get()
            ->filter(fn (Employee $e) => $e->date_of_joining->month === $today->month
                && $e->date_of_joining->day === $today->day
                && $e->date_of_joining->year < $today->year)
            ->values();
    }

    public function isAnniversaryToday(Employee $employee, ?Carbon $date = null): bool
    {
        if (!$employee->date_of_joining) {
            return false;
        }
        $today = $date ?? Carbon::today();
        return $employee->date_of_joining->month === $today->month
            && $employee->date_of_joining->day === $today->day
            && $employee->date_of_joining->year < $today->year;
    }

    public function getCompletedYears(Employee $employee, ?Carbon $date = null): int
    {
        if (!$employee->date_of_joining) {
            return 0;
        }
        $today = $date ?? Carbon::today();
        return (int) $employee->date_of_joining->diffInYears($today);
    }
}
