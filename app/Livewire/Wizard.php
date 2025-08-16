<?php

namespace App\Livewire;

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

    public bool $wpConnected = false;
    public bool $leadTrackerReady = false;
    public bool $socialConnected = false;
    public bool $mailchimpConnected = false;
    public bool $weeklyConfigured = false;

    public ?int $primarySiteId = null;

    // NYTT: kvotstatus
    public bool $sitesQuotaExceeded = false;
    public int $sitesUsed = 0;
    public int $sitesLimit = 1;

    public function mount(CurrentCustomer $current): void
    {
        // Läs senast sparade onboarding-steg från användaren
        $user = auth()->user();
        if ($user && isset($user->onboarding_step)) {
            $this->step = max(1, min(7, (int) $user->onboarding_step ?: 1));
        }

        $this->refreshStatus($current);

        // Om användaren redan har en sajt men hamnar på lägre steg, knuffa fram till WP-steget
        if ($this->primarySiteId && $this->step < 2) {
            $this->step = 2;
            $this->persistStep();
        }
    }

    public function refreshStatus(CurrentCustomer $current = null): void
    {
        $current ??= app(CurrentCustomer::class);
        $customer = $current->get();

        // Kvotberäkning (anpassa efter ert plansystem)
        $this->sitesUsed  = (int) ($customer?->sites()->count() ?? 0);
        $this->sitesLimit = (int) ($customer->plan_max_sites ?? 1); // t.ex. kolumn på kund/plan
        $this->sitesQuotaExceeded = $this->sitesUsed >= $this->sitesLimit;

        $this->primarySiteId = $customer?->sites()->value('id');

        $this->wpConnected = $this->primarySiteId
            ? WpIntegration::where('site_id', $this->primarySiteId)->exists()
            : false;

        $this->leadTrackerReady = $this->leadTrackerReady && (bool)$this->primarySiteId;
    }

    public function createSite(CurrentCustomer $current): void
    {
        // Stoppa direkt om kvoten är slut
        if ($this->sitesQuotaExceeded) {
            $this->addError('site_name', "Du har nått din sajtkvot ({$this->sitesUsed}/{$this->sitesLimit}).");
            return;
        }

        $data = $this->validate([
            'site_name' => ['required', 'string', 'min:2', 'max:120'],
            'site_url'  => ['required', 'url', 'max:255'],
        ], [
            'site_name.required' => 'Ange ett namn för sajten.',
            'site_url.required'  => 'Ange en giltig URL.',
            'site_url.url'       => 'URL måste vara giltig (t.ex. https://example.com).',
        ]);

        $normalizedUrl = rtrim($data['site_url'], '/');

        $customer = $current->get();

        /** @var Site $site */
        $site = $customer->sites()->create([
            'name'       => $data['site_name'],
            'url'        => $normalizedUrl,
            'public_key' => (string) Str::uuid(),
        ]);

        $this->primarySiteId = $site->id;

        // Gå vidare och spara steg
        $this->step = 2;
        $this->persistStep();

        $this->resetErrorBag();
        $this->refreshStatus();
    }

    public function markLeadTrackerReady(): void
    {
        $this->leadTrackerReady = true;
    }

    public function next(): void
    {
        if ($this->step === 3 && !$this->wpConnected) {
            $this->addError('wpConnected', 'Koppla din WordPress-sajt för att fortsätta.');
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

    // Markera Mailchimp som klart (valfritt steg)
    public function markMailchimpConnected(): void
    {
        $this->mailchimpConnected = true;

        $this->persistStep();
    }

    public function markWeeklyConfigured(): void
    {
        $this->weeklyConfigured = true;
        $this->persistStep();
    }

    // NYTT: hoppa över sista steget och gå till Dashboard
    public function goDashboard()
    {
        $this->completeOnboarding();
        return $this->redirectRoute('dashboard');
    }

    // NYTT: slutför (markera klart) och gå till Dashboard
    public function complete()
    {
        $this->weeklyConfigured = true;
        $this->completeOnboarding();
        return $this->redirectRoute('dashboard');
    }

    protected function completeOnboarding(): void
    {
        $user = auth()->user();
        if ($user && isset($user->onboarding_step)) {
            $user->onboarding_step = 8; // färdig
            $user->save();
        }
    }

}
