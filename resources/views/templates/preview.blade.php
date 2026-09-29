@extends('layouts.app')
@section('title', 'Template Preview')
@section('breadcrumb', '<a href="' . route('templates.index') . '">Templates</a> / <a href="' . route('templates.show', $template) . '">' . e($template->name) . '</a> / Preview')

@section('content')
<div class="card mb-3">
    <div class="card-header">
        <i data-lucide="eye"></i>Preview — {{ $template->name }}
        <a href="{{ route('templates.show', $template) }}" class="btn btn-sm btn-outline-secondary ms-auto">
            <i data-lucide="arrow-left"></i>Back
        </a>
    </div>
    <div class="card-body">
        <div class="row g-2 mb-4 align-items-end">
            <form method="GET" action="{{ route('templates.preview', $template) }}" class="d-flex gap-2 align-items-end">
                <div>
                    <label class="form-label mb-1">Preview for Employee</label>
                    <select name="employee_id" class="form-select form-select-sm" style="min-width:240px;">
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ $employee->id === $emp->id ? 'selected' : '' }}>
                                {{ $emp->employee_name }} ({{ $emp->employee_code }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <button class="btn btn-primary btn-sm">Update Preview</button>
            </form>
        </div>

        <div class="alert alert-info mb-3 small">
            <i data-lucide="info"></i>Preview only — no email has been sent.
        </div>

        <div style="max-width:640px;">
            <table class="table table-sm table-borderless mb-3" style="font-size:.87rem;">
                <tr><td class="text-muted fw-semibold" style="width:70px;">From:</td><td>{{ $fromName }} &lt;{{ $from }}&gt;</td></tr>
                <tr><td class="text-muted fw-semibold">To:</td><td>{{ $employee->email }}</td></tr>
                @if($cc)<tr><td class="text-muted fw-semibold">CC:</td><td>{{ $cc }}</td></tr>@endif
                @if($bcc)<tr><td class="text-muted fw-semibold">BCC:</td><td>{{ $bcc }}</td></tr>@endif
                <tr><td class="text-muted fw-semibold">Subject:</td><td><strong>{{ $rendered['subject'] }}</strong></td></tr>
            </table>
            <hr>
            <div style="border:1px solid #e5e7eb;border-radius:8px;overflow:hidden;margin-top:16px;">
                {!! $rendered['body_html'] !!}
            </div>
        </div>
    </div>
    <div class="card-footer">
        <a href="{{ route('email.test') }}?employee_id={{ $employee->id }}&type={{ $template->event_type }}" class="btn btn-sm btn-outline-primary">
            <i data-lucide="send"></i>Send Test Email
        </a>
    </div>
</div>
@endsection
