<div class="max-w-xl mx-auto py-10 space-y-6">
    <h1 class="text-2xl font-semibold">WordPress-koppling</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="space-y-4">
        <div>
            <label class="block text-sm font-medium">WordPress URL</label>
            <input type="url" wire:model.defer="wp_url" class="input input-bordered w-full" placeholder="https://din-sajt.se">
            @error('wp_url') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium">WP Användarnamn</label>
            <input type="text" wire:model.defer="wp_username" class="input input-bordered w-full">
            @error('wp_username') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium">Application Password</label>
            <input type="password" wire:model.defer="wp_app_password" class="input input-bordered w-full" placeholder="••••••••••">
            <p class="text-xs text-gray-500 mt-1">Lämna tomt för att behålla nuvarande (om redan ansluten).</p>
            @error('wp_app_password') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center gap-3">
            <button wire:click="save" class="btn btn-primary">Spara & testa</button>
            @if($status)
                <span class="text-sm {{ $status === 'connected' ? 'text-green-600' : 'text-red-600' }}">
          Status: {{ ucfirst($status) }}
        </span>
            @endif
        </div>

        @if($last_error)
            <div class="text-xs text-red-600">Fel: {{ $last_error }}</div>
        @endif
    </div>
</div>
