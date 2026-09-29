<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #4f46e5; --primary-dark: #3730a3; --primary-light: #eef2ff;
            --sidebar-bg: #1e1b4b;
            --sidebar-hover: rgba(255,255,255,0.08); --sidebar-act: rgba(255,255,255,0.15);
            --sidebar-text: rgba(255,255,255,0.72); --sidebar-lbl: rgba(255,255,255,0.38);
            --page-bg: #f3f4f6; --card-bg: #fff; --card-border: #e5e7eb;
            --card-shadow: 0 1px 3px rgba(0,0,0,0.07), 0 1px 2px rgba(0,0,0,0.04);
            --txt: #111827; --txt2: #6b7280; --r: 10px; --sw: 256px; --th: 60px;
        }
        *, *::before, *::after { box-sizing: border-box; }
        body { font-family: 'Inter', system-ui, sans-serif; background: var(--page-bg); color: var(--txt); font-size: 0.9rem; line-height: 1.6; }

        /* Lucide icon base */
        [data-lucide] { width: 1em; height: 1em; vertical-align: -0.125em; stroke-width: 1.75; display: inline-block; flex-shrink: 0; }

        /* ── Sidebar ─────────────────────────────── */
        .sidebar { width: var(--sw); min-height: 100vh; background: var(--sidebar-bg); position: fixed; top: 0; left: 0; z-index: 200; display: flex; flex-direction: column; overflow-y: auto; }
        .sidebar-brand { padding: 20px 20px 16px; border-bottom: 1px solid rgba(255,255,255,0.08); }
        .brand-icon { width: 36px; height: 36px; background: var(--primary); border-radius: 9px; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 8px; }
        .brand-icon [data-lucide] { width: 18px; height: 18px; color: #fff; stroke-width: 2; }
        .sidebar-brand h5 { color: #fff; font-weight: 700; font-size: .88rem; margin: 0 0 1px; letter-spacing: -.01em; }
        .sidebar-brand small { color: var(--sidebar-lbl); font-size: 0.72rem; }
        .sidebar-nav { padding: 8px 0 20px; flex: 1; }
        .nav-section { color: var(--sidebar-lbl); font-size: .63rem; text-transform: uppercase; letter-spacing: .1em; font-weight: 600; padding: 16px 20px 4px; }
        .nav-section:first-child { padding-top: 12px; }
        .sidebar .nav-link { color: var(--sidebar-text); padding: 7px 12px; display: flex; align-items: center; gap: 10px; transition: background .15s, color .15s; font-size: .845rem; font-weight: 500; text-decoration: none; margin: 1px 8px; border-radius: 7px; }
        .sidebar .nav-link [data-lucide] { width: 16px; height: 16px; opacity: .85; flex-shrink: 0; stroke-width: 2; }
        .sidebar .nav-link:hover { color: #fff; background: var(--sidebar-hover); }
        .sidebar .nav-link.active { color: #fff; background: var(--sidebar-act); }
        .sidebar .nav-link.active [data-lucide] { opacity: 1; }

        /* ── Main ────────────────────────────────── */
        .main-content { margin-left: var(--sw); min-height: 100vh; display: flex; flex-direction: column; }

        /* ── Topbar ──────────────────────────────── */
        .topbar { background: var(--card-bg); border-bottom: 1px solid var(--card-border); height: var(--th); padding: 0 24px; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 100; }
        .topbar-title { font-weight: 600; font-size: .95rem; color: var(--txt); letter-spacing: -.01em; }
        .topbar-breadcrumb { font-size: .75rem; color: var(--txt2); margin-top: 1px; }
        .topbar-breadcrumb a { color: var(--txt2); text-decoration: none; }
        .topbar-breadcrumb a:hover { color: var(--primary); }
        .topbar-user { display: flex; align-items: center; gap: 10px; }
        .user-avatar { width: 32px; height: 32px; background: var(--primary); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-size: .8rem; flex-shrink: 0; }
        .user-name { font-size: .84rem; font-weight: 500; color: var(--txt); }
        .btn-logout { background: none; border: 1px solid var(--card-border); border-radius: 7px; padding: 5px 11px; font-size: .8rem; font-weight: 500; color: var(--txt2); cursor: pointer; transition: border-color .15s, color .15s; display: inline-flex; align-items: center; gap: 6px; }
        .btn-logout:hover { border-color: var(--primary); color: var(--primary); }
        .btn-logout [data-lucide] { width: 14px; height: 14px; }
        .sidebar-toggle { display: none; background: none; border: 1px solid var(--card-border); border-radius: 8px; padding: 6px 10px; cursor: pointer; color: var(--txt2); line-height: 1; }
        .sidebar-toggle [data-lucide] { width: 18px; height: 18px; }

        /* ── Page ────────────────────────────────── */
        .page-content { padding: 24px; flex: 1; }

        /* ── Cards ───────────────────────────────── */
        .card { background: var(--card-bg); border: 1px solid var(--card-border); box-shadow: var(--card-shadow); border-radius: var(--r); }
        .card-header { background: var(--card-bg); border-bottom: 1px solid var(--card-border); font-weight: 600; font-size: .88rem; padding: 13px 18px; border-radius: var(--r) var(--r) 0 0 !important; display: flex; align-items: center; gap: 6px; }
        .card-header [data-lucide] { width: 16px; height: 16px; stroke-width: 2; flex-shrink: 0; }
        .card-footer { background: var(--card-bg); border-top: 1px solid var(--card-border); padding: 12px 18px; border-radius: 0 0 var(--r) var(--r); }

        /* ── KPI Cards ───────────────────────────── */
        .kpi-card { background: var(--card-bg); border: 1px solid var(--card-border); box-shadow: var(--card-shadow); border-radius: var(--r); padding: 20px; height: 100%; }
        .kpi-icon { width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-bottom: 14px; }
        .kpi-icon [data-lucide] { width: 22px; height: 22px; stroke-width: 2; }
        .kpi-value { font-size: 1.85rem; font-weight: 700; line-height: 1; margin-bottom: 4px; letter-spacing: -.03em; }
        .kpi-label { font-size: .78rem; color: var(--txt2); font-weight: 500; }
        .kpi-purple .kpi-icon { background: #ede9fe; color: #7c3aed; }
        .kpi-green  .kpi-icon { background: #d1fae5; color: #059669; }
        .kpi-pink   .kpi-icon { background: #fce7f3; color: #db2777; }
        .kpi-blue   .kpi-icon { background: #dbeafe; color: #2563eb; }
        .kpi-amber  .kpi-icon { background: #fef3c7; color: #d97706; }
        .kpi-red    .kpi-icon { background: #fee2e2; color: #dc2626; }
        .kpi-indigo .kpi-icon { background: #e0e7ff; color: #4338ca; }

        /* ── Tables ──────────────────────────────── */
        .table > thead > tr > th { font-weight: 600; font-size: .72rem; text-transform: uppercase; letter-spacing: .05em; color: var(--txt2); border-bottom: 1px solid var(--card-border); background: #f9fafb; padding: 10px 14px; }
        .table > tbody > tr > td { padding: 11px 14px; vertical-align: middle; border-color: #f3f4f6; }
        .table-hover > tbody > tr:hover > td { background: #f9fafb; }

        /* ── Badges / Status ─────────────────────── */
        .badge-active   { background: #d1fae5; color: #065f46; font-weight: 600; }
        .badge-inactive { background: #f3f4f6; color: #6b7280; font-weight: 600; }
        .status-sent    { background: #d1fae5; color: #065f46; }
        .status-failed  { background: #fee2e2; color: #991b1b; }
        .status-skipped { background: #fef3c7; color: #92400e; }
        .status-pending { background: #dbeafe; color: #1e40af; }

        /* ── Alerts ──────────────────────────────── */
        .alert { border: none; border-radius: 9px; font-size: .875rem; }
        .alert-success { background: #d1fae5; color: #065f46; }
        .alert-danger  { background: #fee2e2; color: #991b1b; }
        .alert-warning { background: #fef3c7; color: #92400e; }
        .alert-info    { background: #dbeafe; color: #1e40af; }

        /* ── Buttons ─────────────────────────────── */
        .btn { border-radius: 8px; font-weight: 500; font-size: .855rem; display: inline-flex; align-items: center; gap: 6px; }
        .btn [data-lucide] { width: 14px; height: 14px; stroke-width: 2; flex-shrink: 0; }
        .btn-primary { background: var(--primary); border-color: var(--primary); }
        .btn-primary:hover, .btn-primary:focus { background: var(--primary-dark); border-color: var(--primary-dark); }
        .btn-sm { font-size: .79rem; padding: 5px 12px; }
        .btn-outline-primary { color: var(--primary); border-color: var(--primary); }
        .btn-outline-primary:hover { background: var(--primary); border-color: var(--primary); color: #fff; }

        /* ── Forms ───────────────────────────────── */
        .form-control, .form-select { border-color: var(--card-border); border-radius: 8px; font-size: .875rem; }
        .form-control:focus, .form-select:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(79,70,229,.1); }
        .form-label { font-weight: 500; font-size: .84rem; margin-bottom: 5px; color: var(--txt); }
        .form-section-title { font-size: .78rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: var(--txt2); padding-bottom: 8px; border-bottom: 1px solid var(--card-border); margin-bottom: 16px; margin-top: 8px; }

        /* ── Celebration rows ────────────────────── */
        .celebration-row { display: flex; align-items: center; justify-content: space-between; padding: 12px 18px; border-bottom: 1px solid #f3f4f6; }
        .celebration-row:last-child { border-bottom: none; }

        /* ── Health check icons ──────────────────── */
        .health-icon { width: 36px; height: 36px; border-radius: 9px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .health-icon [data-lucide] { width: 18px; height: 18px; stroke-width: 2; }

        /* ── Icon display (empty states etc.) ────── */
        .icon-display { width: 2rem; height: 2rem; opacity: .4; display: block; margin: 0 auto .5rem; }

        /* ── Responsive ──────────────────────────── */
        @media (max-width: 991px) {
            .sidebar { transform: translateX(-100%); transition: transform .25s ease; }
            .sidebar.sidebar-open { transform: translateX(0); }
            .sidebar-backdrop { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.4); z-index: 199; }
            .sidebar-backdrop.show { display: block; }
            .main-content { margin-left: 0; }
            .sidebar-toggle { display: inline-flex; align-items: center; }
        }
        @media (max-width: 576px) {
            .page-content { padding: 16px; }
            .topbar { padding: 0 16px; }
            .user-name { display: none; }
        }
    </style>
    @stack('styles')
</head>
<body>
<div class="sidebar-backdrop" id="sidebarBackdrop" onclick="closeSidebar()"></div>

<div class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon"><i data-lucide="calendar-heart"></i></div>
        <h5>HR Scheduler</h5>
        <small>Celebration Scheduler</small>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-section">Main</div>
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i data-lucide="gauge"></i> Dashboard
        </a>

        <div class="nav-section">People</div>
        <a href="{{ route('employees.index') }}" class="nav-link {{ request()->routeIs('employees.index') || request()->routeIs('employees.show') ? 'active' : '' }}">
            <i data-lucide="users"></i> Employees
        </a>
        <a href="{{ route('employees.create') }}" class="nav-link {{ request()->routeIs('employees.create') || request()->routeIs('employees.edit') ? 'active' : '' }}">
            <i data-lucide="user-plus"></i> Add Employee
        </a>

        <div class="nav-section">Celebrations</div>
        <a href="{{ route('templates.index') }}" class="nav-link {{ request()->routeIs('templates.index') || request()->routeIs('templates.show') ? 'active' : '' }}">
            <i data-lucide="file-text"></i> Templates
        </a>
        <a href="{{ route('templates.create') }}" class="nav-link {{ request()->routeIs('templates.create') || request()->routeIs('templates.edit') ? 'active' : '' }}">
            <i data-lucide="circle-plus"></i> New Template
        </a>

        <div class="nav-section">Administration</div>
        <a href="{{ route('settings.email') }}" class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
            <i data-lucide="settings"></i> Email Settings
        </a>
        <a href="{{ route('health') }}" class="nav-link {{ request()->routeIs('health') ? 'active' : '' }}">
            <i data-lucide="activity"></i> System Health
        </a>
    </nav>
</div>

<div class="main-content">
    <div class="topbar">
        <div class="d-flex align-items-center gap-2">
            <button class="sidebar-toggle" onclick="openSidebar()" aria-label="Menu">
                <i data-lucide="menu"></i>
            </button>
            <div>
                <div class="topbar-title">@yield('title', 'Dashboard')</div>
                @hasSection('breadcrumb')
                <div class="topbar-breadcrumb">@yield('breadcrumb')</div>
                @endif
            </div>
        </div>
        <div class="topbar-user">
            <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
            <span class="user-name">{{ Auth::user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button type="submit" class="btn-logout">
                    <i data-lucide="log-out"></i> Logout
                </button>
            </form>
        </div>
    </div>

    <div class="page-content">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i data-lucide="check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <i data-lucide="circle-alert"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
            <i data-lucide="triangle-alert"></i> {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/lucide@latest/dist/umd/lucide.min.js"></script>
<script>
lucide.createIcons();
function openSidebar() {
    document.getElementById('sidebar').classList.add('sidebar-open');
    document.getElementById('sidebarBackdrop').classList.add('show');
}
function closeSidebar() {
    document.getElementById('sidebar').classList.remove('sidebar-open');
    document.getElementById('sidebarBackdrop').classList.remove('show');
}
</script>
@stack('scripts')
</body>
</html>
