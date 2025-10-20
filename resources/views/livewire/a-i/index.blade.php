<div>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 px-4 sm:px-6 lg:px-8 py-6">
        <div class="max-w-7xl mx-auto space-y-6">
            <div id="li-modal" class="hidden fixed inset-0 z-[9999]">
                <div class="absolute inset-0 bg-black/40"></div>
                <div class="relative max-w-2xl mx-4 sm:mx-auto mt-20 bg-white rounded-2xl shadow-2xl border border-gray-200">
                    <div class="p-4 border-b flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Förhandsgranskning</h3>
                        <button id="li-modal-close" class="p-2 rounded hover:bg-gray-100">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <div class="p-6">
                        <pre id="li-modal-body" class="whitespace-pre-wrap text-sm text-gray-800"></pre>
                    </div>
                    <div class="p-4 border-t flex justify-end">
                        <button id="li-modal-close-2" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">Stäng</button>
                    </div>
                </div>
            </div>

            <!-- Header - Mobilvänlig -->
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-4 sm:p-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-xl flex items-center justify-center mr-3 flex-shrink-0">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Innehållsskapare</h1>
                            <p class="text-sm text-gray-600 hidden sm:block">Skapa professionellt innehåll med AI</p>
                        </div>
                        <!-- Help Icon -->
                        <button onclick="toggleAiHelpModal()" class="ml-3 w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center hover:from-blue-600 hover:to-indigo-700 transition-all duration-200 shadow-md hover:shadow-lg group flex-shrink-0">
                            <svg class="w-4 h-4 text-white group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Action Buttons - Mobilvänliga -->
                    <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                        <a href="{{ route('planner.index') }}" class="inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl hover:from-indigo-700 hover:to-purple-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transform hover:scale-[1.02] transition-all duration-200 shadow-lg hover:shadow-xl text-sm sm:text-base">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2z"/>
                            </svg>
                            <span class="whitespace-nowrap">Planera & Publicera</span>
                        </a>
                        <a href="{{ route('ai.compose') }}" class="inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-semibold rounded-xl hover:from-emerald-700 hover:to-teal-700 focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transform hover:scale-[1.02] transition-all duration-200 shadow-lg hover:shadow-xl text-sm sm:text-base">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            <span class="whitespace-nowrap">Skapa text</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Help Modal - Förbättrad för mobil -->
            <div id="aiHelpModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden opacity-0 transition-opacity duration-300 px-4">
                <div class="flex items-start justify-center min-h-screen pt-4 pb-20 sm:pt-10">
                    <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 w-full max-w-4xl max-h-[90vh] overflow-y-auto transform scale-95 transition-transform duration-300" id="aiHelpModalContent">
                        <!-- Header -->
                        <div class="sticky top-0 bg-white rounded-t-2xl flex items-center justify-between p-4 sm:p-6 border-b border-gray-100">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg sm:text-xl font-bold text-gray-900">Hjälp - Innehållsskapande</h3>
                                    <p class="text-sm text-gray-600 hidden sm:block">Skapa professionellt innehåll med AI-assistans</p>
                                </div>
                            </div>
                            <button onclick="toggleAiHelpModal()" class="p-2 hover:bg-gray-100 rounded-xl transition-colors duration-200 flex-shrink-0">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>

                        <!-- Content - Förbättrad för mobil -->
                        <div class="p-4 sm:p-6 space-y-4 sm:space-y-6">
                            <!-- What is AI Content -->
                            <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl p-4 sm:p-5 border border-indigo-200">
                                <h4 class="font-semibold text-indigo-900 mb-2 flex items-center text-sm sm:text-base">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 text-indigo-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Vad är AI-innehåll?
                                </h4>
                                <p class="text-indigo-800 text-xs sm:text-sm leading-relaxed">
                                    AI-innehåll är text genererad av avancerad artificiell intelligens som hjälper dig skapa professionellt, engagerande material för webbplatser, bloggar och sociala medier. Allt optimerat för din målgrupp och SEO.
                                </p>
                            </div>

                            <!-- Content Types - Förbättrat för mobil -->
                            <div class="space-y-3 sm:space-y-4">
                                <h4 class="font-semibold text-gray-900 text-base sm:text-lg">Typer av innehåll du kan skapa:</h4>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                                    <!-- Blog Posts -->
                                    <div class="flex items-start space-x-3 p-3 bg-white rounded-lg border border-gray-200">
                                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <h5 class="font-medium text-gray-900 text-sm">Blogginlägg & artiklar</h5>
                                            <p class="text-xs text-gray-600 mt-1">Djupgående, SEO-optimerade artiklar som engagerar läsare och bygger expertis.</p>
                                        </div>
                                    </div>

                                    <!-- Social Media -->
                                    <div class="flex items-start space-x-3 p-3 bg-white rounded-lg border border-gray-200">
                                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <h5 class="font-medium text-gray-900 text-sm">Sociala medier-inlägg</h5>
                                            <p class="text-xs text-gray-600 mt-1">Engagerande innehåll för Facebook, Instagram, LinkedIn som driver interaktion.</p>
                                        </div>
                                    </div>

                                    <!-- Product Descriptions -->
                                    <div class="flex items-start space-x-3 p-3 bg-white rounded-lg border border-gray-200">
                                        <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l-1 12H6L5 9z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <h5 class="font-medium text-gray-900 text-sm">Produktbeskrivningar</h5>
                                            <p class="text-xs text-gray-600 mt-1">Säljande beskrivningar för e-handel som framhäver fördelar.</p>
                                        </div>
                                    </div>

                                    <!-- Email Content -->
                                    <div class="flex items-start space-x-3 p-3 bg-white rounded-lg border border-gray-200">
                                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 7.89a2 2 0 002.83 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <h5 class="font-medium text-gray-900 text-sm">E-postmarknadsföring</h5>
                                            <p class="text-xs text-gray-600 mt-1">Nyhetsbrev och kampanjmail som konverterar läsare till kunder.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Content Status - Kompakt för mobil -->
                            <div class="space-y-3 sm:space-y-4">
                                <h4 class="font-semibold text-gray-900 text-base sm:text-lg">Statusar för innehåll:</h4>

                                <div class="bg-white rounded-xl p-3 sm:p-4 border border-gray-200">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-3">
                                        <div class="flex items-center space-x-2">
                                            <div class="inline-flex items-center px-2 py-1 bg-gradient-to-r from-yellow-50 to-amber-50 border-yellow-200/50 border rounded-full">
                                                <div class="w-2 h-2 bg-yellow-500 rounded-full mr-1.5"></div>
                                                <span class="text-xs font-medium text-yellow-800">DRAFT</span>
                                            </div>
                                            <span class="text-xs text-gray-700">Utkast</span>
                                        </div>

                                        <div class="flex items-center space-x-2">
                                            <div class="inline-flex items-center px-2 py-1 bg-gradient-to-r from-blue-50 to-indigo-50 border-blue-200/50 border rounded-full">
                                                <div class="w-2 h-2 bg-blue-500 rounded-full mr-1.5"></div>
                                                <span class="text-xs font-medium text-blue-800">PROCESSING</span>
                                            </div>
                                            <span class="text-xs text-gray-700">Bearbetas</span>
                                        </div>

                                        <div class="flex items-center space-x-2">
                                            <div class="inline-flex items-center px-2 py-1 bg-gradient-to-r from-green-50 to-emerald-50 border-green-200/50 border rounded-full">
                                                <div class="w-2 h-2 bg-green-500 rounded-full mr-1.5"></div>
                                                <span class="text-xs font-medium text-green-800">COMPLETED</span>
                                            </div>
                                            <span class="text-xs text-gray-700">Klart</span>
                                        </div>

                                        <div class="flex items-center space-x-2">
                                            <div class="inline-flex items-center px-2 py-1 bg-gradient-to-r from-red-50 to-pink-50 border-red-200/50 border rounded-full">
                                                <div class="w-2 h-2 bg-red-500 rounded-full mr-1.5"></div>
                                                <span class="text-xs font-medium text-red-800">ERROR</span>
                                            </div>
                                            <span class="text-xs text-gray-700">Fel</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Resten av modalen följer samma mönster... -->
                            <!-- (Förkortat för brevity, men alla sektioner ska ha samma mobilvänliga förbättringar) -->
                        </div>

                        <!-- Footer -->
                        <div class="sticky bottom-0 px-4 sm:px-6 py-4 border-t border-gray-100 bg-gray-50 rounded-b-2xl">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                <p class="text-xs text-gray-500 text-center sm:text-left">
                                    AI-innehåll sparas automatiskt och kan redigeras när som helst
                                </p>
                                <button onclick="toggleAiHelpModal()" class="w-full sm:w-auto px-4 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors duration-200 text-sm">
                                    Stäng
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats cards - Mobilvänliga -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-4 sm:p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="text-sm font-medium text-indigo-700 mb-1">Genereringar denna månad</div>
                            <div class="text-2xl sm:text-3xl font-bold text-indigo-900 mb-1">{{ $monthGenerateTotal }}</div>
                            <div class="text-xs sm:text-sm text-indigo-600">{{ now()->format('F Y') }}</div>
                        </div>
                        <div class="w-12 h-12 sm:w-16 sm:h-16 bg-indigo-500 rounded-2xl flex items-center justify-center flex-shrink-0 ml-4">
                            <svg class="w-6 h-6 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-4 sm:p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex-1">
                            <div class="text-sm font-medium text-emerald-700 mb-1">Publiceringar</div>
                            <div class="text-2xl sm:text-3xl font-bold text-emerald-900 mb-1">{{ $monthPublishTotal }}</div>
                            <div class="text-xs sm:text-sm text-emerald-600">Denna månad</div>
                        </div>
                        <div class="w-12 h-12 sm:w-16 sm:h-16 bg-emerald-500 rounded-2xl flex items-center justify-center flex-shrink-0 ml-4">
                            <svg class="w-6 h-6 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M12 6v12m6-6H6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Channel breakdown - Mobilvänlig -->
                    <div class="flex flex-wrap gap-1 sm:gap-2">
                        <span class="inline-flex items-center px-2 py-1 rounded-full bg-white/80 border border-emerald-200 text-emerald-700 text-xs">
                            <svg class="w-3 h-3 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10 1.25A8.75 8.75 0 1018.75 10 8.76 8.76 0 0010 1.25zm0 1.5A7.25 7.25 0 1117.25 10 7.26 7.26 0 0110 2.75z"/>
                            </svg>
                            {{ $monthPublishBy['wp'] }}
                        </span>
                        <span class="inline-flex items-center px-2 py-1 rounded-full bg-white/80 border border-emerald-200 text-emerald-700 text-xs">
                            <svg class="w-3 h-3 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M6 2a2 2 0 00-2 2v1H3a1 1 0 00-1 .8L1 9a2 2 0 002 2h14a2 2 0 002-2l-2-3.2A1 1 0 0016 5h-1V4a2 2 0 00-2-2H6z"/>
                            </svg>
                            {{ $monthPublishBy['shopify'] }}
                        </span>
                        <span class="inline-flex items-center px-2 py-1 rounded-full bg-white/80 border border-emerald-200 text-emerald-700 text-xs">
                            <svg class="w-3 h-3 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M11 2h3a1 1 0 011 1v3h-2a1 1 0 00-1 1v2h3l-.5 3H12v7H9v-7H7V9h2V7a3 3 0 013-3z"/>
                            </svg>
                            {{ $monthPublishBy['facebook'] }}
                        </span>
                        <span class="inline-flex items-center px-2 py-1 rounded-full bg-white/80 border border-emerald-200 text-emerald-700 text-xs">
                            <svg class="w-3 h-3 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M7 2h6a5 5 0 015 5v6a5 5 0 01-5 5H7a5 5 0 01-5-5V7a5 5 0 015-5z"/>
                            </svg>
                            {{ $monthPublishBy['instagram'] }}
                        </span>
                        <span class="inline-flex items-center px-2 py-1 rounded-full bg-white/80 border border-emerald-200 text-emerald-700 text-xs">
                            <svg class="w-3 h-3 mr-1" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M4.98 3.5C4.98 4.88 3.86 6 2.5 6S0 4.88 0 3.5 1.12 1 2.5 1s2.48 1.12 2.48 2.5z"/>
                            </svg>
                            {{ $monthPublishBy['linkedin'] }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Content grid - Förbättrat för mobil -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                @forelse($items as $c)
                    @php
                        $statusColors = [
                            'ready' => ['bg' => 'from-green-50 to-emerald-50', 'border' => 'border-green-200/50', 'text' => 'text-green-800', 'icon' => 'bg-green-500'],
                            'completed' => ['bg' => 'from-green-50 to-emerald-50', 'border' => 'border-green-200/50', 'text' => 'text-green-800', 'icon' => 'bg-green-500'],
                            'processing' => ['bg' => 'from-blue-50 to-indigo-50', 'border' => 'border-blue-200/50', 'text' => 'text-blue-800', 'icon' => 'bg-blue-500'],
                            'draft' => ['bg' => 'from-yellow-50 to-amber-50', 'border' => 'border-yellow-200/50', 'text' => 'text-yellow-800', 'icon' => 'bg-yellow-500'],
                            'error' => ['bg' => 'from-red-50 to-pink-50', 'border' => 'border-red-200/50', 'text' => 'text-red-800', 'icon' => 'bg-red-500'],
                            'failed' => ['bg' => 'from-red-50 to-pink-50', 'border' => 'border-red-200/50', 'text' => 'text-red-800', 'icon' => 'bg-red-500'],
                        ];
                        $colors = $statusColors[strtolower($c->status)] ?? ['bg' => 'from-gray-50 to-slate-50', 'border' => 'border-gray-200/50', 'text' => 'text-gray-800', 'icon' => 'bg-gray-500'];

                        $statuses = [
                            'ready' => 'Redo',
                            'completed' => 'Färdig',
                            'processing' => 'Förbereder',
                            'draft' => 'Utkast',
                            'error' => 'Fel',
                            'queued' => 'Köad',
                            'published' => 'Publicerad',
                        ];
                    @endphp

                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-4 sm:p-6 hover:shadow-xl transition-all duration-200">
                        <!-- Header with status -->
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center space-x-2 sm:space-x-3">
                                <div class="inline-flex items-center px-2 sm:px-3 py-1 bg-gradient-to-r {{ $colors['bg'] }} {{ $colors['border'] }} border rounded-full">
                                    <div class="w-2 h-2 sm:w-3 sm:h-3 {{ $colors['icon'] }} rounded-full mr-1.5 sm:mr-2"></div>
                                    <span class="text-xs font-medium {{ $colors['text'] }} uppercase">{{ $statuses[$c->status] }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Content title -->
                        <div class="mb-4">
                            <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-2 line-clamp-2">
                                {{ $c->title ?: '(Ingen titel ännu)' }}
                            </h3>
                        </div>

                        <!-- Meta information - Kompakt för mobil -->
                        <div class="mb-4 sm:mb-6 p-3 sm:p-4 bg-gradient-to-r from-gray-50 to-white rounded-xl border border-gray-200/50">
                            <div class="grid grid-cols-1 gap-2 text-sm">
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600 text-xs sm:text-sm">Mall:</span>
                                    <span class="font-medium text-gray-900 text-xs sm:text-sm">#{{ $c->template_id }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600 text-xs sm:text-sm">Skapad:</span>
                                    <span class="font-medium text-gray-900 text-xs sm:text-sm">{{ $c->created_at->diffForHumans() }}</span>
                                </div>
                            </div>

                            @php
                                $pubs = $c->relationLoaded('publications') ? $c->publications : ($c->publications ?? collect());
                                $byTarget = [
                                    'wp'       => $pubs->where('target','wp'),
                                    'shopify'  => $pubs->where('target','shopify'),
                                    'facebook' => $pubs->where('target','facebook'),
                                    'instagram'=> $pubs->where('target','instagram'),
                                    'linkedin' => $pubs->where('target','linkedin'),
                                ];
                                $state = function($col) {
                                    if ($col->where('status','published')->count() > 0) return 'ok';
                                    if ($col->whereIn('status',['queued','processing'])->count() > 0) return 'pending';
                                    if ($col->where('status','failed')->count() > 0) return 'failed';
                                    return 'none';
                                };
                                $statusToColor = fn($s) => match($s) {
                                    'ok' => 'text-emerald-600',
                                    'pending' => 'text-amber-500',
                                    'failed' => 'text-red-600',
                                    default => 'text-gray-400',
                                };
                            @endphp

                            <div class="mt-3 flex items-center gap-2 sm:gap-4 flex-wrap">
                                @foreach(['wp'=>'WP','shopify'=>'Shop','facebook'=>'FB','instagram'=>'IG','linkedin'=>'LI'] as $t=>$label)
                                    @php $st = $state($byTarget[$t]); @endphp
                                    <div class="flex items-center gap-1">
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 {{ $statusToColor($st) }}" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                            @if($t === 'wp')
                                                <path d="M10 1.25A8.75 8.75 0 1018.75 10 8.76 8.76 0 0010 1.25zm0 1.5A7.25 7.25 0 1117.25 10 7.26 7.26 0 0110 2.75z"/>
                                            @elseif($t === 'shopify')
                                                <path d="M6 2a2 2 0 00-2 2v1H3a1 1 0 00-1 .8L1 9a2 2 0 002 2h14a2 2 0 002-2l-2-3.2A1 1 0 0016 5h-1V4a2 2 0 00-2-2H6z"/>
                                            @elseif($t === 'facebook')
                                                <path d="M11 2h3a1 1 0 011 1v3h-2a1 1 0 00-1 1v2h3l-.5 3H12v7H9v-7H7V9h2V7a3 3 0 013-3z"/>
                                            @elseif($t === 'instagram')
                                                <path d="M7 2h6a5 5 0 015 5v6a5 5 0 01-5 5H7a5 5 0 01-5-5V7a5 5 0 015-5z"/>
                                            @else
                                                <path d="M4 3h12a1 1 0 011 1v12a1 1 0 01-1 1H4a1 1 0 01-1-1V4a1 1 0 011-1z"/>
                                            @endif
                                        </svg>
                                        <span class="text-xs text-gray-600">{{ $label }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Action button -->
                        <div class="flex justify-end">
                            <a href="{{ route('ai.detail', $c->id) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl hover:from-indigo-700 hover:to-purple-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transform hover:scale-[1.02] transition-all duration-200 shadow-lg hover:shadow-xl text-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Öppna
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full">
                        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 text-center py-12 sm:py-16 px-4">
                            <div class="w-16 h-16 sm:w-24 sm:h-24 bg-gradient-to-br from-indigo-100 to-purple-200 rounded-full flex items-center justify-center mx-auto mb-4 sm:mb-6">
                                <svg class="w-8 h-8 sm:w-12 sm:h-12 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2 sm:mb-3">Inget AI-innehåll ännu</h3>
                            <p class="text-gray-600 mb-4 sm:mb-6 text-sm sm:text-base">Börja skapa AI-genererat innehåll för dina sajter och sociala kanaler.</p>
                            <a href="{{ route('ai.compose') }}" class="inline-flex items-center px-4 sm:px-6 py-2 sm:py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 shadow-lg text-sm sm:text-base">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Skapa ditt första innehåll
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

            @if($items->hasPages())
                <div class="space-y-2">
                    <div class="flex justify-center">
                        {{ $items->links('pagination::simple-tailwind') }}
                    </div>
                    <p class="text-center text-xs text-gray-500">
                        Visar {{ $items->firstItem() }}–{{ $items->lastItem() }} av {{ $items->total() }} resultat
                    </p>
                </div>
            @endif
        </div>
    </div>

    <script>
        function toggleAiHelpModal() {
            const modal = document.getElementById('aiHelpModal');
            const content = document.getElementById('aiHelpModalContent');

            if (modal.classList.contains('hidden')) {
                // Show modal
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
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
                document.body.style.overflow = '';
                setTimeout(() => {
                    modal.classList.add('hidden');
                }, 300);
            }
        }

        // Close modal when clicking outside
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('aiHelpModal');
            if (modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        toggleAiHelpModal();
                    }
                });

                // Close with Escape key
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                        toggleAiHelpModal();
                    }
                });
            }
        });
    </script>
