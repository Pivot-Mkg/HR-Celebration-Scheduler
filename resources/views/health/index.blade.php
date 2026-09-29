@extends('layouts.app')
@section('title', 'System Health')
@section('breadcrumb', 'Administration')

@section('content')

{{-- Quick Actions --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <a href="{{ route('scheduler.index') }}" class="card text-decoration-none h-100" style="transition:.15s;border-color:#e5e7eb;">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <div style="width:42px;height:42px;background:#dbeafe;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i data-lucide="circle-play" style="color:#2563eb;width:20px;height:20px;"></i>
                </div>
                <div>
                    <div class="fw-semibold" style="font-size:.88rem;color:#111;">Run Scheduler</div>
                    <div class="text-muted" style="font-size:.78rem;">Trigger celebration emails</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-sm-6 col-xl-3">
        <a href="{{ route('logs.index') }}" class="card text-decoration-none h-100" style="transition:.15s;border-color:#e5e7eb;">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <div style="width:42px;height:42px;background:#d1fae5;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i data-lucide="book-open" style="color:#059669;width:20px;height:20px;"></i>
                </div>
                <div>
                    <div class="fw-semibold" style="font-size:.88rem;color:#111;">Email Logs</div>
                    <div class="text-muted" style="font-size:.78rem;">View all sent emails</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-sm-6 col-xl-3">
        <a href="{{ route('logs.index') }}?status=failed" class="card text-decoration-none h-100" style="transition:.15s;border-color:#e5e7eb;">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <div style="width:42px;height:42px;background:#fee2e2;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i data-lucide="circle-alert" style="color:#dc2626;width:20px;height:20px;"></i>
                </div>
                <div>
                    <div class="fw-semibold" style="font-size:.88rem;color:#111;">Failed Emails</div>
                    <div class="text-muted" style="font-size:.78rem;">Review delivery failures</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-sm-6 col-xl-3">
        <a href="{{ route('email.test') }}" class="card text-decoration-none h-100" style="transition:.15s;border-color:#e5e7eb;">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <div style="width:42px;height:42px;background:#f5f3ff;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i data-lucide="mail-check" style="color:#7c3aed;width:20px;height:20px;"></i>
                </div>
                <div>
                    <div class="fw-semibold" style="font-size:.88rem;color:#111;">Send Test Email</div>
                    <div class="text-muted" style="font-size:.78rem;">Verify email delivery</div>
                </div>
            </div>
        </a>
    </div>
</div>

<div class="row justify-content-center">
<div class="col-xl-7">
<div class="card">
    <div class="card-header"><i data-lucide="activity"></i>System Health</div>
    <div class="card-body p-0">
        @foreach($checks as $name => $check)
        <div class="d-flex align-items-center gap-3 px-4 py-3 border-bottom">
            <div style="flex-shrink:0;">
                @if($check['status'] === 'ok')
                    <div style="width:36px;height:36px;background:#d1fae5;border-radius:9px;display:flex;align-items:center;justify-content:center;">
                        <i data-lucide="check-circle" style="color:#059669;width:18px;height:18px;"></i>
                    </div>
                @elseif($check['status'] === 'error')
                    <div style="width:36px;height:36px;background:#fee2e2;border-radius:9px;display:flex;align-items:center;justify-content:center;">
                        <i data-lucide="x-circle" style="color:#dc2626;width:18px;height:18px;"></i>
                    </div>
                @elseif($check['status'] === 'warning')
                    <div style="width:36px;height:36px;background:#fef3c7;border-radius:9px;display:flex;align-items:center;justify-content:center;">
                        <i data-lucide="triangle-alert" style="color:#d97706;width:18px;height:18px;"></i>
                    </div>
                @else
                    <div style="width:36px;height:36px;background:#dbeafe;border-radius:9px;display:flex;align-items:center;justify-content:center;">
                        <i data-lucide="info" style="color:#2563eb;width:18px;height:18px;"></i>
                    </div>
                @endif
            </div>
            <div>
                <div class="fw-semibold" style="font-size:.9rem;">{{ ucfirst($name) }}</div>
                <div class="text-muted" style="font-size:.82rem;">{{ $check['message'] }}</div>
            </div>
            <div class="ms-auto">
                @if($check['status'] === 'ok')
                    <span class="badge rounded-pill badge-active px-2" style="font-size:.72rem;">OK</span>
                @elseif($check['status'] === 'error')
                    <span class="badge rounded-pill px-2" style="background:#fee2e2;color:#991b1b;font-size:.72rem;">Error</span>
                @elseif($check['status'] === 'warning')
                    <span class="badge rounded-pill px-2 status-skipped" style="font-size:.72rem;">Warning</span>
                @else
                    <span class="badge rounded-pill px-2 status-pending" style="font-size:.72rem;">Info</span>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>
</div>
</div>
@endsection
