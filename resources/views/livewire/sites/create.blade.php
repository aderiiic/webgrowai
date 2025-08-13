<div class="max-w-xl mx-auto py-10 space-y-6">
    <h1 class="text-2xl font-semibold">Lägg till sajt</h1>

    <div class="space-y-4">
        <div>
            <label class="block text-sm font-medium">Namn</label>
            <input type="text" wire:model.defer="name" class="input input-bordered w-full">
            @error('name') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium">URL</label>
            <input type="url" wire:model.defer="url" class="input input-bordered w-full" placeholder="https://example.com">
            @error('url') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
        </div>
        <div class="flex gap-2">
            <button wire:click="save" class="btn btn-primary">Spara</button>
            <a href="{{ route('sites.index') }}" class="btn">Avbryt</a>
        </div>
    </div>
</div>
