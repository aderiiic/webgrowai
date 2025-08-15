<div class="max-w-3xl mx-auto py-10 space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Faktura #{{ $invoice->id }}</h1>
        <a href="{{ route('admin.invoices.index') }}" class="btn btn-sm">Tillbaka</a>
    </div>

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('error')) <div class="alert alert-error">{{ session('error') }}</div> @endif

    <div class="border rounded p-4 bg-white space-y-3">
        <div class="text-sm text-gray-600">Kund</div>
        <div class="font-medium">{{ $invoice->customer->name ?? ('#'.$invoice->customer_id) }}</div>

        <div class="grid md:grid-cols-2 gap-3">
            <div>
                <div class="text-sm text-gray-600">Period</div>
                <div>{{ $invoice->period }}</div>
            </div>
            <div>
                <div class="text-sm text-gray-600">Status</div>
                <div class="font-medium">{{ strtoupper($invoice->status) }}</div>
            </div>
        </div>

        <div class="grid md:grid-cols-3 gap-3">
            <div>
                <div class="text-sm text-gray-600">Planbelopp</div>
                <div>{{ number_format($invoice->plan_amount/100, 2, ',', ' ') }} kr</div>
            </div>
            <div>
                <div class="text-sm text-gray-600">Tillägg</div>
                <div>{{ number_format($invoice->addon_amount/100, 2, ',', ' ') }} kr</div>
            </div>
            <div>
                <div class="text-sm text-gray-600">Totalt</div>
                <div class="font-semibold">{{ number_format($invoice->total_amount/100, 2, ',', ' ') }} kr</div>
            </div>
        </div>

        <div class="flex gap-2">
            <button class="btn btn-sm" wire:click="recalc">Beräkna belopp</button>
            <button class="btn btn-sm" wire:click="send">Skicka via e‑post</button>
            @if(in_array($invoice->status, ['draft','sent']))
                <button class="btn btn-sm" wire:click="markPaid">Markera betald</button>
            @endif
        </div>

        @if(!empty($invoice->lines))
            <div class="pt-3">
                <div class="text-sm text-gray-600">Rader</div>
                <pre class="bg-gray-50 border rounded p-3 text-xs overflow-auto">{{ json_encode($invoice->lines, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}</pre>
            </div>
        @endif
    </div>
</div>
