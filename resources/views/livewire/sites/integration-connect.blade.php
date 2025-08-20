<div>
    <h1 class="text-xl font-semibold mb-4">Koppla integration</h1>

    <div class="space-y-6">
        <div>
            <label class="block text-sm font-medium mb-1">Plattform</label>
            <!-- Byt till .live och lägg wire:key på selecten -->
            <select wire:model.live="provider" wire:key="provider-select" class="w-full border rounded p-2">
                <option value="wordpress">WordPress</option>
                <option value="shopify">Shopify</option>
                <option value="custom">Custom</option>
            </select>
            <!-- Debug (temporärt): visar vilket värde Livewire har -->
            <p class="text-xs text-gray-500 mt-1">Vald: {{ $provider }}</p>
        </div>

        @if($provider === 'wordpress')
            <div class="grid md:grid-cols-2 gap-4" wire:key="provider-wordpress">
                <div>
                    <label class="block text-sm font-medium mb-1">WordPress URL</label>
                    <input type="url" wire:model.defer="wp_url" class="w-full border rounded p-2" placeholder="https://example.com" />
                    @error('wp_url') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Användarnamn</label>
                    <input type="text" wire:model.defer="wp_username" class="w-full border rounded p-2" placeholder="admin" />
                    @error('wp_username') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-1">App‑lösenord</label>
                    <input type="password" wire:model.defer="wp_app_password" class="w-full border rounded p-2" placeholder="••••••••" />
                    @error('wp_app_password') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
                    <p class="text-xs text-gray-500 mt-1">Fyll i för att sätta/uppdatera. Lämna tomt för att behålla befintligt.</p>
                </div>
            </div>

        @elseif($provider === 'shopify')
            <div class="space-y-3" wire:key="provider-shopify">
                <div>
                    <label class="block text-sm font-medium mb-1">Shop domain</label>
                    <input type="text" wire:model.defer="shop_domain" class="w-full border rounded p-2" placeholder="my-shop.myshopify.com" />
                    @error('shop_domain') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
                </div>

                <div class="flex items-center gap-3">
                    @php
                        $shopVal = trim($shop_domain ?? '');
                        $installUrl = $shopVal
                            ? route('integrations.shopify.install', ['site' => $site->id, 'shop' => $shopVal])
                            : null;
                    @endphp

                    @if($installUrl)
                        <a href="{{ $installUrl }}" class="px-4 py-2 bg-[#95BF47] text-white rounded">
                            Anslut med Shopify
                        </a>
                    @else
                        <button class="px-4 py-2 bg-gray-300 text-gray-700 rounded cursor-not-allowed" disabled>
                            Anslut med Shopify
                        </button>
                    @endif

                    <p class="text-xs text-gray-500">Ange din butik först (ex: my-shop.myshopify.com).</p>
                </div>

                <p class="text-xs text-gray-500">
                    Efter att du godkänt behörigheter i Shopify återvänder du hit och status uppdateras automatiskt.
                </p>
            </div>

        @else
        <div class="space-y-4" wire:key="provider-custom">
                <div>
                    <label class="block text-sm font-medium mb-1">Läge</label>
                    <select wire:model.live="custom_mode" class="w-full border rounded p-2">
                        <option value="crawler">Crawler (sitemap)</option>
                        <option value="api">API</option>
                    </select>
                </div>

                @if($custom_mode === 'crawler')
                    <div>
                        <label class="block text-sm font-medium mb-1">Sitemap URL</label>
                        <input type="url" wire:model.defer="custom_sitemap_url" class="w-full border rounded p-2" placeholder="https://www.example.com/sitemap.xml" />
                        @error('custom_sitemap_url') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
                    </div>
                @else
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">API Base URL</label>
                            <input type="url" wire:model.defer="custom_api_base" class="w-full border rounded p-2" placeholder="https://api.example.com" />
                            @error('custom_api_base') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">API Key</label>
                            <input type="password" wire:model.defer="custom_api_key" class="w-full border rounded p-2" placeholder="••••••••" />
                            @error('custom_api_key') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
                            <p class="text-xs text-gray-500 mt-1">Fyll i för att sätta/uppdatera. Lämna tomt för att behålla befintligt.</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">List endpoint</label>
                            <input type="url" wire:model.defer="custom_endpoint_list" class="w-full border rounded p-2" placeholder="https://api.example.com/docs" />
                            @error('custom_endpoint_list') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Get endpoint</label>
                            <input type="url" wire:model.defer="custom_endpoint_get" class="w-full border rounded p-2" placeholder="https://api.example.com/doc?Id={id}" />
                            @error('custom_endpoint_get') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <div class="flex items-center gap-3">
            <button wire:click="save" class="px-4 py-2 bg-blue-600 text-white rounded">Spara & testa anslutning</button>
            @if($status === 'connected')
                <span class="text-green-700">Status: Ansluten</span>
            @elseif($status === 'error')
                <span class="text-red-700">Status: Fel – {{ $last_error }}</span>
            @elseif($status)
                <span class="text-gray-600">Status: {{ $status }}</span>
            @endif
        </div>
    </div>
</div>
