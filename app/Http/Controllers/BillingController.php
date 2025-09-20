<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BillingController extends Controller
{
    public function pricing(Request $request)
    {
        // Hämta aktiva planer och deras Stripe price-id
        // Antag att du har kolumnerna stripe_price_monthly och stripe_price_yearly i plans
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

        $data = $request->validate([
            'price' => 'required|string', // Stripe Price ID, ex. "price_123"
        ]);

        // Skapa Stripe Checkout för prenumeration
        return $user->newSubscription('default', $data['price'])->checkout([
            'success_url' => route('billing.success').'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'  => route('billing.pricing'),
        ]);
    }

    public function success(Request $request)
    {
        return redirect()->route('dashboard')->with('success', 'Abonnemanget är nu aktivt. Välkommen tillbaka!');
    }

    public function portal(Request $request)
    {
        // Stripes Billing Portal: hantera plan, kort, uppsägning, fakturor
        return $request->user()->redirectToBillingPortal(route('account.usage'));
    }
}
