<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Stripe;

class BillingController extends Controller
{
    public function pricing(Request $request)
    {
        $plans = DB::table('plans')
            ->select('id','name','price_monthly','price_yearly','stripe_price_monthly','stripe_price_yearly')
            ->where('is_active', true)
            ->orderBy('price_monthly')
            ->get();

        return view('billing.pricing', compact('plans'));
    }

    public function checkout(Request $request)
    {
        $user = $request->user();

        if (!$user->email || !$user->name) {
            return back()->withErrors([
                'error' => 'Du behöver komplettera din profil med email och namn innan du kan välja en plan.'
            ]);
        }

        $data = $request->validate([
            'price' => 'required|string', // Stripe Price ID, ex. "price_123"
        ]);

        if (empty($data['price'])) {
            return back()->withErrors([
                'error' => 'Ingen giltig prisplan valdes. Vänligen försök igen.'
            ]);
        }

        try {
            if (empty($user->stripe_id)) {
                $user->stripe_id = null;
                $user->save();
            }

            $user->createOrGetStripeCustomer();
            $user->updateStripeCustomer([
                'address' => array_filter([
                    'country'     => $user->country ?? 'SE',
                    'postal_code' => $user->postal_code ?? null,
                    'city'        => $user->city ?? null,
                    'line1'       => $user->address_line1 ?? null,
                ]),
            ]);
        } catch (\Exception $e) {
            Log::error('Stripe customer creation failed: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'stripe_id' => $user->stripe_id,
            ]);

            return back()->withErrors([
                'error' => 'Det gick inte att skapa Stripe-kunden. Kontakta support om problemet kvarstår.'
            ]);
        }

        // Skapa Stripe Checkout för prenumeration
        return $user->newSubscription('default', $data['price'])->checkout([
            'success_url' => route('billing.success').'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'  => route('billing.pricing'),

            'automatic_tax' => ['enabled' => true],
            'billing_address_collection' => 'required',
            'customer_update' => ['address' => 'auto'],
            'tax_id_collection' => ['enabled' => true],
        ]);
    }

    public function success(Request $request)
    {
        $sessionId = (string) $request->query('session_id', '');
        if ($sessionId) {
            try {
                Stripe::setApiKey(config('services.stripe.secret'));
                $session = StripeSession::retrieve($sessionId, []);
                // Om vi vill, kan vi läsa $session->subscription och visa status.
                // Ingen DB-aktivering här; det görs via webhook för robusthet.
            } catch (\Throwable $e) {
                // Tyst: webhook löser den faktiska aktiveringen ändå
            }
        }

        return redirect()->route('dashboard')->with('success', 'Abonnemanget är nu aktivt. Välkommen tillbaka!');
    }

    public function portal(Request $request)
    {
        // Stripes Billing Portal: hantera plan, kort, uppsägning, fakturor
        return $request->user()->redirectToBillingPortal(route('account.usage'));
    }

    public static function findPlanByStripePrice(string $priceId): ?object
    {
        if ($priceId === '') return null;
        return DB::table('plans')
            ->where('stripe_price_monthly', $priceId)
            ->orWhere('stripe_price_yearly', $priceId)
            ->first();
    }
}
