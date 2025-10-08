<div>
    <div class="max-w-7xl mx-auto space-y-8">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                <svg class="w-8 h-8 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                SEO Audit – Historik
                <!-- Help Icon -->
                <button onclick="toggleSeoHelpModal()" class="ml-3 w-6 h-6 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center hover:from-blue-600 hover:to-indigo-700 transition-all duration-200 shadow-md hover:shadow-lg group">
                    <svg class="w-3.5 h-3.5 text-white group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </button>
            </h1>
            <div class="flex flex-col items-end">
                <a id="runAuditBtn"
                   href="{{ route('seo.audit.run') }}"
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold rounded-xl hover:from-green-700 hover:to-emerald-700 focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transform hover:scale-[1.02] transition-all duration-200 shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Kör ny audit
                </a>
                <div id="runAuditNotice" class="hidden mt-2 text-xs text-gray-700 bg-gray-50 border border-gray-200 rounded px-2 py-1">
                    Audit körs – uppdatera sidan om en stund.
                </div>
            </div>
        </div>

        <!-- Help Modal -->
        <div id="seoHelpModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden opacity-0 transition-opacity duration-300">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 max-w-2xl w-full max-h-[80vh] overflow-y-auto transform scale-95 transition-transform duration-300" id="seoHelpModalContent">
                    <!-- Header -->
                    <div class="flex items-center justify-between p-6 border-b border-gray-100">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">Hjälp - SEO Audit</h3>
                                <p class="text-sm text-gray-600">Förstå dina SEO-resultat och förbättringsområden</p>
                            </div>
                        </div>
                        <button onclick="toggleSeoHelpModal()" class="p-2 hover:bg-gray-100 rounded-xl transition-colors duration-200">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Content -->
                    <div class="p-6 space-y-6">
                        <!-- What is SEO Audit -->
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-5 border border-green-200">
                            <h4 class="font-semibold text-green-900 mb-2 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Vad är en SEO Audit?
                            </h4>
                            <p class="text-green-800 text-sm leading-relaxed">
                                En djupgående teknisk analys av din webbplats som mäter prestanda, tillgänglighet, bästa praxis och SEO-faktorer. Baserad på Google Lighthouse och ger konkreta förbättringsförslag.
                            </p>
                        </div>

                        <!-- Score Meanings -->
                        <div class="space-y-4">
                            <h4 class="font-semibold text-gray-900 text-lg">Vad poängen betyder:</h4>

                            <!-- Performance -->
                            <div class="flex items-start space-x-3">
                                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h5 class="font-medium text-gray-900">Performance (0-100)</h5>
                                    <p class="text-sm text-gray-600">Hur snabbt din sajt laddas. 90+ = utmärkt, 70-89 = bra, under 70 = behöver förbättring.</p>
                                </div>
                            </div>

                            <!-- Accessibility -->
                            <div class="flex items-start space-x-3">
                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h5 class="font-medium text-gray-900">Accessibility (0-100)</h5>
                                    <p class="text-sm text-gray-600">Hur tillgänglig din sajt är för användare med funktionsnedsättningar. Viktigt för inkludering och SEO.</p>
                                </div>
                            </div>

                            <!-- Best Practices -->
                            <div class="flex items-start space-x-3">
                                <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h5 class="font-medium text-gray-900">Best Practices (0-100)</h5>
                                    <p class="text-sm text-gray-600">Följer din sajt moderna webbutvecklingsstandarder? Säkerhet, HTTPS, JavaScript-fel mm.</p>
                                </div>
                            </div>

                            <!-- SEO Score -->
                            <div class="flex items-start space-x-3">
                                <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h5 class="font-medium text-gray-900">SEO Score (0-100)</h5>
                                    <p class="text-sm text-gray-600">Grundläggande SEO-faktorer som meta-taggar, rubriker, alt-text för bilder och mobiloptimering.</p>
                                </div>
                            </div>

                            <!-- Title Issues -->
                            <div class="flex items-start space-x-3">
                                <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h5 class="font-medium text-gray-900">Titelproblem</h5>
                                    <p class="text-sm text-gray-600">Antal sidor med saknade, duplicerade eller för långa/korta title-taggar. 0 = perfekt.</p>
                                </div>
                            </div>

                            <!-- Meta Issues -->
                            <div class="flex items-start space-x-3">
                                <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h5 class="font-medium text-gray-900">Meta-problem</h5>
                                    <p class="text-sm text-gray-600">Antal sidor med saknade eller dåliga meta-beskrivningar. Viktigt för klickfrekvens i sökmotorer.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Score Colors -->
                        <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-5 border border-gray-200">
                            <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM7 21h10a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 002 2z"/>
                                </svg>
                                Färgkoder för poäng:
                            </h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex items-center space-x-2">
                                    <div class="w-4 h-4 bg-emerald-500 rounded-full"></div>
                                    <span><strong class="text-emerald-600">90-100:</strong> Utmärkt - fortsätt så här!</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <div class="w-4 h-4 bg-amber-500 rounded-full"></div>
                                    <span><strong class="text-amber-600">70-89:</strong> Bra - mindre förbättringar behövs</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <div class="w-4 h-4 bg-red-500 rounded-full"></div>
                                    <span><strong class="text-red-600">0-69:</strong> Behöver förbättring - viktiga åtgärder krävs</span>
                                </div>
                            </div>
                        </div>

                        <!-- How to Improve -->
                        <div class="space-y-4">
                            <h4 class="font-semibold text-gray-900 text-lg">Så förbättrar du resultaten:</h4>

                            <div class="bg-white rounded-xl p-4 border border-gray-200">
                                <h5 class="font-medium text-gray-900 mb-2">Snabbare laddningstider (Performance):</h5>
                                <ul class="text-sm text-gray-600 space-y-1">
                                    <li>• Optimera bilder (använd WebP-format, komprimera)</li>
                                    <li>• Aktivera caching på servern</li>
                                    <li>• Minimera CSS och JavaScript</li>
                                    <li>• Välj en snabbare webbhost</li>
                                </ul>
                            </div>

                            <div class="bg-white rounded-xl p-4 border border-gray-200">
                                <h5 class="font-medium text-gray-900 mb-2">Bättre SEO-poäng:</h5>
                                <ul class="text-sm text-gray-600 space-y-1">
                                    <li>• Skriv unika title-taggar för varje sida (50-60 tecken)</li>
                                    <li>• Lägg till meta-beskrivningar (155-160 tecken)</li>
                                    <li>• Använd alt-text på alla bilder</li>
                                    <li>• Strukturera innehåll med H1, H2, H3-rubriker</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Frequency Tips -->
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-5 border border-blue-200">
                            <h4 class="font-semibold text-blue-900 mb-2 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Hur ofta ska du köra audits?
                            </h4>
                            <ul class="text-sm text-blue-800 space-y-1">
                                <li>• <strong>Veckovis:</strong> För nya webbplatser under optimering</li>
                                <li>• <strong>Månadsvis:</strong> För etablerade sajter med regelbundna uppdateringar</li>
                                <li>• <strong>Efter större ändringar:</strong> Ny design, innehållsuppdateringar, plugin-installationer</li>
                                <li>• <strong>Jämför över tid:</strong> Se utvecklingen och förbättringarnas effekt</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 rounded-b-2xl">
                        <div class="flex items-center justify-between">
                            <p class="text-xs text-gray-500">
                                Klicka på "Visa detaljer" för specifika förbättringsförslag per audit
                            </p>
                            <button onclick="toggleSeoHelpModal()" class="px-4 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors duration-200 text-sm">
                                Stäng
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Audits list -->
        <div class="space-y-4">
            @forelse($audits as $a)
                @php
                    // Performance color logic
                    $perf = $a->lighthouse_performance;
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
                @endphp

                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100/50 p-6 hover:shadow-2xl transition-all duration-200">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <!-- Header info -->
                            <div class="flex items-center space-x-4 mb-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">Site #{{ $a->site_id }}</div>
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $a->created_at->format('Y-m-d H:i') }} ({{ $a->created_at->diffForHumans() }})
                                    </div>
                                </div>
                                <!-- Performance badge -->
                                <div class="inline-flex items-center px-3 py-1 {{ $perfBg }} {{ $perfColor }} rounded-full text-sm font-medium">
                                    Performance: {{ $perf ?? '—' }}
                                </div>
                            </div>

                            <!-- Metrics grid -->
                            <div class="grid grid-cols-2 md:grid-cols-6 gap-3">
                                <div class="p-3 bg-gradient-to-br from-green-50 to-emerald-50 rounded-lg border border-green-200/50">
                                    <div class="text-xs font-medium text-green-700">Performance</div>
                                    <div class="text-lg font-bold text-green-900">{{ $a->lighthouse_performance ?? '—' }}</div>
                                </div>
                                <div class="p-3 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg border border-blue-200/50">
                                    <div class="text-xs font-medium text-blue-700">Accessibility</div>
                                    <div class="text-lg font-bold text-blue-900">{{ $a->lighthouse_accessibility ?? '—' }}</div>
                                </div>
                                <div class="p-3 bg-gradient-to-br from-purple-50 to-pink-50 rounded-lg border border-purple-200/50">
                                    <div class="text-xs font-medium text-purple-700">Best Practices</div>
                                    <div class="text-lg font-bold text-purple-900">{{ $a->lighthouse_best_practices ?? '—' }}</div>
                                </div>
                                <div class="p-3 bg-gradient-to-br from-orange-50 to-red-50 rounded-lg border border-orange-200/50">
                                    <div class="text-xs font-medium text-orange-700">SEO Score</div>
                                    <div class="text-lg font-bold text-orange-900">{{ $a->lighthouse_seo ?? '—' }}</div>
                                </div>
                                <div class="p-3 bg-gradient-to-br from-yellow-50 to-amber-50 rounded-lg border border-yellow-200/50">
                                    <div class="text-xs font-medium text-yellow-700">Titelproblem</div>
                                    <div class="text-lg font-bold text-yellow-900">{{ $a->title_issues }}</div>
                                </div>
                                <div class="p-3 bg-gradient-to-br from-red-50 to-pink-50 rounded-lg border border-red-200/50">
                                    <div class="text-xs font-medium text-red-700">Meta-problem</div>
                                    <div class="text-lg font-bold text-red-900">{{ $a->meta_issues }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Action button -->
                        <div class="ml-6 flex-shrink-0">
                            <a href="{{ route('seo.audit.detail', $a->id) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl hover:from-indigo-700 hover:to-purple-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transform hover:scale-[1.02] transition-all duration-200 shadow-lg hover:shadow-xl">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Visa detaljer
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-16">
                    <div class="w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Ingen audit-historik ännu</h3>
                    <p class="text-gray-600 mb-6">Kör din första SEO-audit för att se resultaten här.</p>
                    <a href="{{ route('seo.audit.run') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-lg">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Kör SEO Audit
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($audits->hasPages())
            <div class="flex justify-center">
                {{ $audits->links() }}
            </div>
        @endif
    </div>

    <script>
        function toggleSeoHelpModal() {
            const modal = document.getElementById('seoHelpModal');
            const content = document.getElementById('seoHelpModalContent');

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
            const modal = document.getElementById('seoHelpModal');
            if (modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        toggleSeoHelpModal();
                    }
                });

                // Close with Escape key
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                        toggleSeoHelpModal();
                    }
                });
            }
        });

        (function(){
            const btn = document.getElementById('runAuditBtn');
            const notice = document.getElementById('runAuditNotice');
            if (!btn) return;

            btn.addEventListener('click', function(e){
                if (btn.dataset.busy === '1') {
                    e.preventDefault();
                    return false;
                }
                btn.dataset.busy = '1';
                btn.classList.add('opacity-60','cursor-not-allowed');
                btn.classList.remove('hover:from-green-700','hover:to-emerald-700','hover:shadow-xl','transform','hover:scale-[1.02]');
                if (notice) notice.classList.remove('hidden');
                // länken går iväg; om man stannar kvar på sidan syns bannern
            });
        })();
    </script>
</div>
