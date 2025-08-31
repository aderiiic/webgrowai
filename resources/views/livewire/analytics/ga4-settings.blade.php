<div class="max-w-3xl mx-auto space-y-8">
    <h1 class="text-2xl font-bold">GA4-inställningar</h1>

    @error('oauth')
    <div class="p-3 rounded bg-red-50 border border-red-200 text-sm text-red-700">{{ $message }}</div>
    @enderror

    <div class="p-6 bg-white rounded-2xl border shadow space-y-4">
        <h2 class="font-semibold text-lg">Koppla Property</h2>

        <div>
            <label class="block text-sm font-medium">GA4 Property ID</label>
            <input type="text" wire:model.defer="propertyId" class="mt-1 w-full border rounded-lg px-3 py-2" placeholder="properties/123456789 eller 123456789">
            <p class="text-xs text-gray-500 mt-1">Tips: Du hittar det i GA4 Admin under Property settings.</p>
        </div>

        <div class="flex items-center gap-3">
            <button type="button" wire:click="validateProperty" class="px-4 py-2 bg-indigo-600 text-white rounded-lg">Validera</button>
            @if($validationMessage)
                <span class="text-sm text-gray-700">{{ $validationMessage }}</span>
            @endif
        </div>

        @if(!empty($hostnames))
            <div>
                <label class="block text-sm font-medium">Hostname (valfritt)</label>
                <select wire:model="hostname" class="mt-1 w-full border rounded-lg px-3 py-2">
                    <option value="">— Välj —</option>
                    @foreach($hostnames as $h)
                        <option value="{{ $h }}">{{ $h }}</option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-500 mt-1">Används för per‑inläggsstatistik (matchning mot dina permalinks).</p>
            </div>
        @endif

        <div class="flex gap-3">
            <button type="button" wire:click="saveSelection" class="px-4 py-2 bg-emerald-600 text-white rounded-lg">Spara</button>
            <a href="{{ route('analytics.index') }}" class="px-4 py-2 bg-gray-100 rounded-lg">Till Analys</a>
        </div>
    </div>

    {{-- Valfri fallback för service account (kan döljas om ni bara kör OAuth) --}}
    <div class="p-6 bg-white rounded-2xl border shadow">
        <form wire:submit.prevent="save" class="space-y-4">
            <h2 class="font-semibold text-lg">Service Account (valfritt)</h2>

            <div>
                <label class="block text-sm font-medium">Service Account JSON</label>
                <textarea wire:model.defer="serviceJsonText" rows="6" class="mt-1 w-full border rounded-lg px-3 py-2" placeholder='{"type":"service_account",...}'></textarea>
                <div class="mt-2 text-sm text-gray-500">Alternativt ladda upp .json:</div>
                <input type="file" wire:model="serviceJsonFile" accept=".json" class="mt-1">
                @error('serviceJsonText') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                @error('serviceJsonFile') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="flex gap-3">
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg">Spara</button>
            </div>
        </form>
    </div>
</div>
