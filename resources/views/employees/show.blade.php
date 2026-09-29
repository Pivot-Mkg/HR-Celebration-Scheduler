@extends('layouts.app')
@section('title', $employee->employee_name)
@section('breadcrumb', '<a href="' . route('employees.index') . '">Employees</a> / ' . e($employee->employee_name))

@section('content')
<div class="row g-3">
<div class="col-lg-8">

    <div class="card mb-3">
        <div class="card-header">
            <i data-lucide="user"></i>Employee Details
            <div class="ms-auto d-flex gap-2">
                <a href="{{ route('employees.edit', $employee) }}" class="btn btn-sm btn-primary"><i data-lucide="pencil"></i>Edit</a>
                <a href="{{ route('employees.index') }}" class="btn btn-sm btn-outline-secondary"><i data-lucide="arrow-left"></i>Back</a>
            </div>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-sm-6">
                    <div class="text-muted" style="font-size:.75rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;">Employee Code</div>
                    <div class="fw-semibold mt-1" style="font-family:monospace;">{{ $employee->employee_code }}</div>
                </div>
                <div class="col-sm-6">
                    <div class="text-muted" style="font-size:.75rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;">Status</div>
                    <div class="mt-1">
                        <span class="badge rounded-pill px-2 {{ $employee->status === 'active' ? 'badge-active' : 'badge-inactive' }}">{{ ucfirst($employee->status) }}</span>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="text-muted" style="font-size:.75rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;">Full Name</div>
                    <div class="fw-semibold mt-1">{{ $employee->employee_name }}</div>
                </div>
                <div class="col-sm-6">
                    <div class="text-muted" style="font-size:.75rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;">Email</div>
                    <div class="mt-1">{{ $employee->email }}</div>
                </div>
                <div class="col-sm-6">
                    <div class="text-muted" style="font-size:.75rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;">Department</div>
                    <div class="mt-1">{{ $employee->department ?? '—' }}</div>
                </div>
                <div class="col-sm-6">
                    <div class="text-muted" style="font-size:.75rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;">Designation</div>
                    <div class="mt-1">{{ $employee->designation ?? '—' }}</div>
                </div>
                <div class="col-sm-6">
                    <div class="text-muted" style="font-size:.75rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;">Date of Birth</div>
                    <div class="mt-1">{{ $employee->date_of_birth?->format('d M Y') ?? '—' }}</div>
                </div>
                <div class="col-sm-6">
                    <div class="text-muted" style="font-size:.75rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;">Date of Joining</div>
                    <div class="mt-1">{{ $employee->date_of_joining?->format('d M Y') ?? '—' }}</div>
                </div>
                @if($employee->manager_name)
                <div class="col-sm-6">
                    <div class="text-muted" style="font-size:.75rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;">Manager</div>
                    <div class="mt-1">{{ $employee->manager_name }}</div>
                </div>
                @endif
                @if($employee->manager_email)
                <div class="col-sm-6">
                    <div class="text-muted" style="font-size:.75rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;">Manager Email</div>
                    <div class="mt-1">{{ $employee->manager_email }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>

    @if($employee->date_of_birth || $employee->date_of_joining)
    <div class="card mb-3">
        <div class="card-header"><i data-lucide="calendar-days"></i>Upcoming Celebrations</div>
        <div class="card-body">
            <div class="row g-3">
                @if($employee->date_of_birth)
                <div class="col-sm-6">
                    <div class="p-3 rounded-3" style="background:#fff0f5;border-left:3px solid #f43f5e;">
                        <div class="text-muted" style="font-size:.75rem;">🎂 Next Birthday</div>
                        <div class="fw-semibold mt-1">{{ $employee->next_birthday->format('d M Y') }}</div>
                        <div class="text-muted" style="font-size:.78rem;">In {{ now()->diffInDays($employee->next_birthday) }} days</div>
                    </div>
                </div>
                @endif
                @if($employee->date_of_joining)
                <div class="col-sm-6">
                    <div class="p-3 rounded-3" style="background:#f0f0ff;border-left:3px solid #4f46e5;">
                        <div class="text-muted" style="font-size:.75rem;">🌟 Next Work Anniversary</div>
                        <div class="fw-semibold mt-1">{{ $employee->next_anniversary->format('d M Y') }}</div>
                        <div class="text-muted" style="font-size:.78rem;">{{ $employee->completed_years }} {{ $employee->completed_years === 1 ? 'year' : 'years' }} completed</div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif

</div>
<div class="col-lg-4">
    <div class="card mb-3">
        <div class="card-header"><i data-lucide="mail"></i>Email Preview</div>
        <div class="card-body d-flex flex-column gap-2">
            @if($employee->date_of_birth)
            <a href="{{ route('email.preview.birthday', $employee) }}" class="btn btn-outline-danger">
                <i data-lucide="cake-slice"></i>Preview Birthday Email
            </a>
            @endif
            @if($employee->date_of_joining)
            <a href="{{ route('email.preview.anniversary', $employee) }}" class="btn btn-outline-primary">
                <i data-lucide="star"></i>Preview Anniversary Email
            </a>
            @endif
            @if(!$employee->date_of_birth && !$employee->date_of_joining)
            <p class="text-muted small mb-0">No dates set — add DOB or joining date to preview emails.</p>
            @endif
        </div>
    </div>
</div>
</div>
@endsection
