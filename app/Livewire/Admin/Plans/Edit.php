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

    // NYTT: Stripe Price‑ID för månads/års‑pris
    public ?string $stripe_price_monthly = null;
    public ?string $stripe_price_yearly  = null;

    // Feature-fält
    public array $features = []; // key => ['is_enabled'=>bool, 'limit_value'=>string|null, 'id'=>int|null]

    public string $new_feature_key = '';
    public bool $new_feature_enabled = true;
    public ?string $new_feature_limit = null;

    public function mount(Plan $plan): void
    {
        $this->plan = $plan;
        $this->name = $plan->name;
        $this->price_monthly = (int) $plan->price_monthly;
        $this->price_yearly = (int) $plan->price_yearly;
        $this->is_active = (bool) $plan->is_active;

        // NYTT: hämta Stripe Price‑ID
        $this->stripe_price_monthly = $plan->stripe_price_monthly;
        $this->stripe_price_yearly  = $plan->stripe_price_yearly;

        $this->features = $plan->features()
            ->orderBy('key')
            ->get()
            ->mapWithKeys(function (PlanFeature $f) {
                $val = $f->limit_value;
                // behåll 0 som "0", annars null
                $normalized = ($val === null || $val === '') ? null : (string)$val;
                return [
                    $f->key => [
                        'id' => $f->id,
                        'is_enabled' => (bool) $f->is_enabled,
                        'limit_value' => $normalized,
                    ],
                ];
            })->toArray();
    }

    public function savePlan(): void
    {
        $this->validate([
            'name' => 'required|string|max:100',
            'price_monthly' => 'required|integer|min:0',
            'price_yearly' => 'required|integer|min:0',
            'stripe_price_monthly' => 'nullable|string|max:255',
            'stripe_price_yearly'  => 'nullable|string|max:255',
        ]);

        $this->plan->update([
            'name' => $this->name,
            'price_monthly' => $this->price_monthly,
            'price_yearly' => $this->price_yearly,
            'is_active' => $this->is_active,
            // NYTT: spara Stripe Price‑ID
            'stripe_price_monthly' => $this->stripe_price_monthly ?: null,
            'stripe_price_yearly'  => $this->stripe_price_yearly ?: null,
        ]);

        session()->flash('success', 'Plan uppdaterad.');
    }

    public function saveFeatures(): void
    {
        foreach ($this->features as $key => $data) {
            $cleanKey = trim($key);
            if ($cleanKey === '') {
                continue;
            }
            PlanFeature::updateOrCreate(
                ['id' => $data['id'] ?? null, 'plan_id' => $this->plan->id, 'key' => $cleanKey],
                [
                    'is_enabled' => (bool) ($data['is_enabled'] ?? false),
                    'limit_value' => isset($data['limit_value']) && $data['limit_value'] !== '' ? (string) $data['limit_value'] : null,
                ]
            );
        }

        $existingKeys = collect(array_keys($this->features))
            ->map(fn($k) => trim((string)$k))
            ->filter()
            ->values();

        $this->plan->features()->whereNotIn('key', $existingKeys)->delete();

        $this->mount($this->plan->fresh('features'));

        session()->flash('success', 'Features sparade.');
    }

    public function addFeatureRow(): void
    {
        $this->validate([
            'new_feature_key' => 'required|string|max:190',
            'new_feature_limit' => 'nullable|string|max:190',
        ]);

        $key = trim($this->new_feature_key);
        if ($key === '') return;

        $this->features[$key] = [
            'id' => null,
            'is_enabled' => (bool) $this->new_feature_enabled,
            'limit_value' => $this->new_feature_limit !== '' ? (string) $this->new_feature_limit : null,
        ];

        $this->new_feature_key = '';
        $this->new_feature_enabled = true;
        $this->new_feature_limit = null;
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
