<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'HR Celebration Scheduler') - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { background-color: #f0f2f5; font-size: 0.925rem; }
        .sidebar { width: 240px; min-height: 100vh; background: linear-gradient(180deg, #1a237e 0%, #283593 100%); position: fixed; top: 0; left: 0; z-index: 100; }
        .sidebar-brand { padding: 18px 18px 14px; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .sidebar-brand h5 { color: #fff; font-weight: 700; margin: 0; font-size: .95rem; }
        .sidebar-brand small { color: rgba(255,255,255,0.6); font-size: 0.72rem; }
        .sidebar .nav-link { color: rgba(255,255,255,0.75); padding: 8px 18px; display: flex; align-items: center; gap: 9px; transition: all 0.18s; font-size: 0.88rem; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background: rgba(255,255,255,0.12); }
        .sidebar .nav-section { color: rgba(255,255,255,0.4); font-size: 0.68rem; text-transform: uppercase; letter-spacing: 1px; padding: 12px 18px 4px; }
        .main-content { margin-left: 240px; min-height: 100vh; }
        .topbar { background: #fff; border-bottom: 1px solid #e0e0e0; padding: 10px 22px; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 99; }
        .page-content { padding: 22px; }
        .card { border: none; box-shadow: 0 1px 4px rgba(0,0,0,0.08); border-radius: 10px; }
        .card-header { background: #fff; border-bottom: 1px solid #f0f0f0; font-weight: 600; padding: 13px 18px; border-radius: 10px 10px 0 0 !important; }
        .badge-active { background-color: #e8f5e9; color: #2e7d32; }
        .badge-inactive { background-color: #fafafa; color: #757575; }
        .table > thead > tr > th { font-weight: 600; font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.5px; color: #666; border-bottom: 2px solid #f0f0f0; }
        .stat-card { border-radius: 12px; padding: 18px; color: white; }
        .status-sent    { color: #2e7d32; background: #e8f5e9; }
        .status-failed  { color: #c62828; background: #ffebee; }
        .status-skipped { color: #f57f17; background: #fff8e1; }
        .status-pending { color: #1565c0; background: #e3f2fd; }
        @media (max-width: 768px) { .sidebar { width: 100%; min-height: auto; position: relative; } .main-content { margin-left: 0; } }
    </style>
    @stack('styles')
</head>
<body>
<div class="sidebar">
    <div class="sidebar-brand">
        <h5><i class="bi bi-calendar-heart me-2"></i>HR Scheduler</h5>
        <small>Celebration Scheduler</small>
    </div>
    <nav class="pt-1">
        <div class="nav-section">Main</div>
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>

        <div class="nav-section">Employees</div>
        <a href="{{ route('employees.index') }}" class="nav-link {{ request()->routeIs('employees.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i> All Employees
        </a>
        <a href="{{ route('employees.create') }}" class="nav-link">
            <i class="bi bi-person-plus"></i> Add Employee
        </a>

        <div class="nav-section">Email Templates</div>
        <a href="{{ route('templates.index') }}" class="nav-link {{ request()->routeIs('templates.*') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-text"></i> All Templates
        </a>
        <a href="{{ route('templates.create') }}" class="nav-link">
            <i class="bi bi-plus-circle"></i> Create Template
        </a>

        <div class="nav-section">Scheduler</div>
        <a href="{{ route('scheduler.index') }}" class="nav-link {{ request()->routeIs('scheduler.*') ? 'active' : '' }}">
            <i class="bi bi-play-circle"></i> Run Scheduler
        </a>

        <div class="nav-section">Email History</div>
        <a href="{{ route('logs.index') }}" class="nav-link {{ request()->routeIs('logs.*') ? 'active' : '' }}">
            <i class="bi bi-journal-text"></i> Email Logs
        </a>
        <a href="{{ route('logs.index') }}?status=failed" class="nav-link">
            <i class="bi bi-exclamation-circle"></i> Failed Emails
        </a>

        <div class="nav-section">Settings</div>
        <a href="{{ route('email.test') }}" class="nav-link {{ request()->routeIs('email.*') ? 'active' : '' }}">
            <i class="bi bi-envelope-check"></i> Test Email
        </a>
        <a href="{{ route('settings.email') }}" class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
            <i class="bi bi-gear"></i> Email Settings
        </a>
        <a href="{{ route('health') }}" class="nav-link {{ request()->routeIs('health') ? 'active' : '' }}">
            <i class="bi bi-heart-pulse"></i> System Health
        </a>
    </nav>
</div>

<div class="main-content">
    <div class="topbar">
        <div><span class="text-muted fw-semibold">@yield('title', 'Dashboard')</span></div>
        <div class="d-flex align-items-center gap-3">
            <span class="text-muted small"><i class="bi bi-person-circle me-1"></i>{{ Auth::user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </button>
            </form>
        </div>
    </div>

    <div class="page-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
