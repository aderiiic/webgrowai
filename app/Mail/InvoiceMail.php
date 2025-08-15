<?php

namespace App\Mail;

use App\Models\Invoice;
use App\Services\Billing\InvoicePdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Invoice $invoice) {}

    public function build(InvoicePdf $pdf)
    {
        $this->subject('Faktura '.$this->invoice->period.' – WebGrow AI');
        $this->view('emails.invoice', ['invoice' => $this->invoice->load('customer')]);

        // Bifoga PDF (eller HTML fallback)
        $doc = $pdf->render($this->invoice);
        return $this->attachData($doc['content'], $doc['filename'], ['mime' => $doc['contentType']]);
    }
}
