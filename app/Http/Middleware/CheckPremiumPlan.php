<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\Billing\PlanService;
use App\Support\CurrentCustomer;

class CheckPremiumPlan
{
    public function __construct(
        private PlanService $planService,
        private CurrentCustomer $currentCustomer
    ) {
    }

    public function handle(Request $request, Closure $next)
    {
        // Hämta aktuell kund via CurrentCustomer service
        $customer = $this->currentCustomer->resolveDefaultForUser();

        if (!$customer) {
            return redirect()->route('account.paused');
        }

        $subscription = $this->planService->getSubscription($customer);
        $planId = $this->planService->getPlanId($subscription);

        // Kontrollera om användaren har Premium-åtkomst (plan 2 eller 3)
        if (!$planId || !in_array($planId, [2, 3])) {
            return redirect()->route('account.paused');
        }

        return $next($request);
    }
}
