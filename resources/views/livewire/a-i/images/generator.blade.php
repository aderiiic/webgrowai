<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 lg:space-y-8" wire:init="loadList">
    <!-- Förbättrad header med gradient bakgrund (matchar index.blade.php) -->
    <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 rounded-2xl lg:rounded-3xl p-6 lg:p-8 text-white shadow-2xl">
        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4 lg:gap-6">
            <div class="flex items-center gap-3 lg:gap-4">
                <div class="w-12 h-12 lg:w-16 lg:h-16 bg-white/20 backdrop-blur rounded-xl lg:rounded-2xl flex items-center justify-center">
                    <svg class="w-6 h-6 lg:w-8 lg:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m2-2l1.586-1.586a2 2 0 012.828 0L22 14M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl lg:text-4xl font-bold mb-1 lg:mb-2">Generera bild</h1>
                    <p class="text-white/80 text-sm lg:text-base">Skapa plattformsanpassade, varumärkesmatchade bilder med AI</p>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-stretch gap-3 lg:gap-4 w-full sm:w-auto">
                <div class="px-3 lg:px-4 py-2 lg:py-3 rounded-xl lg:rounded-2xl bg-white/20 backdrop-blur border border-white/30 text-sm lg:text-base text-white font-medium">
                    Genereringar denna månad: {{ is_null($quota) ? 'Obegränsat' : ($used . ' / ' . $quota) }}
                    @if(!is_null($remaining))
                        ({{ $remaining }} kvar)
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if($queued)
        <!-- Statusbanner -->
        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-200 rounded-2xl lg:rounded-3xl p-4 lg:p-6">
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0">
                    <svg class="w-6 h-6 lg:w-8 lg:h-8 animate-spin text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-base lg:text-lg font-bold text-blue-900 mb-1">Bildgenerering pågår</h3>
                    <p class="text-sm lg:text-base text-blue-800">Ditt bildjobb är köat och bearbetas. Det dyker upp i listan nedan när det är klart.</p>
                </div>
                <button wire:click="loadList"
                        class="px-3 lg:px-4 py-2 lg:py-3 bg-white border border-blue-200 text-blue-700 rounded-xl lg:rounded-2xl hover:bg-blue-50 text-sm lg:text-base font-medium transition-all duration-200">
                    Uppdatera nu
                </button>
            </div>
        </div>
    @endif

    @if(!$aiEnabled)
        <div class="bg-gradient-to-br from-amber-50 to-orange-50 border border-amber-200 rounded-2xl lg:rounded-3xl p-4 lg:p-6">
            <div class="flex items-center gap-3">
                <svg class="w-6 h-6 lg:w-8 lg:h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
                <div>
                    <h3 class="text-base lg:text-lg font-bold text-amber-900 mb-1">Bildgenerering inte tillgänglig</h3>
                    <p class="text-sm lg:text-base text-amber-800">Din plan tillåter inte bildgenerering. Uppgradera för att aktivera funktionen.</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Huvudformulär -->
    <div class="bg-white rounded-2xl lg:rounded-3xl shadow-xl border border-gray-100 overflow-hidden"
         x-data="{
             imageType: @entangle('image_type'),
             bgMode: @entangle('background_mode'),
             overlayEnabled: @entangle('overlay_enabled'),
             labelTextEnabled: @entangle('label_text_enabled'),
             fromPostEnabled: @entangle('from_post_enabled')
         }">

        <div class="bg-gradient-to-r from-gray-50 to-indigo-50 p-4 lg:p-6 border-b border-gray-100">
            <h2 class="text-xl lg:text-2xl font-bold text-gray-900 mb-2">Konfigurera din bild</h2>
            <p class="text-sm lg:text-base text-gray-600">Fyll i detaljerna nedan för att skapa en anpassad bild</p>
        </div>

        <div class="p-4 lg:p-6">
            <div class="grid grid-cols-1 xl:grid-cols-4 gap-6 lg:gap-8">
                <div class="xl:col-span-3 space-y-6 lg:space-y-8">
                    <!-- Grundinställningar -->
                    <div class="bg-gradient-to-br from-slate-50 to-gray-50 border border-slate-200 rounded-2xl p-4 lg:p-6">
                        <div class="flex items-center gap-3 mb-4 lg:mb-6">
                            <div class="w-10 h-10 lg:w-12 lg:h-12 bg-gradient-to-br from-slate-600 to-gray-700 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 lg:w-6 lg:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg lg:text-xl font-bold text-gray-900">Grundinställningar</h3>
                                <p class="text-sm text-gray-600">Välj bildtyp, kampanj och målsättning</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m2-2l1.586-1.586a2 2 0 012.828 0L22 14M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    Typ av bild
                                </label>
                                <select wire:model.live="image_type"
                                        class="w-full px-3 lg:px-4 py-2 lg:py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all text-sm lg:text-base">
                                    <option>Produktbild</option>
                                    <option>Facebook-bild</option>
                                    <option>LinkedIn-bild</option>
                                    <option>Bloggbild</option>
                                    <option>Instagrambild</option>
                                    <option>Kampanjbild</option>
                                </select>
                                @error('image_type') <div class="text-sm text-red-600 mt-1 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $message }}
                                </div> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h8M8 11h8m-8 4h6"/>
                                    </svg>
                                    Bildstil
                                </label>
                                <select wire:model="render_mode"
                                        class="w-full px-3 lg:px-4 py-2 lg:py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all text-sm lg:text-base">
                                    <option value="realistic">Realistisk</option>
                                    <option value="animated">Animerad</option>
                                </select>
                                @error('render_mode') <div class="text-sm text-red-600 mt-1 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $message }}
                                </div> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                                    </svg>
                                    Kampanj
                                </label>
                                <select wire:model="campaign_type"
                                        class="w-full px-3 lg:px-4 py-2 lg:py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all text-sm lg:text-base">
                                    <option value="none">Ingen</option>
                                    <option value="launch">Lansering</option>
                                    <option value="sale">Rea/erbjudande</option>
                                    <option value="seasonal">Säsong</option>
                                    <option value="ugc">UGC-känsla</option>
                                    <option value="editorial">Premium editorial</option>
                                    <option value="hero">Hero banner</option>
                                </select>
                                @error('campaign_type') <div class="text-sm text-red-600 mt-1 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $message }}
                                </div> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                    Mål med bilden
                                </label>
                                <select wire:model="goal"
                                        class="w-full px-3 lg:px-4 py-2 lg:py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all text-sm lg:text-base">
                                    <option value="attention">Fånga uppmärksamhet</option>
                                    <option value="sell">Sälja</option>
                                    <option value="educate">Utbilda</option>
                                    <option value="announce">Annonsera nyhet</option>
                                    <option value="retarget">Retarget</option>
                                </select>
                                @error('goal') <div class="text-sm text-red-600 mt-1 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $message }}
                                </div> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                    Produktkategori (valfritt)
                                </label>
                                <input wire:model="product_category" type="text"
                                       placeholder="Ex: skor, smink, kläder"
                                       class="w-full px-3 lg:px-4 py-2 lg:py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all text-sm lg:text-base">
                                @error('product_category') <div class="text-sm text-red-600 mt-1 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $message }}
                                </div> @enderror
                            </div>
                        </div>

                        <!-- Titel/tema -->
                        <div class="mt-4 lg:mt-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                </svg>
                                Titel/tema (valfritt)
                            </label>
                            <input wire:model="title" type="text"
                                   placeholder="Ex: Sommarens sneaker drop"
                                   class="w-full px-3 lg:px-4 py-2 lg:py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all text-sm lg:text-base">
                            @error('title') <div class="text-sm text-red-600 mt-1 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $message }}
                            </div> @enderror
                        </div>
                    </div>

                    <!-- Produktbild-specifika detaljer - visas bara när Produktbild är valt -->
                    <div x-show="imageType === 'Produktbild'"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform scale-95"
                         x-transition:enter-end="opacity-100 transform scale-100"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 transform scale-100"
                         x-transition:leave-end="opacity-0 transform scale-95"
                         class="bg-gradient-to-br from-emerald-50 to-teal-50 border border-emerald-200 rounded-2xl p-4 lg:p-6"
                         style="display: none;">

                        <div class="flex items-center gap-3 mb-4 lg:mb-6">
                            <div class="w-10 h-10 lg:w-12 lg:h-12 bg-gradient-to-br from-emerald-600 to-teal-600 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 lg:w-6 lg:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8a4 4 0 01-8 0m8 0a4 4 0 00-8 0m8 0v10a2 2 0 01-2 2H10a2 2 0 01-2-2V8"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg lg:text-xl font-bold text-emerald-900">Produktbild – detaljer</h3>
                                <p class="text-sm text-emerald-700">Anpassa produkten och dess presentation</p>
                            </div>
                        </div>

                        <div class="space-y-4 lg:space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-6">
                                <div>
                                    <label class="block text-sm font-semibold text-emerald-900 mb-2">Vilken produkt?</label>
                                    <input wire:model="product_item" type="text"
                                           placeholder="Ex: flaska, diskmaskin, bok"
                                           class="w-full px-3 lg:px-4 py-2 lg:py-3 bg-white border border-emerald-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all text-sm lg:text-base">
                                    @error('product_item') <div class="text-sm text-red-600 mt-1 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $message }}
                                    </div> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-emerald-900 mb-2">Språk för text</label>
                                    <select wire:model="text_language"
                                            class="w-full px-3 lg:px-4 py-2 lg:py-3 bg-white border border-emerald-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all text-sm lg:text-base">
                                        <option value="svenska">Svenska</option>
                                        <option value="engelska">Engelska</option>
                                        <option value="norska">Norska</option>
                                        <option value="danska">Danska</option>
                                        <option value="finska">Finska</option>
                                        <option value="tyska">Tyska</option>
                                        <option value="franska">Franska</option>
                                        <option value="spanska">Spanska</option>
                                        <option value="italienska">Italienska</option>
                                    </select>
                                    @error('text_language') <div class="text-sm text-red-600 mt-1 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $message }}
                                    </div> @enderror
                                </div>
                            </div>

                            <!-- Text på produkten/etiketten -->
                            <div class="bg-white rounded-xl p-4 border border-emerald-200">
                                <div class="flex items-center gap-2 mb-3">
                                    <input id="label_text_enabled" type="checkbox"
                                           wire:model.live="label_text_enabled"
                                           class="rounded text-emerald-600 focus:ring-emerald-500">
                                    <label for="label_text_enabled" class="text-sm font-semibold text-emerald-900">Text på produkten (etikett)</label>
                                </div>

                                <div x-show="labelTextEnabled"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 transform -translate-y-2"
                                     x-transition:enter-end="opacity-100 transform translate-y-0"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="opacity-100 transform translate-y-0"
                                     x-transition:leave-end="opacity-0 transform -translate-y-2"
                                     style="display: none;">
                                    <input wire:model="label_text" type="text"
                                           placeholder="Ex: Sommarens smak – Citron"
                                    class="w-full px-3 lg:px-4 py-2 lg:py-3 bg-gray-50 border border-emerald-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all text-sm lg:text-base">
                                    @error('label_text') <div class="text-sm text-red-600 mt-1 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $message }}
                                    </div> @enderror
                                </div>
                                <p class="text-xs text-emerald-700 mt-2">Aktivera för att lägga till specifik text på produktetiketten</p>
                            </div>
                        </div>
                    </div>

                    <!-- Logotyp-sektion - alltid synlig men frivillig -->
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-200 rounded-2xl p-4 lg:p-6">
                        <div class="flex items-center gap-3 mb-4 lg:mb-6">
                            <div class="w-10 h-10 lg:w-12 lg:h-12 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 lg:w-6 lg:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg lg:text-xl font-bold text-blue-900">Logotyp (frivilligt)</h3>
                                <p class="text-sm text-blue-700">Lägg till din logotyp på bilden</p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-blue-900 mb-2 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                </svg>
                                Länk till logotyp (PNG)
                            </label>
                            <input wire:model="logo_url" type="url"
                                   placeholder="https://exempel.se/logo.png"
                                   class="w-full px-3 lg:px-4 py-2 lg:py-3 bg-white border border-blue-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm lg:text-base">
                            @error('logo_url') <div class="text-sm text-red-600 mt-1 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $message }}
                            </div> @enderror
                            <div class="mt-2 p-3 bg-blue-100 border border-blue-200 rounded-xl">
                                <div class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div class="text-xs lg:text-sm text-blue-800">
                                        <p class="font-semibold mb-1">Tips för bästa resultat:</p>
                                        <ul class="space-y-1 text-blue-700">
                                            <li>• Använd transparent PNG utan bakgrund</li>
                                            <li>• Loggan placeras naturligt på produkten/etiketten</li>
                                            <li>• Inte som watermark över hela bilden</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bakgrund och stil -->
                    <div class="bg-gradient-to-br from-purple-50 to-pink-50 border border-purple-200 rounded-2xl p-4 lg:p-6">
                        <div class="flex items-center gap-3 mb-4 lg:mb-6">
                            <div class="w-10 h-10 lg:w-12 lg:h-12 bg-gradient-to-br from-purple-600 to-pink-600 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 lg:w-6 lg:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4h4a2 2 0 002-2V5z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg lg:text-xl font-bold text-purple-900">Bakgrund och stil</h3>
                                <p class="text-sm text-purple-700">Anpassa bildens bakgrund och visuella stil</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-purple-900 mb-2">Bakgrund</label>
                                <select wire:model.live="background_mode"
                                        class="w-full px-3 lg:px-4 py-2 lg:py-3 bg-white border border-purple-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all text-sm lg:text-base">
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
                                @error('background_mode') <div class="text-sm text-red-600 mt-1 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $message }}
                                </div> @enderror

                                <div class="mt-3 flex items-center gap-3">
                                    <input id="strict_background" type="checkbox"
                                           wire:model="strict_background"
                                           class="rounded text-purple-600 focus:ring-purple-500">
                                    <label for="strict_background" class="text-sm text-purple-800">Strikt bakgrund (ingen textur eller mönster)</label>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-purple-900 mb-2">
                                    <span x-show="bgMode !== 'custom_hex'" class="text-gray-500">Färg (endast för egen hex)</span>
                                    <span x-show="bgMode === 'custom_hex'">Hex-färg</span>
                                </label>
                                <input wire:model="background_hex" type="text"
                                       placeholder="#RRGGBB"
                                       :disabled="bgMode !== 'custom_hex'"
                                       :class="{
                                           'bg-white border-purple-300 text-gray-900': bgMode === 'custom_hex',
                                           'bg-gray-100 border-gray-200 text-gray-500 cursor-not-allowed': bgMode !== 'custom_hex'
                                       }"
                                       class="w-full px-3 lg:px-4 py-2 lg:py-3 border rounded-xl focus:ring-2 focus:ring-purple-500 transition-all text-sm lg:text-base">
                                @error('background_hex') <div class="text-sm text-red-600 mt-1 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $message }}
                                </div> @enderror
                                <p class="text-xs text-purple-700 mt-1" x-show="bgMode !== 'custom_hex'">
                                    Välj "Egen hex-färg" ovan för att aktivera fältet.
                                </p>
                            </div>
                        </div>

                        <div class="mt-4 lg:mt-6 grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-purple-900 mb-2">Stil/effekt</label>
                                <select wire:model="style"
                                        class="w-full px-3 lg:px-4 py-2 lg:py-3 bg-white border border-purple-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all text-sm lg:text-base">
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
                                @error('style') <div class="text-sm text-red-600 mt-1 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $message }}
                                </div> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-purple-900 mb-2">Plattform/format</label>
                                <select wire:model="platform"
                                        class="w-full px-3 lg:px-4 py-2 lg:py-3 bg-white border border-purple-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all text-sm lg:text-base">
                                    <option value="facebook_square">Facebook 1080x1080</option>
                                    <option value="facebook_story">Facebook/IG Story 1080x1920</option>
                                    <option value="instagram">Instagram 1080x1350</option>
                                    <option value="linkedin">LinkedIn 1080x1080</option>
                                    <option value="blog">Blogg 1200x628</option>
                                </select>
                                @error('platform') <div class="text-sm text-red-600 mt-1 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $message }}
                                </div> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Text-overlay -->
                    <div class="bg-gradient-to-br from-yellow-50 to-orange-50 border border-yellow-200 rounded-2xl p-4 lg:p-6">
                        <div class="flex items-center gap-3 mb-4 lg:mb-6">
                            <div class="w-10 h-10 lg:w-12 lg:h-12 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 lg:w-6 lg:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    <input id="overlay_enabled" type="checkbox"
                                           wire:model.live="overlay_enabled"
                                           class="rounded text-yellow-600 focus:ring-yellow-500">
                                    <label for="overlay_enabled" class="text-lg lg:text-xl font-bold text-yellow-900">Text-overlay</label>
                                </div>
                                <p class="text-sm text-yellow-700 mt-1">Lägg till text direkt på bilden</p>
                            </div>
                        </div>

                        <div x-show="overlayEnabled"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform scale-95"
                             x-transition:enter-end="opacity-100 transform scale-100"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 transform scale-100"
                             x-transition:leave-end="opacity-0 transform scale-95"
                             class="grid grid-cols-1 md:grid-cols-3 gap-4 lg:gap-6"
                             style="display: none;">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-yellow-900 mb-2">Overlay-text</label>
                                <input wire:model="overlay_text" type="text"
                                       placeholder="Ex: Ny smak! Fräsch & läskande"
                                       class="w-full px-3 lg:px-4 py-2 lg:py-3 bg-white border border-yellow-300 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-all text-sm lg:text-base">
                                @error('overlay_text') <div class="text-sm text-red-600 mt-1 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $message }}
                                </div> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-yellow-900 mb-2">Placering</label>
                                <select wire:model="overlay_position"
                                        class="w-full px-3 lg:px-4 py-2 lg:py-3 bg-white border border-yellow-300 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-all text-sm lg:text-base">
                                    <option value="auto">Auto</option>
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

                    <!-- Skapa från befintligt inlägg -->
                    <div class="bg-gradient-to-br from-indigo-50 to-purple-50 border border-indigo-200 rounded-2xl p-4 lg:p-6">
                        <div class="flex items-center gap-3 mb-4 lg:mb-6">
                            <div class="w-10 h-10 lg:w-12 lg:h-12 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 lg:w-6 lg:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    <input id="from_post_enabled" type="checkbox"
                                           wire:model.live="from_post_enabled"
                                           class="rounded text-indigo-600 focus:ring-indigo-500">
                                    <label for="from_post_enabled" class="text-lg lg:text-xl font-bold text-indigo-900">Skapa bild utifrån ett skapat inlägg</label>
                                </div>
                                <p class="text-sm text-indigo-700 mt-1">Bilden anpassas till inläggets ämne, ton och mål</p>
                            </div>
                        </div>

                        <div x-show="fromPostEnabled"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform scale-95"
                             x-transition:enter-end="opacity-100 transform scale-100"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 transform scale-100"
                             x-transition:leave-end="opacity-0 transform scale-95"
                             style="display: none;">
                            <div>
                                <label class="block text-sm font-semibold text-indigo-900 mb-2">Välj inlägg</label>
                                <select wire:model="from_post_id"
                                        class="w-full px-3 lg:px-4 py-2 lg:py-3 bg-white border border-indigo-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all text-sm lg:text-base">
                                    <option value="">Välj inlägg...</option>
                                    @foreach($availablePosts as $p)
                                        <option value="{{ $p['id'] }}">{{ $p['title'] }}</option>
                                    @endforeach
                                </select>
                                @error('from_post_id') <div class="text-sm text-red-600 mt-1 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $message }}
                                </div> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Genererad prompt (förhandsvisning) -->
                    <div class="bg-slate-900 rounded-2xl p-4 lg:p-6 border border-gray-700">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 lg:w-12 lg:h-12 bg-slate-700 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 lg:w-6 lg:h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg lg:text-xl font-bold text-white">Genererad prompt</h3>
                                <p class="text-sm text-slate-400">Förhandsvisning av AI-instruktionerna</p>
                            </div>
                        </div>
                        <pre class="text-xs lg:text-sm bg-slate-800 text-slate-100 rounded-xl p-4 overflow-auto max-h-56 whitespace-pre-wrap border border-slate-700">{{ $finalPrompt ?: 'Prompten visas här efter att du klickat på "Generera bild".' }}</pre>
                    </div>

                    <!-- Generera-knapp och status -->
                    <div class="bg-gradient-to-r from-gray-50 to-indigo-50 border border-gray-200 rounded-2xl p-4 lg:p-6">
                        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
                            <div>
                                <button wire:click="submit"
                                        wire:loading.attr="disabled"
                                        wire:target="submit"
                                        @disabled(!$aiEnabled || ($quota !== null && $used >= $quota))
                                        class="inline-flex items-center px-6 lg:px-8 py-3 lg:py-4 rounded-xl lg:rounded-2xl text-white font-bold transition-all duration-200 text-sm lg:text-base shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                                    <span wire:loading.remove wire:target="submit" class="flex items-center gap-2">
                                        <svg class="w-5 h-5 lg:w-6 lg:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                        </svg>
                                        Generera bild
                                    </span>
                                    <span wire:loading wire:target="submit" class="flex items-center gap-2">
                                        <svg class="animate-spin w-5 h-5 lg:w-6 lg:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                        </svg>
                                        Genererar...
                                    </span>
                                </button>
                            </div>
                            <div class="text-center lg:text-right">
                                <p class="text-sm lg:text-base text-gray-600 mb-1">Bilden sparas automatiskt i ditt bibliotek</p>
                                <p class="text-xs text-gray-500">Genereringsprocessen tar vanligtvis 30-60 sekunder</p>
                            </div>
                        </div>

                        @if($error)
                            <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-xl">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="text-sm lg:text-base text-red-700 font-medium">{{ $error }}</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Sidebar med info och tips -->
                <div class="xl:col-span-1 space-y-4 lg:space-y-6">
                    <div class="bg-gradient-to-br from-slate-50 to-gray-50 border border-slate-200 rounded-2xl p-4 lg:p-6">
                        <div class="flex items-center gap-3 mb-4">
                            <svg class="w-6 h-6 lg:w-8 lg:h-8 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            <h3 class="text-base lg:text-lg font-bold text-gray-800">Status</h3>
                        </div>
                        <div class="text-sm lg:text-base text-gray-600 space-y-2">
                            <p>Jobb köas och körs i turordning. Följ bannern ovan eller uppdatera listan nedan.</p>
                            <div class="p-3 bg-indigo-50 text-indigo-800 border border-indigo-200 rounded-xl text-xs lg:text-sm">
                                <strong>Tips:</strong> Generera flera bilder – de läggs i kö och dyker upp i listan automatiskt.
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-emerald-50 to-teal-50 border border-emerald-200 rounded-2xl p-4 lg:p-6">
                        <div class="flex items-center gap-3 mb-4">
                            <svg class="w-6 h-6 lg:w-8 lg:h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                            <h3 class="text-base lg:text-lg font-bold text-emerald-800">Tips för bästa resultat</h3>
                        </div>
                        <div class="text-xs lg:text-sm text-emerald-800 space-y-3">
                            <div>
                                <h4 class="font-semibold mb-1">Produktbilder:</h4>
                                <ul class="space-y-1 text-emerald-700">
                                    <li>• Beskriv produkten tydligt</li>
                                    <li>• Använd transparenta PNG-logotyper</li>
                                    <li>• Välj lämplig bakgrund för produkttyp</li>
                                </ul>
                            </div>
                            <div>
                                <h4 class="font-semibold mb-1">Sociala medier:</h4>
                                <ul class="space-y-1 text-emerald-700">
                                    <li>• Välj rätt format för plattform</li>
                                    <li>• Håll text kortfattad och tydlig</li>
                                    <li>• Testa olika stilar för variation</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bildbibliotek -->
    <div class="bg-white rounded-2xl lg:rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-gray-50 to-emerald-50 p-4 lg:p-6 border-b border-gray-100">
            <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
                <div class="flex items-center gap-3 lg:gap-4">
                    <div class="w-10 h-10 lg:w-12 lg:h-12 bg-gradient-to-br from-emerald-600 to-teal-600 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 lg:w-6 lg:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl lg:text-2xl font-bold text-gray-900 mb-1">Dina genererade bilder</h2>
                        <p class="text-sm lg:text-base text-gray-600">Klicka på en bild för att förhandsgranska</p>
                    </div>
                </div>
                <button wire:click="loadList"
                        class="inline-flex items-center gap-2 px-4 lg:px-6 py-2 lg:py-3 bg-white border border-emerald-200 text-emerald-700 rounded-xl lg:rounded-2xl hover:bg-emerald-50 text-sm lg:text-base font-medium transition-all duration-200">
                    <svg class="w-4 h-4 lg:w-5 lg:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Uppdatera
                </button>
            </div>
        </div>

        <div class="p-4 lg:p-6">
            @if(empty($items))
                <div class="py-8 lg:py-12 text-center">
                    <svg class="w-16 h-16 lg:w-20 lg:h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m2-2l1.586-1.586a2 2 0 012.828 0L22 14M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <h3 class="text-lg lg:text-xl font-bold text-gray-700 mb-2">Inga genererade bilder ännu</h3>
                    <p class="text-gray-500">Skapa din första bild med formuläret ovan</p>
                </div>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3 lg:gap-4">
                    @foreach($items as $it)
                        <button type="button"
                                wire:click="openPreview({{ $it['id'] }})"
                                class="group relative rounded-xl lg:rounded-2xl overflow-hidden border-2 border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 hover:border-indigo-300 transition-all duration-200 transform hover:scale-105">
                            <img src="{{ route('assets.thumb', $it['id']) }}" alt="{{ $it['name'] }}"
                                 class="w-full h-32 lg:h-40 object-cover" />
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-all duration-200 flex items-end">
                                <div class="w-full p-2 lg:p-3 text-[10px] lg:text-xs text-white/90 truncate text-left bg-gradient-to-t from-black/50 to-transparent">
                                    {{ $it['name'] }}
                                </div>
                            </div>
                            <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                <div class="w-8 h-8 bg-white/90 backdrop-blur rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </div>
                            </div>
                        </button>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Förhandsvisningsmodal -->
    @if($showPreview)
        <div class="fixed inset-0 z-50"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" wire:click="closePreview"></div>
            <div class="absolute inset-0 flex items-center justify-center p-4">
                <div class="bg-white rounded-2xl lg:rounded-3xl shadow-2xl max-w-[90vw] max-h-[90vh] w-full max-w-4xl flex flex-col"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="transform scale-95"
                     x-transition:enter-end="transform scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="transform scale-100"
                     x-transition:leave-end="transform scale-95">

                    <div class="flex items-center justify-between p-4 lg:p-6 border-b border-gray-100">
                        <h3 class="text-lg lg:text-xl font-bold text-gray-800 truncate pr-4">{{ $previewName }}</h3>
                        <div class="flex items-center gap-2 lg:gap-3">
                            @if($previewUrl)
                                <a href="{{ $previewUrl }}" target="_blank"
                                   class="inline-flex items-center gap-2 px-3 lg:px-4 py-2 lg:py-3 bg-indigo-100 text-indigo-700 rounded-xl hover:bg-indigo-200 text-sm lg:text-base font-medium transition-all duration-200">
                                    <svg class="w-4 h-4 lg:w-5 lg:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                    <span class="hidden sm:inline">Öppna i ny flik</span>
                                </a>
                            @endif
                            <button type="button" wire:click="closePreview"
                                    class="p-2 lg:p-3 rounded-xl hover:bg-gray-100 transition-colors duration-200">
                                <svg class="w-5 h-5 lg:w-6 lg:h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="flex-1 overflow-hidden p-4 lg:p-6">
                        @if($previewUrl)
                            <img src="{{ $previewUrl }}" alt="{{ $previewName }}"
                                 class="w-full h-auto object-contain max-h-[70vh] rounded-xl lg:rounded-2xl mx-auto" />
                        @else
                            <div class="flex items-center justify-center h-64 lg:h-80 bg-gray-50 rounded-xl lg:rounded-2xl">
                                <div class="text-center">
                                    <svg class="w-12 h-12 lg:w-16 lg:h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p class="text-gray-500">Kunde inte ladda bilden</p>
                                </div>
                            </div>
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
