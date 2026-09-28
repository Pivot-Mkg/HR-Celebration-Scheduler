@extends('layouts.app')
@section('title', ucfirst($type) . ' Email Preview')

@section('content')
<div class="card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-eye me-2"></i>{{ ucfirst($type) }} Email Preview — {{ $employee->employee_name }}</span>
        <a href="{{ route('employees.show', $employee) }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>
    <div class="card-body">
        <table class="table table-sm table-borderless mb-3" style="max-width:600px;">
            <tr><td class="text-muted fw-semibold" style="width:80px;">From:</td><td>{{ $fromName }} &lt;{{ $from }}&gt;</td></tr>
            <tr><td class="text-muted fw-semibold">To:</td><td>{{ $to }}</td></tr>
            @if($cc)<tr><td class="text-muted fw-semibold">CC:</td><td>{{ $cc }}</td></tr>@endif
            @if($bcc)<tr><td class="text-muted fw-semibold">BCC:</td><td>{{ $bcc }}</td></tr>@endif
            <tr><td class="text-muted fw-semibold">Subject:</td><td><strong>{{ $subject }}</strong></td></tr>
        </table>
        <hr>
        <div style="max-width:620px; border:1px solid #ddd; border-radius:8px; overflow:hidden;">
            {!! $html !!}
        </div>
    </div>
    <div class="card-footer">
        <p class="text-muted small mb-2"><i class="bi bi-info-circle me-1"></i>This is a preview only. No email has been sent.</p>
        <a href="{{ route('email.test') }}?employee_id={{ $employee->id }}&type={{ $type }}" class="btn btn-sm btn-outline-primary">
            <i class="bi bi-send me-1"></i> Send Test Email
        </a>
    </div>
</div>
@endsection
