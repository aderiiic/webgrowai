<?php

namespace App\Livewire\Admin\Invoices;

use App\Models\Invoice;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    public string $status = 'all'; // all|draft|sent|paid|overdue
    public string $q = ''; // sök kundnamn (enkel join i vy)

    public function render()
    {
        $q = Invoice::query()
            ->join('customers','customers.id','=','invoices.customer_id')
            ->select('invoices.*','customers.name as customer_name')
            ->when($this->status !== 'all', fn($x) => $x->where('invoices.status',$this->status))
            ->when($this->q !== '', fn($x) => $x->where('customers.name','like','%'.$this->q.'%'))
            ->latest('invoices.created_at')
            ->paginate(20);

        return view('livewire.admin.invoices.index', ['rows' => $q]);
    }
}
