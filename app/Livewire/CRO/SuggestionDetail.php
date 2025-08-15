<?php

namespace App\Livewire\CRO;

use App\Jobs\ApplyConversionSuggestionJob;
use App\Models\ConversionSuggestion;
use App\Support\CurrentCustomer;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class SuggestionDetail extends Component
{
    public ConversionSuggestion $sug;

    public function mount(int $id, CurrentCustomer $current): void
    {
        $this->sug = ConversionSuggestion::with('site')->findOrFail($id);
        $customer = $current->get();
        abort_unless($customer && $customer->sites()->whereKey($this->sug->site_id)->exists(), 403);
    }

    public function apply(): void
    {
        if ($this->sug->status !== 'applied') {
            dispatch(new ApplyConversionSuggestionJob($this->sug->id))->onQueue('default');
            session()->flash('success', 'Ändringar köade för WP.');
        }
    }

    public function dismiss(): void
    {
        $this->sug->update(['status' => 'dismissed']);
        session()->flash('success', 'Förslaget avfärdat.');
    }

    public function render()
    {
        return view('livewire.cro.suggestion-detail', [
            's' => $this->sug->suggestions ?? [],
        ]);
    }
}
