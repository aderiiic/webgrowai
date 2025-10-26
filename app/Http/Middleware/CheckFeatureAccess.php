<?php

namespace App\Http\Middleware;

use App\Services\Billing\FeatureGuard;
use App\Support\CurrentCustomer;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckFeatureAccess
{
    public function __construct(
        private CurrentCustomer $currentCustomer,
        private FeatureGuard $featureGuard
    ) {}

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $featureKey): Response
    {
        $customer = $this->currentCustomer->get();

        if (!$customer) {
            return redirect()->route('login')
                ->with('error', 'Du måste vara inloggad för att fortsätta.');
        }

        // Kontrollera om kunden har access till featuren
        if (!$this->featureGuard->canUseFeature($customer, $featureKey)) {
            // Visa "upgrade required" sida
            return redirect()->route('feature.locked', ['feature' => $featureKey])
                ->with('error', 'Denna funktion kräver uppgradering av ditt abonnemang.');
        }

        return $next($request);
    }
}
