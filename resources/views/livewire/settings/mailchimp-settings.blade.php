<div class="max-w-3xl mx-auto py-10 space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Mailchimp – Inställningar</h1>
        @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    </div>

    <div class="border rounded p-4 bg-white space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm text-gray-600">API-nyckel</label>
                <input type="password" wire:model.defer="api_key" class="input input-bordered w-full" placeholder="••••••••••">
                <p class="text-xs text-gray-500 mt-1">Lämna tomt för att behålla nuvarande.</p>
            </div>
            <div>
                <label class="block text-sm text-gray-600">Audience (List) ID</label>
                <input type="text" wire:model.defer="audience_id" class="input input-bordered w-full">
            </div>
            <div>
                <label class="block text-sm text-gray-600">From name</label>
                <input type="text" wire:model.defer="from_name" class="input input-bordered w-full">
            </div>
            <div>
                <label class="block text-sm text-gray-600">Reply-to</label>
                <input type="email" wire:model.defer="reply_to" class="input input-bordered w-full">
            </div>
        </div>

        <div class="flex gap-2">
            <button class="btn btn-primary" wire:click="save">Spara</button>
            <button class="btn" wire:click="test">Testa anslutning</button>
        </div>

        @if($message)
            <div class="text-xs mt-2 {{ $status==='active' ? 'text-green-700' : 'text-red-700' }}">
                {{ $message }}
            </div>
        @endif
    </div>

    <div>
        <a href="{{ route('dashboard') }}" class="btn">Tillbaka</a>
    </div>
</div>
