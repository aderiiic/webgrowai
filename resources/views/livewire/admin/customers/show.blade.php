<div class="max-w-5xl mx-auto py-10 space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Kund – Plan & förbrukning</h1>
        <a href="{{ route('admin.usage.index') }}" class="btn btn-sm">Tillbaka</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="border rounded p-4 bg-white space-y-3">
        <div class="text-sm text-gray-600">Kund</div>
        <div class="font-medium">{{ $customer->name }}</div>
        <div class="text-xs text-gray-500">{{ $customer->billing_email }}</div>
    </div>

    <div class="border rounded p-4 bg-white space-y-3">
        <div class="grid md:grid-cols-4 gap-3 items-end">
            <div>
                <label class="block text-sm text-gray-600">Plan</label>
                <select wire:model="planId" class="select select-bordered w-full">
                    @foreach($plans as $p)
                        <option value="{{ $p['id'] }}">{{ $p['name'] }} ({{ number_format($p['price']/100, 0, ',', ' ') }} kr/mån)</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm text-gray-600">Status</label>
                <select wire:model="status" class="select select-bordered w-full">
                    <option value="active">Active</option>
                    <option value="trial">Trial</option>
                    <option value="paused">Paused</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            <div>
                <label class="block text-sm text-gray-600">Billing</label>
                <select wire:model="billing_cycle" class="select select-bordered w-full">
                    <option value="monthly">Monthly</option>
                    <option value="annual">Annual</option>
                </select>
            </div>
            <div>
                <label class="block text-sm text-gray-600">Trial slutar (valfritt)</label>
                <input type="date" wire:model="trial_ends_at" class="input input-bordered w-full">
            </div>
        </div>
        <div class="flex gap-2">
            <button class="btn btn-primary btn-sm" wire:click="savePlan">Spara</button>
            <button class="btn btn-sm" wire:click="setTrialGrowth">Sätt Trial: Growth (14d)</button>
            <button class="btn btn-sm" wire:click="createDraftInvoice">Skapa fakturautkast ({{ now()->format('Y-m') }})</button>
        </div>
    </div>

    <div class="border rounded p-4 bg-white">
        <div class="text-sm font-medium mb-3">Förbrukning ({{ now()->format('Y-m') }})</div>
        <div class="space-y-3">
            @foreach($rows as $r)
                <div>
                    <div class="flex items-center justify-between text-sm">
                        <div class="font-medium">{{ $r['label'] }}</div>
                        @if($r['quota'] !== null)
                            <div class="text-gray-600">{{ $r['used'] }} / {{ $r['quota'] }}</div>
                        @else
                            <div class="text-gray-600">{{ $r['used'] }} (no quota)</div>
                        @endif
                    </div>
                    @if($r['quota'] !== null)
                        <div class="mt-2 w-full h-2 bg-gray-100 rounded">
                            <div class="h-2 rounded bg-indigo-600" style="width: {{ $r['pct'] }}%"></div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <div class="border rounded p-4 bg-white space-y-3">
        <div class="text-sm font-medium">Extraanvändning (innevarande period)</div>
        <div class="flex items-center gap-2">
            <input type="text" wire:model.defer="overageNote" class="input input-bordered input-sm w-80" placeholder="Anteckning (valfritt)">
            <button class="btn btn-sm" wire:click="toggleOverageApproval">
                {{ $overageApproved ? 'Återkalla godkännande' : 'Godkänn övertramp' }}
            </button>
        </div>
        <div class="text-xs text-gray-600">När godkänt: kvotgränser blockeras inte och extra förbrukning kan faktureras som tillägg.</div>
    </div>
</div>
