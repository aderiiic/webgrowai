<?php

namespace App\Livewire\SEO;

use App\Models\KeywordSuggestion;
use App\Support\CurrentCustomer;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class KeywordSuggestionDetail extends Component
{
    public KeywordSuggestion $sug;
    // Ta bort automatisk WP-apply från UI
    public bool $showManualApplyHint = true;

    public function mount(int $id, CurrentCustomer $current): void
    {
        $this->sug = KeywordSuggestion::with('site')->findOrFail($id);

        $customer = $current->get();
        abort_unless($customer && $customer->sites()->whereKey($this->sug->site_id)->exists(), 403);
    }

    public function dismiss(): void
    {
        $this->sug->update(['status' => 'dismissed']);
        session()->flash('success', 'Förslaget avfärdat.');
    }

    public function markApplied(): void
    {
        $this->sug->update(['status' => 'applied', 'applied_at' => now()]);
        session()->flash('success', 'Markerad som tillämpad.');
    }

    public function render()
    {
        // Härleda Yoast-meta om nuvarande data innehåller yoast_title/yoast_description
        $current   = is_array($this->sug->current) ? $this->sug->current : [];
        $suggested = is_array($this->sug->suggested) ? $this->sug->suggested : [];

        $yoast = [
            'title'       => (isset($current['yoast_title']) && is_string($current['yoast_title'])) ? $current['yoast_title'] : null,
            'description' => (isset($current['yoast_description']) && is_string($current['yoast_description'])) ? $current['yoast_description'] : null,
        ];

        return view('livewire.seo.keyword-suggestion-detail', [
            'current'   => $current,
            'suggested' => $suggested,
            'yoast'     => $yoast,
            'insights'  => is_array($this->sug->insights) ? $this->sug->insights : [],
            'showManualApplyHint' => $this->showManualApplyHint,
            'sug' => $this->sug,
        ]);
    }
}
