<?php

namespace App\Livewire\CRO;

use App\Models\ConversionSuggestion;
use App\Support\CurrentCustomer;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class SuggestionDetail extends Component
{
    public ConversionSuggestion $sug;
    public bool $showManualApplyHint = true;

    public function mount(int $id, CurrentCustomer $current): void
    {
        $this->sug = ConversionSuggestion::with('site')->findOrFail($id);
        $customer = $current->get();
        abort_unless($customer && $customer->sites()->whereKey($this->sug->site_id)->exists(), 403);
    }

    // Ingen auto-apply, endast statusmarkering
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
        $context = $this->sug->site?->aiContextSummary() ?: null;

        // Skydda mot tomma/inkorrekta strukturer
        $s = is_array($this->sug->suggestions) ? $this->sug->suggestions : [];
        $title = is_array(($s['title'] ?? null)) ? $s['title'] : [];
        $cta   = is_array(($s['cta'] ?? null)) ? $s['cta'] : [];
        $form  = is_array(($s['form'] ?? null)) ? $s['form'] : [];

        $insights = is_array($this->sug->insights) ? array_values(array_filter($this->sug->insights, fn($i) => is_string($i) && $i !== '')) : [];

        return view('livewire.cro.suggestion-detail', [
            's'       => ['title' => $title, 'cta' => $cta, 'form' => $form],
            'context' => $context,
            'insights'=> $insights,
            'showManualApplyHint' => $this->showManualApplyHint,
            'sug' => $this->sug,
        ]);
    }
}
