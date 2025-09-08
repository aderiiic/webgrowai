<?php

namespace App\Console\Commands;

use App\Jobs\GenerateWeeklyPlanJob;
use App\Models\Customer;
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
    protected $description = 'Generera veckoplan per site';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        Customer::query()
            ->where('status', 'active')
            ->with('sites:id,customer_id') // minimera laddning
            ->get()
            ->flatMap(fn($customer) => $customer->sites)
            ->each(fn($site) => dispatch(new GenerateWeeklyPlanJob((int) $site->id))->onQueue('ai'));
    }
}