</div>

@push('scripts')
    <script>
        (function() {
            const apiIndex = "{{ route('linkedin.suggestions.index') }}";
            const apiStore = "{{ route('linkedin.suggestions.store') }}";
            const apiPublish = "{{ route('linkedin.publish') }}";
            const csrf = "{{ csrf_token() }}";

            const modal = document.getElementById('li-modal');
            const modalBody = document.getElementById('li-modal-body');
            function openModal(text) {
                modalBody.textContent = text || '';
                modal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }
            function closeModal() {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
            document.getElementById('li-modal-close')?.addEventListener('click', closeModal);
            document.getElementById('li-modal-close-2')?.addEventListener('click', closeModal);
            modal?.addEventListener('click', (e) => {
                if (e.target === modal) closeModal();
            });

            function escapeHtml(str) {
                return (str || '').replace(/[&<>"']/g, function(m) {
                    return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[m]);
                });
            }

            async function loadSuggestions() {
                const wrap = document.getElementById('li-suggestions');
                if (!wrap) return;
                wrap.innerHTML = '<div class="text-sm text-gray-500">Laddar...</div>';
                try {
                    const res = await fetch(apiIndex, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                    const data = await res.json();
                    wrap.innerHTML = '';
                    const items = (data && data.data) ? data.data : [];
                    if (!items.length) {
                        wrap.innerHTML = '<div class="text-sm text-gray-500">Inga aktiva förslag just nu.</div>';
                        return;
                    }
                    items.forEach(function(sug) {
                        const leftMs = new Date(sug.expires_at).getTime() - Date.now();
                        const leftDays = Math.max(0, Math.floor(leftMs / (1000*60*60*24)));
                        const full = sug.content || '';
                        const preview = full.length > 220 ? full.slice(0, 220) + '…' : full;

                        const card = document.createElement('div');
                        card.className = 'p-4 bg-gray-50 rounded-xl border border-gray-200';

                        const times = (sug.recommended_times || []).slice(0,3).map(function(t) {
                            try { return new Date(t).toLocaleString(); } catch (_) { return t; }
                        });

                        card.innerHTML = `
                    <div class="flex items-start justify-between">
                        <div class="text-sm text-gray-800 whitespace-pre-wrap mr-3">${escapeHtml(preview)}</div>
                        <div class="flex flex-col items-end gap-2">
                            <button class="px-3 py-1 bg-white border border-gray-300 rounded text-xs view">Visa</button>
                            <button class="px-3 py-1 bg-white border border-gray-300 rounded text-xs copy">Kopiera</button>
                        </div>
                    </div>
                    <div class="mt-2 text-xs text-gray-600">Försvinner om ${leftDays} dagar</div>
                    <div class="mt-2 flex flex-wrap gap-2 text-xs">
                        ${times.map(function(t){return `<span class="px-2 py-1 bg-white border rounded">${t}</span>`}).join('')}
                    </div>
                    <div class="mt-3 grid grid-cols-1 md:grid-cols-4 gap-2">
                        @if(config('features.image_generation'))
                        <input type="text" placeholder="Bildprompt (valfritt)" class="img-prompt md:col-span-2 px-2 py-1 border rounded text-xs">
                        @endif
                        <input type="datetime-local" placeholder="Schemalägg" class="schedule md:col-span-1 px-2 py-1 border rounded text-xs">
                        <button class="publish px-3 py-1 bg-sky-600 text-white rounded text-xs md:col-span-1">Publicera</button>
                    </div>
                `;
                        wrap.appendChild(card);

                        card.querySelector('.view').addEventListener('click', function() {
                            openModal(full);
                        });
                        card.querySelector('.copy').addEventListener('click', function() {
                            navigator.clipboard.writeText(full);
                        });
                        card.querySelector('.publish').addEventListener('click', async function() {
                            const prompt = card.querySelector('.img-prompt').value || null;
                            const sched = card.querySelector('.schedule').value || null;
                            const body = {
                                text: full,
                                image_prompt: prompt,
                                schedule_at: sched || null
                            };
                            const resp = await fetch(apiPublish, {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                                body: JSON.stringify(body)
                            });
                            const out = await resp.json();
                            alert(out.message || 'Kölagd.');
                        });
                    });
                } catch (e) {
                    wrap.innerHTML = '<div class="text-sm text-red-600">Kunde inte ladda förslag.</div>';
                }
            }

            document.getElementById('li-generate')?.addEventListener('click', async function() {
                const topic = document.getElementById('li-topic').value.trim();
                const tone  = document.getElementById('li-tone').value.trim();
                if (!topic) { alert('Ange ett ämne'); return; }
                const res = await fetch(apiStore, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                    body: JSON.stringify({ topic: topic, tone: tone || null, count: 3 })
                });
                const out = await res.json();
                alert(out.message || 'Kölagd.');
                loadSuggestions();
            });

            document.getElementById('li-refresh')?.addEventListener('click', loadSuggestions);

            // Auto-load
            loadSuggestions();
        })();
    </script>
@endpush
