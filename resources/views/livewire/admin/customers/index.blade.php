<div class="max-w-6xl mx-auto py-10 space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Kunder</h1>
        <a href="{{ route('admin.subscription.requests') }}" class="btn btn-sm">Planbegäran</a>
    </div>

    <div class="flex items-center gap-3">
        <input type="text" wire:model.live="q" class="input input-bordered input-sm w-64" placeholder="Sök kund...">
        <select wire:model.live="status" class="select select-bordered select-sm w-40">
            <option value="all">Alla</option>
            <option value="active">Active</option>
            <option value="trial">Trial</option>
            <option value="paused">Paused</option>
            <option value="cancelled">Cancelled</option>
        </select>
    </div>

    <div class="border rounded bg-white">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50">
                <tr class="text-left">
                    <th class="px-4 py-2">Kund</th>
                    <th class="px-4 py-2">Plan</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Trial slutar</th>
                    <th class="px-4 py-2">Åtgärder</th>
                </tr>
                </thead>
                <tbody>
                @foreach($rows as $r)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $r->name }}</td>
                        <td class="px-4 py-2">{{ $r->plan_name ?? '—' }}</td>
                        <td class="px-4 py-2">{{ strtoupper($r->sub_status ?? '—') }}</td>
                        <td class="px-4 py-2">{{ $r->trial_ends_at ? \Illuminate\Support\Carbon::parse($r->trial_ends_at)->toDateString() : '—' }}</td>
                        <td class="px-4 py-2">
                            <a href="{{ route('admin.customers.show', $r->id) }}" class="btn btn-sm">Öppna</a>
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
