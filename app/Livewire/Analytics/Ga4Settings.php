<?php
// app/Livewire/Analytics/Ga4Settings.php

namespace App\Livewire\Analytics;

use App\Models\Ga4Integration;
use App\Support\CurrentCustomer;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class Ga4Settings extends Component
{
    use WithFileUploads;

    public ?int $siteId = null;
    public string $propertyId = '';
    public ?string $serviceJsonText = null;
    public $serviceJsonFile = null;

    public function mount(CurrentCustomer $current): void
    {
        $customer = $current->get();
        abort_unless($customer, 403);

        $sid = (int) ($current->getSiteId() ?: 0);
        abort_unless($sid > 0 && $customer->sites()->whereKey($sid)->exists(), 404, 'Välj en sajt i topbaren.');

        $this->siteId = $sid;

        $ga4 = Ga4Integration::where('site_id', $sid)->first();
        if ($ga4) {
            $this->propertyId = (string) $ga4->property_id;
        }
    }

    public function save(): void
    {
        $this->validate([
            'propertyId' => ['required','string','max:120'],
            'serviceJsonText' => [Rule::requiredIf(!$this->serviceJsonFile), 'nullable', 'string'],
            'serviceJsonFile' => [Rule::requiredIf(!$this->serviceJsonText), 'nullable', 'file', 'mimes:json', 'max:1024'],
        ]);

        $json = $this->serviceJsonText ?: ($this->serviceJsonFile ? file_get_contents($this->serviceJsonFile->getRealPath()) : null);

        $ga4 = Ga4Integration::updateOrCreate(
            ['site_id' => $this->siteId],
            [
                'property_id' => $this->propertyId,
                // Lagra krypterat
                'service_account_json' => $json ? encrypt($json) : \DB::raw('service_account_json'),
                'status' => 'connected',
            ]
        );

        session()->flash('success', 'GA4 sparad.');
    }

    public function render()
    {
        return view('livewire.analytics.ga4-settings', [
            'siteId' => $this->siteId,
        ]);
    }
}
