<?php

namespace App\Http\Controllers;

use App\Models\EmailLog;
use App\Models\Employee;
use App\Services\CelebrationProcessor;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EmailLogController extends Controller
{
    public function __construct(private CelebrationProcessor $processor) {}

    public function index(Request $request)
    {
        $filters = $request->only(['status', 'event_type', 'employee_id', 'date_from', 'date_to']);

        $logs = EmailLog::with(['employee', 'template'])
            ->filtered($filters)
            ->orderByDesc('created_at')
            ->paginate(25)
            ->withQueryString();

        $employees = Employee::orderBy('employee_name')->get();

        return view('logs.index', compact('logs', 'employees', 'filters'));
    }

    public function show(EmailLog $log)
    {
        $log->load(['employee', 'template']);
        return view('logs.show', compact('log'));
    }

    public function retry(EmailLog $log)
    {
        if ($log->status === 'sent') {
            return back()->with('error', 'Email already sent successfully — retry not needed.');
        }

        $employee = $log->employee;
        if (!$employee || $employee->status !== 'active') {
            return back()->with('error', 'Employee is inactive or not found.');
        }

        // Delete the failed/skipped log so the processor can create a fresh one
        $log->delete();

        $results = $this->processor->process(Carbon::parse($log->event_date)->setTime(12, 0));

        $sent = collect($results['details'])->where('status', 'sent')
            ->where('event', $log->event_type)->count();

        if ($sent > 0) {
            return redirect()->route('logs.index')
                ->with('success', 'Email retried and sent successfully.');
        }

        return back()->with('error', 'Retry failed. Check email settings and logs.');
    }
}
