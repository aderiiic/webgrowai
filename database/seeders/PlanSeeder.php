<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\PlanFeature;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $starter = Plan::firstOrCreate(
            ['name' => 'Starter'],
            ['price_monthly' => 0, 'price_yearly' => 0, 'is_active' => true]
        );

        $pro = Plan::firstOrCreate(
            ['name' => 'Pro'],
            ['price_monthly' => 990, 'price_yearly' => 9990, 'is_active' => true]
        );

        $scale = Plan::firstOrCreate(
            ['name' => 'Scale'],
            ['price_monthly' => 2490, 'price_yearly' => 24900, 'is_active' => true]
        );

        $matrix = [
            'ai.short.monthly_limit'            => ['Starter' => 20,  'Pro' => 200,  'Scale' => 1000],
            'ai.long.monthly_limit'             => ['Starter' => 5,   'Pro' => 50,   'Scale' => 250],
            'seo.audit.monthly_limit'           => ['Starter' => 2,   'Pro' => 20,   'Scale' => 100],
            'integrations.wordpress.sites_limit'=> ['Starter' => 1,   'Pro' => 3,    'Scale' => 10],
            'integrations.mailchimp.enabled'    => ['Starter' => 0,   'Pro' => 1,    'Scale' => 1],
            'publish.schedule.enabled'          => ['Starter' => 0,   'Pro' => 1,    'Scale' => 1],
        ];

        $plans = [
            'Starter' => $starter,
            'Pro'     => $pro,
            'Scale'   => $scale,
        ];

        foreach ($matrix as $key => $perPlan) {
            foreach ($plans as $label => $plan) {
                $value = $perPlan[$label] ?? null;
                PlanFeature::updateOrCreate(
                    ['plan_id' => $plan->id, 'key' => $key],
                    [
                        'is_enabled' => (bool) $value,
                        'limit_value' => is_numeric($value) ? (string) $value : null,
                    ]
                );
            }
        }
    }
}
