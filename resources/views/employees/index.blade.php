@extends('layouts.app')
@section('title', 'Employees')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-people me-2"></i>Employees</span>
        <a href="{{ route('employees.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-person-plus me-1"></i> Add Employee
        </a>
    </div>
    <div class="card-body border-bottom">
        <form method="GET" action="{{ route('employees.index') }}" class="row g-2">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search name, code, email, department..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
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
                <thead class="table-light">
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Department</th>
                        <th>Designation</th>
                        <th>DOB</th>
                        <th>Joining Date</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $employee)
                    <tr>
                        <td class="fw-semibold text-monospace">{{ $employee->employee_code }}</td>
                        <td>{{ $employee->employee_name }}</td>
                        <td>{{ $employee->email }}</td>
                        <td>{{ $employee->department ?? '—' }}</td>
                        <td>{{ $employee->designation ?? '—' }}</td>
                        <td>{{ $employee->date_of_birth?->format('d M Y') ?? '—' }}</td>
                        <td>{{ $employee->date_of_joining?->format('d M Y') ?? '—' }}</td>
                        <td>
                            <span class="badge {{ $employee->status === 'active' ? 'badge-active' : 'badge-inactive' }} rounded-pill px-3">
                                {{ ucfirst($employee->status) }}
                            </span>
                        </td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('employees.show', $employee) }}" class="btn btn-outline-secondary" title="View"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('employees.edit', $employee) }}" class="btn btn-outline-primary" title="Edit"><i class="bi bi-pencil"></i></a>
                                @if($employee->status === 'active')
                                <form method="POST" action="{{ route('employees.destroy', $employee) }}" onsubmit="return confirm('Deactivate this employee?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-outline-warning" title="Deactivate"><i class="bi bi-person-dash"></i></button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-5">
                            <i class="bi bi-people fs-2 d-block mb-2"></i>
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
        <div class="text-muted small">Showing {{ $employees->firstItem() }}–{{ $employees->lastItem() }} of {{ $employees->total() }}</div>
        {{ $employees->links() }}
    </div>
    @endif
</div>
@endsection
