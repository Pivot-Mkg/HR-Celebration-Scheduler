@extends('layouts.app')
@section('title', 'System Health')
@section('breadcrumb', 'Administration')

@section('content')
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
