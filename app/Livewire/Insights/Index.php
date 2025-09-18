<?php

namespace App\Livewire\Insights;

use App\Models\SiteInsight;
use App\Services\Insights\TrendsCollector;
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

    public function mount(CurrentCustomer $current)
    {
        $customer = $current->get();
        abort_unless($customer, 403);

        $this->siteId = $current->getSiteId();
        abort_unless($this->siteId, 404, 'Ingen aktiv hemsida vald');

        $this->loadInsights();
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

    public function generateInsights(): void
    {
        $this->loading = true;

        try {
            // Köa jobbet för att generera nya insights
            \App\Jobs\GenerateWeeklySiteInsightsJob::dispatch($this->siteId);

            session()->flash('success', 'Genererar nya insights... Uppdatera sidan om en minut för att se resultatet.');
        } catch (\Exception $e) {
            session()->flash('error', 'Kunde inte generera insights just nu. Försök igen senare.');
        } finally {
            $this->loading = false;
        }
    }

    public function createContentFromTopic(string $topic): \Illuminate\Http\RedirectResponse
    {
        // Redirect till AI-verktyget med förifyllt ämne
        $this->redirect(route('ai.compose', ['topic' => $topic]));
    }

    public function copyHashtags(): void
    {
        if (!$this->siteInsight || empty($this->siteInsight->recommended_hashtags)) {
            return;
        }

        $hashtags = implode(' ', $this->siteInsight->recommended_hashtags);

        // Vi använder JavaScript för att kopiera till clipboard
        $this->dispatch('copyToClipboard', text: $hashtags);

        session()->flash('success', 'Hashtags kopierade till clipboard!');
    }
}
