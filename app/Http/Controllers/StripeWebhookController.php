<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\Billing\SubscriptionManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierWebhookController;
use Stripe\Stripe;
use Stripe\Webhook;
use Stripe\Event;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Subscription as StripeSubscription;
use Stripe\Invoice as StripeInvoice;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secret = (string) env('STRIPE_WEBHOOK_SECRET');

        Log::info('Stripe webhook received', [
            'payload_length' => strlen($payload),
            'signature_present' => !empty($sigHeader),
            'secret_configured' => !empty($secret)
        ]);

        try {
            $event = $secret
                ? Webhook::constructEvent($payload, $sigHeader, $secret)
                : Event::constructFrom(json_decode($payload, true));
        } catch (\Throwable $e) {
            Log::warning('Stripe webhook signature verification failed', ['error' => $e->getMessage()]);
            return response('Invalid signature', 400);
        }

        try {
            return $this->dispatchEvent($event);
        } catch (\Throwable $e) {
            Log::error('Stripe webhook handler failed', ['type' => $event->type ?? null, 'error' => $e->getMessage()]);
        }

        try {
            $cashier = app(CashierWebhookController::class);
            return $cashier->handleWebhook($request);
        } catch (\Throwable $e) {
            Log::error('Cashier webhook delegation failed', ['type' => $event->type ?? null, 'error' => $e->getMessage()]);
            return response('ok', 200); // svara 200 så Stripe inte spam-retry:ar – vår del är redan klar
        }
    }

    protected function dispatchEvent(Event $event)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        Log::info('Processing Stripe webhook event', [
            'event_type' => $event->type,
            'event_id' => $event->id ?? 'unknown'
        ]);

        switch ($event->type) {
            case 'checkout.session.completed':
                $this->onCheckoutSessionCompleted($event->data->object);
                break;

            case 'customer.subscription.created':
            case 'customer.subscription.updated':
                $this->onSubscriptionSynced($event->data->object);
                break;

            case 'invoice.paid':
                $this->onInvoicePaid($event->data->object);
                break;

            default:
                // ignore others
                break;
        }

        return response('ok', 200);
    }

    protected function onCheckoutSessionCompleted($session): void
    {
        Log::info('Processing checkout session completed', [
            'session_id' => $session->id ?? 'unknown',
            'mode' => $session->mode ?? 'unknown',
            'customer' => $session->customer ?? 'unknown',
            'subscription' => $session->subscription ?? 'unknown'
        ]);

        // $session = \Stripe\Checkout\Session
        if (($session->mode ?? null) !== 'subscription') {
            return;
        }

        $stripeCustomerId = (string) ($session->customer ?? '');
        $subscriptionId   = (string) ($session->subscription ?? '');
        $priceId          = '';
        if (!empty($session->line_items) && isset($session->line_items->data[0]->price->id)) {
            $priceId = (string) $session->line_items->data[0]->price->id;
        } else {
            try {
                $sess = StripeSession::retrieve(['id' => $session->id, 'expand' => ['line_items.data.price']]);
                $priceId = (string)($sess->line_items->data[0]->price->id ?? '');
            } catch (\Throwable $e) {
                Log::warning('Cannot expand Checkout Session line items', ['sid' => $session->id, 'err' => $e->getMessage()]);
            }
        }

        $this->activateLocalSubscription($stripeCustomerId, $subscriptionId, $priceId);
    }

    protected function onSubscriptionSynced($stripeSub): void
    {
        // $stripeSub = \Stripe\Subscription
        $stripeCustomerId = (string) $stripeSub->customer;
        $subscriptionId   = (string) $stripeSub->id;

        $priceId = '';
        if (!empty($stripeSub->items->data[0]->price->id)) {
            $priceId = (string) $stripeSub->items->data[0]->price->id;
        }

        if ($priceId === '') {
            try {
                $expanded = StripeSubscription::retrieve(['id' => $subscriptionId, 'expand' => ['items.data.price']]);
                $priceId = (string) ($expanded->items->data[0]->price->id ?? '');
                $stripeSub = $expanded;
            } catch (\Throwable $e) {
                Log::warning('Stripe sub expand failed', ['sub' => $subscriptionId, 'err' => $e->getMessage()]);
            }
        }

        $this->activateLocalSubscription($stripeCustomerId, $subscriptionId, $priceId, $stripeSub);
    }

    protected function onInvoicePaid($invoice): void
    {
        // $invoice = \Stripe\Invoice
//        $stripeCustomerId = (string) ($invoice->customer ?? '');
//        if ($stripeCustomerId === '') return;
//
//        $user = User::where('stripe_id', $stripeCustomerId)->first();
//        if (!$user) return;
//
//        // Spara fakturinfo (lättviktigt) för “Min plan”-sida
//        $periodStart = isset($invoice->lines->data[0]->period->start) ? now()->createFromTimestamp($invoice->lines->data[0]->period->start) : null;
//        $periodEnd   = isset($invoice->lines->data[0]->period->end)   ? now()->createFromTimestamp($invoice->lines->data[0]->period->end)   : null;
//
//        DB::table('invoices')->updateOrInsert(
//            ['provider' => 'stripe', 'provider_invoice_id' => (string) $invoice->id],
//            [
//                'user_id'          => $user->id,
//                'customer_email'   => (string) ($invoice->customer_email ?? $user->email),
//                'amount'           => (int) ($invoice->amount_paid ?? 0),
//                'currency'         => (string) ($invoice->currency ?? 'sek'),
//                'status'           => (string) ($invoice->status ?? 'paid'),
//                'hosted_invoice_url' => (string) ($invoice->hosted_invoice_url ?? ''),
//                'invoice_pdf'        => (string) ($invoice->invoice_pdf ?? ''),
//                'period_start'     => $periodStart,
//                'period_end'       => $periodEnd,
//                'created_at'       => now(),
//                'updated_at'       => now(),
//            ]
//        );

        Log::info('Invoice paid event received (skipping - using internal invoicing system)', [
            'invoice_id' => $invoice->id ?? 'unknown'
        ]);

        // Kvitto: enklast att låta Stripe mejla (aktivera i Stripe Dashboard).
        // Om vi vill mejla själva kan vi skicka Mail här med länkar till hosted_invoice_url/invoice_pdf.
    }

    protected function activateLocalSubscription(string $stripeCustomerId, string $stripeSubscriptionId, string $priceId, $stripeSub = null): void
    {
        Log::info('Attempting to activate local subscription', [
            'stripe_customer_id' => $stripeCustomerId,
            'stripe_subscription_id' => $stripeSubscriptionId,
            'price_id' => $priceId
        ]);

        if ($stripeCustomerId === '' || $priceId === '') return;

        $user = User::where('stripe_id', $stripeCustomerId)->first();
        if (!$user) return;

        $customer = DB::table('customers')
            ->join('customer_user', 'customers.id', '=', 'customer_user.customer_id')
            ->where('customer_user.user_id', $user->id)
            ->select('customers.*')
            ->first();
        if (!$customer) return;

        $plan = BillingController::findPlanByStripePrice($priceId);
        if (!$plan) return;

        // Bestäm billingCycle från vilken kolumn matchade
        $billingCycle = ($plan->stripe_price_yearly === $priceId) ? 'annual' : 'monthly';

        // Perioder: ta från Stripe sub om tillgängligt, annars fallback till månadens gränser
        $start = $stripeSub && isset($stripeSub->current_period_start) ? now()->createFromTimestamp($stripeSub->current_period_start) : now()->startOfMonth();
        $end   = $stripeSub && isset($stripeSub->current_period_end)   ? now()->createFromTimestamp($stripeSub->current_period_end)   : now()->endOfMonth();

        // Uppdatera/aktivera sub i vår DB
        /** @var SubscriptionManager $manager */
        $manager = app(SubscriptionManager::class);
        $manager->applyPlanChange((int) $customer->id, (int) $plan->id, $billingCycle);

        $latest = DB::table('app_subscriptions')
            ->where('customer_id', $customer->id)
            ->orderByDesc('id')
            ->first();

        if (!$latest) {
            Log::warning('No app_subscriptions row found after applyPlanChange', ['customer_id' => $customer->id]);
            return;
        }

        // Sätt perioder explicit
        DB::table('app_subscriptions')->where('id', $latest->id)->update([
            'status'               => 'active',
            'trial_ends_at'        => null,
            'billing_cycle'        => $billingCycle,
            'current_period_start' => $start,
            'current_period_end'   => $end,
            'updated_at'           => now(),
        ]);
    }
}
