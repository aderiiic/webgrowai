<div>
    <div class="max-w-7xl mx-auto space-y-8">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                <svg class="w-8 h-8 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"/>
                </svg>
                Sajter
                <!-- Help Icon -->
                <button onclick="toggleHelpModal()" class="ml-3 w-6 h-6 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center hover:from-blue-600 hover:to-indigo-700 transition-all duration-200 shadow-md hover:shadow-lg group">
                    <svg class="w-3.5 h-3.5 text-white group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </button>
            </h1>
            <a href="{{ route('sites.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl hover:from-indigo-700 hover:to-purple-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transform hover:scale-[1.02] transition-all duration-200 shadow-lg hover:shadow-xl">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Lägg till sajt
            </a>
        </div>

        <!-- Help Modal -->
        <div id="helpModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden opacity-0 transition-opacity duration-300">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 max-w-2xl w-full max-h-[80vh] overflow-y-auto transform scale-95 transition-transform duration-300" id="helpModalContent">
                    <!-- Header -->
                    <div class="flex items-center justify-between p-6 border-b border-gray-100">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">Hjälp - Sajter</h3>
                                <p class="text-sm text-gray-600">Lär dig hantera dina webbplatser</p>
                            </div>
                        </div>
                        <button onclick="toggleHelpModal()" class="p-2 hover:bg-gray-100 rounded-xl transition-colors duration-200">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Content -->
                    <div class="p-6 space-y-6">
                        <!-- What are Sites -->
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-5 border border-blue-200">
                            <h4 class="font-semibold text-blue-900 mb-2 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Vad är sajter?
                            </h4>
                            <p class="text-blue-800 text-sm leading-relaxed">
                                Här hanterar du alla dina webbplatser på ett ställe. Varje sajt kan kopplas till WordPress, Shopify eller andra plattformar för automatisk SEO-optimering och innehållshantering.
                            </p>
                        </div>

                        <!-- Key Features -->
                        <div class="space-y-4">
                            <h4 class="font-semibold text-gray-900 text-lg">Viktiga funktioner:</h4>

                            <!-- Performance Badge -->
                            <div class="flex items-start space-x-3">
                                <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h5 class="font-medium text-gray-900">Performance-märke</h5>
                                    <p class="text-sm text-gray-600">Visar din webbplats hastighetspoäng (0-100). Grön = bra (90+), gul = ok (70-89), röd = behöver förbättring.</p>
                                </div>
                            </div>

                            <!-- Integration Status -->
                            <div class="flex items-start space-x-3">
                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v4m0 8v4m8-8h-4M8 12H4"/>
                                    </svg>
                                </div>
                                <div>
                                    <h5 class="font-medium text-gray-900">Integrationsstatus</h5>
                                    <p class="text-sm text-gray-600">Visar om sajten är kopplad till WordPress, Shopify eller annan plattform. <span class="text-emerald-600 font-medium">"Connected"</span> betyder att automatisk synkning fungerar.</p>
                                </div>
                            </div>

                            <!-- SEO Audit -->
                            <div class="flex items-start space-x-3">
                                <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h5 class="font-medium text-gray-900">SEO Audit</h5>
                                    <p class="text-sm text-gray-600">Analyserar din sajt tekniskt och ger konkreta förbättringsförslag för bättre synlighet i Google. Kör regelbundet för bästa resultat.</p>
                                </div>
                            </div>

                            <!-- CRO Analysis -->
                            <div class="flex items-start space-x-3">
                                <div class="w-8 h-8 bg-teal-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h5 class="font-medium text-gray-900">CRO-analys</h5>
                                    <p class="text-sm text-gray-600">Conversion Rate Optimization - analyserar din sajt för att öka antalet besökare som blir kunder. Ger förslag på förbättringar för bättre konvertering.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Start -->
                        <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-5 border border-gray-200">
                            <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                                Så här kommer du igång:
                            </h4>
                            <ol class="space-y-2 text-sm text-gray-700">
                                <li class="flex items-center space-x-2">
                                    <span class="w-5 h-5 bg-indigo-600 text-white rounded-full text-xs flex items-center justify-center font-semibold">1</span>
                                    <span>Klicka på <strong>"Lägg till sajt"</strong> för att registrera din webbplats</span>
                                </li>
                                <li class="flex items-center space-x-2">
                                    <span class="w-5 h-5 bg-indigo-600 text-white rounded-full text-xs flex items-center justify-center font-semibold">2</span>
                                    <span>Koppla din WordPress/Shopify via <strong>"Hantera koppling"</strong></span>
                                </li>
                                <li class="flex items-center space-x-2">
                                    <span class="w-5 h-5 bg-indigo-600 text-white rounded-full text-xs flex items-center justify-center font-semibold">3</span>
                                    <span>Kör din första <strong>"SEO Audit"</strong> för att få förbättringsförslag</span>
                                </li>
                                <li class="flex items-center space-x-2">
                                    <span class="w-5 h-5 bg-indigo-600 text-white rounded-full text-xs flex items-center justify-center font-semibold">4</span>
                                    <span>Starta <strong>"CRO-analys"</strong> för bättre konverteringar</span>
                                </li>
                            </ol>
                        </div>

                        <!-- Tips -->
                        <div class="bg-gradient-to-r from-amber-50 to-yellow-50 rounded-xl p-5 border border-amber-200">
                            <h4 class="font-semibold text-amber-900 mb-2 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                </svg>
                                Pro-tips:
                            </h4>
                            <ul class="text-sm text-amber-800 space-y-1">
                                <li>• Kör SEO-audits varje vecka för att följa framsteg</li>
                                <li>• Performance-poäng över 90 = snabb sajt som Google älskar</li>
                                <li>• Connected integration = automatisk optimering fungerar</li>
                                <li>• CRO-analys hjälper dig få fler kunder från samma trafik</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 rounded-b-2xl">
                        <div class="flex items-center justify-between">
                            <p class="text-xs text-gray-500">
                                Behöver mer hjälp? Kontakta info@webbi.se
                            </p>
                            <button onclick="toggleHelpModal()" class="px-4 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors duration-200 text-sm">
                                Stäng
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notifications -->
        @if(session('success'))
            <div class="p-4 bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-200 rounded-xl">
                <div class="flex items-center space-x-3">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 bg-gradient-to-r from-red-50 to-pink-50 border border-red-200 rounded-xl">
                <div class="flex items-center space-x-3">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- Sites grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @forelse($sites as $site)
                @php
                    $latest = $latestBySite[$site->id] ?? null;
                    $perf = $latest['lighthouse_performance'] ?? null;

                    // Performance color logic
                    if ($perf === null) {
                        $perfColor = 'text-gray-500';
                        $perfBg = 'bg-gray-100';
                    } elseif ($perf >= 90) {
                        $perfColor = 'text-emerald-700';
                        $perfBg = 'bg-emerald-100';
                    } elseif ($perf >= 70) {
                        $perfColor = 'text-amber-700';
                        $perfBg = 'bg-amber-100';
                    } else {
                        $perfColor = 'text-red-700';
                        $perfBg = 'bg-red-100';
                    }

                    $int = $integrationsBySite[$site->id] ?? null;
                    $provider = $int['provider'] ?? null; // 'wordpress'|'shopify'|'custom'|null
                    $status = $int['status'] ?? 'disconnected';

                    $provLabel = $provider ? ucfirst($provider) : 'Ingen';
                    $statusColor = match ($status) {
                        'connected' => 'text-emerald-700 bg-emerald-100 border-emerald-200',
                        'error'     => 'text-red-700 bg-red-100 border-red-200',
                        default     => 'text-gray-700 bg-gray-100 border-gray-200',
                    };
                @endphp

                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100/50 p-6 hover:shadow-2xl transition-all duration-200">
                    <!-- Site header -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"/>
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h3 class="text-lg font-bold text-gray-900 truncate">{{ $site->name }}</h3>
                                <a href="{{ $site->url }}" target="_blank" rel="noopener" class="text-sm text-indigo-600 hover:text-indigo-800 hover:underline truncate block">
                                    {{ $site->url }}
                                </a>
                            </div>
                        </div>

                        <!-- Performance badge -->
                        <div class="inline-flex items-center px-3 py-1 {{ $perfBg }} {{ $perfColor }} rounded-full text-sm font-medium">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            {{ $perf ?? '—' }}
                        </div>
                    </div>

                    <!-- Integration info -->
                    <div class="mb-4 grid md:grid-cols-2 gap-3">
                        <div class="p-3 rounded-xl border {{ $statusColor }}">
                            <div class="text-xs font-medium">Integration</div>
                            <div class="text-sm font-semibold">
                                {{ $provLabel }}
                                <span class="text-xs font-medium ml-2">({{ $status }})</span>
                            </div>
                        </div>
                        <div class="p-3 rounded-xl border border-gray-200/50 bg-gradient-to-r from-gray-50 to-white">
                            <div class="text-xs font-medium text-gray-600 mb-1">Site Key</div>
                            <div class="font-mono text-sm text-gray-900 break-all">{{ $site->public_key }}</div>
                        </div>
                    </div>

                    <!-- Audit info -->
                    <div class="mb-6 flex items-center justify-between p-3 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200/50">
                        <div class="text-sm">
                            <div class="font-medium text-blue-900">Senaste audit</div>
                            <div class="text-blue-700">
                                @if($latest)
                                    {{ \Illuminate\Support\Carbon::parse($latest['created_at'])->diffForHumans() }}
                                @else
                                    Ingen audit ännu
                                @endif
                            </div>
                        </div>
                        <form method="POST" action="{{ route('sites.seo.audit.run', $site) }}">
                            @csrf
                            <button class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors duration-200 text-sm">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                                Kör audit
                            </button>
                        </form>
                    </div>

                    <!-- Quick actions -->
                    <div class="mb-4">
                        <form method="POST" action="{{ route('sites.cro.analyze', $site) }}">
                            @csrf
                            <button class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-semibold rounded-xl hover:from-emerald-700 hover:to-teal-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                Kör CRO-analys
                            </button>
                        </form>
                    </div>

                    <!-- Navigation buttons -->
                    <div class="grid grid-cols-2 gap-3">
                        <a href="{{ route('sites.edit', $site) }}" class="inline-flex items-center justify-center px-4 py-2 bg-white border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 shadow-sm hover:shadow-md">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Redigera
                        </a>

                        @if($provider === 'wordpress')
                            <!-- WP-specifika genvägar -->
                            <a href="{{ route('sites.wordpress', $site) }}" class="inline-flex items-center justify-center px-4 py-2 bg-white border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 shadow-sm hover:shadow-md">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M21.469 6.825c.84 1.537 1.318 3.3 1.318 5.175 0 3.979-2.156 7.456-5.363 9.325l3.295-9.527c.615-1.54.82-2.771.82-3.864 0-.405-.026-.78-.07-1.11m-7.981.105c.647-.03 1.232-.105 1.232-.105.582-.075.514-.93-.067-.899 0 0-1.755.135-2.88.135-1.064 0-2.85-.15-2.85-.15-.584-.03-.661.854-.075.884 0 0 .54.061 1.125.09l1.68 4.605-2.37 7.08L5.354 6.9c.649-.03 1.234-.1 1.234-.1.585-.075.516-.93-.065-.896 0 0-1.746.138-2.874.138-.2 0-.438-.008-.69-.015C4.911 3.15 8.235 1.215 12 1.215c2.809 0 5.365 1.072 7.286 2.833-.046-.003-.091-.009-.141-.009-1.06 0-1.812.923-1.812 1.914 0 .89.513 1.643 1.06 2.531.411.72.89 1.643.89 2.977 0 .915-.354 1.994-.821 3.479l-1.075 3.585-3.9-11.61.001.014z"/>
                                </svg>
                                WordPress
                            </a>

                            <a href="{{ route('wp.posts.index', $site) }}" class="inline-flex items-center justify-center px-4 py-2 bg-white border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 shadow-sm hover:shadow-md col-span-2">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                WP-inlägg
                            </a>
                        @else
                            <!-- Generisk integrationsknapp för Shopify/Custom -->
                            <a href="{{ route('sites.integrations.connect', ['site' => $site->id]) }}"
                               class="inline-flex items-center justify-center px-4 py-2 bg-white border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 shadow-sm hover:shadow-md">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v4m0 8v4m8-8h-4M8 12H4"/>
                                </svg>
                                Hantera koppling
                            </a>
                            <div></div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="text-center py-16">
                        <div class="w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">Inga sajter ännu</h3>
                        <p class="text-gray-600 mb-6">Lägg till din första sajt för att komma igång med SEO-analyser och optimering.</p>
                        <a href="{{ route('sites.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 shadow-lg">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Lägg till sajt
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <script>
        function toggleHelpModal() {
            const modal = document.getElementById('helpModal');
            const content = document.getElementById('helpModalContent');

            if (modal.classList.contains('hidden')) {
                // Show modal
                modal.classList.remove('hidden');
                setTimeout(() => {
                    modal.classList.remove('opacity-0');
                    content.classList.remove('scale-95');
                    content.classList.add('scale-100');
                }, 10);
            } else {
                // Hide modal
                modal.classList.add('opacity-0');
                content.classList.add('scale-95');
                content.classList.remove('scale-100');
                setTimeout(() => {
                    modal.classList.add('hidden');
                }, 300);
            }
        }

        // Close modal when clicking outside
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('helpModal');
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    toggleHelpModal();
                }
            });

            // Close with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                    toggleHelpModal();
                }
            });
        });
    </script>
</div>
