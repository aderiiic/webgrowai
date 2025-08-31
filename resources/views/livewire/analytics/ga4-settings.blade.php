<div class="max-w-3xl mx-auto space-y-8">
    <h1 class="text-2xl font-bold">GA4-inställningar</h1>

    @error('oauth')
    <div class="p-3 rounded bg-red-50 border border-red-200 text-sm text-red-700">{{ $message }}</div>
    @enderror

    @if($hasOAuth && empty($propertyId))
        <div class="p-6 bg-white rounded-2xl border shadow space-y-4">
            <h2 class="font-semibold text-lg">Välj GA4‑property</h2>

            <div>
                <label class="block text-sm font-medium">Property</label>
                <select wire:model="selectedProperty" class="mt-1 w-full border rounded-lg px-3 py-2">
                    <option value="">— Välj —</option>
                    @foreach($properties as $p)
                        <option value="{{ $p['id'] }}">{{ $p['displayName'] }} ({{ $p['id'] }})</option>
                    @endforeach
                </select>
            </div>

            @if(!empty($streams))
                <div>
                    <label class="block text-sm font-medium">Datastream (valfritt)</label>
                    <select wire:model="selectedStream" class="mt-1 w-full border rounded-lg px-3 py-2">
                        <option value="">— Ingen —</option>
                        @foreach($streams as $s)
                            <option value="{{ $s['id'] }}">{{ $s['displayName'] }} — {{ $s['type'] }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div>
                <label class="block text-sm font-medium">Hostname (valfritt, auto från web‑stream)</label>
                <input type="text" wire:model.defer="hostname" class="mt-1 w-full border rounded-lg px-3 py-2" placeholder="www.exempel.se">
            </div>

            <div class="flex gap-3">
                <button wire:click="saveSelection" type="button" class="px-4 py-2 bg-indigo-600 text-white rounded-lg">Spara val</button>
                <a href="{{ route('analytics.index') }}" class="px-4 py-2 bg-gray-100 rounded-lg">Till Analys</a>
            </div>

            <div class="text-xs text-gray-500">
                Saknar du din property? Säkerställ att ditt Google‑konto har åtkomst och rättigheter.
            </div>
        </div>
    @endif

    {{-- Befintlig manuell inmatning (behåll som fallback eller ta bort om du bara vill köra OAuth) --}}
    <div class="p-6 bg-white rounded-2xl border shadow">
        <form wire:submit.prevent="save" class="space-y-4">
            <div>
                <label class="block text-sm font-medium">GA4 Property ID</label>
                <input type="text" wire:model.defer="propertyId" class="mt-1 w-full border rounded-lg px-3 py-2" placeholder="properties/123456789">
                @error('propertyId') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
            </div>

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
                <a href="{{ route('analytics.index') }}" class="px-4 py-2 bg-gray-100 rounded-lg">Till Analys</a>
            </div>
        </form>
    </div>
</div>
