<?php

namespace App\Console\Commands;

use App\Services\CelebrationProcessor;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ProcessCelebrations extends Command
{
    protected $signature = 'celebrations:process {--dry-run : Preview what would be sent without actually sending}';
    protected $description = 'Process birthday and anniversary emails for today';

    public function handle(CelebrationProcessor $processor): int
    {
        $dryRun = $this->option('dry-run');
        $date   = Carbon::now();

        $this->line('');
        $this->info('HR Celebration Scheduler');
        $this->line('─────────────────────────────────────────');
        $this->line('Date: ' . $date->format('d M Y (l)'));
        if ($dryRun) {
            $this->warn('⚠  DRY RUN — No emails will be sent');
        }
        $this->line('');

        $results = $processor->process($date, $dryRun);

        $this->line("Birthdays found:     {$results['birthdays']}");
        $this->line("Anniversaries found: {$results['anniversaries']}");
        $this->line("Founding Day:        " . ($results['founding_day'] > 0 ? "Yes ({$results['founding_day']} employees)" : 'No'));
        $this->line('');

        if (empty($results['details'])) {
            $this->line('No celebrations today.');
            $this->line('');
            return Command::SUCCESS;
        }

        $this->line('Results:');
        foreach ($results['details'] as $detail) {
            $icon   = match($detail['status']) {
                'sent'       => '✓',
                'would_send' => '→',
                'failed'     => '✗',
                'skipped'    => '⊘',
                default      => '?',
            };
            $event  = ucfirst($detail['event']);
            $name   = $detail['employee'];
            $status = strtoupper($detail['status']);
            $extra  = '';
            if ($dryRun && isset($detail['to'])) {
                $extra = "  To: {$detail['to']}";
                if (!empty($detail['cc'])) $extra .= "  CC: {$detail['cc']}";
                $extra .= "  Template: {$detail['template']}";
            }
            if (isset($detail['error'])) {
                $extra = "  Error: {$detail['error']}";
            }
            if (isset($detail['reason'])) {
                $extra = "  ({$detail['reason']})";
            }

            $line = "  {$icon} {$name} — {$event} — {$status}{$extra}";
            match($detail['status']) {
                'sent', 'would_send' => $this->info($line),
                'failed'             => $this->error($line),
                default              => $this->warn($line),
            };
        }

        $this->line('');
        $this->line('─────────────────────────────────────────');
        $total = $results['sent'] + $results['failed'] + $results['skipped'];
        $this->line("Total processed: {$total}");
        if (!$dryRun) {
            $this->info("  Sent:    {$results['sent']}");
            $this->error("  Failed:  {$results['failed']}");
            $this->warn("  Skipped: {$results['skipped']}");
        } else {
            $wouldSend = count(array_filter($results['details'], fn($d) => $d['status'] === 'would_send'));
            $this->line("  Would send: {$wouldSend}");
        }
        $this->line('');

        return $results['failed'] > 0 ? Command::FAILURE : Command::SUCCESS;
    }
}
