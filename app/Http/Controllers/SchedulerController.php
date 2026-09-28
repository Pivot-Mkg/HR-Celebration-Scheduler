<?php

namespace App\Http\Controllers;

use App\Services\CelebrationProcessor;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SchedulerController extends Controller
{
    public function __construct(private CelebrationProcessor $processor) {}

    public function index()
    {
        return view('scheduler.index');
    }

    public function run(Request $request)
    {
        $dryRun  = $request->boolean('dry_run');
        $results = $this->processor->process(Carbon::now(), $dryRun);

        $label = $dryRun ? 'Dry Run completed' : 'Scheduler completed';
        return view('scheduler.results', compact('results', 'label'));
    }
}
