@extends('layouts.app')
@section('title', 'Email Log Detail')
@section('breadcrumb', '<a href="' . route('logs.index') . '">Email Logs</a> / Detail')

@section('content')
<div class="row justify-content-center">
<div class="col-xl-8">
<div class="card">
    <div class="card-header">
        <i data-lucide="book-open"></i>Email Log Detail
        <a href="{{ route('logs.index') }}" class="btn btn-sm btn-outline-secondary ms-auto">
            <i data-lucide="arrow-left"></i>Back
        </a>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-sm-6">
                <div class="text-muted" style="font-size:.75rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;">Employee</div>
                <div class="fw-semibold mt-1">{{ $log->employee?->employee_name ?? '—' }}</div>
            </div>
            <div class="col-sm-6">
                <div class="text-muted" style="font-size:.75rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;">Event</div>
                <div class="mt-1">{{ $log->event_type === 'birthday' ? '🎂' : '🌟' }} {{ ucfirst($log->event_type) }}</div>
            </div>
            <div class="col-sm-6">
                <div class="text-muted" style="font-size:.75rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;">Event Date</div>
                <div class="mt-1">{{ $log->event_date->format('d M Y') }}</div>
            </div>
            <div class="col-sm-6">
                <div class="text-muted" style="font-size:.75rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;">Status</div>
                <div class="mt-1">
                    <span class="badge rounded-pill px-2 status-{{ $log->status }}">{{ ucfirst($log->status) }}</span>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="text-muted" style="font-size:.75rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;">Template</div>
                <div class="mt-1">{{ $log->template?->name ?? '—' }}</div>
            </div>
            <div class="col-sm-6">
                <div class="text-muted" style="font-size:.75rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;">Sent At</div>
                <div class="mt-1">{{ $log->sent_at?->format('d M Y H:i:s') ?? '—' }}</div>
            </div>
            <div class="col-sm-6">
                <div class="text-muted" style="font-size:.75rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;">From</div>
                <div class="mt-1">{{ $log->from_name }} &lt;{{ $log->from_email }}&gt;</div>
            </div>
            <div class="col-sm-6">
                <div class="text-muted" style="font-size:.75rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;">To</div>
                <div class="mt-1">{{ $log->to_email }}</div>
            </div>
            @if($log->cc_email)
            <div class="col-sm-6">
                <div class="text-muted" style="font-size:.75rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;">CC</div>
                <div class="mt-1">{{ $log->cc_email }}</div>
            </div>
            @endif
            @if($log->bcc_email)
            <div class="col-sm-6">
                <div class="text-muted" style="font-size:.75rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;">BCC</div>
                <div class="mt-1">{{ $log->bcc_email }}</div>
            </div>
            @endif
            <div class="col-12">
                <div class="text-muted" style="font-size:.75rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;">Subject</div>
                <div class="fw-semibold mt-1">{{ $log->subject }}</div>
            </div>
            @if($log->error_message)
            <div class="col-12">
                <div class="text-muted" style="font-size:.75rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;">Error</div>
                <div class="alert alert-danger small mb-0 mt-1">{{ $log->error_message }}</div>
            </div>
            @endif
        </div>
    </div>
    @if(in_array($log->status, ['failed','skipped']))
    <div class="card-footer">
        <form method="POST" action="{{ route('logs.retry', $log) }}" onsubmit="return confirm('Retry sending this email?')">
            @csrf
            <button class="btn btn-warning"><i data-lucide="refresh-cw"></i>Retry Email</button>
        </form>
    </div>
    @endif
</div>
</div>
</div>
@endsection
