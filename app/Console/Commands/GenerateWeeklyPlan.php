<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateWeeklyPlan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-weekly-plan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        \App\Models\Customer::query()
            ->where('status','active')
            ->pluck('id')
            ->each(fn($cid) => dispatch(new \App\Jobs\GenerateWeeklyPlanJob((int)$cid))->onQueue('ai'));
    }
}
