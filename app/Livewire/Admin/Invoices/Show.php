<?php

namespace App\Livewire\Admin\Invoices;

use App\Mail\InvoiceMail;
use App\Models\Invoice;
use App\Services\Billing\InvoiceCalculator;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Show extends Component
{
    public Invoice $invoice;

    public function mount(int $id): void
    {
        $this->invoice = Invoice::with('customer')->findOrFail($id);
    }

    public function recalc(InvoiceCalculator $calc): void
    {
        $calc->recalc($this->invoice->id);
        $this->invoice->refresh();
        session()->flash('success','Belopp beräknade.');
    }

    public function send(): void
    {
        $to = $this->invoice->customer?->billing_email ?: $this->invoice->customer?->contact_email;
        if (!$to) {
            session()->flash('error', 'Ingen mottagaradress finns på kunden.');
            return;
        }

        Mail::to($to)->send(new InvoiceMail($this->invoice));

        // markera som sent
        $this->invoice->update(['status' => 'sent', 'sent_at' => now()]);
        session()->flash('success', 'Faktura skickad.');
    }

    public function markPaid(): void
    {
        $this->invoice->update(['status' => 'paid', 'paid_at' => now()]);
        session()->flash('success','Faktura markerad som betald.');
    }

    public function render()
    {
        return view('livewire.admin.invoices.show');
    }
}
