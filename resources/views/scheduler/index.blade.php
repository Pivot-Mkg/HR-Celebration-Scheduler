@extends('layouts.app')
@section('title', 'Scheduler')

@section('content')
<div class="row justify-content-center">
<div class="col-xl-7">
<div class="card mb-3">
    <div class="card-header"><i class="bi bi-play-circle me-2"></i>Run Celebrations Scheduler</div>
    <div class="card-body">
        <div class="alert alert-info small">
            <i class="bi bi-info-circle me-2"></i>
            The scheduler automatically runs daily at <strong>08:00 {{ config('app.timezone') }}</strong> via cron.
            Use the buttons below to run it manually or preview what would happen.
        </div>
        <div class="alert alert-warning small">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <strong>Duplicate protection is active.</strong> If emails were already sent today, they will be skipped automatically.
        </div>

        <div class="d-flex gap-3">
            <form method="POST" action="{{ route('scheduler.run') }}">
                @csrf
                <button type="submit" class="btn btn-primary btn-lg" onclick="return confirm('Run the scheduler now? This will send real emails.')">
                    <i class="bi bi-play-fill me-2"></i>Run Scheduler Now
                </button>
            </form>
            <form method="POST" action="{{ route('scheduler.run') }}">
                @csrf <input type="hidden" name="dry_run" value="1">
                <button type="submit" class="btn btn-outline-secondary btn-lg">
                    <i class="bi bi-eye me-2"></i>Dry Run (No Emails)
                </button>
            </form>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header"><i class="bi bi-info-circle me-2"></i>Cron Configuration</div>
    <div class="card-body">
        <p class="text-muted small">Add this line to your server's crontab to enable automatic daily scheduling:</p>
        <pre class="bg-dark text-light p-3 rounded small">* * * * * cd /path/to/hr-celebration-scheduler &amp;&amp; php artisan schedule:run &gt;&gt; /dev/null 2&gt;&amp;1</pre>
        <p class="text-muted small">Replace <code>/path/to/hr-celebration-scheduler</code> with the actual production path.</p>
    </div>
</div>
</div>
</div>
@endsection
