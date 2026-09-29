@extends('layouts.app')
@section('title', $label)
@section('breadcrumb', '<a href="' . route('scheduler.index') . '">Scheduler</a> / Results')

@section('content')
<div class="card mb-3">
    <div class="card-header">
        <i data-lucide="clipboard-check"></i>{{ $label }}
        <a href="{{ route('scheduler.index') }}" class="btn btn-sm btn-outline-secondary ms-auto">
            <i data-lucide="arrow-left"></i>Back
        </a>
    </div>
    <div class="card-body">
        @if($results['dry_run'])
        <div class="alert alert-warning mb-4">
            <i data-lucide="eye"></i><strong>DRY RUN</strong> — No emails were sent. This is a preview only.
        </div>
        @endif

        <div class="row g-3 mb-4">
            <div class="col-6 col-sm-3">
                <div class="kpi-card kpi-blue">
                    <div class="kpi-icon"><i data-lucide="cake-slice"></i></div>
                    <div class="kpi-value">{{ $results['birthdays'] }}</div>
                    <div class="kpi-label">Birthdays</div>
                </div>
            </div>
            <div class="col-6 col-sm-3">
                <div class="kpi-card kpi-purple">
                    <div class="kpi-icon"><i data-lucide="star"></i></div>
                    <div class="kpi-value">{{ $results['anniversaries'] }}</div>
                    <div class="kpi-label">Anniversaries</div>
                </div>
            </div>
            @if(($results['founding_day'] ?? 0) > 0)
            <div class="col-6 col-sm-3">
                <div class="kpi-card kpi-indigo">
                    <div class="kpi-icon"><i data-lucide="building-2"></i></div>
                    <div class="kpi-value">{{ $results['founding_day'] }}</div>
                    <div class="kpi-label">Founding Day</div>
                </div>
            </div>
            @endif
            @if(!$results['dry_run'])
            <div class="col-6 col-sm-2">
                <div class="kpi-card kpi-green">
                    <div class="kpi-icon"><i data-lucide="mail-check"></i></div>
                    <div class="kpi-value">{{ $results['sent'] }}</div>
                    <div class="kpi-label">Sent</div>
                </div>
            </div>
            <div class="col-6 col-sm-2">
                <div class="kpi-card kpi-red">
                    <div class="kpi-icon"><i data-lucide="mail-x"></i></div>
                    <div class="kpi-value">{{ $results['failed'] }}</div>
                    <div class="kpi-label">Failed</div>
                </div>
            </div>
            <div class="col-6 col-sm-2">
                <div class="kpi-card kpi-amber">
                    <div class="kpi-icon"><i data-lucide="mail-minus"></i></div>
                    <div class="kpi-value">{{ $results['skipped'] }}</div>
                    <div class="kpi-label">Skipped</div>
                </div>
            </div>
            @else
            <div class="col-6 col-sm-3">
                <div class="kpi-card kpi-blue">
                    <div class="kpi-icon"><i data-lucide="send"></i></div>
                    <div class="kpi-value">{{ count(array_filter($results['details'], fn($d) => $d['status'] === 'would_send')) }}</div>
                    <div class="kpi-label">Would Send</div>
                </div>
            </div>
            @endif
        </div>

        @if(!empty($results['details']))
        <div class="table-responsive">
            <table class="table table-sm">
                <thead>
                    <tr><th>Employee</th><th>Event</th><th>Status</th><th>Details</th></tr>
                </thead>
                <tbody>
                    @foreach($results['details'] as $detail)
                    <tr>
                        <td class="fw-semibold">{{ $detail['employee'] }}</td>
                        <td>{{ $detail['event'] === 'birthday' ? '🎂' : '🌟' }} {{ ucfirst($detail['event']) }}</td>
                        <td>
                            <span class="badge rounded-pill px-2 status-{{ $detail['status'] === 'would_send' ? 'pending' : $detail['status'] }}" style="font-size:.73rem;">
                                {{ strtoupper($detail['status']) }}
                            </span>
                        </td>
                        <td style="font-size:.83rem;color:#6b7280;">
                            @if(isset($detail['to'])) To: {{ $detail['to'] }} @endif
                            @if(!empty($detail['cc'])) · CC: {{ $detail['cc'] }} @endif
                            @if(isset($detail['template'])) · {{ $detail['template'] }} @endif
                            @if(isset($detail['reason'])) {{ $detail['reason'] }} @endif
                            @if(isset($detail['error'])) <span class="text-danger">{{ $detail['error'] }}</span> @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center text-muted py-5">
            <i data-lucide="calendar-x" class="icon-display"></i>
            <span style="font-size:.87rem;">No celebrations today.</span>
        </div>
        @endif
    </div>
    <div class="card-footer">
        <a href="{{ route('logs.index') }}" class="btn btn-sm btn-outline-secondary">
            <i data-lucide="book-open"></i>View Email Logs
        </a>
    </div>
</div>
@endsection
