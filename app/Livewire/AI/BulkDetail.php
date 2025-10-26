<?php

namespace App\Livewire\AI;

use App\Models\BulkGeneration;
use App\Support\CurrentCustomer;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class BulkDetail extends Component
{
    public BulkGeneration $bulk;

    public function mount(int $id, CurrentCustomer $current): void
    {
        $customer = $current->get();
        abort_unless($customer, 403);

        $this->bulk = BulkGeneration::with(['contents' => function ($q) {
            $q->orderBy('batch_index');
        }])->findOrFail($id);

        abort_unless((int)$this->bulk->customer_id === (int)$customer->id, 403);
    }

    public function refreshData(): void
    {
        $this->bulk->refresh();
        $this->bulk->load(['contents' => function ($q) {
            $q->orderBy('batch_index');
        }]);
    }

    public function render(): View
    {
        $this->refreshData();

        return view('livewire.a-i.bulk-detail');
    }
}
