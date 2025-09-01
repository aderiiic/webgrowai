
{{-- resources/views/livewire/analytics/overview.blade.php --}}
<div>
    <div class="max-w-7xl mx-auto space-y-8">
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                Analys
                @if($activeSiteId) <span class="ml-2 text-lg text-gray-600">(Sajt #{{ $activeSiteId }})</span>@endif
                <!-- Help Icon -->
                <button onclick="toggleAnalyticsHelpModal()" class="ml-3 w-6 h-6 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center hover:from-blue-600 hover:to-indigo-700 transition-all duration-200 shadow-md hover:shadow-lg group">
                    <svg class="w-3.5 h-3.5 text-white group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </button>
            </h1>
        </div>

        <!-- Help Modal -->
        <div id="analyticsHelpModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden opacity-0 transition-opacity duration-300">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 max-w-2xl w-full max-h-[80vh] overflow-y-auto transform scale-95 transition-transform duration-300" id="analyticsHelpModalContent">
                    <!-- Header -->
                    <div class="flex items-center justify-between p-6 border-b border-gray-100">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12h4l3 8 4-16 3 8h4"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">Hjälp - Analys</h3>
                                <p class="text-sm text-gray-600">Förstå din data och prestanda</p>
                            </div>
                        </div>
                        <button onclick="toggleAnalyticsHelpModal()" class="p-2 hover:bg-gray-100 rounded-xl transition-colors duration-200">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Content -->
                    <div class="p-6 space-y-6">
                        <!-- What is Analytics -->
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-5 border border-blue-200">
                            <h4 class="font-semibold text-blue-900 mb-2 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Vad är analysen?
                            </h4>
                            <p class="text-blue-800 text-sm leading-relaxed">
                                Din centrala översikt över webbtrafikens prestanda, sociala mediers engagement och innehållspubliceringar. Här ser du hur din digitala närvaro utvecklas över tid.
                            </p>
                        </div>

                        <!-- Key Metrics -->
                        <div class="space-y-4">
                            <h4 class="font-semibold text-gray-900 text-lg">Viktiga mätvärden:</h4>

                            <!-- Website Traffic -->
                            <div class="flex items-start space-x-3">
                                <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12h4l3 8 4-16 3 8h4"/>
                                    </svg>
                                </div>
                                <div>
                                    <h5 class="font-medium text-gray-900">Webbtrafik</h5>
                                    <p class="text-sm text-gray-600">Antal besökare och sessioner de senaste 7 dagarna. Trend visar utvecklingen jämfört med föregående period.</p>
                                </div>
                            </div>

                            <!-- Publications -->
                            <div class="flex items-start space-x-3">
                                <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                </div>
                                <div>
                                    <h5 class="font-medium text-gray-900">Publiceringar</h5>
                                    <p class="text-sm text-gray-600">Antal lyckade och misslyckade publiceringar de senaste 30 dagarna, samt genomsnitt per vecka för planering.</p>
                                </div>
                            </div>

                            <!-- Social Media -->
                            <div class="flex items-start space-x-3">
                                <div class="w-8 h-8 bg-pink-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h5 class="font-medium text-gray-900">Sociala medier</h5>
                                    <p class="text-sm text-gray-600">Reach (räckvidd) och engagement från Facebook, Instagram och LinkedIn de senaste 7 dagarna.</p>
                                </div>
                            </div>

                            <!-- Advanced Features -->
                            <div class="flex items-start space-x-3">
                                <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h5 class="font-medium text-gray-900">Avancerade insikter</h5>
                                    <p class="text-sm text-gray-600">För högre planer: toppinnehåll, bästa tider att posta, engagemangstrender och AI-genererade insikter.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Connections -->
                        <div class="bg-gradient-to-r from-amber-50 to-yellow-50 rounded-xl p-5 border border-amber-200">
                            <h4 class="font-semibold text-amber-900 mb-3 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v4m0 8v4m8-8h-4M8 12H4"/>
                                </svg>
                                Kopplingar och integrationer:
                            </h4>
                            <div class="space-y-3 text-sm text-amber-800">
                                <div class="flex items-center space-x-2">
                                    <div class="w-4 h-4 bg-amber-600 rounded-full flex-shrink-0"></div>
                                    <span><strong>Google Analytics 4:</strong> Koppla för detaljerad webbtrafik-data</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <div class="w-4 h-4 bg-amber-600 rounded-full flex-shrink-0"></div>
                                    <span><strong>Sociala kanaler:</strong> Anslut Facebook, Instagram och LinkedIn</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <div class="w-4 h-4 bg-amber-600 rounded-full flex-shrink-0"></div>
                                    <span><strong>Webbplatsintegrationer:</strong> WordPress, Shopify för automatisk datainhämtning</span>
                                </div>
                            </div>
                        </div>

                        <!-- Understanding the Data -->
                        <div class="space-y-4">
                            <h4 class="font-semibold text-gray-900 text-lg">Så tolkar du data:</h4>

                            <div class="bg-white rounded-xl p-4 border border-gray-200">
                                <h5 class="font-medium text-gray-900 mb-2">Färgkoder för trender:</h5>
                                <div class="space-y-2 text-sm">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-3 h-3 bg-emerald-500 rounded-full"></div>
                                        <span><strong class="text-emerald-600">Grön/Positiv:</strong> Ökning jämfört med föregående period</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                                        <span><strong class="text-red-600">Röd/Negativ:</strong> Minskning jämfört med föregående period</span>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white rounded-xl p-4 border border-gray-200">
                                <h5 class="font-medium text-gray-900 mb-2">Viktiga siffror att följa:</h5>
                                <ul class="text-sm text-gray-600 space-y-1">
                                    <li>• <strong>Besökare vs Sessions:</strong> Besökare = unika personer, Sessions = besök (kan vara flera per person)</li>
                                    <li>• <strong>Reach vs Engagement:</strong> Reach = hur många som såg, Engagement = likes, kommentarer, delningar</li>
                                    <li>• <strong>Publiceringsfrekvens:</strong> Regelbunden publicering ger bättre resultat</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Action Tips -->
                        <div class="bg-gradient-to-r from-emerald-50 to-teal-50 rounded-xl p-5 border border-emerald-200">
                            <h4 class="font-semibold text-emerald-900 mb-2 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                </svg>
                                Actionabla tips:
                            </h4>
                            <ul class="text-sm text-emerald-800 space-y-1">
                                <li>• Negativ trend? Öka publiceringsfrekvensen eller förbättra innehållskvaliteten</li>
                                <li>• Låg räckvidd på sociala medier? Testa att posta på de "bästa tiderna"</li>
                                <li>• Få misslyckade publiceringar? Kontrollera integrationer under "Inställningar"</li>
                                <li>• Använd "Toppinnehåll" för att se vad som fungerar bäst</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 rounded-b-2xl">
                        <div class="flex items-center justify-between">
                            <p class="text-xs text-gray-500">
                                Vill du koppla fler tjänster? Gå till "Inställningar → Sociala kanaler"
                            </p>
                            <button onclick="toggleAnalyticsHelpModal()" class="px-4 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors duration-200 text-sm">
                                Stäng
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($activeSiteId)
            <div class="p-4 bg-blue-50 border border-blue-200 rounded-xl">
                <p class="text-sm text-blue-800">Visar statistik för vald sajt.</p>
            </div>
        @else
            <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl">
                <p class="text-sm text-amber-800">Ingen specifik sajt vald. Visar aggregerad bild för alla sajter.</p>
            </div>
        @endif

        <a href="{{ route('analytics.ga4.connect') }}" class="inline-flex items-center px-3 py-2 bg-indigo-600 text-white rounded-lg">
            Koppla GA4
        </a>

        {{-- Starter: Webb + Publicering + Social bas --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Webbtrafik --}}
            <div class="bg-white rounded-2xl shadow p-6 border">
                <h3 class="font-semibold mb-3">Webbtrafik (7 dagar)</h3>
                @if($website['connected'])
                    <div class="text-sm text-gray-700 space-y-1">
                        <div>Besökare: <span class="font-semibold">{{ $website['visitors_7d'] }}</span></div>
                        <div>Sessions: <span class="font-semibold">{{ $website['sessions_7d'] }}</span></div>
                        <div>Trend: <span class="font-semibold {{ $website['trend_pct'] >= 0 ? 'text-emerald-600' : 'text-red-600' }}">{{ $website['trend_pct'] }}%</span></div>
                    </div>
                @else
                    <div class="text-sm text-gray-600">Ingen webb-integration hittad. <a href="{{ route('settings.social') }}" class="text-indigo-600 underline">Koppla</a></div>
                @endif
            </div>

            {{-- Publiceringar --}}
            <div class="bg-white rounded-2xl shadow p-6 border">
                <h3 class="font-semibold mb-3">Publiceringar</h3>
                <div class="text-sm text-gray-700 space-y-1">
                    <div>Publicerade (30d): <span class="font-semibold">{{ $publications['published_30d'] }}</span></div>
                    <div>Misslyckade (30d): <span class="font-semibold">{{ $publications['failed_30d'] }}</span></div>
                    <div>Genomsnitt/vecka: <span class="font-semibold">{{ $publications['avg_per_week'] }}</span></div>
                </div>
            </div>

            {{-- Social bas --}}
            <div class="bg-white rounded-2xl shadow p-6 border">
                <h3 class="font-semibold mb-3">Socialt (7 dagar)</h3>
                <div class="text-sm text-gray-700 space-y-2">
                    @foreach(['facebook' => 'Facebook', 'instagram' => 'Instagram', 'linkedin' => 'LinkedIn'] as $key => $label)
                        @php($m = $social[$key] ?? null)
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ $label }}</span>
                            @if($m && $m['connected'])
                                <span class="font-semibold">Reach: {{ $m['reach'] }}, Eng: {{ $m['engagement'] }}</span>
                            @else
                                <a href="{{ route('settings.social') }}" class="text-indigo-600 underline">Koppla</a>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Avancerad sektion för större planer --}}
        @if($cap->advanced)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="bg-white rounded-2xl shadow p-6 border lg:col-span-2">
                    <h3 class="font-semibold mb-3">Toppinnehåll (30 dagar)</h3>
                    @if(empty($advanced['topContent']))
                        <div class="text-sm text-gray-600">Ingen data ännu.</div>
                    @else
                        <ul class="text-sm space-y-2">
                            @foreach($advanced['topContent'] as $row)
                                <li class="flex justify-between">
                                    <span class="truncate">{{ $row['title'] }}</span>
                                    <span class="font-semibold">{{ $row['score'] }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
                <div class="bg-white rounded-2xl shadow p-6 border">
                    <h3 class="font-semibold mb-3">Bästa tider att posta</h3>
                    @if(empty($advanced['bestPostTimes']))
                        <div class="text-sm text-gray-600">Ingen data ännu.</div>
                    @else
                        <div class="text-sm text-gray-700">
                            @foreach($advanced['bestPostTimes'] as $row)
                                <div>{{ $row['day'] }}: <span class="font-semibold">{{ $row['hour'] }}</span></div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="bg-white rounded-2xl shadow p-6 border">
                    <h3 class="font-semibold mb-3">Engagemangstrend</h3>
                    <div class="text-sm text-gray-700">
                        30d: <span class="font-semibold {{ ($advanced['engagementTrends']['pct_30d'] ?? 0) >= 0 ? 'text-emerald-600' : 'text-red-600' }}">{{ $advanced['engagementTrends']['pct_30d'] ?? 0 }}%</span>
                    </div>
                </div>
                <div class="bg-white rounded-2xl shadow p-6 border">
                    <h3 class="font-semibold mb-3">Sajter jämförelse</h3>
                    @if($activeSiteId)
                        <div class="text-sm text-gray-600">Välj "Alla sajter" för att jämföra.</div>
                    @else
                        @if(empty($advanced['siteCompare']))
                            <div class="text-sm text-gray-600">Ingen data.</div>
                        @else
                            <ul class="text-sm space-y-2">
                                @foreach($advanced['siteCompare'] as $row)
                                    <li class="flex justify-between">
                                        <span class="truncate">{{ $row['site_name'] }}</span>
                                        <span class="font-semibold">Score: {{ $row['score'] }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    @endif
                </div>
                <div class="bg-white rounded-2xl shadow p-6 border">
                    <h3 class="font-semibold mb-3">Insikter</h3>
                    @if(!$advanced['insights'])
                        <div class="text-sm text-gray-600">Ingen data ännu.</div>
                    @else
                        <ul class="list-disc ml-5 text-sm text-gray-700 space-y-2">
                            @foreach($advanced['insights'] as $tip)
                                <li>{{ $tip }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        @endif

        {{-- Digest-info för större planer --}}
        @if($cap->advanced && $cap->digest)
            <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl">
                <div class="text-sm text-emerald-800">
                    Veckodigest skickas automatiskt på söndagar kväll. Hantera inställningar under "Veckodigest".
                </div>
            </div>
        @endif
    </div>

    <script>
        function toggleAnalyticsHelpModal() {
            const modal = document.getElementById('analyticsHelpModal');
            const content = document.getElementById('analyticsHelpModalContent');

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
            const modal = document.getElementById('analyticsHelpModal');
            if (modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        toggleAnalyticsHelpModal();
                    }
                });

                // Close with Escape key
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                        toggleAnalyticsHelpModal();
                    }
                });
            }
        });
    </script>
</div>
