<div class="max-w-6xl mx-auto py-10 space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Planbegäran</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="flex flex-wrap items-center gap-3">
        <input type="text" wire:model.live="q" class="input input-bordered input-sm w-64" placeholder="Sök kund...">
        <select wire:model.live="status" class="select select-bordered select-sm w-40">
            <option value="pending">Pending</option>
            <option value="approved">Approved</option>
            <option value="rejected">Rejected</option>
            <option value="all">Alla</option>
        </select>
    </div>

    <div class="border rounded bg-white">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50">
                <tr class="text-left">
                    <th class="px-4 py-2">Kund</th>
                    <th class="px-4 py-2">Önskad plan</th>
                    <th class="px-4 py-2">Billing</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Skapad</th>
                    <th class="px-4 py-2">Åtgärd</th>
                </tr>
                </thead>
                <tbody>
                @foreach($rows as $r)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $r->customer_name }}</td>
                        <td class="px-4 py-2">{{ $r->plan_name }}</td>
                        <td class="px-4 py-2">{{ strtoupper($r->billing_cycle) }}</td>
                        <td class="px-4 py-2">{{ strtoupper($r->status) }}</td>
                        <td class="px-4 py-2">{{ \Illuminate\Support\Carbon::parse($r->created_at)->format('Y-m-d H:i') }}</td>
                        <td class="px-4 py-2">
                            @if($r->status === 'pending')
                                <div class="flex gap-2">
                                    <button class="btn btn-sm btn-primary" wire:click="approve({{ $r->id }})">Godkänn</button>
                                    <button class="btn btn-sm" wire:click="reject({{ $r->id }})">Avvisa</button>
                                </div>
                            @else
                                —
                            @endif
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
