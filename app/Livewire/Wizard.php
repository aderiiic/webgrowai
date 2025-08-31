<?php

namespace App\Livewire;

use App\Models\Integration;
use App\Models\Site;
use App\Models\WpIntegration;
use App\Support\CurrentCustomer;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Wizard extends Component
{
    public int $step = 1;

    public string $site_name = '';
    public string $site_url = '';

    public string $provider = 'wordpress';
    public ?string $connectedProvider = null;

    public string $shopify_shop = '';

    public bool $integrationConnected = false;
    public bool $wpConnected = false;
    public bool $leadTrackerReady = false;
    public bool $socialConnected = false;
    public bool $mailchimpConnected = false;
    public bool $weeklyConfigured = false;

    public ?int $primarySiteId = null;

    public bool $sitesQuotaExceeded = false;
    public int $sitesUsed = 0;
    public int $sitesLimit = 1;

    public function mount(CurrentCustomer $current): void
    {
        $user = auth()->user();
        if ($user && isset($user->onboarding_step)) {
            $this->step = max(1, min(7, (int) $user->onboarding_step ?: 1));
        }

        // NYTT: tillåt explicit steg via ?step=3 i query (från Shopify-callback)
        $reqStep = (int) request()->query('step', 0);
        if ($reqStep >= 1 && $reqStep <= 7) {
            $this->step = $reqStep;
        }

        $this->refreshStatus($current);

        if ($this->primarySiteId && $this->step < 2) {
            $this->step = 2;
        }

        $this->persistStep();
    }

    public function refreshStatus(CurrentCustomer $current = null): void
    {
        $current ??= app(CurrentCustomer::class);
        $customer = $current->get();

        $this->sitesUsed  = (int) ($customer?->sites()->count() ?? 0);
        $this->sitesLimit = (int) ($customer->plan_max_sites ?? 1);
        $this->sitesQuotaExceeded = $this->sitesUsed >= $this->sitesLimit;

        $this->primarySiteId = $customer?->sites()->value('id');

        $integration = $this->primarySiteId
            ? Integration::where('site_id', $this->primarySiteId)->first()
            : null;

        $this->integrationConnected = (bool) $integration;
        $this->connectedProvider = $integration?->provider;

        if ($this->integrationConnected && $this->connectedProvider) {
            $this->provider = $this->connectedProvider;
        }

        $this->wpConnected = $this->primarySiteId
            ? WpIntegration::where('site_id', $this->primarySiteId)->exists()
            : false;

        $this->leadTrackerReady = $this->leadTrackerReady && (bool)$this->primarySiteId;
    }

    public function createSite(CurrentCustomer $current): void
    {
        if ($this->sitesQuotaExceeded) {
            $this->addError('site_name', "Du har nått din sajtkvot ({$this->sitesUsed}/{$this->sitesLimit}).");
            return;
        }

        $data = $this->validate([
            'site_name' => ['required', 'string', 'min:2', 'max:120'],
            'site_url'  => ['required', 'url', 'max:255'],
        ]);

        $normalizedUrl = rtrim($data['site_url'], '/');
        $customer = $current->get();

        $site = $customer->sites()->create([
            'name'       => $data['site_name'],
            'url'        => $normalizedUrl,
            'public_key' => (string) Str::uuid(),
        ]);

        $this->primarySiteId = $site->id;

        $this->step = 2;
        $this->persistStep();

        $this->resetErrorBag();
        $this->refreshStatus();
    }

    public function next(): void
    {
        if ($this->step === 3 && !$this->integrationConnected) {
            $this->addError('integrationConnected', 'Koppla din sajt (WordPress/Shopify/Custom) för att fortsätta.');
            return;
        }
        if ($this->step === 4 && !$this->leadTrackerReady) {
            $this->addError('leadTrackerReady', 'Bekräfta att lead-spårningen är installerad.');
            return;
        }
        if ($this->step === 7 && !$this->weeklyConfigured) {
            $this->addError('weeklyConfigured', 'Spara Weekly Digest-inställningarna eller hoppa över.');
            return;
        }

        $this->step = min($this->step + 1, 7);
        $this->persistStep();
    }

    public function skip(): void
    {
        if (in_array($this->step, [5, 6, 7], true)) {
            $this->step = min($this->step + 1, 7);
            $this->persistStep();
        }
    }

    public function prev(): void
    {
        $this->step = max($this->step - 1, 1);
        $this->persistStep();
    }

    public function goto(int $step): void
    {
        $this->step = max(1, min(7, $step));
        $this->persistStep();
    }

    protected function persistStep(): void
    {
        $user = auth()->user();
        if ($user && isset($user->onboarding_step)) {
            $user->onboarding_step = $this->step;
            $user->save();
        }
    }

    public function goConnect()
    {
        if (!$this->primarySiteId) return;
        $this->redirectRoute('sites.integrations.connect', ['site' => $this->primarySiteId]);
    }

    public function startShopifyConnect(): void
    {
        if (!$this->primarySiteId) return;

        $data = $this->validate([
            'shopify_shop' => ['required', 'string', 'regex:/^[a-zA-Z0-9][a-zA-Z0-9-]*\.myshopify\.com$/'],
        ], [
            'shopify_shop.required' => 'Ange din myshopify.com-domän.',
            'shopify_shop.regex'    => 'Ange domänen i formatet my-shop.myshopify.com.',
        ]);

        $url = route('integrations.shopify.embedded', [
            'shop' => $data['shopify_shop'],
            'site' => $this->primarySiteId,
        ]);

        $this->redirect($url, navigate: false);
    }

    public function providerLabel(): string
    {
        return match ($this->provider) {
            'shopify'   => 'Shopify',
            'custom'    => 'Custom',
            default     => 'WordPress',
        };
    }

    public function render(CurrentCustomer $current): View
    {
        $this->refreshStatus($current);

        return view('livewire.wizard', [
            'primarySiteId' => $this->primarySiteId,
        ]);
    }

    public function markSocialConnected(): void
    {
        $this->socialConnected = true;
        $this->persistStep();
    }

    public function markMailchimpConnected(): void
    {
        $this->mailchimpConnected = true;
        $this->persistStep();
    }

    public function markLeadTrackerReady(): void
    {
        $this->leadTrackerReady = true;
        $this->persistStep();
    }

    public function markWeeklyConfigured(): void
    {
        $this->weeklyConfigured = true;
        $this->persistStep();
    }

    public function goDashboard()
    {
        $this->completeOnboarding();
        return $this->redirectRoute('get-started');
    }

    public function complete()
    {
        $this->weeklyConfigured = true;
        $this->completeOnboarding();
        return $this->redirectRoute('get-started');
    }

    protected function completeOnboarding(): void
    {
        $user = auth()->user();
        if ($user && isset($user->onboarding_step)) {
            $user->onboarding_step = 8;
            $user->save();
        }
    }
}
