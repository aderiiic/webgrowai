<div class="max-w-2xl mx-auto py-10 space-y-6">
    <h1 class="text-2xl font-semibold">Skapa nyhetsbrev</h1>

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif

    <div class="border rounded p-4 bg-white space-y-4">
        <div>
            <label class="block text-sm text-gray-600">Ämnesrad</label>
            <input type="text" wire:model.defer="subject" class="input input-bordered w-full" placeholder="Nyheter & tips för veckan">
            @error('subject') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm text-gray-600">Sändningstid (valfritt)</label>
                <input type="datetime-local" wire:model.defer="sendAt" class="input input-bordered w-full">
                <p class="text-xs text-gray-500 mt-1">Lämna tomt för omedelbar sändning.</p>
            </div>
            <div>
                <label class="block text-sm text-gray-600">Antal poster</label>
                <input type="number" min="1" max="10" wire:model.defer="numItems" class="input input-bordered w-full">
            </div>
        </div>

        <div>
            <button class="btn btn-primary" wire:click="submit">Generera & köa kampanj</button>
        </div>
    </div>

    <div>
        <a href="{{ route('dashboard') }}" class="btn">Tillbaka</a>
    </div>
</div>
