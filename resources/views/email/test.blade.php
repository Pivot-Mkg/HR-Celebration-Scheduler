@extends('layouts.app')
@section('title', 'Send Test Email')

@section('content')
<div class="row justify-content-center">
<div class="col-xl-6">
<div class="card">
    <div class="card-header"><i class="bi bi-envelope-check me-2"></i>Send Test Email</div>
    <div class="card-body">
        <div class="alert alert-info small">
            <i class="bi bi-info-circle me-2"></i>
            Test emails are sent to the specified test address only — <strong>never to the employee</strong>.
        </div>
        <form method="POST" action="{{ route('email.test.send') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">Employee</label>
                <select name="employee_id" class="form-select @error('employee_id') is-invalid @enderror" required>
                    <option value="">— Select Employee —</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>
                            {{ $emp->employee_name }} ({{ $emp->employee_code }})
                        </option>
                    @endforeach
                </select>
                @error('employee_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Email Type</label>
                <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                    <option value="birthday" {{ request('type') === 'birthday' ? 'selected' : '' }}>Birthday</option>
                    <option value="anniversary" {{ request('type') === 'anniversary' ? 'selected' : '' }}>Work Anniversary</option>
                </select>
                @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Send Test Email To</label>
                <input type="email" name="test_email" class="form-control @error('test_email') is-invalid @enderror"
                       value="{{ old('test_email', $testEmail) }}"
                       placeholder="your-test@example.com" required>
                @error('test_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <div class="form-text">This address receives the test — not the employee.</div>
            </div>
            <button type="submit" class="btn btn-primary"><i class="bi bi-send me-2"></i>Send Test Email</button>
        </form>
    </div>
</div>
</div>
</div>
@endsection
