
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="max-w-5xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Redigera plan</h1>
                    <p class="mt-2 text-sm text-gray-600">Uppdatera planeninformation och features</p>
                </div>
                <a href="{{ route('admin.plans.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Tillbaka
                </a>
            </div>
        </div>

        <div class="space-y-8">
            <!-- Plan Details Section -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="p-2 bg-blue-100 rounded-lg">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <h2 class="ml-3 text-lg font-semibold text-gray-900">Planinformation</h2>
                        </div>

                        <!-- Snabb status om Stripe IDs -->
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs
                                {{ $stripe_price_monthly ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-600' }}">
                                Månads‑price {{ $stripe_price_monthly ? 'OK' : 'saknas' }}
                            </span>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs
                                {{ $stripe_price_yearly ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-600' }}">
                                Års‑price {{ $stripe_price_yearly ? 'OK' : 'saknas' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Plannamn *</label>
                            <input type="text" wire:model.defer="name"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                   placeholder="t.ex. Basic, Pro, Enterprise">
                            @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Månadspris (SEK) *</label>
                            <input type="number" wire:model.defer="price_monthly"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="0">
                            @error('price_monthly') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Årspris (SEK) *</label>
                            <input type="number" wire:model.defer="price_yearly"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="0">
                            @error('price_yearly') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="flex items-end">
                            <label class="inline-flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200 cursor-pointer w-full">
                                <input type="checkbox" wire:model="is_active"
                                       class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                                <span class="ml-3 text-sm text-gray-700">Plan är aktiv</span>
                            </label>
                        </div>
                    </div>

                    <!-- NYTT: Stripe Price‑ID fält -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Stripe Price (månadsvis)</label>
                            <input type="text" wire:model.defer="stripe_price_monthly" placeholder="price_..."
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('stripe_price_monthly') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                            <p class="text-xs text-gray-500 mt-1">Klistra in Price‑ID från Stripe (återkommande monthly)</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Stripe Price (årsvis)</label>
                            <input type="text" wire:model.defer="stripe_price_yearly" placeholder="price_..."
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('stripe_price_yearly') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                            <p class="text-xs text-gray-500 mt-1">Klistra in Price‑ID från Stripe (återkommande yearly)</p>
                        </div>
                    </div>

                    <div class="mt-6 flex gap-3">
                        <button wire:click="savePlan"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Spara plan
                        </button>
                        <button type="button" onclick="window.history.back()"
                                class="inline-flex items-center px-4 py-2 bg-white hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-lg border border-gray-300 shadow-sm transition-colors duration-200">
                            Avbryt
                        </button>
                    </div>
                </div>
            </div>

            <div class="p-6">
                    @if(empty($features))
                        <div class="text-center py-8">
                            <div class="inline-flex items-center justify-center w-12 h-12 bg-gray-100 rounded-full mb-4">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h3 class="text-sm font-medium text-gray-900 mb-2">Inga features ännu</h3>
                            <p class="text-sm text-gray-500">Lägg till features för att definiera vad som ingår i planen</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($features as $key => $data)
                                <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-end">
                                        <div class="lg:col-span-5 space-y-1">
                                            <label class="block text-xs font-medium text-gray-700">Feature nyckel *</label>
                                            <input type="text" wire:model.defer="features.{{ $key }}.key" value="{{ $key }}"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors duration-200 text-sm font-mono"
                                                   placeholder="t.ex. ai.short.monthly_limit">
                                        </div>

                                        <div class="lg:col-span-3 space-y-1">
                                            <label class="block text-xs font-medium text-gray-700">Gräns (valfri)</label>
                                            <input type="text" wire:model.defer="features.{{ $key }}.limit_value"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors duration-200"
                                                   placeholder="t.ex. 200">
                                        </div>

                                        <div class="lg:col-span-2 space-y-1">
                                            <label class="block text-xs font-medium text-gray-700">Status</label>
                                            <label class="inline-flex items-center p-2 bg-white rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors duration-200 cursor-pointer">
                                                <input type="checkbox" wire:model="features.{{ $key }}.is_enabled"
                                                       class="w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500 focus:ring-2">
                                                <span class="ml-2 text-xs text-gray-700">Aktiv</span>
                                            </label>
                                        </div>

                                        <div class="lg:col-span-2">
                                            <button wire:click="deleteFeature('{{ $key }}')"
                                                    class="w-full inline-flex justify-center items-center px-3 py-2 bg-red-100 hover:bg-red-200 text-red-700 text-sm font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                                Ta bort
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if(!empty($features))
                        <div class="mt-6 pt-4 border-t border-gray-200">
                            <button wire:click="saveFeatures"
                                    class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Spara alla features
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
