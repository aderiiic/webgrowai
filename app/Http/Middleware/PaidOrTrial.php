<?php

namespace App\Http\Middleware;

use App\Services\Billing\PlanService;
use Closure;
use Illuminate\Http\Request;

class PaidOrTrial
{
    public function __construct(private PlanService $plans) {}

    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if (!$user) return redirect()->route('login');

        $current = app(\App\Support\CurrentCustomer::class)->get();
        if (!$current) {
            return redirect()->route('onboarding')->with('success', 'Fortsätt onboarding först.');
        }

        if (!$this->plans->hasAccess($current)) {
            return redirect()->route('account.paused');
        }

        return $next($request);
    }
}
