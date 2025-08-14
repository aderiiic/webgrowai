<div class="max-w-6xl mx-auto py-10 space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Usage</h1>
        <button class="btn btn-sm" wire:click="exportCsv">Exportera CSV</button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm text-gray-600">Kund</label>
            <select wire:model="customerId" class="select select-bordered w-full">
                <option value="">Alla</option>
                @foreach($customers as $c)
                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm text-gray-600">Period (YYYY-MM)</label>
            <input type="month" wire:model="period" class="input input-bordered w-full">
        </div>

        <div>
            <label class="block text-sm text-gray-600">Nyckel (sök)</label>
            <input type="text" placeholder="t.ex. ai.generate" wire:model.defer="searchKey" class="input input-bordered w-full">
            <div class="text-xs text-gray-500 mt-1">Tryck Enter för att söka</div>
        </div>

        <div>
            <label class="block text-sm text-gray-600">Per sida</label>
            <select wire:model="perPage" class="select select-bordered w-full">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>
        </div>
    </div>

    <div class="border rounded p-4 bg-white">
        <div class="text-sm text-gray-600 mb-2">Summering (filtrerad vy)</div>
        <div class="flex flex-wrap gap-2">
      <span class="inline-flex items-center gap-2 border rounded-full px-3 py-1 text-xs bg-gray-50">
        Totalt: <span class="font-semibold">{{ $summary['grand_total'] ?? 0 }}</span>
      </span>
            @foreach(($summary['total_by_key'] ?? []) as $k => $v)
                <span class="inline-flex items-center gap-2 border rounded-full px-3 py-1 text-xs bg-gray-50">
          {{ $k }}: <span class="font-semibold">{{ $v }}</span>
        </span>
            @endforeach
        </div>
    </div>

    <div class="border rounded bg-white">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50">
                <tr class="text-left">
                    <th class="px-4 py-2">Kund</th>
                    <th class="px-4 py-2">Period</th>
                    <th class="px-4 py-2">Nyckel</th>
                    <th class="px-4 py-2">Användning</th>
                    <th class="px-4 py-2">Uppdaterad</th>
                </tr>
                </thead>
                <tbody>
                @forelse($metrics as $m)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $m->customer?->name ?? '#'.$m->customer_id }}</td>
                        <td class="px-4 py-2">{{ $m->period }}</td>
                        <td class="px-4 py-2 font-mono">{{ $m->metric_key }}</td>
                        <td class="px-4 py-2">{{ (int) $m->used_value }}</td>
                        <td class="px-4 py-2 text-gray-500">{{ $m->updated_at?->diffForHumans() }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-4 text-gray-600">Inga rader för vald filtrering.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-4 py-3">
            {{ $metrics->links() }}
        </div>
    </div>
</div>
