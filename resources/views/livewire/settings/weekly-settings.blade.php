<div class="max-w-3xl mx-auto py-10 space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Veckodigest – Inställningar</h1>
        <div class="text-sm text-gray-500">{{ $customer?->name }}</div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="space-y-5 border rounded p-4 bg-white">
        <div>
            <label class="block text-sm font-medium">Mottagare (komma-separerade e-post)</label>
            <textarea rows="3" wire:model.defer="weekly_recipients" class="textarea textarea-bordered w-full" placeholder="namn1@example.com, namn2@example.com"></textarea>
            @if($recipientsPreview->isNotEmpty())
                <div class="mt-1 text-xs text-gray-500">Tolkas som: {{ $recipientsPreview->implode(', ') }}</div>
            @endif
            @error('weekly_recipients') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium">Varumärkesröst</label>
                <input type="text" wire:model.defer="weekly_brand_voice" class="input input-bordered w-full" placeholder="t.ex. professionell, hjälpsam">
                @error('weekly_brand_voice') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium">Målgrupp</label>
                <input type="text" wire:model.defer="weekly_audience" class="input input-bordered w-full" placeholder="t.ex. SMB-beslutsfattare">
                @error('weekly_audience') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium">Affärsmål</label>
            <input type="text" wire:model.defer="weekly_goal" class="input input-bordered w-full" placeholder="t.ex. öka kvalificerade leads">
            @error('weekly_goal') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium">Nyckelord (komma-separerade)</label>
            <input type="text" wire:model.defer="weekly_keywords" class="input input-bordered w-full" placeholder="t.ex. CRM, kundresa, automatisering">
            @error('weekly_keywords') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center gap-2">
            <button class="btn btn-primary" wire:click="save">Spara</button>
            <button class="btn" wire:click="sendTest">Skicka testsammandrag</button>
        </div>
    </div>

    <div>
        <a href="{{ route('dashboard') }}" class="btn">Tillbaka till dashboard</a>
    </div>
</div>
