<?php

namespace App\Support;
use App\Models\Customer;
use App\Models\Site;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CurrentCustomer
{
    const SESSION_KEY = 'current_customer_id';
    const SITE_SESSION_KEY = 'current_site_id';

    public function get(): ?Customer
    {
        $id = Session::get(self::SESSION_KEY);
        if (!$id) {
            return null;
        }
        return Customer::find($id);
    }

    public function set(int $customerId): void
    {
        Session::put(self::SESSION_KEY, $customerId);
        Session::forget(self::SITE_SESSION_KEY);
    }

    public function clear(): void
    {
        Session::forget(self::SESSION_KEY);
        Session::forget(self::SITE_SESSION_KEY);
    }

    public function getSiteId(): ?int
    {
        $sid = Session::get(self::SITE_SESSION_KEY);
        return $sid ? (int)$sid : null;
    }

    public function setSiteId(int $siteId): void
    {
        Session::put(self::SITE_SESSION_KEY, $siteId);
    }

    public function getSite(): ?Site
    {
        $sid = $this->getSiteId();
        return $sid ? Site::find($sid) : null;
    }

    public function clearSite(): void
    {
        Session::forget(self::SITE_SESSION_KEY);
    }

    public function resolveDefaultForUser(): ?Customer
    {
        $user = Auth::user();
        if (!$user) return null;

        // Admin: försök behålla vald kund; annars ingen default förrän man väljer
        if ($user->isAdmin()) {
            return $this->get();
        }

        // Kund: välj första tillhörande kund om ingen satt
        $existing = $this->get();
        if ($existing && $user->customers()->whereKey($existing->id)->exists()) {
            return $existing;
        }

        return $user->customers()->orderBy('id')->first();
    }
}
