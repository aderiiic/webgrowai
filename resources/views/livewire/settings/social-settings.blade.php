<div class="max-w-3xl mx-auto py-10 space-y-8">
    <div>
        <h1 class="text-2xl font-semibold">Sociala kanaler – Inställningar</h1>
        @if(session('success'))
            <div class="alert alert-success mt-3">{{ session('success') }}</div>
        @endif
    </div>

    <!-- Facebook -->
    <div class="border rounded p-4 bg-white space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-medium">Facebook Page</h2>
            <div class="flex items-center gap-2">
                <a href="{{ route('auth.facebook.redirect') }}" class="btn btn-sm">Anslut Facebook-sida</a>
                <div class="text-xs">
                    Status:
                    <span class="{{ $fb_status === 'active' ? 'text-green-600' : ($fb_status === 'error' ? 'text-red-600' : 'text-gray-500') }}">
            {{ $fb_status ? ucfirst($fb_status) : '—' }}
          </span>
                </div>
            </div>
        </div>

        <!-- Avancerat (valfritt) – behåll manuellt stöd -->
        <details class="mt-2">
            <summary class="text-sm cursor-pointer">Avancerat (fyll i manuellt)</summary>
            <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-gray-600">Page ID</label>
                    <input type="text" wire:model.defer="fb_page_id" class="input input-bordered w-full">
                </div>
                <div>
                    <label class="block text-sm text-gray-600">Access Token</label>
                    <input type="password" wire:model.defer="fb_access_token" class="input input-bordered w-full" placeholder="••••••••••">
                    <p class="text-xs text-gray-500 mt-1">Lämna tomt för att behålla nuvarande.</p>
                </div>
            </div>

            <div class="flex gap-2 mt-2">
                <button class="btn btn-primary btn-sm" wire:click="saveFacebook">Spara Facebook</button>
                <button class="btn btn-sm" wire:click="testFacebook">Testa anslutning</button>
            </div>
        </details>

        @if($fb_message)
            <div class="text-xs mt-2 {{ str_starts_with($fb_message, 'OK') ? 'text-green-700' : 'text-red-700' }}">
                {{ $fb_message }}
            </div>
        @endif
    </div>

    <!-- Instagram -->
    <div class="border rounded p-4 bg-white space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-medium">Instagram Business</h2>
            <div class="flex items-center gap-2">
                <a href="{{ route('auth.instagram.redirect') }}" class="btn btn-sm">Anslut Instagram</a>
                <div class="text-xs">
                    Status:
                    <span class="{{ $ig_status === 'active' ? 'text-green-600' : ($ig_status === 'error' ? 'text-red-600' : 'text-gray-500') }}">
            {{ $ig_status ? ucfirst($ig_status) : '—' }}
          </span>
                </div>
            </div>
        </div>

        <details class="mt-2">
            <summary class="text-sm cursor-pointer">Avancerat (fyll i manuellt)</summary>
            <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-gray-600">IG User ID (Business)</label>
                    <input type="text" wire:model.defer="ig_user_id" class="input input-bordered w-full">
                </div>
                <div>
                    <label class="block text-sm text-gray-600">Access Token</label>
                    <input type="password" wire:model.defer="ig_access_token" class="input input-bordered w-full" placeholder="••••••••••">
                    <p class="text-xs text-gray-500 mt-1">Lämna tomt för att behålla nuvarande.</p>
                </div>
            </div>

            <div class="flex gap-2 mt-2">
                <button class="btn btn-primary btn-sm" wire:click="saveInstagram">Spara Instagram</button>
                <button class="btn btn-sm" wire:click="testInstagram">Testa anslutning</button>
            </div>
        </details>

        @if($ig_message)
            <div class="text-xs mt-2 {{ str_starts_with($ig_message, 'OK') ? 'text-green-700' : 'text-red-700' }}">
                {{ $ig_message }}
            </div>
        @endif
    </div>

    <div>
        <a href="{{ route('dashboard') }}" class="btn">Tillbaka</a>
    </div>
</div>
