<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class PlansSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            'Starter' => [
                'price_monthly' => 49000, 'price_yearly' => 529200,
                'features' => [
                    ['key'=>'ai.generate','limit'=>500],
                    ['key'=>'ai.publish','limit'=>50],
                    ['key'=>'seo.audit','limit'=>2],
                    ['key'=>'leads.events','limit'=>5000],
                    ['key'=>'sites','limit'=>1],
                    ['key'=>'users','limit'=>2],
                ],
            ],
            'Growth' => [
                'price_monthly' => 149000, 'price_yearly' => 1609200,
                'features' => [
                    ['key'=>'ai.generate','limit'=>2500],
                    ['key'=>'ai.publish','limit'=>200],
                    ['key'=>'seo.audit','limit'=>8],
                    ['key'=>'leads.events','limit'=>25000],
                    ['key'=>'sites','limit'=>3],
                    ['key'=>'users','limit'=>5],
                ],
            ],
            'Pro' => [
                'price_monthly' => 399000, 'price_yearly' => 4309200,
                'features' => [
                    ['key'=>'ai.generate','limit'=>10000],
                    ['key'=>'ai.publish','limit'=>1000],
                    ['key'=>'seo.audit','limit'=>30],
                    ['key'=>'leads.events','limit'=>100000],
                    ['key'=>'sites','limit'=>10],
                    ['key'=>'users','limit'=>20],
                ],
            ],
        ];

        foreach ($plans as $name => $data) {
            $planId = DB::table('plans')->updateOrInsert(
                ['name' => $name],
                [
                    'description' => null,
                    'is_active' => true,
                    'price_monthly' => $data['price_monthly'],
                    'price_yearly'  => $data['price_yearly'],
                    'updated_at' => now(), 'created_at' => now(),
                ]
            );

            $plan = DB::table('plans')->where('name',$name)->first();
            if (!$plan) continue;

            foreach ($data['features'] as $f) {
                DB::table('plan_features')->updateOrInsert(
                    ['plan_id' => $plan->id, 'key' => $f['key']],
                    ['limit_value' => (string)$f['limit'], 'is_enabled' => true, 'updated_at'=>now(), 'created_at'=>now()]
                );
            }
        }
    }
}
