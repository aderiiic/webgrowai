<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureOnboardingCompleted
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user && $user->onboarding_step < 3 && !$request->routeIs('onboarding')) {
            return redirect()->route('onboarding');
        }
        return $next($request);
    }
}
