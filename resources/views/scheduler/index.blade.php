@extends('layouts.app')
@section('title', 'Run Scheduler')
@section('breadcrumb', 'Celebrations')

@section('content')
<div class="row justify-content-center">
<div class="col-xl-7">

<div class="card mb-3">
    <div class="card-header"><i data-lucide="circle-play"></i>Run Celebrations Scheduler</div>
    <div class="card-body">
        <div class="alert alert-info mb-3">
            <i data-lucide="info"></i>
            The scheduler automatically runs daily at <strong>08:00 {{ config('app.timezone') }}</strong> via cron.
            Use the buttons below to run it manually or preview what would happen.
        </div>
        <div class="alert alert-warning mb-4">
            <i data-lucide="shield-check"></i>
            <strong>Duplicate protection is active.</strong> Emails already sent today are skipped automatically.
        </div>

        <div class="d-flex flex-wrap gap-3">
            <form method="POST" action="{{ route('scheduler.run') }}">
                @csrf
                <button type="submit" class="btn btn-primary" onclick="return confirm('Run the scheduler now? This will send real emails.')">
                    <i data-lucide="play"></i>Run Scheduler Now
                </button>
            </form>
            <form method="POST" action="{{ route('scheduler.run') }}">
                @csrf <input type="hidden" name="dry_run" value="1">
                <button type="submit" class="btn btn-outline-secondary">
                    <i data-lucide="eye"></i>Dry Run (No Emails)
                </button>
            </form>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header"><i data-lucide="terminal"></i>Cron Configuration</div>
    <div class="card-body">
        <p class="text-muted small mb-2">Add this line to your server's crontab to enable automatic daily scheduling:</p>
        <pre class="rounded p-3 mb-2" style="background:#1e1b4b;color:#a5b4fc;font-size:.82rem;overflow-x:auto;">* * * * * cd /path/to/hr-celebration-scheduler &amp;&amp; php artisan schedule:run &gt;&gt; /dev/null 2&gt;&amp;1</pre>
        <p class="text-muted small mb-0">Replace <code>/path/to/hr-celebration-scheduler</code> with the actual production path.</p>
    </div>
</div>

</div>
</div>
@endsection
