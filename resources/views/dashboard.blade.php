@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
{{-- KPI Row --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="kpi-card kpi-purple">
            <div class="kpi-icon"><i data-lucide="users"></i></div>
            <div class="kpi-value">{{ $totalEmployees }}</div>
            <div class="kpi-label">Total Employees</div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="kpi-card kpi-green">
            <div class="kpi-icon"><i data-lucide="user-check"></i></div>
            <div class="kpi-value">{{ $activeEmployees }}</div>
            <div class="kpi-label">Active</div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="kpi-card kpi-pink">
            <div class="kpi-icon"><i data-lucide="cake-slice"></i></div>
            <div class="kpi-value">{{ $todayBirthdays->count() }}</div>
            <div class="kpi-label">Birthdays Today</div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="kpi-card kpi-blue">
            <div class="kpi-icon"><i data-lucide="star"></i></div>
            <div class="kpi-value">{{ $todayAnniversaries->count() }}</div>
            <div class="kpi-label">Anniversaries Today</div>
        </div>
    </div>
</div>

{{-- Email stats (shown only when there's activity today) --}}
@if($sentCount + $failedCount + $skippedCount > 0)
<div class="row g-3 mb-4">
    <div class="col-4">
        <div class="kpi-card kpi-green">
            <div class="kpi-icon"><i data-lucide="mail-check"></i></div>
            <div class="kpi-value">{{ $sentCount }}</div>
            <div class="kpi-label">Sent Today</div>
        </div>
    </div>
    <div class="col-4">
        <div class="kpi-card kpi-red">
            <div class="kpi-icon"><i data-lucide="mail-x"></i></div>
            <div class="kpi-value">{{ $failedCount }}</div>
            <div class="kpi-label">Failed Today</div>
        </div>
    </div>
    <div class="col-4">
        <div class="kpi-card kpi-amber">
            <div class="kpi-icon"><i data-lucide="mail-minus"></i></div>
            <div class="kpi-value">{{ $skippedCount }}</div>
            <div class="kpi-label">Skipped Today</div>
        </div>
    </div>
</div>
@endif

<div class="row g-3 mb-4">
    {{-- Today's Birthdays --}}
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">
                <i data-lucide="cake-slice" style="color:#db2777;"></i>Today's Birthdays
                <span class="badge rounded-pill ms-auto" style="background:#fee2e2;color:#991b1b;font-size:.72rem;">{{ now()->format('d M Y') }}</span>
            </div>
            <div class="card-body p-0">
                @forelse($todayBirthdays as $emp)
                <div class="celebration-row">
                    <div>
                        <div class="fw-semibold" style="font-size:.9rem;">{{ $emp->employee_name }}</div>
                        <div class="text-muted" style="font-size:.78rem;">{{ $emp->designation ?? '' }}{{ ($emp->designation && $emp->department) ? ' · ' : '' }}{{ $emp->department ?? '' }}</div>
                    </div>
                    <div class="d-flex gap-1">
                        <a href="{{ route('email.preview.birthday', $emp) }}" class="btn btn-sm btn-outline-primary" title="Preview email"><i data-lucide="eye"></i></a>
                        <a href="{{ route('employees.show', $emp) }}" class="btn btn-sm btn-outline-secondary" title="View employee"><i data-lucide="arrow-right"></i></a>
                    </div>
                </div>
                @empty
                <div class="text-center text-muted py-5">
                    <i data-lucide="calendar-x" class="icon-display"></i>
                    <span style="font-size:.85rem;">No birthdays today</span>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Today's Anniversaries --}}
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">
                <i data-lucide="star" style="color:#d97706;"></i>Today's Work Anniversaries
                <span class="badge rounded-pill ms-auto" style="background:#fef3c7;color:#92400e;font-size:.72rem;">{{ now()->format('d M Y') }}</span>
            </div>
            <div class="card-body p-0">
                @forelse($todayAnniversaries as $emp)
                <div class="celebration-row">
                    <div>
                        <div class="fw-semibold" style="font-size:.9rem;">{{ $emp->employee_name }}</div>
                        <div class="text-muted" style="font-size:.78rem;">
                            {{ $emp->completed_years }} {{ $emp->completed_years === 1 ? 'Year' : 'Years' }}{{ $emp->department ? ' · ' . $emp->department : '' }}
                        </div>
                    </div>
                    <div class="d-flex gap-1">
                        <a href="{{ route('email.preview.anniversary', $emp) }}" class="btn btn-sm btn-outline-primary" title="Preview email"><i data-lucide="eye"></i></a>
                        <a href="{{ route('employees.show', $emp) }}" class="btn btn-sm btn-outline-secondary" title="View employee"><i data-lucide="arrow-right"></i></a>
                    </div>
                </div>
                @empty
                <div class="text-center text-muted py-5">
                    <i data-lucide="calendar-x" class="icon-display"></i>
                    <span style="font-size:.85rem;">No anniversaries today</span>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- Quick Actions --}}
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header"><i data-lucide="zap" style="color:#d97706;"></i>Quick Actions</div>
            <div class="card-body d-flex flex-column gap-2 pt-3">
                <a href="{{ route('scheduler.index') }}" class="btn btn-primary">
                    <i data-lucide="circle-play"></i>Run Scheduler
                </a>
                <form method="POST" action="{{ route('scheduler.run') }}">
                    @csrf <input type="hidden" name="dry_run" value="1">
                    <button class="btn btn-outline-secondary w-100"><i data-lucide="eye"></i>Dry Run</button>
                </form>
                <a href="{{ route('employees.create') }}" class="btn btn-outline-primary">
                    <i data-lucide="user-plus"></i>Add Employee
                </a>
                <a href="{{ route('templates.create') }}" class="btn btn-outline-success">
                    <i data-lucide="circle-plus"></i>Create Template
                </a>
                <a href="{{ route('logs.index') }}" class="btn btn-outline-secondary">
                    <i data-lucide="book-open"></i>Email Logs
                </a>
                <a href="{{ route('settings.email') }}" class="btn btn-outline-secondary">
                    <i data-lucide="settings"></i>Email Settings
                </a>
            </div>
        </div>
    </div>

    {{-- Upcoming 7 days --}}
    <div class="col-md-8">
        <div class="card h-100">
            <div class="card-header"><i data-lucide="calendar-range" style="color:#4f46e5;"></i>Upcoming — Next 7 Days</div>
            <div class="card-body p-0">
                @forelse($upcoming as $event)
                <div class="celebration-row">
                    <div class="d-flex align-items-center gap-3">
                        <div class="text-muted text-center" style="min-width:52px;font-size:.8rem;">
                            <div class="fw-bold" style="font-size:1.1rem;color:#111827;">{{ $event['date']->format('d') }}</div>
                            <div>{{ $event['date']->format('M') }}</div>
                        </div>
                        <div style="font-size:1.05rem;">{{ $event['type'] === 'birthday' ? '🎂' : '🌟' }}</div>
                        <div>
                            <div class="fw-semibold" style="font-size:.9rem;">{{ $event['employee']->employee_name }}</div>
                            <div class="text-muted" style="font-size:.78rem;">
                                {{ ucfirst($event['type']) }}
                                @if($event['type'] === 'anniversary' && $event['years'])
                                    · {{ $event['years'] + 1 }} {{ ($event['years'] + 1) === 1 ? 'Year' : 'Years' }}
                                @endif
                                @if($event['employee']->department)· {{ $event['employee']->department }}@endif
                            </div>
                        </div>
                    </div>
                    <span class="badge rounded-pill" style="font-size:.7rem;{{ $event['type'] === 'birthday' ? 'background:#fce7f3;color:#9d174d;' : 'background:#ede9fe;color:#5b21b6;' }}">
                        {{ $event['type'] === 'birthday' ? 'Birthday' : 'Anniversary' }}
                    </span>
                </div>
                @empty
                <div class="text-center text-muted py-5">
                    <i data-lucide="calendar" class="icon-display"></i>
                    <span style="font-size:.85rem;">No celebrations in the next 7 days</span>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
