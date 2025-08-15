<?php

namespace App\Livewire\Admin\Plans;

use App\Models\Plan;
use App\Models\PlanFeature;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Edit extends Component
{
    public Plan $plan;
    public string $name = '';
    public int $price_monthly = 0;
    public int $price_yearly = 0;
    public bool $is_active = true;

    // Feature-fält
    public array $features = []; // key => ['is_enabled'=>bool, 'limit_value'=>string|null, 'id'=>int|null]

    public function mount(Plan $plan): void
    {
        $this->plan = $plan;
        $this->name = $plan->name;
        $this->price_monthly = (int) $plan->price_monthly;
        $this->price_yearly = (int) $plan->price_yearly;
        $this->is_active = (bool) $plan->is_active;

        $this->features = $plan->features()
            ->orderBy('key')
            ->get()
            ->mapWithKeys(fn(PlanFeature $f) => [
                $f->key => [
                    'id' => $f->id,
                    'is_enabled' => (bool) $f->is_enabled,
                    'limit_value' => $f->limit_value,
                ]
            ])->toArray();
    }

    public function savePlan(): void
    {
        $this->validate([
            'name' => 'required|string|max:100',
            'price_monthly' => 'required|integer|min:0',
            'price_yearly' => 'required|integer|min:0',
        ]);

        $this->plan->update([
            'name' => $this->name,
            'price_monthly' => $this->price_monthly,
            'price_yearly' => $this->price_yearly,
            'is_active' => $this->is_active,
        ]);

        session()->flash('success', 'Plan uppdaterad.');
    }

    public function addFeatureRow(): void
    {
        $this->features[''] = ['id' => null, 'is_enabled' => true, 'limit_value' => null];
    }

    public function saveFeatures(): void
    {
        foreach ($this->features as $key => $data) {
            $cleanKey = trim($key);
            if ($cleanKey === '') {
                // hoppa över tomma rader
                continue;
            }
            PlanFeature::updateOrCreate(
                ['id' => $data['id'] ?? null, 'plan_id' => $this->plan->id, 'key' => $cleanKey],
                [
                    'plan_id' => $this->plan->id,
                    'key' => $cleanKey,
                    'is_enabled' => (bool) ($data['is_enabled'] ?? false),
                    'limit_value' => $data['limit_value'] !== '' ? (string) $data['limit_value'] : null,
                ]
            );
        }

        // Rensa bort features som tagits bort i UI
        $existingKeys = collect($this->features)->keys()->map(fn($k) => trim($k))->filter()->values();
        $this->plan->features()->whereNotIn('key', $existingKeys)->delete();

        // Reload för att uppdatera ids
        $this->mount($this->plan->fresh('features'));

        session()->flash('success', 'Features sparade.');
    }

    public function deleteFeature(string $key): void
    {
        $cleanKey = trim($key);
        if ($cleanKey !== '') {
            $this->plan->features()->where('key', $cleanKey)->delete();
            unset($this->features[$key]);
            session()->flash('success', "Feature '{$cleanKey}' borttagen.");
        }
    }

    public function render()
    {
        return view('livewire.admin.plans.edit');
    }
}
