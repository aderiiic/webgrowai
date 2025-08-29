<?php

namespace App\Policies;

use App\Models\ImageAsset;
use App\Models\User;

class ImageAssetPolicy
{
    public function viewAny(User $user): bool
    {
        // Admin: alltid OK. Annars krävs någon form av kundkontext.
        if (method_exists($user, 'isAdmin') && $user->isAdmin()) {
            return true;
        }
        $hasUserCustomer = !is_null($user->customer_id ?? null);
        $current = app(\App\Support\CurrentCustomer::class)->get();
        $hasCurrentCustomer = !is_null($current?->id);
        return $hasUserCustomer || $hasCurrentCustomer;
    }

    public function view(User $user, ImageAsset $asset): bool
    {
        // Admin: alltid OK
        if (method_exists($user, 'isAdmin') && $user->isAdmin()) {
            return true;
        }

        // Tillåt om användarens customer_id matchar
        if ((int)($user->customer_id ?? 0) === (int)$asset->customer_id) {
            return true;
        }

        // Tillåt om aktiv kund (CurrentCustomer) matchar
        $current = app(\App\Support\CurrentCustomer::class)->get();
        if ((int)($current?->id ?? 0) === (int)$asset->customer_id) {
            return true;
        }

        return false;
    }

    public function create(User $user): bool
    {
        if (method_exists($user, 'isAdmin') && $user->isAdmin()) {
            return true;
        }
        $hasUserCustomer = !is_null($user->customer_id ?? null);
        $current = app(\App\Support\CurrentCustomer::class)->get();
        $hasCurrentCustomer = !is_null($current?->id);
        return $hasUserCustomer || $hasCurrentCustomer;
    }

    public function delete(User $user, ImageAsset $asset): bool
    {
        if (method_exists($user, 'isAdmin') && $user->isAdmin()) {
            return true;
        }
        if ((int)($user->customer_id ?? 0) === (int)$asset->customer_id) {
            return true;
        }
        $current = app(\App\Support\CurrentCustomer::class)->get();
        return (int)($current?->id ?? 0) === (int)$asset->customer_id;
    }
}
