<?php

namespace App\Console\Commands;

use App\Http\Controllers\BillingController;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Subscription as StripeSubscription;

class SyncStripeSubscription extends Command
{
    protected $signature = 'stripe:sync-subscription
                            {user_email : Email för användaren}
                            {--stripe-sub-id= : Stripe Subscription ID (valfritt, hämtas automatiskt om inte angivet)}';

    protected $description = 'Synkronisera Stripe-prenumeration till lokal databas';

    public function handle(): int
    {
        $email = $this->argument('user_email');
        $stripeSubId = $this->option('stripe-sub-id');

        // Hitta användaren
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("❌ Användare med email '{$email}' hittades inte.");
            return 1;
        }

        $this->info("✓ Användare hittad: {$user->name} (ID: {$user->id})");

        // Kolla om användaren har Stripe Customer ID
        if (empty($user->stripe_id)) {
            $this->error("❌ Användaren saknar stripe_id i databasen.");
            return 1;
        }

        $this->info("✓ Stripe Customer ID: {$user->stripe_id}");

        // Hitta customer
        $customer = DB::table('customers')
            ->join('customer_user', 'customers.id', '=', 'customer_user.customer_id')
            ->where('customer_user.user_id', $user->id)
            ->select('customers.*')
            ->first();

        if (!$customer) {
            $this->error("❌ Ingen customer-koppling hittades för användaren.");
            return 1;
        }

        $this->info("✓ Customer ID: {$customer->id}");

