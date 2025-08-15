<?php

namespace App\Livewire\Admin\Customers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\OveragePermission;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Show extends Component
{
    public Customer $customer;
    public array $rows = [];
    public array $plans = [];
    public ?int $planId = null;
    public string $status = 'active'; // trial|active|paused|cancelled
    public string $billing_cycle = 'monthly'; // monthly|annual
    public ?string $trial_ends_at = null;

    public bool $overageApproved = false;
    public ?string $overageNote = null;

    public function mount(int $id): void
    {
        $this->customer = Customer::findOrFail($id);
        $this->loadData();
    }

    private function loadData(): void
    {
        $period = now()->format('Y-m');

        $sub = DB::table('subscriptions')->where('customer_id', $this->customer->id)->orderByDesc('id')->first();
        $this->status = (string) ($sub->status ?? 'active');
        $this->billing_cycle = (string) ($sub->billing_cycle ?? 'monthly');
        $this->trial_ends_at = $sub?->trial_ends_at ? \Illuminate\Support\Carbon::parse($sub->trial_ends_at)->toDateString() : null;
        $this->planId = $sub?->plan_id;

        $this->plans = DB::table('plans')->where('is_active', true)->orderBy('price_monthly')->get()->map(fn($p) => [
            'id' => $p->id, 'name' => $p->name, 'price' => $p->price_monthly
        ])->toArray();

        // Hämta plan features
        $features = DB::table('plan_features')->where('plan_id', $this->planId)->where('is_enabled', true)->get();
        $rows = [];
        foreach ($features as $feat) {
            $key = $feat->key;
            $label = match ($key) {
                'ai.generate'   => 'AI‑genereringar',
                'ai.publish.wp' => 'WP‑publiceringar',
                'seo.audit'     => 'SEO‑audits',
                'leads.events'  => 'Lead events',
                'sites'         => 'Sajter',
                'users'         => 'Användare',
                default         => $key,
            };
            $quota = is_numeric($feat->limit_value) ? (int)$feat->limit_value : null;

            if (in_array($key, ['sites','users'])) {
                $used = $key === 'sites'
                    ? DB::table('sites')->where('customer_id', $this->customer->id)->count()
                    : DB::table('customer_user')->where('customer_id', $this->customer->id)->count();
            } else {
                $used = (int) (DB::table('usage_metrics')
                    ->where('customer_id', $this->customer->id)
                    ->where('period', $period)
                    ->where('metric_key', $key)
                    ->value('used_value') ?? 0);
            }

            $pct = ($quota && $quota > 0) ? min(100, (int) round(($used / $quota) * 100)) : 0;

            $rows[] = [
                'key' => $key, 'label' => $label, 'used' => $used, 'quota' => $quota, 'pct' => $pct,
            ];
        }
        $this->rows = $rows;

        $ov = OveragePermission::where('customer_id', $this->customer->id)->where('period', $period)->first();
        $this->overageApproved = (bool) ($ov?->approved ?? false);
        $this->overageNote = $ov?->note;
    }

    public function savePlan(): void
    {
        $sub = DB::table('subscriptions')->where('customer_id', $this->customer->id)->orderByDesc('id')->first();

        if ($sub) {
            DB::table('subscriptions')->where('id', $sub->id)->update([
                'plan_id' => $this->planId,
                'status' => $this->status,
                'billing_cycle' => $this->billing_cycle,
                'trial_ends_at' => $this->trial_ends_at ? \Illuminate\Support\Carbon::parse($this->trial_ends_at) : null,
                'updated_at' => now(),
            ]);
        } else {
            DB::table('subscriptions')->insert([
                'customer_id' => $this->customer->id,
                'plan_id'     => $this->planId,
                'status'      => $this->status,
                'billing_cycle' => $this->billing_cycle,
                'trial_ends_at' => $this->trial_ends_at ? \Illuminate\Support\Carbon::parse($this->trial_ends_at) : null,
                'current_period_start' => now()->startOfMonth(),
                'current_period_end'   => now()->endOfMonth(),
                'created_at' => now(), 'updated_at' => now(),
            ]);
        }

        session()->flash('success', 'Plan uppdaterad.');
        $this->loadData();
    }

    public function setTrialGrowth(): void
    {
        $growth = DB::table('plans')->where('name','Growth')->first();
        abort_if(!$growth, 400, 'Growth-plan saknas.');

        DB::table('subscriptions')->updateOrInsert(
            ['customer_id' => $this->customer->id],
            [
                'plan_id' => $growth->id,
                'status' => 'trial',
                'trial_ends_at' => now()->addDays(14),
                'billing_cycle' => 'monthly',
                'current_period_start' => now()->startOfMonth(),
                'current_period_end'   => now()->endOfMonth(),
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
        session()->flash('success', 'Trial (Growth, 14 dagar) aktiverad.');
        $this->loadData();
    }

    public function toggleOverageApproval(): void
    {
        $period = now()->format('Y-m');
        $ov = OveragePermission::firstOrNew([
            'customer_id' => $this->customer->id,
            'period' => $period,
        ]);
        $ov->approved = !$ov->approved;
        $ov->approved_at = $ov->approved ? now() : null;
        if ($this->overageNote) $ov->note = $this->overageNote;
        $ov->save();

        $this->overageApproved = $ov->approved;
        session()->flash('success', $ov->approved ? 'Övertramp godkänt för perioden.' : 'Övertramp ogiltigförklarat.');
    }

    public function createDraftInvoice(): void
    {
        $period = now()->format('Y-m');
        $exists = Invoice::where('customer_id', $this->customer->id)->where('period', $period)->exists();
        if ($exists) {
            session()->flash('success', 'Faktura finns redan för perioden.');
            return;
        }

        // MVP: endast tomt draft, detaljlinor fylls i ett senare steg
        Invoice::create([
            'customer_id' => $this->customer->id,
            'period' => $period,
            'plan_amount' => 0,
            'addon_amount' => 0,
            'total_amount' => 0,
            'currency' => 'SEK',
            'status' => 'draft',
        ]);
        session()->flash('success', 'Fakturautkast skapat.');
    }

    public function render()
    {
        return view('livewire.admin.customers.show');
    }
}
