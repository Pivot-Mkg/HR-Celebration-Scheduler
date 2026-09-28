@extends('layouts.app')
@section('title', 'System Health')

@section('content')
<div class="row justify-content-center">
<div class="col-xl-7">
<div class="card">
    <div class="card-header"><i class="bi bi-heart-pulse me-2"></i>System Health</div>
    <div class="card-body p-0">
        @foreach($checks as $name => $check)
        <div class="d-flex align-items-center px-4 py-3 border-bottom">
            <div class="me-3">
                @if($check['status'] === 'ok')
                    <i class="bi bi-check-circle-fill text-success fs-5"></i>
                @elseif($check['status'] === 'error')
                    <i class="bi bi-x-circle-fill text-danger fs-5"></i>
                @elseif($check['status'] === 'warning')
                    <i class="bi bi-exclamation-triangle-fill text-warning fs-5"></i>
                @else
                    <i class="bi bi-info-circle-fill text-info fs-5"></i>
                @endif
            </div>
            <div>
                <div class="fw-semibold">{{ ucfirst($name) }}</div>
                <div class="text-muted small">{{ $check['message'] }}</div>
            </div>
        </div>
        @endforeach
    </div>
</div>
</div>
</div>
@endsection
