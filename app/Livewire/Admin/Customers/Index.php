<?php

namespace App\Livewire\Admin\Customers;

use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    public string $q = '';
    public string $status = 'all'; // all|active|trial|paused|cancelled

    public function render()
    {
        $sub = DB::table('subscriptions as s')
            ->rightJoin('customers as c', 'c.id', '=', 's.customer_id')
            ->leftJoin('plans as p', 'p.id', '=', 's.plan_id')
            ->select('c.*', 's.status as sub_status', 's.trial_ends_at', 'p.name as plan_name', 'p.price_monthly')
            ->when($this->status !== 'all', fn($q) => $q->where('s.status', $this->status))
            ->when($this->q !== '', fn($q) => $q->where('c.name','like','%'.$this->q.'%'))
            ->orderBy('c.name');

        return view('livewire.admin.customers.index', [
            'rows' => $sub->paginate(20),
        ]);
    }
}
