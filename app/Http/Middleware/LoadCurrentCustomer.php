<?php

namespace App\Http\Middleware;

use App\Models\Customer;
use App\Support\CurrentCustomer;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LoadCurrentCustomer
{
    public function __construct(private CurrentCustomer $current) {}

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (!$user) {
            return $next($request);
        }

        // Tillåt byte via ?customer=ID (praktiskt för länkar)
        if ($request->has('customer')) {
            $requestedId = (int) $request->query('customer');
            if ($requestedId > 0) {
                if ($user->isAdmin() || $user->customers()->whereKey($requestedId)->exists()) {
                    $this->current->set($requestedId);
                }
            }
        }

        $active = $this->current->get();

        // Om ingen vald – försök sätta default för kundanvändare
        if (!$active) {
            $default = $this->current->resolveDefaultForUser();
            if ($default) {
                $this->current->set($default->id);
            }
        } else {
            // Säkerställ att icke-admin fortfarande har access
            if (!$user->isAdmin() && !$user->customers()->whereKey($active->id)->exists()) {
                // Tappa bort olovlig kund och välj om
                $this->current->clear();
                $default = $this->current->resolveDefaultForUser();
                if ($default) {
                    $this->current->set($default->id);
                }
            }
        }

        return $next($request);
    }
}
