<div>
    <div class="min-h-screen flex items-center justify-center px-4 py-8">
        <div class="w-full max-w-3xl">
            <!-- Progress: 1..7 -->
            <div class="mb-8">
                <div class="flex items-center justify-center space-x-4">
                    @for($i = 1; $i <= 7; $i++)
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
                            @if($i < 7)
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
                        <h1 class="text-3xl font-bold text-white">Onboarding</h1>
                        <p class="text-indigo-100 mt-2">Låt oss komma igång med WebGrow AI</p>
                    </div>
                </div>

                <!-- Content -->
                <div class="px-8 py-8">
                    <!-- Steg 1: Skapa första sajt -->
                    @if($step === 1)
                        <div class="space-y-6">
                            <div class="text-center">
                                <h2 class="text-2xl font-bold text-gray-900 mb-2">Steg 1: Lägg till första sajten</h2>
                                <p class="text-gray-600">Registrera din webbplats för att komma igång</p>
                            </div>

                            @if($sitesQuotaExceeded)
                                <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl text-amber-800">
                                    Du har nått din sajtkvot ({{ $sitesUsed }}/{{ $sitesLimit }}). Uppgradera plan eller ta bort en sajt för att fortsätta.
                                </div>
                            @endif

                            <div class="space-y-5">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Sajtnamn *</label>
                                    <input
                                        type="text"
                                        wire:model.defer="site_name"
                                        class="w-full px-4 py-3 rounded-2xl border border-gray-300 bg-white/80 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 placeholder-gray-400 transition"
                                        placeholder="Min Webbplats"
                                        @disabled($sitesQuotaExceeded)
                                    >
                                    @error('site_name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">URL *</label>
                                    <input
                                        type="url"
                                        wire:model.defer="site_url"
                                        class="w-full px-4 py-3 rounded-2xl border border-gray-300 bg-white/80 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 placeholder-gray-400 transition"
                                        placeholder="https://example.com"
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
                                    wire:click="createSite"
                                    class="inline-flex items-center justify-center px-6 py-3 rounded-2xl bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold shadow-lg hover:from-indigo-700 hover:to-purple-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition disabled:opacity-60 disabled:cursor-not-allowed"
                                    wire:loading.attr="disabled"
                                    wire:target="createSite"
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
                                <h2 class="text-2xl font-bold text-gray-900 mb-2">Steg 2: Välj plattform</h2>
                                <p class="text-gray-600">Var ligger din sajt?</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <label class="border rounded-2xl p-4 flex flex-col items-center gap-2 cursor-pointer hover:shadow {{ $provider==='wordpress' ? 'ring-2 ring-indigo-500' : '' }}">
                                    <input type="radio" class="hidden" wire:model.live="provider" value="wordpress">
                                    <span class="font-semibold">WordPress</span>
                                    <span class="text-xs text-gray-500">WP REST API</span>
                                </label>
                                <label class="border rounded-2xl p-4 flex flex-col items-center gap-2 cursor-pointer hover:shadow {{ $provider==='shopify' ? 'ring-2 ring-indigo-500' : '' }}">
                                    <input type="radio" class="hidden" wire:model.live="provider" value="shopify">
                                    <span class="font-semibold">Shopify</span>
                                    <span class="text-xs text-gray-500">Admin API (OAuth)</span>
                                </label>
                                <label class="border rounded-2xl p-4 flex flex-col items-center gap-2 cursor-pointer hover:shadow {{ $provider==='custom' ? 'ring-2 ring-indigo-500' : '' }}">
                                    <input type="radio" class="hidden" wire:model.live="provider" value="custom">
                                    <span class="font-semibold">Custom</span>
                                    <span class="text-xs text-gray-500">Sitemap eller eget API</span>
                                </label>
                            </div>

                            @if($integrationConnected && $connectedProvider)
                                <div class="p-3 mt-2 rounded-xl bg-emerald-50 border border-emerald-200 text-sm text-emerald-800">
                                    Redan kopplad plattform: <strong>{{ ucfirst($connectedProvider) }}</strong>. Du kan byta val om du vill, men nuvarande koppling används.
                                </div>
                            @endif

                            <div class="pt-2 flex items-center justify-between">
                                <button wire:click="prev" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50">
                                    Tillbaka
                                </button>
                                <button wire:click="next" class="modern-btn-primary">
                                    <span>Fortsätt</span>
                                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                    @elseif($step === 3)
                        <!-- Steg 3: Koppla integration -->
                        <div class="space-y-6">
                            <div class="text-center">
                                <h2 class="text-2xl font-bold text-gray-900 mb-2">
                                    Steg 3: Koppla {{ $this->providerLabel() }}
                                </h2>
                                <p class="text-gray-600">Koppla din sajt för att kunna publicera innehåll och köra analyser</p>
                            </div>

                            <div class="p-4 rounded-xl border {{ $integrationConnected ? 'bg-emerald-50 border-emerald-200' : 'bg-amber-50 border-amber-200' }}">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-3 h-3 rounded-full {{ $integrationConnected ? 'bg-emerald-500' : 'bg-amber-500' }}"></div>
                                        <div class="text-sm {{ $integrationConnected ? 'text-emerald-800' : 'text-amber-800' }}">
                                            {{ $integrationConnected ? 'Integration är kopplad' : 'Ingen integration kopplad ännu' }}
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        @if($primarySiteId)
                                            <button wire:click="goConnect" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50">
                                                Hantera koppling
                                            </button>
                                        @endif
                                        <button wire:click="refreshStatus" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50">
                                            Uppdatera status
                                        </button>
                                    </div>
                                </div>
                                @error('integrationConnected')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror

                                @if($provider === 'shopify' && !$integrationConnected)
                                    <div class="mt-4 grid md:grid-cols-3 gap-3">
                                        <div class="md:col-span-2">
                                            <label class="block text-sm font-medium mb-1">Shop domain</label>
                                            <input type="text" wire:model.defer="shopify_shop" class="w-full border rounded p-2" placeholder="my-shop.myshopify.com">
                                            @error('shopify_shop') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="flex items-end">
                                            <button wire:click="startShopifyConnect" class="px-4 py-2 bg-[#95BF47] text-white rounded w-full">
                                                Anslut med Shopify
                                            </button>
                                        </div>
                                    </div>
                                    <p class="mt-2 text-xs text-gray-600">
                                        Du omdirigeras till Shopify för att godkänna behörigheter. Efteråt återvänder du hit.
                                    </p>
                                @elseif($provider === 'custom' && !$integrationConnected)
                                    <p class="mt-3 text-xs text-gray-600">
                                        Tips: För Custom kan du välja sitemap‑läge eller API‑läge när du öppnar kopplingen.
                                    </p>
                                @endif
                            </div>

                            <!-- Vad kan du göra härnäst? (insiktskort) -->
                            <div class="mt-6">
                                <h3 class="text-lg font-semibold mb-3">Vad kan du göra härnäst?</h3>
                                <div class="grid md:grid-cols-2 gap-4">
                                    <a href="{{ route('seo.audit.history') }}" class="block p-4 rounded-xl border hover:shadow transition">
                                        <div class="font-semibold">SEO Audits</div>
                                        <div class="text-sm text-gray-600">Mät Core Web Vitals, tillgänglighet och SEO‑poäng.</div>
                                    </a>
                                    <a href="{{ route('seo.keywords.index') }}" class="block p-4 rounded-xl border hover:shadow transition">
                                        <div class="font-semibold">Nyckelordsförslag</div>
                                        <div class="text-sm text-gray-600">Hämta rankingar och få AI‑förslag på titlar och metas.</div>
                                    </a>
                                    <a href="{{ route('ai.list') }}" class="block p-4 rounded-xl border hover:shadow transition">
                                        <div class="font-semibold">AI Innehåll</div>
                                        <div class="text-sm text-gray-600">Skapa artiklar och publicera dem till din sajt.</div>
                                    </a>
                                    <a href="{{ route('publications.index') }}" class="block p-4 rounded-xl border hover:shadow transition">
                                        <div class="font-semibold">Publiceringar</div>
                                        <div class="text-sm text-gray-600">Se publiceringsköer och status för innehåll.</div>
                                    </a>
                                    <a href="{{ route('leads.index') }}" class="block p-4 rounded-xl border hover:shadow transition">
                                        <div class="font-semibold">Leads</div>
                                        <div class="text-sm text-gray-600">Följ upp intresseanmälningar och förfrågningar.</div>
                                    </a>
                                    <a href="{{ route('settings.weekly') }}" class="block p-4 rounded-xl border hover:shadow transition">
                                        <div class="font-semibold">Veckodigest</div>
                                        <div class="text-sm text-gray-600">Skicka veckovisa sammanfattningar till teamet.</div>
                                    </a>
                                </div>
                            </div>

                            <div class="pt-4 flex items-center justify-between">
                                <button wire:click="prev" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50">
                                    Tillbaka
                                </button>
                                <button
                                    wire:click="next"
                                    class="modern-btn-primary"
                                    @disabled(!$integrationConnected)
                                >
                                    <span>Fortsätt</span>
                                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                    @elseif($step === 4)
                        <!-- Steg 4: Lead tracker -->
                        <div class="space-y-6">
                            <div class="text-center">
                                <h2 class="text-2xl font-bold text-gray-900 mb-2">Steg 4: Installera lead tracking</h2>
                                <p class="text-gray-600">Installera plugin eller script och bekräfta</p>
                            </div>

                            <div class="space-y-4">
                                <div class="p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200/50">
                                    <div class="flex flex-wrap gap-3">
                                        <a href="{{ route('onboarding.tracker') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-800 rounded-xl hover:bg-gray-50">
                                            Öppna installationsguide
                                        </a>
                                        <button wire:click.prevent="markLeadTrackerReady" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700">
                                            Markera som klart
                                        </button>
                                        <button wire:click="refreshStatus" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-800 rounded-xl hover:bg-gray-50">
                                            Uppdatera status
                                        </button>
                                    </div>
                                    <div class="mt-3 text-sm {{ $leadTrackerReady ? 'text-emerald-700' : 'text-amber-700' }}">
                                        Status: {{ $leadTrackerReady ? 'Klar' : 'Ej klar' }}
                                    </div>
                                    @error('leadTrackerReady')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="pt-4 flex items-center justify-between">
                                <button wire:click="prev" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50">
                                    Tillbaka
                                </button>
                                <button wire:click="next" class="modern-btn-primary">
                                    <span>Fortsätt</span>
                                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                    @elseif($step === 5)
                        <!-- Steg 5: Sociala medier (valfritt) -->
                        <div class="space-y-6">
                            <div class="text-center">
                                <h2 class="text-2xl font-bold text-gray-900 mb-2">Steg 5: Koppla sociala medier (valfritt)</h2>
                                <p class="text-gray-600">Koppla Facebook/Instagram för att kunna publicera</p>
                            </div>

                            <div class="p-4 bg-gradient-to-r from-pink-50 to-purple-50 rounded-xl border border-pink-200/50">
                                <div class="flex flex-wrap gap-3">
                                    <a href="{{ route('settings.social') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-800 rounded-xl hover:bg-gray-50">
                                        Öppna sociala inställningar
                                    </a>
                                    <button wire:click="markSocialConnected" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700">
                                        Markera som klart
                                    </button>
                                </div>
                                <div class="mt-3 text-sm {{ $socialConnected ? 'text-emerald-700' : 'text-gray-700' }}">
                                    Status: {{ $socialConnected ? 'Klar' : 'Valfritt (kan hoppas över)' }}
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
                                    <button wire:click="next" class="modern-btn-primary">
                                        <span>Fortsätt</span>
                                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                    @elseif($step === 6)
                        <!-- Steg 6: Mailchimp (valfritt) -->
                        <div class="space-y-6">
                            <div class="text-center">
                                <h2 class="text-2xl font-bold text-gray-900 mb-2">Steg 6: Koppla Mailchimp (valfritt)</h2>
                                <p class="text-gray-600">Koppla ditt nyhetsbrev för att kunna skicka kampanjer</p>
                            </div>

                            <div class="p-4 bg-gradient-to-r from-yellow-50 to-orange-50 rounded-xl border border-yellow-200/50">
                                <div class="flex flex-wrap gap-3">
                                    <a href="{{ route('settings.mailchimp') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-800 rounded-xl hover:bg-gray-50">
                                        Öppna Mailchimp-inställningar
                                    </a>
                                    <button wire:click="markMailchimpConnected" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700">
                                        Markera som klart
                                    </button>
                                </div>
                                <div class="mt-3 text-sm {{ $mailchimpConnected ? 'text-emerald-700' : 'text-gray-700' }}">
                                    Status: {{ $mailchimpConnected ? 'Klar' : 'Valfritt (kan hoppas över)' }}
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
                                    <button wire:click="next" class="modern-btn-primary">
                                        <span>Fortsätt</span>
                                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                    @elseif($step === 7)
                        <!-- Steg 7: Weekly Digest -->
                        <div class="space-y-6">
                            <div class="text-center">
                                <h2 class="text-2xl font-bold text-gray-900 mb-2">Steg 7: Veckodigest</h2>
                                <p class="text-gray-600">Konfigurera veckosammanfattning (kan hoppas över)</p>
                            </div>

                            <div class="p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200/50">
                                <div class="flex flex-wrap gap-3">
                                    <a href="{{ route('settings.weekly') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-800 rounded-xl hover:bg-gray-50">
                                        Öppna inställningar
                                    </a>
                                    <button wire:click="markWeeklyConfigured" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700">
                                        Markera som klart
                                    </button>
                                </div>
                                <div class="mt-3 text-sm {{ $weeklyConfigured ? 'text-emerald-700' : 'text-gray-700' }}">
                                    Status: {{ $weeklyConfigured ? 'Klar' : 'Valfritt (kan hoppas över)' }}
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
                                        Hoppa över och gå till Dashboard
                                    </button>
                                    <button
                                        wire:click="complete"
                                        class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-semibold rounded-lg hover:from-emerald-700 hover:to-teal-700 focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition"
                                    >
                                        <span>Slutför och gå till Dashboard</span>
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
