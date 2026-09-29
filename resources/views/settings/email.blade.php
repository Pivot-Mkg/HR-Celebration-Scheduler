@extends('layouts.app')
@section('title', 'Email Settings')
@section('breadcrumb', 'Administration')

@section('content')
<div class="row justify-content-center">
<div class="col-xl-8">
<div class="card">
    <div class="card-header"><i data-lucide="settings"></i>Email Settings</div>
    <div class="card-body">
        <div class="alert alert-info mb-4">
            <i data-lucide="info"></i>
            <strong>SMTP transport</strong> (host, port, username, password, encryption) is configured in
            <code>.env</code> on the server — not managed here. This page controls sender identity and recipient defaults only.
        </div>

        <form method="POST" action="{{ route('settings.email.update') }}">
            @csrf

            <div class="form-section-title">Sender Identity</div>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label">From Email <span class="text-danger">*</span></label>
                    <input type="email" name="mail_from_address"
                           class="form-control @error('mail_from_address') is-invalid @enderror"
                           value="{{ old('mail_from_address', $config['from_address']) }}"
                           placeholder="hr@pivotmkg.com" required>
                    @error('mail_from_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">From Name <span class="text-danger">*</span></label>
                    <input type="text" name="mail_from_name"
                           class="form-control @error('mail_from_name') is-invalid @enderror"
                           value="{{ old('mail_from_name', $config['from_name']) }}"
                           placeholder="HR Team" required>
                    @error('mail_from_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-section-title">Default Recipients</div>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label">Default CC</label>
                    <input type="text" name="mail_default_cc"
                           class="form-control @error('mail_default_cc') is-invalid @enderror"
                           value="{{ old('mail_default_cc', $config['cc']) }}"
                           placeholder="hr@pivotmkg.com, manager@pivotmkg.com">
                    @error('mail_default_cc')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="form-text">Comma-separated. Applied to all celebration emails unless overridden by a template.</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Default BCC</label>
                    <input type="text" name="mail_default_bcc"
                           class="form-control @error('mail_default_bcc') is-invalid @enderror"
                           value="{{ old('mail_default_bcc', $config['bcc']) }}"
                           placeholder="archive@pivotmkg.com">
                    @error('mail_default_bcc')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="form-text">Comma-separated.</div>
                </div>
            </div>

            <div class="form-section-title">Test Email</div>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label">Test Email Address</label>
                    <input type="email" name="mail_test_address"
                           class="form-control @error('mail_test_address') is-invalid @enderror"
                           value="{{ old('mail_test_address', $config['test_email']) }}"
                           placeholder="you@example.com">
                    @error('mail_test_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="form-text">Pre-fills the test email form. Test emails always go here — never to the employee.</div>
                </div>
            </div>

            <div class="pt-2 border-top mt-2">
                <button type="submit" class="btn btn-primary">
                    <i data-lucide="check"></i>Save Settings
                </button>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection
