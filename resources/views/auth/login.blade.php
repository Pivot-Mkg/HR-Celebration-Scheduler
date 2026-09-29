<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In — HR Celebration Scheduler</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Inter', system-ui, sans-serif; background: #f3f4f6; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        [data-lucide] { width: 1em; height: 1em; vertical-align: -0.125em; stroke-width: 1.75; display: inline-block; }
        .login-wrap { width: 100%; max-width: 420px; padding: 20px; }
        .login-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 14px; padding: 40px; box-shadow: 0 4px 24px rgba(0,0,0,0.08); }
        .brand-icon { width: 48px; height: 48px; background: #4f46e5; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 16px; }
        .brand-icon [data-lucide] { width: 24px; height: 24px; color: #fff; stroke-width: 2; }
        h4 { font-weight: 700; font-size: 1.25rem; color: #111827; margin-bottom: 4px; }
        .subtitle { font-size: .85rem; color: #6b7280; margin-bottom: 28px; }
        .form-label { font-weight: 500; font-size: .84rem; color: #374151; margin-bottom: 5px; }
        .form-control { border-color: #e5e7eb; border-radius: 8px; font-size: .875rem; padding: 9px 13px; }
        .form-control:focus { border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,.1); }
        .btn-signin { background: #4f46e5; border: none; border-radius: 8px; color: #fff; font-weight: 600; font-size: .9rem; padding: 10px; width: 100%; cursor: pointer; transition: background .15s; display: flex; align-items: center; justify-content: center; gap: 8px; }
        .btn-signin:hover { background: #3730a3; }
        .btn-signin [data-lucide] { width: 16px; height: 16px; stroke-width: 2; }
        .alert-danger { background: #fee2e2; color: #991b1b; border: none; border-radius: 9px; font-size: .875rem; display: flex; align-items: center; gap: 8px; }
        .alert-danger [data-lucide] { width: 16px; height: 16px; flex-shrink: 0; }
    </style>
</head>
<body>
<div class="login-wrap">
    <div class="login-card">
        <div class="text-center">
            <div class="brand-icon"><i data-lucide="calendar-heart"></i></div>
            <h4>HR Celebration Scheduler</h4>
            <p class="subtitle">Sign in to your account</p>
        </div>

        @if($errors->any())
        <div class="alert alert-danger mb-3">
            <i data-lucide="circle-alert"></i>{{ $errors->first() }}
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email') }}" autofocus required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
            </div>
            <div class="mb-4 form-check">
                <input type="checkbox" name="remember" id="remember" class="form-check-input">
                <label class="form-check-label" for="remember" style="font-size:.84rem;">Remember me</label>
            </div>
            <button type="submit" class="btn-signin">
                <i data-lucide="log-in"></i> Sign In
            </button>
        </form>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/lucide@latest/dist/umd/lucide.min.js"></script>
<script>lucide.createIcons();</script>
</body>
</html>
