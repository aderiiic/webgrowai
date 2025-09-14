<div class="space-y-8" wire:init="loadList">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m2-2l1.586-1.586a2 2 0 012.828 0L22 14M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Generera bild</h1>
                <p class="text-sm text-gray-600">Skapa plattformsanpassade, varumärkesmatchade bilder med AI.</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <div class="px-3 py-1.5 rounded-xl bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-200/60 text-sm text-emerald-800">
                Genereringar denna månad: {{ is_null($quota) ? 'Obegränsat' : ($used . ' / ' . $quota) }}
                @if(!is_null($remaining))
                    ({{ $remaining }} kvar)
                @endif
            </div>
        </div>
    </div>

    @if($queued)
        <!-- Statusbanner -->
        <div class="flex items-center gap-3 p-3 bg-blue-50 border border-blue-200 rounded-xl text-blue-800">
            <svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            <div class="text-sm">
                Ditt bildjobb är köat och bearbetas. Det dyker upp i listan nedan när det är klart.
            </div>
            <button wire:click="loadList" class="ml-auto text-sm underline">Uppdatera nu</button>
        </div>
    @endif

    @if(!$aiEnabled)
        <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl text-amber-800">
            Din plan tillåter inte bildgenerering. Uppgradera för att aktivera funktionen.
        </div>
    @endif

    <!-- Formulär + Status -->
    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100/50 p-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-4">
                <!-- fält (oförändrade) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Typ av bild</label>
                    <select wire:model="image_type" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                        <option>Produktbild</option>
                        <option>Facebook-bild</option>
                        <option>LinkedIn-bild</option>
                        <option>Bloggbild</option>
                        <option>Instagrambild</option>
                    </select>
                    @error('image_type') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Bakgrund</label>
                        <select wire:model="background" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                            <option>vit</option>
                            <option>annan färg</option>
                            <option>natur</option>
                            <option>anpassad till titel</option>
                        </select>
                        @error('background') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Stil/effekt</label>
                        <select wire:model="style" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                            <option value="">Ingen</option>
                            <option>icy</option>
                            <option>blur</option>
                            <option>clean</option>
                            <option>minimal</option>
                            <option>branding-anpassad</option>
                        </select>
                        @error('style') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Titel/tema (valfritt)</label>
                    <input wire:model="title" type="text" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500" placeholder="Ex: Citronsoda – Sommarkampanj">
                    @error('title') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl space-y-3">
                    <div class="flex items-center gap-2">
                        <input id="overlay_enabled" type="checkbox" wire:model="overlay_enabled" class="rounded text-indigo-600">
                        <label for="overlay_enabled" class="text-sm font-medium text-gray-800">Text-overlay</label>
                    </div>
                    <div @class(['grid grid-cols-1 md:grid-cols-3 gap-3', 'hidden' => !$overlay_enabled])>
                        <div class="md:col-span-2">
                            <input wire:model="overlay_text" type="text" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500" placeholder="Ex: Ny smak! Fräsch & läskande">
                            @error('overlay_text') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div>
                            <p class="text-xs text-gray-600">Texten placeras läsbart och anpassas till motivet.</p>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Plattform/format</label>
                    <select wire:model="platform" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                        <option value="facebook_square">Facebook 1080x1080</option>
                        <option value="facebook_story">Facebook/IG Story 1080x1920</option>
                        <option value="instagram">Instagram 1080x1350</option>
                        <option value="linkedin">LinkedIn 1080x1080</option>
                        <option value="blog">Blogg 1200x628</option>
                    </select>
                    @error('platform') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Genererad prompt (förhandsvisning)</label>
                    <pre class="text-xs bg-slate-900 text-slate-100 rounded-lg p-3 overflow-auto max-h-48">{{ $finalPrompt ?: 'Prompten visas här efter Generera.' }}</pre>
                </div>

                <div class="flex items-center gap-3">
                    <button wire:click="submit" wire:loading.attr="disabled"
                            class="inline-flex items-center px-5 py-2.5 rounded-xl text-white font-semibold transition-all bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 shadow-lg hover:shadow-xl disabled:opacity-50">
                        <span wire:loading.remove>
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5h2m-1 0v14m-7-7h14"/>
                            </svg>
                        </span>
                        <span wire:loading>
                            <svg class="animate-spin w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                        </span>
                        <span>Generera bild</span>
                    </button>
                    <p class="text-sm text-gray-500">Bilden sparas automatiskt i ditt bibliotek.</p>
                </div>

                @if($error)
                    <div class="mt-3 p-3 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm">
                        {{ $error }}
                    </div>
                @endif
            </div>

            <!-- Info -->
            <div class="lg:col-span-1">
                <div class="bg-gradient-to-b from-slate-50 to-white border border-slate-200 rounded-2xl p-4">
                    <h3 class="text-sm font-semibold text-gray-800 mb-2">Status</h3>
                    <div class="text-sm text-gray-600">
                        <p>Jobb köas och körs i turordning. Följ bannern ovan eller uppdatera listan nedan.</p>
                    </div>
                    <div class="mt-3 p-2 bg-indigo-50 text-indigo-800 border border-indigo-200 rounded-lg text-xs">
                        Tips: Generera flera – de läggs i kö och dyker upp i listan automatiskt.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Översikt över genererade bilder (samma källa som MediaPicker) -->
    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100/50 p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 7h18M3 12h18M3 17h18"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Dina genererade bilder</h2>
                    <p class="text-sm text-gray-600">Klicka på en bild för att förhandsgranska stort.</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <button wire:click="loadList" class="text-sm text-emerald-700 hover:underline">Uppdatera</button>
            </div>
        </div>

        @if(empty($items))
            <div class="py-8 text-center text-gray-500 text-sm">
                Inga genererade bilder ännu.
            </div>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach($items as $it)
                    <button type="button"
                            wire:click="openPreview({{ $it['id'] }})"
                            class="group relative rounded-xl overflow-hidden border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <img src="{{ route('assets.thumb', $it['id']) }}" alt="{{ $it['name'] }}" class="w-full h-40 object-cover" />
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition flex items-end">
                            <div class="w-full p-2 text-[10px] text-white/90 truncate text-left">{{ $it['name'] }}</div>
                        </div>
                    </button>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Preview-modal -->
    @if($showPreview)
        <div class="fixed inset-0 z-50">
            <div class="absolute inset-0 bg-black/60" wire:click="closePreview"></div>
            <div class="absolute inset-0 flex items-center justify-center p-4">
                <div class="bg-white rounded-2xl shadow-2xl max-w-[90vw] max-h-[90vh] w-full">
                    <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
                        <h3 class="text-sm font-semibold text-gray-800 truncate pr-4">{{ $previewName }}</h3>
                        <div class="flex items-center gap-2">
                            @if($previewUrl)
                                <a href="{{ $previewUrl }}" target="_blank" class="text-sm text-indigo-600 hover:underline">Öppna i ny flik</a>
                            @endif
                            <button type="button" wire:click="closePreview" class="p-2 rounded-lg hover:bg-gray-100">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="p-3">
                        @if($previewUrl)
                            <img src="{{ $previewUrl }}" alt="{{ $previewName }}" class="w-full h-auto object-contain max-h-[80vh] rounded-xl" />
                        @else
                            <div class="p-8 text-center text-gray-500 text-sm">Kunde inte ladda bilden.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <script>
            // Stöd för ESC att stänga modal
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                @this.call('closePreview');
                }
            }, { once: true });
        </script>
    @endif

    {{-- Pollning efter köad generering – slutar efter 60s --}}
    <script>
        document.addEventListener('livewire:initialized', () => {
            let timer = null;
            window.Livewire.on('ai-image-queued', () => {
                if (timer) clearInterval(timer);
                timer = setInterval(() => {
                @this.call('loadList');
                }, 6000);
                setTimeout(() => { if (timer) { clearInterval(timer); timer = null; } }, 60000);
            });
        });
    </script>
</div>
