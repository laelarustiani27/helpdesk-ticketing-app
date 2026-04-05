<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Issue;
use App\Services\RuleEngine\IncidentRuleService;

class IncidentScan extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'incident:scan';

    /**
     * The console command description.
     */
    protected $description = 'Scan issues and classify incidents based on defined rules';

    protected IncidentRuleService $incidentRuleService;

    /**
     * Create a new command instance.
     */
    public function __construct(IncidentRuleService $incidentRuleService)
    {
        parent::__construct();
        $this->incidentRuleService = $incidentRuleService;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Scanning issues...');
        $issues = Issue::whereNull('severity')->get();

        if ($issues->isEmpty()) {
            $this->info(' No issues to process.');
            return Command::SUCCESS;
        }

        foreach ($issues as $issue) {
            $severity = $this->incidentRuleService->evaluate([
                'title'       => $issue->title ?? '',
                'description' => $issue->description ?? '',
                'priority'    => $issue->priority ?? 0,
            ]);

            $issue->severity = $severity;
            $issue->save();

            $this->line("✔ Issue #{$issue->id} classified as {$severity}");
        }

        $this->info('Incident scan completed.');

        return Command::SUCCESS;
    }
}