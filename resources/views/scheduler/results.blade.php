@extends('layouts.app')
@section('title', $label)

@section('content')
<div class="card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-clipboard-check me-2"></i>{{ $label }}</span>
        <a href="{{ route('scheduler.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Back</a>
    </div>
    <div class="card-body">
        @if($results['dry_run'])
            <div class="alert alert-warning"><i class="bi bi-eye me-2"></i><strong>DRY RUN</strong> — No emails were sent.</div>
        @endif

        <div class="row g-3 mb-4">
            <div class="col-sm-3"><div class="text-center p-3 rounded" style="background:#f8f9fa;"><div class="fs-3 fw-bold text-primary">{{ $results['birthdays'] }}</div><div class="small text-muted">Birthdays</div></div></div>
            <div class="col-sm-3"><div class="text-center p-3 rounded" style="background:#f8f9fa;"><div class="fs-3 fw-bold text-info">{{ $results['anniversaries'] }}</div><div class="small text-muted">Anniversaries</div></div></div>
            @if(!$results['dry_run'])
            <div class="col-sm-2"><div class="text-center p-3 rounded status-sent"><div class="fs-3 fw-bold">{{ $results['sent'] }}</div><div class="small">Sent</div></div></div>
            <div class="col-sm-2"><div class="text-center p-3 rounded status-failed"><div class="fs-3 fw-bold">{{ $results['failed'] }}</div><div class="small">Failed</div></div></div>
            <div class="col-sm-2"><div class="text-center p-3 rounded status-skipped"><div class="fs-3 fw-bold">{{ $results['skipped'] }}</div><div class="small">Skipped</div></div></div>
            @else
            <div class="col-sm-3"><div class="text-center p-3 rounded" style="background:#e3f2fd;color:#1565c0;"><div class="fs-3 fw-bold">{{ count(array_filter($results['details'], fn($d) => $d['status'] === 'would_send')) }}</div><div class="small">Would Send</div></div></div>
            @endif
        </div>

        @if(!empty($results['details']))
        <table class="table table-sm">
            <thead class="table-light">
                <tr><th>Employee</th><th>Event</th><th>Status</th><th>Details</th></tr>
            </thead>
            <tbody>
                @foreach($results['details'] as $detail)
                <tr>
                    <td>{{ $detail['employee'] }}</td>
                    <td>{{ $detail['event'] === 'birthday' ? '🎂' : '🌟' }} {{ ucfirst($detail['event']) }}</td>
                    <td>
                        <span class="badge rounded-pill px-2 status-{{ $detail['status'] === 'would_send' ? 'pending' : $detail['status'] }}">
                            {{ strtoupper($detail['status']) }}
                        </span>
                    </td>
                    <td class="text-muted small">
                        @if(isset($detail['to'])) To: {{ $detail['to'] }} @endif
                        @if(!empty($detail['cc'])) · CC: {{ $detail['cc'] }} @endif
                        @if(isset($detail['template'])) · Template: {{ $detail['template'] }} @endif
                        @if(isset($detail['reason'])) {{ $detail['reason'] }} @endif
                        @if(isset($detail['error'])) <span class="text-danger">{{ $detail['error'] }}</span> @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="text-center text-muted py-4"><i class="bi bi-calendar-x fs-2 d-block mb-2"></i>No celebrations today.</div>
        @endif
    </div>
    <div class="card-footer">
        <a href="{{ route('logs.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-journal-text me-1"></i>View Email Logs
        </a>
    </div>
</div>
@endsection
