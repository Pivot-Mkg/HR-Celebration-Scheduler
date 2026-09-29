@extends('layouts.app')
@section('title', 'Employees')
@section('breadcrumb', 'People')

@section('content')
<div class="card">
    <div class="card-header">
        <i data-lucide="users"></i>Employees
        <div class="ms-auto">
            <a href="{{ route('employees.create') }}" class="btn btn-primary btn-sm">
                <i data-lucide="user-plus"></i>Add Employee
            </a>
        </div>
    </div>
    <div class="card-body border-bottom" style="padding:14px 18px;">
        <form method="GET" action="{{ route('employees.index') }}" class="row g-2 align-items-end">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search name, code, email, department…" value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Status</option>
                    <option value="active"   {{ request('status') === 'active'   ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="department" class="form-select form-select-sm">
                    <option value="">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept }}" {{ request('department') === $dept ? 'selected' : '' }}>{{ $dept }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary btn-sm">Clear</a>
            </div>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Code</th><th>Name</th><th>Email</th><th>Department</th>
                        <th>Designation</th><th>DOB</th><th>Joined</th><th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $employee)
                    <tr>
                        <td><span style="font-family:monospace;font-size:.82rem;color:#6b7280;">{{ $employee->employee_code }}</span></td>
                        <td class="fw-semibold">{{ $employee->employee_name }}</td>
                        <td style="font-size:.83rem;color:#6b7280;">{{ $employee->email }}</td>
                        <td style="font-size:.85rem;">{{ $employee->department ?? '—' }}</td>
                        <td style="font-size:.85rem;">{{ $employee->designation ?? '—' }}</td>
                        <td style="font-size:.83rem;color:#6b7280;">{{ $employee->date_of_birth?->format('d M Y') ?? '—' }}</td>
                        <td style="font-size:.83rem;color:#6b7280;">{{ $employee->date_of_joining?->format('d M Y') ?? '—' }}</td>
                        <td>
                            <span class="badge rounded-pill px-2 {{ $employee->status === 'active' ? 'badge-active' : 'badge-inactive' }}">
                                {{ ucfirst($employee->status) }}
                            </span>
                        </td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('employees.show', $employee) }}" class="btn btn-outline-secondary" title="View"><i data-lucide="eye"></i></a>
                                <a href="{{ route('employees.edit', $employee) }}" class="btn btn-outline-primary" title="Edit"><i data-lucide="pencil"></i></a>
                                @if($employee->status === 'active')
                                <form method="POST" action="{{ route('employees.destroy', $employee) }}" onsubmit="return confirm('Deactivate this employee?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-outline-warning" title="Deactivate"><i data-lucide="user-minus"></i></button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-5">
                            <i data-lucide="users" class="icon-display"></i>
                            No employees found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($employees->hasPages())
    <div class="card-footer d-flex justify-content-between align-items-center">
        <div class="text-muted" style="font-size:.8rem;">Showing {{ $employees->firstItem() }}–{{ $employees->lastItem() }} of {{ $employees->total() }}</div>
        {{ $employees->links() }}
    </div>
    @endif
</div>
@endsection
