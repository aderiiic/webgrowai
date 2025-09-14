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
    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100/50 p-6" x-data="{ bg: @entangle('background_mode') }">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Typ av bild</label>
                        <select wire:model="image_type" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                            <option>Produktbild</option>
                            <option>Facebook-bild</option>
                            <option>LinkedIn-bild</option>
                            <option>Bloggbild</option>
                            <option>Instagrambild</option>
                            <option>Kampanjbild</option>
                        </select>
                        @error('image_type') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kampanj</label>
                        <select wire:model="campaign_type" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                            <option value="none">Ingen</option>
                            <option value="launch">Lansering</option>
                            <option value="sale">Rea/erbjudande</option>
                            <option value="seasonal">Säsong</option>
                            <option value="ugc">UGC-känsla</option>
                            <option value="editorial">Premium editorial</option>
                            <option value="hero">Hero banner</option>
                        </select>
                        @error('campaign_type') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mål med bilden</label>
                        <select wire:model="goal" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                            <option value="attention">Fånga uppmärksamhet</option>
                            <option value="sell">Sälja</option>
                            <option value="educate">Utbilda</option>
                            <option value="announce">Annonsera nyhet</option>
                            <option value="retarget">Retarget</option>
                        </select>
                        @error('goal') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Produktkategori (valfritt)</label>
                        <input wire:model="product_category" type="text" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500" placeholder="Ex: skor, smink, kläder">
                        @error('product_category') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Bakgrund</label>
                        <select wire:model="background_mode" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                            <option value="white">Helt vit</option>
                            <option value="black">Helt svart</option>
                            <option value="gray">Neutral grå</option>
                            <option value="solid">Solid varumärkesfärg</option>
                            <option value="brand">Branding-anpassad</option>
                            <option value="custom_hex">Egen hex-färg</option>
                            <option value="gradient">Mjuk gradient</option>
                            <option value="pattern">Diskret mönster</option>
                            <option value="marble">Marmor</option>
                            <option value="concrete">Betong</option>
                            <option value="wood">Träyta</option>
                            <option value="studio">Studio seamless</option>
                            <option value="lifestyle_in">Lifestyle – inomhus</option>
                            <option value="lifestyle_out">Lifestyle – utomhus</option>
                        </select>
                        @error('background_mode') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                        <div class="mt-2 flex items-center gap-3">
                            <input id="strict_background" type="checkbox" wire:model="strict_background" class="rounded text-indigo-600">
                            <label for="strict_background" class="text-sm text-gray-700">Strikt bakgrund (ingen textur eller mönster)</label>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Färg (om egen hex)</label>
                        <input
                            x-bind:disabled="bg !== 'custom_hex'"
                            x-bind:class="bg === 'custom_hex' ? 'bg-white border-gray-300 text-gray-900' : 'bg-gray-100 border-gray-200 text-gray-500 cursor-not-allowed'"
                            wire:model="background_hex"
                            type="text"
                            placeholder="#RRGGBB"
                            class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-indigo-500"
                        >
                        @error('background_hex') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                        <p class="text-xs text-gray-500 mt-1" x-show="bg !== 'custom_hex'">Välj “Egen hex-färg” ovan för att aktivera fältet.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Stil/effekt</label>
                        <select wire:model="style" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                            <option value="">Ingen</option>
                            <option>icy</option>
                            <option>blur</option>
                            <option>clean</option>
                            <option>minimal</option>
                            <option>branding-anpassad</option>
                            <option>vintage</option>
                            <option>filmig</option>
                            <option>high-contrast</option>
                        </select>
                        @error('style') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
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
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Titel/tema (valfritt)</label>
                    <input wire:model="title" type="text" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500" placeholder="Ex: Sommarens sneaker drop">
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
                            <select wire:model="overlay_position" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                                <option value="auto">Placering: Auto</option>
                                <option value="safe-top">Säker topp</option>
                                <option value="safe-bottom">Säker botten</option>
                                <option value="top">Topp</option>
                                <option value="bottom">Botten</option>
                                <option value="left">Vänster</option>
                                <option value="right">Höger</option>
                                <option value="center">Center</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="p-4 bg-indigo-50 border border-indigo-200 rounded-xl space-y-3">
                    <div class="flex items-center gap-2">
                        <input id="from_post_enabled" type="checkbox" wire:model="from_post_enabled" x-model="fromPost" class="rounded text-indigo-600">
                        <label for="from_post_enabled" class="text-sm font-medium text-gray-800">Skapa bild utifrån ett skapat inlägg</label>
                    </div>

                    <div x-show="$wire.from_post_enabled" x-transition>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Välj inlägg</label>
                        <select wire:model="from_post_id" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                            <option value="">Välj inlägg...</option>
                            @foreach($availablePosts as $p)
                                <option value="{{ $p['id'] }}">{{ $p['title'] }}</option>
                            @endforeach
                        </select>
                        @error('from_post_id') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>

                    <p class="text-xs text-indigo-800">Bilden anpassas till inläggets ämne/ton och mål.</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Genererad prompt (förhandsvisning)</label>
                    <pre class="text-xs bg-slate-900 text-slate-100 rounded-lg p-3 overflow-auto max-h-56 whitespace-pre-wrap">{{ $finalPrompt ?: 'Prompten visas här efter Generera.' }}</pre>
                </div>

                <div class="flex items-center gap-3">
                    <button wire:click="submit"
                            wire:loading.attr="disabled"
                            wire:target="submit"
                            @disabled(!$aiEnabled || ($quota !== null && $used >= $quota))
                            class="inline-flex items-center px-5 py-2.5 rounded-xl text-white font-semibold transition-all bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed">
                        <span wire:loading.remove wire:target="submit">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5h2m-1 0v14m-7-7h14"/>
                            </svg>
                        </span>
                                            <span wire:loading wire:target="submit">
                            <svg class="animate-spin w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                        </span>
                        <span>{{ $busy ? 'Genererar...' : 'Generera bild' }}</span>
                    </button>
                    <p class="text-sm text-gray-500">Bilden sparas automatiskt i ditt bibliotek.</p>
                </div>

                @if($error)
                    <div class="mt-3 p-3 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm">
                        {{ $error }}
                    </div>
                @endif
            </div>

            <!-- Info-kort (oförändrat) -->
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

    <script>
        document.addEventListener('livewire:initialized', () => {
            let timer = null;
            let pollCount = 0;
            const maxPolls = 10; // Begränsa antal polls

            window.Livewire.on('ai-image-queued', () => {
                if (timer) clearInterval(timer);
                pollCount = 0;

                timer = setInterval(() => {
                    pollCount++;
                @this.call('loadList');

                    // Stoppa efter max antal polls
                    if (pollCount >= maxPolls) {
                        clearInterval(timer);
                        timer = null;
                    }
                }, 6000);

                // Fallback timeout
                setTimeout(() => {
                    if (timer) {
                        clearInterval(timer);
                        timer = null;
                    }
                }, 60000);
            });
        });
    </script>
</div>
