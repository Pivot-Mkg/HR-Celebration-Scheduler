@extends('layouts.app')
@section('title', 'Email Log Detail')

@section('content')
<div class="row justify-content-center">
<div class="col-xl-8">
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-journal-text me-2"></i>Email Log Detail</span>
        <a href="{{ route('logs.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Back</a>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-sm-6"><div class="text-muted small">Employee</div><div class="fw-semibold">{{ $log->employee?->employee_name ?? '—' }}</div></div>
            <div class="col-sm-6"><div class="text-muted small">Event</div><div>{{ $log->event_type === 'birthday' ? '🎂' : '🌟' }} {{ ucfirst($log->event_type) }}</div></div>
            <div class="col-sm-6"><div class="text-muted small">Event Date</div><div>{{ $log->event_date->format('d M Y') }}</div></div>
            <div class="col-sm-6"><div class="text-muted small">Status</div>
                <span class="badge rounded-pill px-2 status-{{ $log->status }}">{{ ucfirst($log->status) }}</span>
            </div>
            <div class="col-sm-6"><div class="text-muted small">Template</div><div>{{ $log->template?->name ?? '—' }}</div></div>
            <div class="col-sm-6"><div class="text-muted small">Sent At</div><div>{{ $log->sent_at?->format('d M Y H:i:s') ?? '—' }}</div></div>
            <div class="col-sm-6"><div class="text-muted small">From</div><div>{{ $log->from_name }} &lt;{{ $log->from_email }}&gt;</div></div>
            <div class="col-sm-6"><div class="text-muted small">To</div><div>{{ $log->to_email }}</div></div>
            @if($log->cc_email)<div class="col-sm-6"><div class="text-muted small">CC</div><div>{{ $log->cc_email }}</div></div>@endif
            @if($log->bcc_email)<div class="col-sm-6"><div class="text-muted small">BCC</div><div>{{ $log->bcc_email }}</div></div>@endif
            <div class="col-12"><div class="text-muted small">Subject</div><div class="fw-semibold">{{ $log->subject }}</div></div>
            @if($log->error_message)
            <div class="col-12">
                <div class="text-muted small">Error</div>
                <div class="alert alert-danger small mb-0">{{ $log->error_message }}</div>
            </div>
            @endif
        </div>
    </div>
    @if(in_array($log->status, ['failed','skipped']))
    <div class="card-footer">
        <form method="POST" action="{{ route('logs.retry', $log) }}" onsubmit="return confirm('Retry sending this email?')">
            @csrf
            <button class="btn btn-warning"><i class="bi bi-arrow-repeat me-2"></i>Retry Email</button>
        </form>
    </div>
    @endif
</div>
</div>
</div>
@endsection
