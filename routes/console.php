<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('celebrations:process')
    ->dailyAt('08:00')
    ->timezone(config('app.timezone'))
    ->withoutOverlapping()
    ->runInBackground();
