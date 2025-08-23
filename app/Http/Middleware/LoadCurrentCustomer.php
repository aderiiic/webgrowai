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

        if (!$current->get() && $user) {
            if (method_exists($user, 'isAdmin') && $user->isAdmin()) {
                $first = Customer::orderBy('id')->first();
                if ($first) {
                    $current->set($first->id);
                }
            } else {
                if (method_exists($user, 'customers')) {
                    $firstOwned = $user->customers()->orderBy('customers.id')->first();
                    if ($firstOwned) {
                        $current->set($firstOwned->id);
                    }
                }
            }
        }

        // Säkerställ att aktiv site är konsistent med aktiv kund
        $customer = $current->get();
        if ($customer) {
            $siteId = $current->getSiteId();

            $siteQuery = $customer->sites()->orderBy('sites.id');

            // Om ingen site vald eller den inte tillhör kunden → välj första
            if (!$siteId || !$siteQuery->clone()->whereKey($siteId)->exists()) {
                $firstSiteId = $siteQuery->value('id');
                if ($firstSiteId) {
                    $current->setSiteId((int)$firstSiteId);
                } else {
                    // Ingen site för kunden – rensa ev. gammalt värde
                    $current->clearSite();
                }
            }
        } else {
            $current->clearSite();
        }

        return $next($request);
    }
}
