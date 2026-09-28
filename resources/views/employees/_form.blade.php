<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label fw-semibold">Employee Code <span class="text-danger">*</span></label>
        <input type="text" name="employee_code" class="form-control @error('employee_code') is-invalid @enderror"
               value="{{ old('employee_code', $employee->employee_code ?? '') }}" required>
        @error('employee_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">Employee Name <span class="text-danger">*</span></label>
        <input type="text" name="employee_name" class="form-control @error('employee_name') is-invalid @enderror"
               value="{{ old('employee_name', $employee->employee_name ?? '') }}" required>
        @error('employee_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
               value="{{ old('email', $employee->email ?? '') }}" required>
        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
            <option value="active" {{ old('status', $employee->status ?? 'active') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ old('status', $employee->status ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">Department</label>
        <input type="text" name="department" class="form-control @error('department') is-invalid @enderror"
               value="{{ old('department', $employee->department ?? '') }}">
        @error('department')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">Designation</label>
        <input type="text" name="designation" class="form-control @error('designation') is-invalid @enderror"
               value="{{ old('designation', $employee->designation ?? '') }}">
        @error('designation')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">Date of Birth</label>
        <input type="date" name="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror"
               value="{{ old('date_of_birth', isset($employee->date_of_birth) ? $employee->date_of_birth->format('Y-m-d') : '') }}">
        @error('date_of_birth')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">Date of Joining</label>
        <input type="date" name="date_of_joining" class="form-control @error('date_of_joining') is-invalid @enderror"
               value="{{ old('date_of_joining', isset($employee->date_of_joining) ? $employee->date_of_joining->format('Y-m-d') : '') }}">
        @error('date_of_joining')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">Manager Name</label>
        <input type="text" name="manager_name" class="form-control @error('manager_name') is-invalid @enderror"
               value="{{ old('manager_name', $employee->manager_name ?? '') }}">
        @error('manager_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">Manager Email</label>
        <input type="email" name="manager_email" class="form-control @error('manager_email') is-invalid @enderror"
               value="{{ old('manager_email', $employee->manager_email ?? '') }}">
        @error('manager_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>
