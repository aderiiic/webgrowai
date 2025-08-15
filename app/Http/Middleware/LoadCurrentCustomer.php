<?php

namespace App\Http\Middleware;

use App\Models\Customer;
use App\Support\CurrentCustomer;
use Closure;
use Illuminate\Http\Request;

class LoadCurrentCustomer
{
    public function handle(Request $request, Closure $next)
    {
        $current = app(CurrentCustomer::class);
        $user = $request->user();

        // Om ingen aktiv kund satt i sessionen
        if (!$current->get() && $user) {
            // Admin-fallback: välj första kunden om sådan finns
            if (method_exists($user, 'isAdmin') && $user->isAdmin()) {
                $first = Customer::orderBy('id')->first();
                if ($first) {
                    $current->set($first->id);
                }
                // Finns ingen kund ännu? Låt flödet fortsätta – onboarding/GUI får be användaren skapa en kund.
            } else {
                // Icke-admin: om användaren har kopplade kunder, välj första
                if (method_exists($user, 'customers')) {
                    $firstOwned = $user->customers()->orderBy('customers.id')->first();
                    if ($firstOwned) {
                        $current->set($firstOwned->id);
                    }
                }
            }
        }

        return $next($request);
    }
}
