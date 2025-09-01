
<div>
    <div class="max-w-7xl mx-auto space-y-8">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                <svg class="w-8 h-8 mr-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                Konverteringsförslag
                <!-- Help Icon -->
                <button onclick="toggleCroHelpModal()" class="ml-3 w-6 h-6 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center hover:from-blue-600 hover:to-indigo-700 transition-all duration-200 shadow-md hover:shadow-lg group">
                    <svg class="w-3.5 h-3.5 text-white group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </button>
            </h1>
            <a href="{{ route('cro.analyze.run') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-semibold rounded-xl hover:from-purple-700 hover:to-pink-700 focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transform hover:scale-[1.02] transition-all duration-200 shadow-lg hover:shadow-xl">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Kör analys
            </a>
        </div>

        <!-- Help Modal -->
        <div id="croHelpModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden opacity-0 transition-opacity duration-300">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 max-w-2xl w-full max-h-[80vh] overflow-y-auto transform scale-95 transition-transform duration-300" id="croHelpModalContent">
                    <!-- Header -->
                    <div class="flex items-center justify-between p-6 border-b border-gray-100">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">Hjälp - CRO-förslag</h3>
                                <p class="text-sm text-gray-600">Öka dina konverteringar med AI-optimering</p>
                            </div>
                        </div>
                        <button onclick="toggleCroHelpModal()" class="p-2 hover:bg-gray-100 rounded-xl transition-colors duration-200">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Content -->
                    <div class="p-6 space-y-6">
                        <!-- What is CRO -->
                        <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-5 border border-purple-200">
                            <h4 class="font-semibold text-purple-900 mb-2 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Vad är CRO (Conversion Rate Optimization)?
                            </h4>
                            <p class="text-purple-800 text-sm leading-relaxed">
                                CRO handlar om att optimera din webbplats för att få fler besökare att genomföra önskade handlingar - som köp, registreringar eller kontakt. AI:n analyserar din sajt och ger konkreta förslag för att höja konverteringsgraden.
                            </p>
                        </div>

                        <!-- How CRO Analysis Works -->
                        <div class="space-y-4">
                            <h4 class="font-semibold text-gray-900 text-lg">Så fungerar CRO-analysen:</h4>

                            <!-- Content Analysis -->
                            <div class="flex items-start space-x-3">
                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h5 class="font-medium text-gray-900">Innehållsanalys</h5>
                                    <p class="text-sm text-gray-600">AI:n läser och analyserar innehållet på dina sidor för att identifiera styrkor och svagheter i budskap och struktur.</p>
                                </div>
                            </div>

                            <!-- UX Assessment -->
                            <div class="flex items-start space-x-3">
                                <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h5 class="font-medium text-gray-900">Användarupplevelse (UX)</h5>
                                    <p class="text-sm text-gray-600">Utvärderar sidlayout, navigation, call-to-actions och övriga element som påverkar användarens beslut att konvertera.</p>
                                </div>
                            </div>

                            <!-- Psychology -->
                            <div class="flex items-start space-x-3">
                                <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h5 class="font-medium text-gray-900">Psykologiska triggers</h5>
                                    <p class="text-sm text-gray-600">Identifierar möjligheter att använda beprövade psykologiska principer som social proof, urgency och trust-faktorer.</p>
                                </div>
                            </div>

                            <!-- Technical -->
                            <div class="flex items-start space-x-3">
                                <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h5 class="font-medium text-gray-900">Teknisk optimering</h5>
                                    <p class="text-sm text-gray-600">Ser över laddningstider, mobiloptimering och andra tekniska faktorer som kan hindra konverteringar.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Status Meanings -->
                        <div class="space-y-4">
                            <h4 class="font-semibold text-gray-900 text-lg">Förslagsstatus:</h4>

                            <div class="bg-white rounded-xl p-4 border border-gray-200 space-y-3">
                                <!-- New -->
                                <div class="flex items-center space-x-3">
                                    <div class="inline-flex items-center px-2 py-1 bg-gradient-to-r from-blue-50 to-indigo-50 border-blue-200/50 border rounded-full">
                                        <div class="w-4 h-4 bg-blue-500 rounded-full flex items-center justify-center mr-2">
                                            <svg class="w-2 h-2 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 100-2 1 1 0 000 2zm7-1a1 1 0 11-2 0 1 1 0 012 0zm-.464 5.535a1 1 0 10-1.415-1.414 3 3 0 01-4.242 0 1 1 0 00-1.415 1.414 5 5 0 007.072 0z"/>
                                            </svg>
                                        </div>
                                        <span class="text-xs font-medium text-blue-800 uppercase">NYA</span>
                                    </div>
                                    <span class="text-sm text-gray-700"><strong>Nya förslag:</strong> Nyligen genererade förslag som väntar på implementering</span>
                                </div>

                                <!-- Applied -->
                                <div class="flex items-center space-x-3">
                                    <div class="inline-flex items-center px-2 py-1 bg-gradient-to-r from-green-50 to-emerald-50 border-green-200/50 border rounded-full">
                                        <div class="w-4 h-4 bg-green-500 rounded-full flex items-center justify-center mr-2">
                                            <svg class="w-2 h-2 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                                            </svg>
                                        </div>
                                        <span class="text-xs font-medium text-green-800 uppercase">APPLICERADE</span>
                                    </div>
                                    <span class="text-sm text-gray-700"><strong>Implementerade:</strong> Förslag du har tillämpat på din webbplats</span>
                                </div>

                                <!-- Dismissed -->
                                <div class="flex items-center space-x-3">
                                    <div class="inline-flex items-center px-2 py-1 bg-gradient-to-r from-gray-50 to-slate-50 border-gray-200/50 border rounded-full">
                                        <div class="w-4 h-4 bg-gray-500 rounded-full flex items-center justify-center mr-2">
                                            <svg class="w-2 h-2 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"/>
                                            </svg>
                                        </div>
                                        <span class="text-xs font-medium text-gray-800 uppercase">AVFÄRDADE</span>
                                    </div>
                                    <span class="text-sm text-gray-700"><strong>Borttagna:</strong> Förslag du valt att inte implementera</span>
                                </div>
                            </div>
                        </div>

                        <!-- Types of Suggestions -->
                        <div class="bg-gradient-to-r from-emerald-50 to-teal-50 rounded-xl p-5 border border-emerald-200">
                            <h4 class="font-semibold text-emerald-900 mb-3 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Vanliga förbättringsförslag:
                            </h4>
                            <ul class="text-sm text-emerald-800 space-y-1">
                                <li>• <strong>Call-to-Action:</strong> Bättre knappar och länktext för att driva handlingar</li>
                                <li>• <strong>Rubriker:</strong> Mer övertygande och klara budskap</li>
                                <li>• <strong>Social proof:</strong> Kundrecensioner, testimonials och trovärdighet</li>
                                <li>• <strong>Urgency:</strong> Tidsbegränsade erbjudanden och knapphet</li>
                                <li>• <strong>Förtroende:</strong> Säkerhetsmärken, garantier och riskfri provperiod</li>
                                <li>• <strong>Formuläroptimering:</strong> Enklare och mer konverterande formulär</li>
                            </ul>
                        </div>

                        <!-- Getting Started -->
                        <div class="space-y-4">
                            <h4 class="font-semibold text-gray-900 text-lg">Så kommer du igång:</h4>

                            <div class="bg-white rounded-xl p-4 border border-gray-200">
                                <ol class="space-y-3 text-sm">
                                    <li class="flex items-start space-x-3">
                                        <span class="w-6 h-6 bg-purple-600 text-white rounded-full text-xs flex items-center justify-center font-semibold flex-shrink-0">1</span>
                                        <div>
                                            <strong>Kör analys:</strong> Klicka på "Kör analys" för att starta CRO-scanningen av din sajt
                                        </div>
                                    </li>
                                    <li class="flex items-start space-x-3">
                                        <span class="w-6 h-6 bg-purple-600 text-white rounded-full text-xs flex items-center justify-center font-semibold flex-shrink-0">2</span>
                                        <div>
                                            <strong>Vänta på resultat:</strong> AI:n analyserar upp till 12 sidor och genererar förslag (tar några minuter)
                                        </div>
                                    </li>
                                    <li class="flex items-start space-x-3">
                                        <span class="w-6 h-6 bg-purple-600 text-white rounded-full text-xs flex items-center justify-center font-semibold flex-shrink-0">3</span>
                                        <div>
                                            <strong>Prioritera förslag:</strong> Börja med förslag som är enkla att implementera och har stor påverkan
                                        </div>
                                    </li>
                                    <li class="flex items-start space-x-3">
                                        <span class="w-6 h-6 bg-purple-600 text-white rounded-full text-xs flex items-center justify-center font-semibold flex-shrink-0">4</span>
                                        <div>
                                            <strong>Implementera och mät:</strong> Gör ändringarna och markera som "Applied" för att följa framsteg
                                        </div>
                                    </li>
                                </ol>
                            </div>
                        </div>

                        <!-- Pro Tips -->
                        <div class="bg-gradient-to-r from-amber-50 to-yellow-50 rounded-xl p-5 border border-amber-200">
                            <h4 class="font-semibold text-amber-900 mb-2 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                                Pro-tips för maximal effekt:
                            </h4>
                            <ul class="text-sm text-amber-800 space-y-1">
                                <li>• <strong>Testa en i taget:</strong> Implementera ett förslag åt gången för att mäta effekten</li>
                                <li>• <strong>Fokusera på högtrafikerade sidor:</strong> Störst påverkan på startsidor och produktsidor</li>
                                <li>• <strong>Mät resultaten:</strong> Använd Google Analytics för att följa konverteringsförändringar</li>
                                <li>• <strong>Kör regelbundet:</strong> Månadsvis analys för kontinuerlig optimering</li>
                            </ul>
                        </div>

                        <!-- Requirements -->
                        <div class="bg-white rounded-xl p-4 border border-gray-200">
                            <h5 class="font-medium text-gray-900 mb-2">Krav för CRO-analys:</h5>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• <strong>Integration:</strong> Kräver kopplad WordPress, Shopify eller Custom integration</li>
                                <li>• <strong>Innehåll:</strong> Fungerar bäst på sidor med tydliga mål (produktsidor, landingssidor)</li>
                                <li>• <strong>Språk:</strong> Optimerad för svenskt innehåll men fungerar med alla språk</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 rounded-b-2xl">
                        <div class="flex items-center justify-between">
                            <p class="text-xs text-gray-500">
                                CRO-förbättringar kan öka konverteringsgraden med 10-50% eller mer
                            </p>
                            <button onclick="toggleCroHelpModal()" class="px-4 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors duration-200 text-sm">
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
                <label class="text-sm font-medium text-gray-700">Status:</label>
                <select wire:model.live="status" class="px-4 py-2 bg-white border border-gray-300 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200">
                    <option value="new">Nya</option>
                    <option value="applied">Applicerade</option>
                    <option value="dismissed">Avfärdade</option>
                    <option value="all">Alla</option>
                </select>
            </div>
        </div>

        <!-- Suggestions list -->
        <div class="space-y-4">
            @forelse($sugs as $s)
                @php
                    $statusColors = [
                        'new' => ['bg' => 'from-blue-50 to-indigo-50', 'border' => 'border-blue-200/50', 'text' => 'text-blue-800', 'icon' => 'bg-blue-500'],
                        'applied' => ['bg' => 'from-green-50 to-emerald-50', 'border' => 'border-green-200/50', 'text' => 'text-green-800', 'icon' => 'bg-green-500'],
                        'dismissed' => ['bg' => 'from-gray-50 to-slate-50', 'border' => 'border-gray-200/50', 'text' => 'text-gray-800', 'icon' => 'bg-gray-500'],
                    ];
                    $colors = $statusColors[$s->status] ?? ['bg' => 'from-purple-50 to-pink-50', 'border' => 'border-purple-200/50', 'text' => 'text-purple-800', 'icon' => 'bg-purple-500'];
                @endphp

                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100/50 p-6 hover:shadow-2xl transition-all duration-200">
                    <div class="flex items-start justify-between">
                        <div class="flex-1 min-w-0">
                            <!-- Status badge and type -->
                            <div class="flex items-center space-x-3 mb-3">
                                <div class="inline-flex items-center px-3 py-1 bg-gradient-to-r {{ $colors['bg'] }} {{ $colors['border'] }} border rounded-full">
                                    <div class="w-4 h-4 {{ $colors['icon'] }} rounded-full flex items-center justify-center mr-2">
                                        @if($s->status === 'applied')
                                            <svg class="w-2 h-2 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                                            </svg>
                                        @elseif($s->status === 'dismissed')
                                            <svg class="w-2 h-2 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"/>
                                            </svg>
                                        @else
                                            <svg class="w-2 h-2 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 100-2 1 1 0 000 2zm7-1a1 1 0 11-2 0 1 1 0 012 0zm-.464 5.535a1 1 0 10-1.415-1.414 3 3 0 01-4.242 0 1 1 0 00-1.415 1.414 5 5 0 007.072 0z"/>
                                            </svg>
                                        @endif
                                    </div>
                                    <span class="text-xs font-medium {{ $colors['text'] }} uppercase">{{ $s->status }}</span>
                                </div>

                                <div class="inline-flex items-center px-3 py-1 bg-gradient-to-r from-gray-50 to-white border border-gray-200/50 rounded-full">
                                    <svg class="w-3 h-3 mr-1 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/>
                                    </svg>
                                    <span class="text-xs font-medium text-gray-700">{{ $s->wp_type }} #{{ $s->wp_post_id }}</span>
                                </div>
                            </div>

                            <!-- URL -->
                            <div class="mb-4">
                                <a href="{{ $s->url }}" target="_blank" rel="noopener" class="flex items-center text-purple-600 hover:text-purple-800 hover:underline">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                    <span class="truncate">{{ $s->url }}</span>
                                </a>
                            </div>

                            <!-- Insights -->
                            @if($s->insights)
                                <div class="p-4 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl border border-purple-200/50">
                                    <div class="text-xs font-medium text-purple-700 mb-2 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                        </svg>
                                        Insikter
                                    </div>
                                    <ul class="space-y-1">
                                        @foreach(($s->insights ?? []) as $i)
                                            <li class="flex items-start text-sm text-purple-800">
                                                <svg class="w-3 h-3 mr-2 mt-1 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"/>
                                                </svg>
                                                {{ $i }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>

                        <!-- Action button -->
                        <div class="ml-6 flex-shrink-0">
                            <a href="{{ route('cro.suggestion.detail', $s->id) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl hover:from-indigo-700 hover:to-purple-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transform hover:scale-[1.02] transition-all duration-200 shadow-lg hover:shadow-xl">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Detaljer
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="text-center py-16">
                        <div class="w-24 h-24 bg-gradient-to-br from-purple-100 to-pink-200 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-12 h-12 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">Inga förslag ännu</h3>
                        <p class="text-gray-600 mb-6">Kör en konverteringsanalys för att få optimeringsförslag för din sajt.</p>
                        <a href="{{ route('cro.analyze.run') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-semibold rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-200 shadow-lg">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            Kör analys
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($sugs->hasPages())
            <div class="flex justify-center">
                {{ $sugs->links() }}
            </div>
        @endif
    </div>

    <script>
        function toggleCroHelpModal() {
            const modal = document.getElementById('croHelpModal');
            const content = document.getElementById('croHelpModalContent');

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
            const modal = document.getElementById('croHelpModal');
            if (modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        toggleCroHelpModal();
                    }
                });

                // Close with Escape key
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                        toggleCroHelpModal();
                    }
                });
            }
        });
    </script>
</div>
