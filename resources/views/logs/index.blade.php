@extends('layouts.app')
@section('title', 'Email Logs')
@section('breadcrumb', 'Communication')

@section('content')
<div class="card">
    <div class="card-header"><i data-lucide="book-open"></i>Email History</div>
    <div class="card-body border-bottom" style="padding:14px 18px;">
        <form method="GET" action="{{ route('logs.index') }}" class="row g-2 align-items-end">
            <div class="col-md-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Status</option>
                    @foreach(['sent','failed','skipped','pending'] as $s)
                        <option value="{{ $s }}" {{ ($filters['status'] ?? '') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="event_type" class="form-select form-select-sm">
                    <option value="">All Events</option>
                    <option value="birthday"    {{ ($filters['event_type'] ?? '') === 'birthday'    ? 'selected' : '' }}>Birthday</option>
                    <option value="anniversary" {{ ($filters['event_type'] ?? '') === 'anniversary' ? 'selected' : '' }}>Anniversary</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="employee_id" class="form-select form-select-sm">
                    <option value="">All Employees</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}" {{ ($filters['employee_id'] ?? '') == $emp->id ? 'selected' : '' }}>{{ $emp->employee_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" name="date_from" class="form-control form-control-sm" value="{{ $filters['date_from'] ?? '' }}">
            </div>
            <div class="col-md-2">
                <input type="date" name="date_to" class="form-control form-control-sm" value="{{ $filters['date_to'] ?? '' }}">
            </div>
            <div class="col-md-1 d-flex gap-1">
                <button class="btn btn-primary btn-sm">Go</button>
                <a href="{{ route('logs.index') }}" class="btn btn-outline-secondary btn-sm">✕</a>
            </div>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Date</th><th>Employee</th><th>Event</th><th>Template</th>
                        <th>To</th><th>Subject</th><th>Status</th><th>Sent At</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td style="font-size:.83rem;color:#6b7280;">{{ $log->event_date->format('d M Y') }}</td>
                        <td style="font-size:.87rem;">{{ $log->employee?->employee_name ?? '—' }}</td>
                        <td style="font-size:.85rem;">{{ $log->event_type === 'birthday' ? '🎂' : '🌟' }} {{ ucfirst($log->event_type) }}</td>
                        <td style="font-size:.82rem;color:#6b7280;">{{ $log->template?->name ?? '—' }}</td>
                        <td style="font-size:.82rem;color:#6b7280;">{{ $log->to_email }}</td>
                        <td style="font-size:.82rem;color:#6b7280;">{{ Str::limit($log->subject, 40) }}</td>
                        <td>
                            <span class="badge rounded-pill px-2 status-{{ $log->status }}" style="font-size:.73rem;">{{ ucfirst($log->status) }}</span>
                        </td>
                        <td style="font-size:.82rem;color:#6b7280;">{{ $log->sent_at?->format('d M H:i') ?? '—' }}</td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('logs.show', $log) }}" class="btn btn-outline-secondary"><i data-lucide="eye"></i></a>
                                @if(in_array($log->status, ['failed','skipped']))
                                <form method="POST" action="{{ route('logs.retry', $log) }}" class="d-inline" onsubmit="return confirm('Retry this email?')">@csrf
                                    <button class="btn btn-outline-warning" title="Retry"><i data-lucide="refresh-cw"></i></button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-5">
                            <i data-lucide="book-x" class="icon-display"></i>
                            No email logs found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($logs->hasPages())
    <div class="card-footer d-flex justify-content-between align-items-center">
        <div class="text-muted" style="font-size:.8rem;">Showing {{ $logs->firstItem() }}–{{ $logs->lastItem() }} of {{ $logs->total() }}</div>
        {{ $logs->links() }}
    </div>
    @endif
</div>
@endsection
