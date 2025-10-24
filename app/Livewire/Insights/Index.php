<?php

namespace App\Livewire\Insights;

use App\Jobs\GenerateWeeklySiteInsightsJob;
use App\Models\SiteInsight;
use App\Support\CurrentCustomer;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Index extends Component
{
    public ?SiteInsight $siteInsight = null;
    public string $weekLabel = '';
    public int $siteId;
    public bool $loading = false;

    public bool $polling = false;
    public ?string $lastGeneratedAt = null;
    public int $pollAttemptsLeft = 18; // ~3 min om vi pollar var 10e sekund

    public function mount(CurrentCustomer $current)
    {
        $customer = $current->get();
        abort_unless($customer, 403);

        $this->siteId = $current->getSiteId();
        abort_unless($this->siteId, 404, 'Ingen aktiv hemsida vald');

        $this->loadInsights();
        $this->lastGeneratedAt = $this->siteInsight?->generated_at?->toIso8601String();
    }

    public function render()
    {
        return view('livewire.insights.index');
    }

    public function loadInsights(): void
    {
        $weekStart = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $this->weekLabel = $weekStart->isoWeek() . '/' . $weekStart->year;

        $this->siteInsight = SiteInsight::where('site_id', $this->siteId)
            ->where('week_start', $weekStart->toDateString())
            ->first();
    }

    public function pollForCompletion(): void
    {
        if (!$this->polling) {
            return;
        }

        $this->loadInsights();

        $current = $this->siteInsight?->generated_at?->toIso8601String();
        if ($current && $current !== $this->lastGeneratedAt) {
            $this->polling = false;
            $this->loading = false;
            $this->lastGeneratedAt = $current;
            session()->flash('success', 'Klart! Dina insights har uppdaterats.');
            $this->dispatch('insights-ready'); // för ev. UI‑toast
            return;
        }

        $this->pollAttemptsLeft--;
        if ($this->pollAttemptsLeft <= 0) {
            $this->polling = false;
            $this->loading = false;
            session()->flash('error', 'Tog för lång tid. Försök uppdatera sidan om en stund.');
        }
    }

    public function generateInsights(bool $force = true): void
    {
        $this->loading = true;
        $this->polling = true;
        $this->pollAttemptsLeft = 18;

        try {
            $customer = app(\App\Support\CurrentCustomer::class)->get();
            app(\App\Services\Billing\QuotaGuard::class)->checkCreditsOrFail($customer, 50, 'credits');
        } catch (\Throwable $e) {
            $this->loading = false;
            $this->polling = false;
            session()->flash('error', $e->getMessage());
            return;
        }

        try {
            $weekStart = Carbon::now()->startOfWeek(Carbon::MONDAY)->toDateString();
            dispatch(new GenerateWeeklySiteInsightsJob($this->siteId, $weekStart, $force))->onQueue('ai');

            session()->flash('success', 'Genererar nya insights just nu… sidan uppdateras automatiskt när det är klart.');
            $this->dispatch('insights-queued'); // för UI‑toast
        } catch (\Exception $e) {
            $this->polling = false;
            session()->flash('error', 'Kunde inte generera insights just nu. Försök igen senare.');
        }
    }

    public function createContentFromTopic(string $topic)
    {
        // Säker redirect (Livewire v3): navigate=true för SPA‑känsla
        return $this->redirectRoute('ai.compose', ['topic' => $topic], navigate: true);
    }

    public function copyHashtags(): void
    {
        if (!$this->siteInsight || empty($this->siteInsight->recommended_hashtags)) {
            return;
        }

        $hashtags = implode(' ', $this->siteInsight->recommended_hashtags);
        $this->dispatch('copyToClipboard', text: $hashtags);
        session()->flash('success', 'Hashtags kopierade till clipboard!');
    }
}
