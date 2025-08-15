<div class="max-w-3xl mx-auto py-10 space-y-6">
    <h1 class="text-2xl font-semibold">Uppgradera plan</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="border rounded p-4 bg-white space-y-3">
        <div>
            <label class="block text-sm text-gray-600">Välj plan</label>
            <select wire:model="desired_plan_id" class="select select-bordered w-full">
                <option value="">— Välj —</option>
                @foreach($plans as $p)
                    <option value="{{ $p['id'] }}">
                        {{ $p['name'] }} — {{ number_format($p['price_monthly']/100, 0, ',', ' ') }} kr/mån
                    </option>
                @endforeach
            </select>
            @error('desired_plan_id')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
        </div>

        <div>
            <label class="block text-sm text-gray-600">Faktureringsintervall</label>
            <select wire:model="billing_cycle" class="select select-bordered w-full">
                <option value="monthly">Månadsvis</option>
                <option value="annual">Årsvis (rabatt)</option>
            </select>
            @error('billing_cycle')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
        </div>

        @if($estimate_amount > 0)
            <div class="rounded border p-3 bg-gray-50 text-sm">
                <div><strong>Estimerad nästa faktura:</strong> {{ number_format($estimate_amount/100, 2, ',', ' ') }} kr</div>
                <div class="text-gray-600">{{ $estimate_text }}</div>
            </div>
        @endif

        <div>
            <label class="block text-sm text-gray-600">Meddelande (valfritt)</label>
            <textarea wire:model.defer="note" rows="3" class="textarea textarea-bordered w-full" placeholder="Ev. önskemål eller frågor"></textarea>
        </div>

        <div class="flex items-center justify-end">
            <button class="btn btn-primary" wire:click="submit">Skicka begäran</button>
        </div>
    </div>
</div>
