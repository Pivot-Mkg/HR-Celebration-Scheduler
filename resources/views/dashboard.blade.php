@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="stat-card" style="background: linear-gradient(135deg,#667eea,#764ba2);">
            <div class="d-flex justify-content-between align-items-center">
                <div><div class="opacity-75 small">Total Employees</div><div class="fs-2 fw-bold">{{ $totalEmployees }}</div></div>
                <i class="bi bi-people fs-1 opacity-40"></i>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card" style="background: linear-gradient(135deg,#11998e,#38ef7d);">
            <div class="d-flex justify-content-between align-items-center">
                <div><div class="opacity-75 small">Active</div><div class="fs-2 fw-bold">{{ $activeEmployees }}</div></div>
                <i class="bi bi-person-check fs-1 opacity-40"></i>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card" style="background: linear-gradient(135deg,#f093fb,#f5576c);">
            <div class="d-flex justify-content-between align-items-center">
                <div><div class="opacity-75 small">Birthdays Today</div><div class="fs-2 fw-bold">{{ $todayBirthdays->count() }}</div></div>
                <i class="bi bi-cake2 fs-1 opacity-40"></i>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card" style="background: linear-gradient(135deg,#4facfe,#00f2fe);">
            <div class="d-flex justify-content-between align-items-center">
                <div><div class="opacity-75 small">Anniversaries Today</div><div class="fs-2 fw-bold">{{ $todayAnniversaries->count() }}</div></div>
                <i class="bi bi-star fs-1 opacity-40"></i>
            </div>
        </div>
    </div>
</div>

{{-- Email stats row --}}
@if($sentCount + $failedCount + $skippedCount > 0)
<div class="row g-3 mb-4">
    <div class="col-4">
        <div class="card p-3 text-center status-sent rounded-3">
            <div class="fs-3 fw-bold">{{ $sentCount }}</div>
            <div class="small">Emails Sent Today</div>
        </div>
    </div>
    <div class="col-4">
        <div class="card p-3 text-center status-failed rounded-3">
            <div class="fs-3 fw-bold">{{ $failedCount }}</div>
            <div class="small">Failed Today</div>
        </div>
    </div>
    <div class="col-4">
        <div class="card p-3 text-center status-skipped rounded-3">
            <div class="fs-3 fw-bold">{{ $skippedCount }}</div>
            <div class="small">Skipped Today</div>
        </div>
    </div>
</div>
@endif

<div class="row g-3 mb-4">
    {{-- Today Birthdays --}}
    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-cake2 me-2 text-danger"></i>Today's Birthdays</span>
                <span class="badge bg-danger text-white">{{ now()->format('d M Y') }}</span>
            </div>
            <div class="card-body p-0">
                @forelse($todayBirthdays as $emp)
                <div class="d-flex align-items-center justify-content-between px-4 py-3 border-bottom">
                    <div>
                        <div class="fw-semibold">{{ $emp->employee_name }}</div>
                        <div class="text-muted small">{{ $emp->designation }} · {{ $emp->department }}</div>
                    </div>
                    <div class="d-flex gap-1">
                        <a href="{{ route('email.preview.birthday', $emp) }}" class="btn btn-sm btn-outline-primary" title="Preview"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('employees.show', $emp) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
                @empty
                <div class="px-4 py-4 text-center text-muted"><i class="bi bi-calendar-x fs-3 d-block mb-1"></i>No birthdays today</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Today Anniversaries --}}
    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-star me-2 text-warning"></i>Today's Work Anniversaries</span>
                <span class="badge bg-warning text-dark">{{ now()->format('d M Y') }}</span>
            </div>
            <div class="card-body p-0">
                @forelse($todayAnniversaries as $emp)
                <div class="d-flex align-items-center justify-content-between px-4 py-3 border-bottom">
                    <div>
                        <div class="fw-semibold">{{ $emp->employee_name }}</div>
                        <div class="text-muted small">{{ $emp->completed_years }} {{ $emp->completed_years === 1 ? 'Year' : 'Years' }} · {{ $emp->department }}</div>
                    </div>
                    <div class="d-flex gap-1">
                        <a href="{{ route('email.preview.anniversary', $emp) }}" class="btn btn-sm btn-outline-primary" title="Preview"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('employees.show', $emp) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
                @empty
                <div class="px-4 py-4 text-center text-muted"><i class="bi bi-calendar-x fs-3 d-block mb-1"></i>No anniversaries today</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- Quick Actions + Upcoming --}}
<div class="row g-3">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header"><i class="bi bi-lightning me-2"></i>Quick Actions</div>
            <div class="card-body d-flex flex-column gap-2">
                <a href="{{ route('scheduler.index') }}" class="btn btn-primary"><i class="bi bi-play-circle me-2"></i>Run Scheduler</a>
                <form method="POST" action="{{ route('scheduler.run') }}">
                    @csrf <input type="hidden" name="dry_run" value="1">
                    <button class="btn btn-outline-secondary w-100"><i class="bi bi-eye me-2"></i>Dry Run</button>
                </form>
                <a href="{{ route('employees.create') }}" class="btn btn-outline-primary"><i class="bi bi-person-plus me-2"></i>Add Employee</a>
                <a href="{{ route('templates.create') }}" class="btn btn-outline-success"><i class="bi bi-plus-circle me-2"></i>Create Template</a>
                <a href="{{ route('logs.index') }}" class="btn btn-outline-secondary"><i class="bi bi-journal-text me-2"></i>Email Logs</a>
                <a href="{{ route('settings.email') }}" class="btn btn-outline-secondary"><i class="bi bi-gear me-2"></i>Email Settings</a>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header"><i class="bi bi-calendar-range me-2"></i>Upcoming (Next 7 Days)</div>
            <div class="card-body p-0">
                @forelse($upcoming as $event)
                <div class="d-flex align-items-center justify-content-between px-4 py-2 border-bottom">
                    <div class="d-flex align-items-center gap-3">
                        <span class="text-muted small" style="min-width:70px;">{{ $event['date']->format('d M') }}</span>
                        <span>{{ $event['type'] === 'birthday' ? '🎂' : '🌟' }}</span>
                        <div>
                            <div class="fw-semibold">{{ $event['employee']->employee_name }}</div>
                            <div class="text-muted small">
                                {{ ucfirst($event['type']) }}
                                @if($event['type'] === 'anniversary' && $event['years'])
                                    · {{ $event['years'] + 1 }} {{ ($event['years'] + 1) === 1 ? 'Year' : 'Years' }}
                                @endif
                                · {{ $event['employee']->department }}
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="px-4 py-4 text-center text-muted"><i class="bi bi-calendar fs-3 d-block mb-1"></i>No celebrations in the next 7 days</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
