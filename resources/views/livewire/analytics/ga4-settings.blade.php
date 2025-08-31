<div class="max-w-3xl mx-auto space-y-8">
    <h1 class="text-2xl font-bold">GA4-inställningar</h1>

    <div class="p-6 bg-white rounded-2xl border shadow">
        <form wire:submit.prevent="save" class="space-y-4">
            <div>
                <label class="block text-sm font-medium">GA4 Property ID</label>
                <input type="text" wire:model.defer="propertyId" class="mt-1 w-full border rounded-lg px-3 py-2">
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
