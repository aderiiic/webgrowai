<?php

namespace App\Livewire\Account;

use App\Models\Customer;
use App\Models\OveragePermission;
use App\Services\Billing\PlanService;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Usage extends Component
{
    public array $rows = [];
    public ?Customer $customer = null;
    public bool $overageRequested = false;
    public ?string $overageNote = null;

    // Plan/trial-badge
    public ?string $planName = null;
    public ?string $planStatus = null; // active|trial|paused|cancelled
    public ?int $trialDaysLeft = null;

    private array $featureMap = [
        'ai.generate'   => 'AI‑genereringar',
        'ai.publish.wp' => 'WP‑publiceringar',
        'seo.audit'     => 'SEO‑audits',
        'leads.events'  => 'Lead events',
    ];

    public function mount(PlanService $plans): void
    {
        $this->customer = app(\App\Support\CurrentCustomer::class)->get();
        abort_unless($this->customer, 403);

        // Plan och trial-info
        $sub = $plans->getSubscription($this->customer);
        $this->planStatus = $sub->status ?? null;
        $this->planName = null;
        if ($sub?->plan_id) {
            $this->planName = (string) DB::table('plans')->where('id', $sub->plan_id)->value('name');
        }
        if ($sub?->status === 'trial' && $sub?->trial_ends_at) {
            $ends = \Illuminate\Support\Carbon::parse($sub->trial_ends_at);
            $this->trialDaysLeft = now()->lt($ends) ? now()->diffInDays($ends) : 0;
        } else {
            $this->trialDaysLeft = null;
        }

        $period = now()->format('Y-m');
        $rows = [];

        foreach ($this->featureMap as $key => $label) {
            $quota = $plans->getQuota($this->customer, $key);
            if ($quota === null) continue;

            $used = (int) (DB::table('usage_metrics')
                ->where('customer_id', $this->customer->id)
                ->where('period', $period)
                ->where('metric_key', $key)
                ->value('used_value') ?? 0);

            $pct = $quota > 0 ? min(100, (int) round(($used / $quota) * 100)) : 0;

            $rows[] = [
                'key'   => $key,
                'label' => $label,
                'used'  => $used,
                'quota' => $quota,
                'pct'   => $pct,
                'warn'  => $pct >= 80 && $pct < 100,
                'stop'  => $pct >= 100,
            ];
        }

        // sites/users
        $sitesQuota = $plans->getQuota($this->customer, 'sites');
        if ($sitesQuota !== null) {
            $count = DB::table('sites')->where('customer_id', $this->customer->id)->count();
            $pct = $sitesQuota > 0 ? min(100, (int) round(($count / $sitesQuota) * 100)) : 0;
            $rows[] = [
                'key'   => 'sites',
                'label' => 'Sajter',
                'used'  => $count,
                'quota' => $sitesQuota,
                'pct'   => $pct,
                'warn'  => $pct >= 80 && $pct < 100,
                'stop'  => $pct >= 100,
            ];
        }

        $usersQuota = $plans->getQuota($this->customer, 'users');
        if ($usersQuota !== null) {
            $count = DB::table('customer_user')->where('customer_id', $this->customer->id)->count();
            $pct = $usersQuota > 0 ? min(100, (int) round(($count / $usersQuota) * 100)) : 0;
            $rows[] = [
                'key'   => 'users',
                'label' => 'Användare',
                'used'  => $count,
                'quota' => $usersQuota,
                'pct'   => $pct,
                'warn'  => $pct >= 80 && $pct < 100,
                'stop'  => $pct >= 100,
            ];
        }

        $this->rows = $rows;

        $ov = OveragePermission::where('customer_id', $this->customer->id)
            ->where('period', $period)->first();
        $this->overageRequested = (bool) $ov;
    }

    public function requestOverage(): void
    {
        $period = now()->format('Y-m');
        $exists = OveragePermission::where('customer_id', $this->customer->id)->where('period', $period)->exists();
        if ($exists) {
            $this->dispatch('toast', type: 'info', message: 'Begäran finns redan för perioden.');
            return;
        }
        OveragePermission::create([
            'customer_id' => $this->customer->id,
            'period'      => $period,
            'approved'    => false,
            'note'        => $this->overageNote,
        ]);
        $this->overageRequested = true;
        session()->flash('success', 'Begäran om extraanvändning skickad. Vi återkommer inom kort.');
    }

    public function render()
    {
        return view('livewire.account.usage');
    }
}
