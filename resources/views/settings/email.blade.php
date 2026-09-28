@extends('layouts.app')
@section('title', 'Email Settings')

@section('content')
<div class="row justify-content-center">
<div class="col-xl-8">
<div class="card">
    <div class="card-header"><i class="bi bi-gear me-2"></i>Email Settings</div>
    <div class="card-body">
        <div class="alert alert-info small mb-4">
            <i class="bi bi-info-circle me-2"></i>
            <strong>SMTP transport</strong> (host, port, username, password, encryption) is configured in <code>.env</code>
            on the server — not here. This page controls sender identity and recipient defaults only.
        </div>

        <form method="POST" action="{{ route('settings.email.update') }}">
            @csrf

            <h6 class="fw-bold text-muted mb-3">Sender Identity</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">From Email <span class="text-danger">*</span></label>
                    <input type="email" name="mail_from_address"
                           class="form-control @error('mail_from_address') is-invalid @enderror"
                           value="{{ old('mail_from_address', $config['from_address']) }}"
                           placeholder="hr@pivotmkg.com" required>
                    @error('mail_from_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">From Name <span class="text-danger">*</span></label>
                    <input type="text" name="mail_from_name"
                           class="form-control @error('mail_from_name') is-invalid @enderror"
                           value="{{ old('mail_from_name', $config['from_name']) }}"
                           placeholder="HR Team" required>
                    @error('mail_from_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <h6 class="fw-bold text-muted mb-3">Default Recipients</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Default CC</label>
                    <input type="text" name="mail_default_cc"
                           class="form-control @error('mail_default_cc') is-invalid @enderror"
                           value="{{ old('mail_default_cc', $config['cc']) }}"
                           placeholder="hr@pivotmkg.com, manager@pivotmkg.com">
                    @error('mail_default_cc')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="form-text">Comma-separated. Applied to all celebration emails unless overridden by a template.</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Default BCC</label>
                    <input type="text" name="mail_default_bcc"
                           class="form-control @error('mail_default_bcc') is-invalid @enderror"
                           value="{{ old('mail_default_bcc', $config['bcc']) }}"
                           placeholder="archive@pivotmkg.com">
                    @error('mail_default_bcc')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="form-text">Comma-separated.</div>
                </div>
            </div>

            <h6 class="fw-bold text-muted mb-3">Test Email</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Test Email Address</label>
                    <input type="email" name="mail_test_address"
                           class="form-control @error('mail_test_address') is-invalid @enderror"
                           value="{{ old('mail_test_address', $config['test_email']) }}"
                           placeholder="you@example.com">
                    @error('mail_test_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="form-text">Pre-fills the test email form. Test emails always go here, never to the employee.</div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-lg me-1"></i> Save Settings
            </button>
        </form>
    </div>
</div>
</div>
</div>
@endsection
