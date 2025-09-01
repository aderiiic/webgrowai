<div>
    <div class="max-w-7xl mx-auto space-y-8">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                <svg class="w-8 h-8 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1721 9z"/>
                </svg>
                Nyckelordsförslag
                <!-- Help Icon -->
                <button onclick="toggleKeywordsHelpModal()" class="ml-3 w-6 h-6 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center hover:from-blue-600 hover:to-indigo-700 transition-all duration-200 shadow-md hover:shadow-lg group">
                    <svg class="w-3.5 h-3.5 text-white group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </button>
            </h1>
            <div class="flex items-center gap-3">
                <a href="{{ route('seo.keywords.fetch_analyze') }}" class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-semibold rounded-xl hover:from-emerald-700 hover:to-teal-700 focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transform hover:scale-[1.02] transition-all duration-200 shadow-lg hover:shadow-xl">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6M9 19l3 3m0 0l3-3m-3 3V10M9.663 17h4.673"/>
                    </svg>
                    Hämta rankning & analys
                </a>
            </div>
        </div>

        <!-- Help Modal -->
        <div id="keywordsHelpModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden opacity-0 transition-opacity duration-300">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 max-w-2xl w-full max-h-[80vh] overflow-y-auto transform scale-95 transition-transform duration-300" id="keywordsHelpModalContent">
                    <!-- Header -->
                    <div class="flex items-center justify-between p-6 border-b border-gray-100">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1721 9z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">Hjälp - Nyckelordsförslag</h3>
                                <p class="text-sm text-gray-600">Optimera din SEO med AI-drivna insikter</p>
                            </div>
                        </div>
                        <button onclick="toggleKeywordsHelpModal()" class="p-2 hover:bg-gray-100 rounded-xl transition-colors duration-200">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Content -->
                    <div class="p-6 space-y-6">
                        <!-- What are Keyword Suggestions -->
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-5 border border-blue-200">
                            <h4 class="font-semibold text-blue-900 mb-2 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Vad är nyckelordsförslag?
                            </h4>
                            <p class="text-blue-800 text-sm leading-relaxed">
                                AI-driven analys av din webbplats som identifierar potentiella nyckelord du kan ranka för. Systemet analyserar ditt innehåll, konkurrenter och sökvolymer för att ge dig konkreta förbättringsförslag.
                            </p>
                        </div>

                        <!-- How it Works -->
                        <div class="space-y-4">
                            <h4 class="font-semibold text-gray-900 text-lg">Så fungerar det:</h4>

                            <!-- Step 1 -->
                            <div class="flex items-start space-x-3">
                                <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6M9 19l3 3m0 0l3-3m-3 3V10"/>
                                    </svg>
                                </div>
                                <div>
                                    <h5 class="font-medium text-gray-900">1. Hämta rankning</h5>
                                    <p class="text-sm text-gray-600">Systemet analyserar din webbplats och identifierar vilka sidor som finns, deras innehåll och nuvarande sökranking.</p>
                                </div>
                            </div>

                            <!-- Step 2 -->
                            <div class="flex items-start space-x-3">
                                <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h5 class="font-medium text-gray-900">2. AI-analys</h5>
                                    <p class="text-sm text-gray-600">AI:n analyserar innehållet och identifierar nyckelordsmöjligheter, konkurrentanalys och optimeringsförslag.</p>
                                </div>
                            </div>

                            <!-- Step 3 -->
                            <div class="flex items-start space-x-3">
                                <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h5 class="font-medium text-gray-900">3. Generera förslag</h5>
                                    <p class="text-sm text-gray-600">Du får konkreta förslag på nyckelord att fokusera på, inklusive svårighetsgrad och potentiella vinster.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Status Meanings -->
                        <div class="space-y-4">
                            <h4 class="font-semibold text-gray-900 text-lg">Förslagsstatus:</h4>

                            <div class="bg-white rounded-xl p-4 border border-gray-200 space-y-3">
                                <!-- New -->
                                <div class="flex items-center space-x-3">
                                    <div class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium border border-blue-200">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"/>
                                        </svg>
                                        NEW
                                    </div>
                                    <span class="text-sm text-gray-700"><strong>Nya förslag:</strong> Ännu inte behandlade - behöver din uppmärksamhet</span>
                                </div>

                                <!-- Applied -->
                                <div class="flex items-center space-x-3">
                                    <div class="inline-flex items-center px-2 py-1 bg-emerald-100 text-emerald-800 rounded-full text-sm font-medium border border-emerald-200">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                        </svg>
                                        APPLIED
                                    </div>
                                    <span class="text-sm text-gray-700"><strong>Applicerade:</strong> Du har implementerat förslagen på din webbplats</span>
                                </div>

                                <!-- Dismissed -->
                                <div class="flex items-center space-x-3">
                                    <div class="inline-flex items-center px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-sm font-medium border border-gray-200">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/>
                                        </svg>
                                        DISMISSED
                                    </div>
                                    <span class="text-sm text-gray-700"><strong>Avfärdade:</strong> Förslag du valt att inte implementera</span>
                                </div>
                            </div>
                        </div>

                        <!-- AI Insights -->
                        <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-5 border border-purple-200">
                            <h4 class="font-semibold text-purple-900 mb-3 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                </svg>
                                Vad AI-insikterna innehåller:
                            </h4>
                            <ul class="text-sm text-purple-800 space-y-1">
                                <li>• <strong>Nyckelordspotential:</strong> Vilka ord du kan ranka för</li>
                                <li>• <strong>Svårighetsgrad:</strong> Hur svårt det är att ranka för nyckelordet</li>
                                <li>• <strong>Innehållsförslag:</strong> Vad du ska skriva om för bättre ranking</li>
                                <li>• <strong>Konkurrentanalys:</strong> Vad andra gör bättre</li>
                                <li>• <strong>Optimeringstips:</strong> Konkreta steg för förbättring</li>
                            </ul>
                        </div>

                        <!-- Getting Started -->
                        <div class="bg-gradient-to-r from-emerald-50 to-teal-50 rounded-xl p-5 border border-emerald-200">
                            <h4 class="font-semibold text-emerald-900 mb-3 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                                Så kommer du igång:
                            </h4>
                            <ol class="text-sm text-emerald-800 space-y-2">
                                <li class="flex items-start space-x-2">
                                    <span class="w-5 h-5 bg-emerald-600 text-white rounded-full text-xs flex items-center justify-center font-semibold flex-shrink-0 mt-0.5">1</span>
                                    <span>Klicka på <strong>"Hämta rankning & analys"</strong> för att starta processen</span>
                                </li>
                                <li class="flex items-start space-x-2">
                                    <span class="w-5 h-5 bg-emerald-600 text-white rounded-full text-xs flex items-center justify-center font-semibold flex-shrink-0 mt-0.5">2</span>
                                    <span>Vänta medan AI:n analyserar din sajt (kan ta några minuter)</span>
                                </li>
                                <li class="flex items-start space-x-2">
                                    <span class="w-5 h-5 bg-emerald-600 text-white rounded-full text-xs flex items-center justify-center font-semibold flex-shrink-0 mt-0.5">3</span>
                                    <span>Gå igenom förslagen och klicka <strong>"Visa detaljer"</strong> för specifika tips</span>
                                </li>
                                <li class="flex items-start space-x-2">
                                    <span class="w-5 h-5 bg-emerald-600 text-white rounded-full text-xs flex items-center justify-center font-semibold flex-shrink-0 mt-0.5">4</span>
                                    <span>Implementera förslagen och markera som <strong>"Applied"</strong></span>
                                </li>
                            </ol>
                        </div>

                        <!-- Pro Tips -->
                        <div class="bg-gradient-to-r from-amber-50 to-yellow-50 rounded-xl p-5 border border-amber-200">
                            <h4 class="font-semibold text-amber-900 mb-2 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                </svg>
                                Pro-tips för bästa resultat:
                            </h4>
                            <ul class="text-sm text-amber-800 space-y-1">
                                <li>• Fokusera på nyckelord med <strong>låg svårighetsgrad</strong> först</li>
                                <li>• Implementera 2-3 förslag åt gången för bäst resultat</li>
                                <li>• Kör ny analys månadsvis för att se utvecklingen</li>
                                <li>• Kombinera med regelbundna SEO-audits för bäst effekt</li>
                            </ul>
                        </div>

                        <!-- Requirements -->
                        <div class="bg-white rounded-xl p-4 border border-gray-200">
                            <h5 class="font-medium text-gray-900 mb-2">Krav för analys:</h5>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• <strong>WordPress-sajter:</strong> Kräver kopplad integration under "Sajter → WordPress"</li>
                                <li>• <strong>Shopify-sajter:</strong> Fungerar utan integration</li>
                                <li>• <strong>Andra sajter:</strong> Fungerar med grundläggande analys</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 rounded-b-2xl">
                        <div class="flex items-center justify-between">
                            <p class="text-xs text-gray-500">
                                Resultat visas typiskt inom 5-15 minuter efter start av analysen
                            </p>
                            <button onclick="toggleKeywordsHelpModal()" class="px-4 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors duration-200 text-sm">
                                Stäng
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100/50 p-6">
            <div class="flex items-center space-x-4">
                <label class="text-sm font-medium text-gray-700">Filtrera status:</label>
                <select wire:model.live="status" class="px-4 py-2 bg-white border border-gray-300 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200">
                    <option value="new">Nya förslag</option>
                    <option value="applied">Applicerade</option>
                    <option value="dismissed">Avfärdade</option>
                    <option value="all">Alla</option>
                </select>
            </div>
        </div>

        <!-- Suggestions list -->
        <div class="space-y-4">
            @forelse($rows as $r)
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100/50 p-6 hover:shadow-2xl transition-all duration-200">
                    <div class="flex items-start justify-between">
                        <div class="flex-1 min-w-0">
                            <!-- Status badge -->
                            <div class="flex items-center space-x-3 mb-3">
                                <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    @if($r->status === 'new') bg-blue-100 text-blue-800 border border-blue-200
                                    @elseif($r->status === 'applied') bg-emerald-100 text-emerald-800 border border-emerald-200
                                    @elseif($r->status === 'dismissed') bg-gray-100 text-gray-800 border border-gray-200
                                    @else bg-yellow-100 text-yellow-800 border border-yellow-200 @endif">
                                    @if($r->status === 'new')
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"/>
                                        </svg>
                                    @elseif($r->status === 'applied')
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                        </svg>
                                    @elseif($r->status === 'dismissed')
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/>
                                        </svg>
                                    @endif
                                    {{ strtoupper($r->status) }}
                                </div>
                            </div>

                            <!-- URL -->
                            <div class="mb-4">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                    </svg>
                                    <a href="{{ $r->url }}" target="_blank" rel="noopener" class="font-medium text-indigo-600 hover:text-indigo-800 hover:underline truncate">
                                        {{ $r->url }}
                                    </a>
                                </div>
                            </div>

                            <!-- Insights -->
                            @php
                                $ins = is_array($r->insights) ? array_slice($r->insights, 0, 4) : [];
                            @endphp
                            @if(count($ins) > 0)
                                <div class="p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200/50">
                                    <div class="flex items-center mb-2">
                                        <svg class="w-4 h-4 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                        </svg>
                                        <span class="font-medium text-blue-900">AI-insikter</span>
                                    </div>
                                    <ul class="space-y-1">
                                        @foreach($ins as $insight)
                                            <li class="flex items-start space-x-2 text-sm text-blue-800">
                                                <svg class="w-3 h-3 mt-0.5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"/>
                                                </svg>
                                                <span>{{ $insight }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>

                        <!-- Action button -->
                        <div class="ml-6 flex-shrink-0">
                            <a href="{{ route('seo.keywords.detail', $r->id) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl hover:from-indigo-700 hover:to-purple-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transform hover:scale-[1.02] transition-all duration-200 shadow-lg hover:shadow-xl">
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1721 9z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Inga förslag ännu</h3>
                    <p class="text-gray-600 mb-6">Kör "Hämta rankning & analys" för att generera nyckelordsförslag.</p>
                    <div class="flex justify-center space-x-4">
                        <a href="{{ route('seo.keywords.fetch_analyze') }}" class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-semibold rounded-xl hover:from-emerald-700 hover:to-teal-700 transition-all duration-200 shadow-lg">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6M9 19l3 3m0 0l3-3m-3 3V10M9.663 17h4.673"/>
                            </svg>
                            Hämta rankning & analys
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($rows->hasPages())
            <div class="flex justify-center">
                {{ $rows->links() }}
            </div>
        @endif
    </div>

    <script>
        function toggleKeywordsHelpModal() {
            const modal = document.getElementById('keywordsHelpModal');
            const content = document.getElementById('keywordsHelpModalContent');

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
            const modal = document.getElementById('keywordsHelpModal');
            if (modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        toggleKeywordsHelpModal();
                    }
                });

                // Close with Escape key
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                        toggleKeywordsHelpModal();
                    }
                });
            }
        });
    </script>
</div>
