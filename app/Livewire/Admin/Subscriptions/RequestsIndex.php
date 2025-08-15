<?php

namespace App\Livewire\Admin\Subscriptions;

use App\Services\Billing\SubscriptionManager;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class RequestsIndex extends Component
{
    use WithPagination;

    public string $status = 'pending'; // pending|approved|rejected|all
    public string $q = ''; // sök kundnamn

    public function approve(int $id, SubscriptionManager $manager): void
    {
        $req = DB::table('subscription_change_requests')->where('id', $id)->first();
        abort_unless($req && $req->status === 'pending', 400);

        // Sätt plan på subscription
        $manager->applyPlanChange((int)$req->customer_id, (int)$req->desired_plan_id, $req->billing_cycle ?: 'monthly');

        // Markera begäran approved
        DB::table('subscription_change_requests')->where('id', $id)->update([
            'status' => 'approved',
            'updated_at' => now(),
        ]);

        session()->flash('success', 'Planändring godkänd och aktiverad.');
        $this->resetPage();
    }

    public function reject(int $id): void
    {
        $req = DB::table('subscription_change_requests')->where('id', $id)->first();
        abort_unless($req && $req->status === 'pending', 400);

        DB::table('subscription_change_requests')->where('id', $id)->update([
            'status' => 'rejected',
            'updated_at' => now(),
        ]);

        session()->flash('success', 'Begäran avvisad.');
        $this->resetPage();
    }

    public function render()
    {
        $query = DB::table('subscription_change_requests as r')
            ->join('customers as c', 'c.id', '=', 'r.customer_id')
            ->join('plans as p', 'p.id', '=', 'r.desired_plan_id')
            ->select('r.*', 'c.name as customer_name', 'p.name as plan_name')
            ->when($this->status !== 'all', fn($q) => $q->where('r.status', $this->status))
            ->when($this->q !== '', fn($q) => $q->where('c.name','like','%'.$this->q.'%'))
            ->latest('r.created_at');

        return view('livewire.admin.subscriptions.requests-index', [
            'rows' => $query->paginate(20),
        ]);
    }
}
