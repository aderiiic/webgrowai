<div class="max-w-6xl mx-auto py-10 space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Fakturor</h1>
    </div>

    <div class="flex items-center gap-3">
        <input type="text" wire:model.live="q" class="input input-bordered input-sm w-64" placeholder="Sök kundnamn...">
        <select wire:model.live="status" class="select select-bordered select-sm w-40">
            <option value="all">Alla</option>
            <option value="draft">Draft</option>
            <option value="sent">Sent</option>
            <option value="paid">Paid</option>
            <option value="overdue">Overdue</option>
        </select>
    </div>

    <div class="border rounded bg-white">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50">
                <tr class="text-left">
                    <th class="px-4 py-2">Kund</th>
                    <th class="px-4 py-2">Period</th>
                    <th class="px-4 py-2">Belopp</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Åtgärder</th>
                </tr>
                </thead>
                <tbody>
                @foreach($rows as $inv)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $inv->customer_name }}</td>
                        <td class="px-4 py-2">{{ $inv->period }}</td>
                        <td class="px-4 py-2">{{ number_format($inv->total_amount / 100, 2, ',', ' ') }} kr</td>
                        <td class="px-4 py-2">{{ strtoupper($inv->status) }}</td>
                        <td class="px-4 py-2">
                            <a class="btn btn-sm" href="{{ route('admin.invoices.show', $inv->id) }}">Öppna</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3">
            {{ $rows->links() }}
        </div>
    </div>
</div>
