<?php

namespace App\Livewire\SEO;

use App\Jobs\ApplyKeywordSuggestionJob;
use App\Models\Integration;
use App\Models\KeywordSuggestion;
use App\Support\CurrentCustomer;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class KeywordSuggestionDetail extends Component
{
    public KeywordSuggestion $sug;
    public bool $showWpApply = false;

    public function mount(int $id, CurrentCustomer $current): void
    {
        $this->sug = KeywordSuggestion::with('site')->findOrFail($id);

        $customer = $current->get();
        abort_unless($customer && $customer->sites()->whereKey($this->sug->site_id)->exists(), 403);

        // Visa "Applicera till WordPress" endast om aktiva sajten har WP-integration
        $siteId = $current->getSiteId();
        if ($siteId) {
            $this->showWpApply = Integration::where('site_id', $siteId)
                ->where('provider', 'wordpress')
                ->exists();
        }
    }

    public function apply(): void
    {
        if ($this->sug->status !== 'applied') {
            dispatch(new ApplyKeywordSuggestionJob($this->sug->id))->onQueue('default');
            session()->flash('success', 'Meta/titel köade för uppdatering i WP.');
        }
    }

    public function dismiss(): void
    {
        $this->sug->update(['status' => 'dismissed']);
        session()->flash('success', 'Förslaget avfärdat.');
    }

    public function render()
    {
        return view('livewire.seo.keyword-suggestion-detail', [
            'current'   => $this->sug->current ?? [],
            'suggested' => $this->sug->suggested ?? [],
        ]);
    }
}
