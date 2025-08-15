<?php

namespace App\Services\Billing;

use App\Models\Invoice;
use Illuminate\Support\Facades\View;

class InvoicePdf
{
    /**
     * Rendera faktura till binärt innehåll och content-type.
     * Om barryvdh/laravel-dompdf finns används PDF, annars HTML som fallback.
     *
     * @return array{content: string, contentType: string, filename: string}
     */
    public function render(Invoice $invoice): array
    {
        $html = View::make('invoices.pdf', ['invoice' => $invoice->load('customer')])->render();
        $filename = 'invoice-'.$invoice->id.'.pdf';

        // Försök använda Dompdf om den finns installerad
        if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html);
            return [
                'content' => $pdf->output(),
                'contentType' => 'application/pdf',
                'filename' => $filename,
            ];
        }

        // Fallback: skicka HTML
        return [
            'content' => $html,
            'contentType' => 'text/html; charset=UTF-8',
            'filename' => 'invoice-'.$invoice->id.'.html',
        ];
    }
}
