<?php

namespace App\Actions\Auth;

use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class RegisterCompany
{
    public function handle(\App\Models\User $user, array $input): void
    {
        $isCompany = ($input['account_type'] ?? 'company') === 'company';

        // For personal accounts, use user's name as company name
        $companyName = $isCompany
            ? ($input['company_name'] ?? $user->name . " Company")
            : $user->name;

        $customerId = DB::table('customers')->insertGetId([
            'name'            => $companyName,
            'company_name'    => $isCompany ? ($input['company_name'] ?? null) : null,
            'contact_email'   => $user->email,
            'contact_name'    => $input['contact_name'] ?? $user->name,
            'contact_phone'   => $input['contact_phone'] ?? null,
            'org_nr'          => $input['org_nr'] ?? null,
            'vat_nr'          => $input['vat_nr'] ?? null,
            'billing_email'   => $input['billing_email'] ?? $user->email,
            'billing_address' => $input['billing_address'] ?? null,
            'billing_zip'     => $input['billing_zip'] ?? null,
            'billing_city'    => $input['billing_city'] ?? null,
            'billing_country' => $input['billing_country'] ?? 'SE',
            'status'          => 'active',
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        if (method_exists($user, 'customers')) {
            $user->customers()->syncWithoutDetaching([$customerId => ['role_in_customer' => 'owner']]);
        }

        $starterPlan = DB::table('plans')->where('name','Starter')->first();
        DB::table('app_subscriptions')->insert([
            'customer_id' => $customerId,
            'plan_id'     => $starterPlan?->id,
            'status'      => 'trial',
            'trial_ends_at' => now()->addDays(14),
            'billing_cycle' => 'monthly',
            'current_period_start' => now()->startOfMonth(),
            'current_period_end'   => now()->endOfMonth(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        app(\App\Support\CurrentCustomer::class)->set($customerId);
    }
}
