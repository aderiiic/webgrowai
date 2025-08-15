<div class="max-w-4xl mx-auto py-10 space-y-8">
    <div>
        <h1 class="text-2xl font-semibold">Redigera plan</h1>
    </div>

    <div class="border rounded p-4 space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium">Namn</label>
                <input type="text" wire:model.defer="name" class="input input-bordered w-full">
                @error('name') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium">Månadspris</label>
                <input type="number" wire:model.defer="price_monthly" class="input input-bordered w-full">
                @error('price_monthly') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium">Årspris</label>
                <input type="number" wire:model.defer="price_yearly" class="input input-bordered w-full">
                @error('price_yearly') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>
            <div class="flex items-end">
                <label class="inline-flex items-center gap-2">
                    <input type="checkbox" wire:model="is_active" class="checkbox">
                    <span>Aktiv</span>
                </label>
            </div>
        </div>

        <div class="flex gap-2">
            <button wire:click="savePlan" class="btn btn-primary">Spara plan</button>
            <a href="{{ route('admin.plans.index') }}" class="btn">Tillbaka</a>
        </div>
    </div>

    <div class="border rounded p-4 space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-medium">Features</h2>
            <button wire:click="addFeatureRow" class="btn btn-sm">Lägg till feature</button>
        </div>

        <div class="space-y-3">
            @foreach($features as $key => $data)
                <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">
                    <div class="md:col-span-5">
                        <label class="block text-xs text-gray-600">Nyckel</label>
                        <input type="text" wire:model.defer="features.{{ $key }}.key" value="{{ $key }}" class="input input-bordered w-full" placeholder="t.ex. ai.short.monthly_limit">
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-xs text-gray-600">Limit (valfri)</label>
                        <input type="text" wire:model.defer="features.{{ $key }}.limit_value" class="input input-bordered w-full" placeholder="t.ex. 200">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs text-gray-600">Aktiv</label>
                        <input type="checkbox" wire:model="features.{{ $key }}.is_enabled" class="checkbox">
                    </div>
                    <div class="md:col-span-2">
                        <button class="btn btn-sm btn-error" wire:click="deleteFeature('{{ $key }}')">Ta bort</button>
                    </div>
                </div>
            @endforeach
        </div>

        <div>
            <button wire:click="saveFeatures" class="btn btn-primary">Spara features</button>
        </div>
    </div>
</div>
