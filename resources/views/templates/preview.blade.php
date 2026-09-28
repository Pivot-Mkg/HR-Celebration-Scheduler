@extends('layouts.app')
@section('title', 'Template Preview')

@section('content')
<div class="card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-eye me-2"></i>Template Preview — {{ $template->name }}</span>
        <a href="{{ route('templates.show', $template) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Back</a>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('templates.preview', $template) }}" class="row g-2 mb-4">
            <div class="col-md-5">
                <label class="form-label fw-semibold">Preview for Employee</label>
                <select name="employee_id" class="form-select form-select-sm">
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}" {{ $employee->id === $emp->id ? 'selected' : '' }}>
                            {{ $emp->employee_name }} ({{ $emp->employee_code }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button class="btn btn-primary btn-sm w-100">Update Preview</button>
            </div>
        </form>

        <div class="alert alert-info small mb-3">
            <i class="bi bi-info-circle me-2"></i>Preview only — no email has been sent.
        </div>

        <table class="table table-sm table-borderless mb-3" style="max-width:620px;">
            <tr><td class="text-muted fw-semibold" style="width:75px;">From:</td><td>{{ $fromName }} &lt;{{ $from }}&gt;</td></tr>
            <tr><td class="text-muted fw-semibold">To:</td><td>{{ $employee->email }}</td></tr>
            @if($cc)<tr><td class="text-muted fw-semibold">CC:</td><td>{{ $cc }}</td></tr>@endif
            @if($bcc)<tr><td class="text-muted fw-semibold">BCC:</td><td>{{ $bcc }}</td></tr>@endif
            <tr><td class="text-muted fw-semibold">Subject:</td><td><strong>{{ $rendered['subject'] }}</strong></td></tr>
        </table>
        <hr>
        <div style="max-width:620px; border:1px solid #dee2e6; border-radius:8px; overflow:hidden;">
            {!! $rendered['body_html'] !!}
        </div>
    </div>
    <div class="card-footer">
        <a href="{{ route('email.test') }}?employee_id={{ $employee->id }}&type={{ $template->event_type }}" class="btn btn-sm btn-outline-primary">
            <i class="bi bi-send me-1"></i> Send Test Email
        </a>
    </div>
</div>
@endsection
