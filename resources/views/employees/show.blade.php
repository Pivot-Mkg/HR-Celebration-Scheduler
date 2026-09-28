@extends('layouts.app')
@section('title', $employee->employee_name)

@section('content')
<div class="row">
<div class="col-lg-8">
<div class="card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-person me-2"></i>Employee Details</span>
        <div class="d-flex gap-2">
            <a href="{{ route('employees.edit', $employee) }}" class="btn btn-sm btn-primary">
                <i class="bi bi-pencil me-1"></i> Edit
            </a>
            <a href="{{ route('employees.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-sm-6"><div class="text-muted small">Employee Code</div><div class="fw-semibold">{{ $employee->employee_code }}</div></div>
            <div class="col-sm-6"><div class="text-muted small">Name</div><div class="fw-semibold">{{ $employee->employee_name }}</div></div>
            <div class="col-sm-6"><div class="text-muted small">Email</div><div>{{ $employee->email }}</div></div>
            <div class="col-sm-6"><div class="text-muted small">Status</div>
                <span class="badge {{ $employee->status === 'active' ? 'badge-active' : 'badge-inactive' }} rounded-pill px-3">
                    {{ ucfirst($employee->status) }}
                </span>
            </div>
            <div class="col-sm-6"><div class="text-muted small">Department</div><div>{{ $employee->department ?? '—' }}</div></div>
            <div class="col-sm-6"><div class="text-muted small">Designation</div><div>{{ $employee->designation ?? '—' }}</div></div>
            <div class="col-sm-6"><div class="text-muted small">Date of Birth</div><div>{{ $employee->date_of_birth?->format('d M Y') ?? '—' }}</div></div>
            <div class="col-sm-6"><div class="text-muted small">Date of Joining</div><div>{{ $employee->date_of_joining?->format('d M Y') ?? '—' }}</div></div>
            <div class="col-sm-6"><div class="text-muted small">Manager</div><div>{{ $employee->manager_name ?? '—' }}</div></div>
            <div class="col-sm-6"><div class="text-muted small">Manager Email</div><div>{{ $employee->manager_email ?? '—' }}</div></div>
        </div>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header"><i class="bi bi-calendar-event me-2"></i>Upcoming Celebrations</div>
    <div class="card-body">
        <div class="row g-3">
            @if($employee->date_of_birth)
            <div class="col-sm-6">
                <div class="p-3 rounded" style="background:#fff0f5; border-left: 3px solid #f5576c;">
                    <div class="text-muted small">🎂 Next Birthday</div>
                    <div class="fw-semibold">{{ $employee->next_birthday->format('d M Y') }}</div>
                    <div class="text-muted small">In {{ now()->diffInDays($employee->next_birthday) }} days</div>
                </div>
            </div>
            @endif
            @if($employee->date_of_joining)
            <div class="col-sm-6">
                <div class="p-3 rounded" style="background:#f0f5ff; border-left: 3px solid #667eea;">
                    <div class="text-muted small">🌟 Next Work Anniversary</div>
                    <div class="fw-semibold">{{ $employee->next_anniversary->format('d M Y') }}</div>
                    <div class="text-muted small">{{ $employee->completed_years }} years completed</div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
</div>

<div class="col-lg-4">
<div class="card mb-3">
    <div class="card-header"><i class="bi bi-envelope me-2"></i>Email Preview</div>
    <div class="card-body d-flex flex-column gap-2">
        @if($employee->date_of_birth)
        <a href="{{ route('email.preview.birthday', $employee) }}" class="btn btn-outline-danger">
            <i class="bi bi-cake2 me-2"></i> Preview Birthday Email
        </a>
        @endif
        @if($employee->date_of_joining)
        <a href="{{ route('email.preview.anniversary', $employee) }}" class="btn btn-outline-primary">
            <i class="bi bi-star me-2"></i> Preview Anniversary Email
        </a>
        @endif
    </div>
</div>
</div>
</div>
@endsection
