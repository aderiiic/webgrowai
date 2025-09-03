<div>
    <div class="min-h-screen flex items-center justify-center px-4 py-8">
        <div class="w-full max-w-3xl">
            <!-- Progress: 1..7 -->
            <div class="mb-8">
                <div class="flex items-center justify-center space-x-4">
                    @for($i = 1; $i <= 8; $i++)
                        <div class="flex items-center">
                            <div class="flex items-center justify-center w-10 h-10 rounded-2xl {{ $step >= $i ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-md' : 'bg-gray-100 text-gray-500 border border-gray-200' }} font-semibold">
                                @if($step > $i)
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                @else
                                    {{ $i }}
                                @endif
                            </div>
                            @if($i < 8)
                                <div class="w-16 h-0.5 {{ $step > $i ? 'bg-gradient-to-r from-indigo-600 to-purple-600' : 'bg-gray-200' }}"></div>
                            @endif
                        </div>
                    @endfor
                </div>
            </div>

            <!-- Main card -->
            <div class="bg-white/90 backdrop-blur-lg rounded-3xl shadow-2xl border border-gray-100/50 overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 px-8 py-6">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                        </div>
                        <h1 class="text-3xl font-bold text-white">Kom igång med WebGrow AI</h1>
                        <p class="text-indigo-100 mt-2">Vi hjälper dig att sätta upp allt på några minuter</p>
                    </div>
                </div>

                <!-- Content -->
                <div class="px-8 py-8">
                    <!-- Steg 1: Skapa första sajt -->
                    @if($step === 1)
                        <div class="space-y-6">
                            <div class="text-center">
                                <h2 class="text-2xl font-bold text-gray-900 mb-2">Lägg till din webbplats</h2>
                                <p class="text-gray-600">Vi behöver veta vilken sajt du vill optimera</p>
                            </div>

                            @if($sitesQuotaExceeded)
                                <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl text-amber-800">
                                    Du har nått din sajtkvot ({{ $sitesUsed }}/{{ $sitesLimit }}). Uppgradera plan eller ta bort en sajt för att fortsätta.
                                </div>
                            @endif

                            <div class="space-y-5">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Vad heter din sajt? *</label>
                                    <input
                                        type="text"
                                        wire:model.defer="site_name"
                                        class="w-full px-4 py-3 rounded-2xl border border-gray-300 bg-white/80 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 placeholder-gray-400 transition"
                                        placeholder="T.ex. Min Företagssajt"
                                        @disabled($sitesQuotaExceeded)
                                    >
                                    @error('site_name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Vad är din webbadress? *</label>
                                    <input
                                        type="url"
                                        wire:model.defer="site_url"
                                        class="w-full px-4 py-3 rounded-2xl border border-gray-300 bg-white/80 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 placeholder-gray-400 transition"
                                        placeholder="https://minwebbplats.se"
                                        @disabled($sitesQuotaExceeded)
                                    >
                                    @error('site_url')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="pt-2 flex items-center justify-between">
                                <button
                                    wire:click="prev"
                                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 hover:border-gray-400 transition"
                                    @disabled($step === 1)
                                >
                                    Tillbaka
                                </button>

                                <button
                                    wire:click="{{ $primarySiteId ? 'updateSite' : 'createSite' }}"
                                    class="inline-flex items-center justify-center px-6 py-3 rounded-2xl bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold shadow-lg hover:from-indigo-700 hover:to-purple-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition disabled:opacity-60 disabled:cursor-not-allowed"
                                    wire:loading.attr="disabled"
                                    wire:target="{{ $primarySiteId ? 'updateSite' : 'createSite' }}"
                                    @disabled($sitesQuotaExceeded)
                                >
                                    <span>Fortsätt</span>
                                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                    @elseif($step === 2)
                        <!-- Steg 2: Välj plattform -->
                        <div class="space-y-6">
                            <div class="text-center">
                                <h2 class="text-2xl font-bold text-gray-900 mb-2">Vilken plattform använder du?</h2>
                                <p class="text-gray-600">Vi behöver veta vart din webbplats är byggd för att kunna optimera den</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <label class="border-2 rounded-2xl p-6 flex flex-col items-center gap-4 cursor-pointer hover:shadow-md transition {{ $provider==='wordpress' ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200 hover:border-gray-300' }}">
                                    <input type="radio" class="hidden" wire:model.live="provider" value="wordpress">
                                    <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M21.469 6.825c.84 1.537 1.318 3.3 1.318 5.175 0 3.979-2.156 7.456-5.363 9.325l3.295-9.527c.615-1.54.82-2.771.82-3.864 0-.405-.026-.78-.07-1.11m-7.981.105c.647-.03 1.232-.105 1.232-.105.582-.075.514-.93-.067-.899 0 0-1.755.135-2.88.135-1.064 0-2.85-.15-2.85-.15-.584-.03-.661.854-.075.884 0 0 .54.061 1.125.09l1.68 4.605-2.37 7.08L5.354 6.9c.649-.03 1.234-.1 1.234-.1.585-.075.516-.93-.065-.896 0 0-1.746.138-2.874.138-.2 0-.438-.008-.69-.015C4.911 3.15 8.235 1.215 12 1.215c2.809 0 5.365 1.072 7.286 2.833-.046-.003-.091-.009-.141-.009-1.06 0-1.812.923-1.812 1.914 0 .89.513 1.643 1.06 2.531.411.72.89 1.643.89 2.977 0 .915-.354 1.994-.821 3.479l-1.075 3.585-3.9-11.61.001.014z"/>
                                        </svg>
                                    </div>
                                    <div class="text-center">
                                        <span class="font-semibold text-lg">WordPress</span>
                                        <p class="text-xs text-gray-500 mt-1">Vanligaste CMS-systemet</p>
                                    </div>
                                </label>

                                <label class="border-2 rounded-2xl p-6 flex flex-col items-center gap-4 cursor-pointer hover:shadow-md transition {{ $provider==='shopify' ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200 hover:border-gray-300' }}">
                                    <input type="radio" class="hidden" wire:model.live="provider" value="shopify">
                                    <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M15.337 2.001c-0.907,0-1.76,0.655-2.332,1.603-0.458-0.174-0.985-0.266-1.562-0.266-2.156,0-4.003,1.299-5.229,3.284-1.117-0.426-2.162-0.37-2.805,0.273-0.764,0.763-0.764,2.212,0,2.975l8.834,8.834c0.381,0.381,0.893,0.596,1.438,0.596s1.057-0.215,1.438-0.596l8.834-8.834c0.764-0.763,0.764-2.212,0-2.975-0.643-0.643-1.688-0.699-2.805-0.273-1.226-1.985-3.073-3.284-5.229-3.284-0.577,0-1.104,0.092-1.562,0.266C17.097,2.656,16.244,2.001,15.337,2.001z"/>
                                        </svg>
                                    </div>
                                    <div class="text-center">
                                        <span class="font-semibold text-lg">Shopify</span>
                                        <p class="text-xs text-gray-500 mt-1">E-handelsplattform</p>
                                    </div>
                                </label>

                                <label class="border-2 rounded-2xl p-6 flex flex-col items-center gap-4 cursor-pointer hover:shadow-md transition {{ $provider==='custom' ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200 hover:border-gray-300' }}">
                                    <input type="radio" class="hidden" wire:model.live="provider" value="custom">
                                    <div class="w-12 h-12 bg-purple-500 rounded-xl flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                        </svg>
                                    </div>
                                    <div class="text-center">
                                        <span class="font-semibold text-lg">Annan</span>
                                        <p class="text-xs text-gray-500 mt-1">Anpassad webbplats</p>
                                    </div>
                                </label>
                            </div>

                            @if($integrationConnected && $connectedProvider)
                                <div class="p-3 mt-2 rounded-xl bg-emerald-50 border border-emerald-200 text-sm text-emerald-800">
                                    ✅ Din sajt är redan kopplad till <strong>{{ ucfirst($connectedProvider) }}</strong>
                                </div>
                            @endif

                            <div class="pt-2 flex items-center justify-between">
                                <button wire:click="prev" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50">
                                    Tillbaka
                                </button>
                                <button wire:click="next" class="inline-flex items-center justify-center px-6 py-3 rounded-2xl bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold shadow-lg hover:from-indigo-700 hover:to-purple-700 transition">
                                    <span>Fortsätt</span>
                                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                    @elseif($step === 3)
                        <div class="space-y-6">
                            <div class="text-center">
                                <h2 class="text-2xl font-bold text-gray-900 mb-2">Berätta om din verksamhet</h2>
                                <p class="text-gray-600">Detta hjälper AI:n att ge mer relevanta förslag. Du kan fylla i mer senare.</p>
                            </div>

                            <div class="grid grid-cols-1 gap-5">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Bransch</label>
                                    <input type="text" wire:model.defer="industry" class="w-full px-4 py-3 rounded-2xl border border-gray-300 bg-white/80 shadow-sm focus:ring-2 focus:ring-indigo-500" placeholder="T.ex. Läxhjälp, Bygg, Advokatbyrå">
                                    @error('industry') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Beskrivning av verksamheten</label>
                                    <textarea wire:model.defer="business_description" rows="4" class="w-full px-4 py-3 rounded-2xl border border-gray-300 bg-white/80 shadow-sm focus:ring-2 focus:ring-indigo-500" placeholder="Vad erbjuder ni? Tjänster/produkter, geografi, differentiatorer..."></textarea>
                                    @error('business_description') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Målgrupp</label>
                                    <input type="text" wire:model.defer="target_audience" class="w-full px-4 py-3 rounded-2xl border border-gray-300 bg-white/80 shadow-sm focus:ring-2 focus:ring-indigo-500" placeholder="T.ex. Föräldrar till högstadieelever i Stockholm">
                                    @error('target_audience') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <div class="grid md:grid-cols-2 gap-5">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Tonalitet/Brand voice</label>
                                        <input type="text" wire:model.defer="brand_voice" class="w-full px-4 py-3 rounded-2xl border border-gray-300 bg-white/80 shadow-sm focus:ring-2 focus:ring-indigo-500" placeholder="T.ex. Varm, professionell och pedagogisk">
                                        @error('brand_voice') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Språk/Locale</label>
                                        <input type="text" wire:model.defer="locale" class="w-full px-4 py-3 rounded-2xl border border-gray-300 bg-white/80 shadow-sm focus:ring-2 focus:ring-indigo-500" placeholder="sv_SE">
                                        @error('locale') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="pt-2 flex items-center justify-between">
                                <button wire:click="prev" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50">
                                    Tillbaka
                                </button>
                                <div class="flex items-center gap-3">
                                    <button wire:click="next" class="inline-flex items-center justify-center px-6 py-3 rounded-2xl bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold shadow-lg hover:from-indigo-700 hover:to-purple-700 transition">
                                        <span>Spara & fortsätt</span>
                                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                    @elseif($step === 4)
                        <!-- Steg 3: Koppla integration (endast för WordPress) -->
                        <div class="space-y-6">
                            <div class="text-center">
                                <h2 class="text-2xl font-bold text-gray-900 mb-2">
                                    @if($provider === 'wordpress')
                                        Koppla din WordPress-sajt
                                    @else
                                        Plattform vald: {{ $this->providerLabel() }}
                                    @endif
                                </h2>
                                <p class="text-gray-600">
                                    @if($provider === 'wordpress')
                                        Vi behöver tillgång till din WordPress-sajt för att kunna optimera och publicera innehåll
                                    @else
                                        Bra val! Vi går vidare till nästa steg
                                    @endif
                                </p>
                            </div>

                            @if($provider === 'wordpress')
                                <div class="p-4 rounded-xl border {{ $integrationConnected ? 'bg-emerald-50 border-emerald-200' : 'bg-amber-50 border-amber-200' }}">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-3 h-3 rounded-full {{ $integrationConnected ? 'bg-emerald-500' : 'bg-amber-500' }}"></div>
                                            <div class="text-sm {{ $integrationConnected ? 'text-emerald-800' : 'text-amber-800' }}">
                                                {{ $integrationConnected ? '✅ WordPress är kopplad' : '⏳ WordPress är inte kopplad ännu' }}
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-3">
                                            @if($primarySiteId)
                                                <button wire:click="goConnect" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700">
                                                    {{ $integrationConnected ? 'Hantera koppling' : 'Koppla WordPress' }}
                                                </button>
                                            @endif
                                            <button wire:click="refreshStatus" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50">
                                                Uppdatera
                                            </button>
                                        </div>
                                    </div>
                                    @error('integrationConnected')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Hjälpsektion för WordPress -->
                                <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                                    <h3 class="font-semibold text-blue-900 mb-3">
                                        Behöver du hjälp?
                                    </h3>
                                    <p class="text-blue-800 text-sm mb-4">
                                        Vi hjälper gärna till att koppla din WordPress-sajt. Skicka ett mejl eller kontakta oss på Facebook för snabb respons.
                                    </p>
                                    <div class="flex gap-3">
                                        <a href="mailto:info@webbi.se" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                            Skicka mejl
                                        </a>
                                        <a href="https://facebook.com/webgrowai" target="_blank" class="inline-flex items-center px-4 py-2 bg-[#1877F2] text-white rounded-lg hover:bg-[#166FE5] text-sm">
                                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                            </svg>
                                            Facebook
                                        </a>
                                    </div>
                                </div>
                            @else
                                <!-- För Shopify och Custom -->
                                <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-6 text-center">
                                    <div class="w-16 h-16 bg-emerald-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-bold text-emerald-900 mb-2">Perfekt!</h3>
                                    <p class="text-emerald-800">
                                        {{ $provider === 'shopify' ? 'Snyggt! Nästan färdiga.' : 'Snyggt! Nästan i mål.' }}
                                    </p>
                                </div>
                            @endif

                            <div class="pt-4 flex items-center justify-between">
                                <button wire:click="prev" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50">
                                    Tillbaka
                                </button>
                                <button
                                    wire:click="next"
                                    class="inline-flex items-center justify-center px-6 py-3 rounded-2xl bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold shadow-lg hover:from-indigo-700 hover:to-purple-700 transition"
                                >
                                    <span>Fortsätt</span>
                                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                    @elseif($step === 5)
                        <!-- Steg 4: Lead tracker -->
                        <div class="space-y-6">
                            <div class="text-center">
                                <h2 class="text-2xl font-bold text-gray-900 mb-2">Installera besökarspårning</h2>
                                <p class="text-gray-600">Så att vi kan se vilka som besöker din sajt och vad de gör</p>
                            </div>

                            <div class="space-y-4">
                                <div class="p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200/50">
                                    <div class="flex flex-wrap gap-3">
                                        <a href="{{ route('onboarding.tracker') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-2M7 9l3 3 6-6"/>
                                            </svg>
                                            Öppna installationsguide
                                        </a>
                                        <button wire:click.prevent="markLeadTrackerReady" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Jag har installerat det
                                        </button>
                                    </div>
                                    <div class="mt-3 text-sm {{ $leadTrackerReady ? 'text-emerald-700' : 'text-gray-700' }}">
                                        Status: {{ $leadTrackerReady ? '✅ Klart' : '⏳ Inte installerat än' }}
                                    </div>
                                    @error('leadTrackerReady')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
                                    <h4 class="font-semibold text-yellow-900 mb-2">Vad gör besökarspårningen?</h4>
                                    <ul class="text-sm text-yellow-800 space-y-1">
                                        <li>• Ser vilka sidor som besöks mest</li>
                                        <li>• Spårar när någon klickar på viktiga knappar</li>
                                        <li>• Hjälper oss förstå vad som fungerar på din sajt</li>
                                        <li>• All data är anonym och GDPR-säker</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="pt-4 flex items-center justify-between">
                                <button wire:click="prev" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50">
                                    Tillbaka
                                </button>
                                <button wire:click="next" class="inline-flex items-center justify-center px-6 py-3 rounded-2xl bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold shadow-lg hover:from-indigo-700 hover:to-purple-700 transition">
                                    <span>Fortsätt</span>
                                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                    @elseif($step === 6)
                        <!-- Steg 5: Sociala medier (valfritt) -->
                        <div class="space-y-6">
                            <div class="text-center">
                                <h2 class="text-2xl font-bold text-gray-900 mb-2">Koppla sociala medier (valfritt)</h2>
                                <p class="text-gray-600">Så vi kan publicera inlägg åt dig på Facebook och Instagram</p>
                            </div>

                            <div class="p-4 bg-gradient-to-r from-pink-50 to-purple-50 rounded-xl border border-pink-200/50">
                                <div class="flex flex-wrap gap-3">
                                    <a href="{{ route('settings.social') }}" class="inline-flex items-center px-4 py-2 bg-pink-600 text-white rounded-xl hover:bg-pink-700">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                                        </svg>
                                        Öppna sociala inställningar
                                    </a>
                                    <button wire:click="markSocialConnected" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700">
                                        Markera som klart
                                    </button>
                                </div>
                                <div class="mt-3 text-sm {{ $socialConnected ? 'text-emerald-700' : 'text-gray-700' }}">
                                    Status: {{ $socialConnected ? '✅ Klart' : '⏳ Valfritt (kan hoppas över)' }}
                                </div>
                            </div>

                            <div class="pt-4 flex items-center justify-between">
                                <button wire:click="prev" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50">
                                    Tillbaka
                                </button>
                                <div class="flex items-center gap-3">
                                    <button wire:click="skip" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50">
                                        Hoppa över
                                    </button>
                                    <button wire:click="next" class="inline-flex items-center justify-center px-6 py-3 rounded-2xl bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold shadow-lg hover:from-indigo-700 hover:to-purple-700 transition">
                                        <span>Fortsätt</span>
                                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                    @elseif($step === 7)
                        <!-- Steg 6: Mailchimp (valfritt) -->
                        <div class="space-y-6">
                            <div class="text-center">
                                <h2 class="text-2xl font-bold text-gray-900 mb-2">Koppla ditt nyhetsbrev (valfritt)</h2>
                                <p class="text-gray-600">Så vi kan skicka ut kampanjer till dina prenumeranter</p>
                            </div>

                            <div class="p-4 bg-gradient-to-r from-yellow-50 to-orange-50 rounded-xl border border-yellow-200/50">
                                <div class="flex flex-wrap gap-3">
                                    <a href="{{ route('settings.mailchimp') }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white rounded-xl hover:bg-yellow-700">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                        Öppna Mailchimp-inställningar
                                    </a>
                                    <button wire:click="markMailchimpConnected" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700">
                                        Markera som klart
                                    </button>
                                </div>
                                <div class="mt-3 text-sm {{ $mailchimpConnected ? 'text-emerald-700' : 'text-gray-700' }}">
                                    Status: {{ $mailchimpConnected ? '✅ Klart' : '⏳ Valfritt (kan hoppas över)' }}
                                </div>
                            </div>

                            <div class="pt-4 flex items-center justify-between">
                                <button wire:click="prev" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50">
                                    Tillbaka
                                </button>
                                <div class="flex items-center gap-3">
                                    <button wire:click="skip" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50">
                                        Hoppa över
                                    </button>
                                    <button wire:click="next" class="inline-flex items-center justify-center px-6 py-3 rounded-2xl bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold shadow-lg hover:from-indigo-700 hover:to-purple-700 transition">
                                        <span>Fortsätt</span>
                                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                    @elseif($step === 8)
                        <!-- Steg 8: Weekly Digest -->
                        <div class="space-y-6">
                            <div class="text-center">
                                <h2 class="text-2xl font-bold text-gray-900 mb-2">Veckorapport</h2>
                                <p class="text-gray-600">Vill du få en sammanfattning varje vecka med vad som hänt? (kan hoppas över)</p>
                            </div>

                            <div class="p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200/50">
                                <div class="flex flex-wrap gap-3">
                                    <a href="{{ route('settings.weekly') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                        </svg>
                                        Öppna inställningar
                                    </a>
                                    <button wire:click="markWeeklyConfigured" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700">
                                        Markera som klart
                                    </button>
                                </div>
                                <div class="mt-3 text-sm {{ $weeklyConfigured ? 'text-emerald-700' : 'text-gray-700' }}">
                                    Status: {{ $weeklyConfigured ? '✅ Klart' : '⏳ Valfritt (kan hoppas över)' }}
                                </div>
                                @error('weeklyConfigured')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="pt-4 flex items-center justify-between">
                                <button wire:click="prev" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 hover:border-gray-400 transition">
                                    Tillbaka
                                </button>
                                <div class="flex items-center gap-3">
                                    <button
                                        wire:click="goDashboard"
                                        class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 hover:border-gray-400 transition"
                                    >
                                        Hoppa över och starta
                                    </button>
                                    <button
                                        wire:click="complete"
                                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-semibold rounded-xl hover:from-emerald-700 hover:to-teal-700 shadow-lg transition"
                                    >
                                        <span>Slutför och starta</span>
                                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
