<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmailLog;
use App\Services\BirthdayService;
use App\Services\AnniversaryService;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct(
        private BirthdayService $birthdayService,
        private AnniversaryService $anniversaryService
    ) {}

    public function index()
    {
        $today = Carbon::now();

        $todayBirthdays     = $this->birthdayService->getTodaysBirthdays($today);
        $todayAnniversaries = $this->anniversaryService->getTodaysAnniversaries($today);
        $totalEmployees     = Employee::count();
        $activeEmployees    = Employee::active()->count();

        // Email log stats for today
        $logStats = EmailLog::whereDate('event_date', $today->toDateString())
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $sentCount    = $logStats->get('sent', 0);
        $failedCount  = $logStats->get('failed', 0);
        $skippedCount = $logStats->get('skipped', 0);

        // Upcoming 7 days
        $upcoming = $this->getUpcoming(7);

        return view('dashboard', compact(
            'todayBirthdays', 'todayAnniversaries',
            'totalEmployees', 'activeEmployees',
            'sentCount', 'failedCount', 'skippedCount',
            'upcoming'
        ));
    }

    private function getUpcoming(int $days): array
    {
        $today = Carbon::today();
        $events = [];

        $employees = Employee::active()
            ->whereNotNull('date_of_birth')
            ->orWhere(fn($q) => $q->active()->whereNotNull('date_of_joining'))
            ->get();

        for ($i = 1; $i <= $days; $i++) {
            $date = $today->copy()->addDays($i);

            foreach ($employees as $emp) {
                if ($emp->date_of_birth
                    && $emp->date_of_birth->month === $date->month
                    && $emp->date_of_birth->day   === $date->day) {
                    $events[] = ['date' => $date->copy(), 'employee' => $emp, 'type' => 'birthday', 'years' => null];
                }
                if ($emp->date_of_joining
                    && $emp->date_of_joining->month === $date->month
                    && $emp->date_of_joining->day   === $date->day
                    && $emp->date_of_joining->year   < $date->year) {
                    $years = (int) $emp->date_of_joining->diffInYears($date);
                    $events[] = ['date' => $date->copy(), 'employee' => $emp, 'type' => 'anniversary', 'years' => $years];
                }
            }
        }

        usort($events, fn($a, $b) => $a['date']->timestamp - $b['date']->timestamp);
        return $events;
    }
}