        // Hämta prenumeration från Stripe
        try {
            Stripe::setApiKey(config('services.stripe.secret'));

            if ($stripeSubId) {
                // Hämta specifik prenumeration
                $stripeSub = StripeSubscription::retrieve($stripeSubId);
                $this->info("✓ Hämtade prenumeration från Stripe: {$stripeSub->id}");
            } else {
                // Hitta aktiv prenumeration för kunden
                $subscriptions = StripeSubscription::all([
                    'customer' => $user->stripe_id,
                    'status' => 'active',
                    'limit' => 1,
                ]);

                if (count($subscriptions->data) === 0) {
                    $this->error("❌ Ingen aktiv prenumeration hittades i Stripe för denna kund.");
                    $this->info("💡 Försök igen med --stripe-sub-id om prenumerationen inte är 'active'");
                    return 1;
                }

                $stripeSub = $subscriptions->data[0];
                $this->info("✓ Hämtade aktiv prenumeration: {$stripeSub->id}");
            }

            // Visa prenumerationsinfo
            $this->newLine();
            $this->info("📋 Prenumerationsinformation från Stripe:");
            $this->table(
                ['Fält', 'Värde'],
                [
                    ['ID', $stripeSub->id],
                    ['Status', $stripeSub->status],
                    ['Skapad', date('Y-m-d H:i:s', $stripeSub->created)],
                    ['Period start', date('Y-m-d H:i:s', $stripeSub->current_period_start)],
                    ['Period slut', date('Y-m-d H:i:s', $stripeSub->current_period_end)],
                ]
            );

            // Hämta Price ID från första subscription item
            if (empty($stripeSub->items->data)) {
                $this->error("❌ Prenumerationen har inga items.");
                return 1;
            }

            $priceId = $stripeSub->items->data[0]->price->id;
            $this->info("✓ Stripe Price ID: {$priceId}");

            // Hitta motsvarande plan i databasen
            $plan = BillingController::findPlanByStripePrice($priceId);

            if (!$plan) {
                $this->error("❌ Ingen plan i databasen matchar Price ID: {$priceId}");
                $this->info("💡 Kolla att plans-tabellen har rätt stripe_price_monthly/stripe_price_yearly");
                return 1;
            }

            $this->info("✓ Plan hittad: {$plan->name} (ID: {$plan->id})");

            // Bestäm billing cycle
            $billingCycle = ($plan->stripe_price_yearly === $priceId) ? 'annual' : 'monthly';
            $this->info("✓ Faktureringsintervall: {$billingCycle}");

            // Bekräfta innan vi sparar
            $this->newLine();
            if (!$this->confirm('Vill du spara denna prenumeration till databasen?', true)) {
                $this->info('Avbruten.');
                return 0;
            }

            // Spara till app_subscriptions
            $existingSub = DB::table('app_subscriptions')
                ->where('customer_id', $customer->id)
                ->orderByDesc('id')
                ->first();

            if ($existingSub) {
                $this->warn("⚠️  En befintlig prenumeration hittades (ID: {$existingSub->id})");

                if (!$this->confirm('Vill du uppdatera den befintliga prenumerationen?', true)) {
                    $this->info('Avbruten.');
                    return 0;
                }

                DB::table('app_subscriptions')
                    ->where('id', $existingSub->id)
                    ->update([
                        'plan_id' => $plan->id,
                        'status' => $stripeSub->status === 'active' ? 'active' : $stripeSub->status,
                        'billing_cycle' => $billingCycle,
                        'current_period_start' => date('Y-m-d H:i:s', $stripeSub->current_period_start),
                        'current_period_end' => date('Y-m-d H:i:s', $stripeSub->current_period_end),
                        'trial_ends_at' => null,
                        'updated_at' => now(),
                    ]);

                $this->info("✅ Prenumeration uppdaterad (ID: {$existingSub->id})");
            } else {
                $subId = DB::table('app_subscriptions')->insertGetId([
                    'customer_id' => $customer->id,
                    'plan_id' => $plan->id,
                    'status' => $stripeSub->status === 'active' ? 'active' : $stripeSub->status,
                    'billing_cycle' => $billingCycle,
                    'current_period_start' => date('Y-m-d H:i:s', $stripeSub->current_period_start),
                    'current_period_end' => date('Y-m-d H:i:s', $stripeSub->current_period_end),
                    'trial_ends_at' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $this->info("✅ Ny prenumeration skapad (ID: {$subId})");
            }

            // Synka även till Cashier's subscriptions-tabell
            $this->newLine();
            if ($this->confirm('Vill du även synkronisera till Cashiers subscriptions-tabell?', true)) {
                $this->syncToCashier($user, $stripeSub, $priceId);
            }

            $this->newLine();
            $this->info("🎉 Synkronisering klar!");

            return 0;

        } catch (\Exception $e) {
            $this->error("❌ Fel vid hämtning från Stripe: {$e->getMessage()}");
            Log::error('Stripe sync error', [
                'user_email' => $email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return 1;
        }
    }

    private function syncToCashier(User $user, StripeSubscription $stripeSub, string $priceId): void
    {
        try {
            // Kolla om prenumerationen redan finns i Cashiers tabell
            $existing = DB::table('subscriptions')
                ->where('stripe_id', $stripeSub->id)
                ->first();

            if ($existing) {
                // Uppdatera befintlig
                DB::table('subscriptions')
                    ->where('id', $existing->id)
                    ->update([
                        'stripe_status' => $stripeSub->status,
                        'stripe_price' => $priceId,
                        'quantity' => $stripeSub->items->data[0]->quantity ?? 1,
                        'ends_at' => null,
                        'updated_at' => now(),
                    ]);

                $this->info("✅ Cashier subscription uppdaterad (ID: {$existing->id})");
            } else {
                // Skapa ny - FIXAT: Använd insertGetId istället för lastInsertId
                $subscriptionId = DB::table('subscriptions')->insertGetId([
                    'user_id' => $user->id,
                    'type' => 'default',
                    'stripe_id' => $stripeSub->id,
                    'stripe_status' => $stripeSub->status,
                    'stripe_price' => $priceId,
                    'quantity' => $stripeSub->items->data[0]->quantity ?? 1,
                    'trial_ends_at' => null,
                    'ends_at' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $this->info("✅ Cashier subscription skapad (ID: {$subscriptionId})");

                // Skapa subscription_items
                foreach ($stripeSub->items->data as $item) {
                    DB::table('subscription_items')->insert([
                        'subscription_id' => $subscriptionId, // FIX: Använd variabeln istället för lastInsertId()
                        'stripe_id' => $item->id,
                        'stripe_product' => $item->price->product,
                        'stripe_price' => $item->price->id,
                        'quantity' => $item->quantity ?? 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                $this->info("✅ Subscription items skapade");
            }

        } catch (\Exception $e) {
            $this->error("❌ Fel vid Cashier-synk: {$e->getMessage()}");
            Log::error('Cashier sync error', [
                'user_id' => $user->id,
                'stripe_sub_id' => $stripeSub->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
