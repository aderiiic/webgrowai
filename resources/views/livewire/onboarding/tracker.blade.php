
<div>
    <div class="max-w-5xl mx-auto space-y-8" @if($listening) wire:poll.10s="listen" @endif>
        <!-- Header -->
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                <svg class="w-8 h-8 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Lead Tracking Setup
            </h1>
            <div class="text-sm text-gray-600 bg-gray-100 px-3 py-1 rounded-full">
                Onboarding steg 2
            </div>
        </div>

        <!-- Site information -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100/50 p-6">
            <div class="flex items-center space-x-4 mb-6">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Din sajt</h2>
                    <p class="text-sm text-gray-600">Information för att ansluta lead tracking</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="p-4 bg-gradient-to-r from-emerald-50 to-teal-50 rounded-xl border border-emerald-200/50">
                    <div class="text-xs font-medium text-emerald-700 mb-2">Sajt</div>
                    <div class="text-lg font-semibold text-emerald-900">{{ $siteName }}</div>
                </div>

                <div class="p-4 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl border border-purple-200/50">
                    <div class="text-xs font-medium text-purple-700 mb-2">Site Key</div>
                    <div class="flex items-center space-x-2">
                        <input class="flex-1 px-3 py-2 bg-white border border-purple-300 rounded-lg text-sm font-mono text-purple-900 focus:ring-2 focus:ring-purple-500 focus:border-purple-500" value="{{ $siteKey }}" readonly>
                    </div>
                </div>

                <div class="p-4 bg-gradient-to-r from-orange-50 to-red-50 rounded-xl border border-orange-200/50">
                    <div class="text-xs font-medium text-orange-700 mb-2">Track URL</div>
                    <div class="flex items-center space-x-2">
                        <input class="flex-1 px-3 py-2 bg-white border border-orange-300 rounded-lg text-sm font-mono text-orange-900 focus:ring-2 focus:ring-orange-500 focus:border-orange-500" value="{{ rtrim($trackUrl,'/') }}" readonly>
                    </div>
                </div>
            </div>
        </div>

        <!-- WordPress plugin method -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100/50 p-8">
            <div class="flex items-center space-x-4 mb-6">
                <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M21.469 6.825c.84 1.537 1.318 3.3 1.318 5.175 0 3.979-2.156 7.456-5.363 9.325l3.295-9.527c.615-1.54.82-2.771.82-3.864 0-.405-.026-.78-.07-1.11m-7.981.105c.647-.03 1.232-.105 1.232-.105.582-.075.514-.93-.067-.899 0 0-1.755.135-2.88.135-1.064 0-2.85-.15-2.85-.15-.584-.03-.661.854-.075.884 0 0 .54.061 1.125.09l1.68 4.605-2.37 7.08L5.354 6.9c.649-.03 1.234-.1 1.234-.1.585-.075.516-.93-.065-.896 0 0-1.746.138-2.874.138-.2 0-.438-.008-.69-.015C4.911 3.15 8.235 1.215 12 1.215c2.809 0 5.365 1.072 7.286 2.833-.046-.003-.091-.009-.141-.009-1.06 0-1.812.923-1.812 1.914 0 .89.513 1.643 1.06 2.531.411.72.89 1.643.89 2.977 0 .915-.354 1.994-.821 3.479l-1.075 3.585-3.9-11.61.001.014z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Rekommenderat: WordPress Plugin</h2>
                    <p class="text-sm text-gray-600">Enklaste sättet att installera lead tracking</p>
                </div>
            </div>

            <div class="space-y-6">
                <div class="p-6 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200/50">
                    <h3 class="font-semibold text-blue-900 mb-4">Installationssteg:</h3>
                    <ol class="space-y-3 text-sm text-blue-800">
                        <li class="flex items-start">
                            <span class="w-6 h-6 bg-blue-500 text-white text-xs rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">1</span>
                            <span>Ladda ner pluginet "Webbi Lead Tracker" (zip-fil)</span>
                        </li>
                        <li class="flex items-start">
                            <span class="w-6 h-6 bg-blue-500 text-white text-xs rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">2</span>
                            <span>Installera & aktivera i WordPress admin → Plugins → Add New → Upload Plugin</span>
                        </li>
                        <li class="flex items-start">
                            <span class="w-6 h-6 bg-blue-500 text-white text-xs rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">3</span>
                            <div>
                                <span>Gå till Inställningar → Webbi Lead Tracker och ange:</span>
                                <ul class="mt-2 ml-4 space-y-1">
                                    <li class="flex items-center">
                                        <span class="w-2 h-2 bg-blue-400 rounded-full mr-2"></span>
                                        <strong>Site Key:</strong> <code class="ml-1 px-2 py-1 bg-white bg-opacity-60 rounded text-xs font-mono">{{ $siteKey }}</code>
                                    </li>
                                    <li class="flex items-center">
                                        <span class="w-2 h-2 bg-blue-400 rounded-full mr-2"></span>
                                        <strong>Track URL:</strong> <code class="ml-1 px-2 py-1 bg-white bg-opacity-60 rounded text-xs font-mono">{{ rtrim($trackUrl,'/') }}</code>
                                    </li>
                                    <li class="flex items-center">
                                        <span class="w-2 h-2 bg-blue-400 rounded-full mr-2"></span>
                                        <span><strong>Cookie-samtycke:</strong> Kryssa om ni använder cookie-banner</span>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ol>
                </div>

                <div class="flex justify-center">
                    <a href="{{ route('downloads.webbi-lead-tracker') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-semibold rounded-xl hover:from-emerald-700 hover:to-teal-700 focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transform hover:scale-[1.02] transition-all duration-200 shadow-lg hover:shadow-xl">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
                        </svg>
                        Ladda ner WordPress Plugin
                    </a>
                </div>
            </div>
        </div>

        <!-- Manual installation -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100/50 p-8">
            <div class="flex items-center space-x-4 mb-6">
                <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Alternativ: Manuell installation</h2>
                    <p class="text-sm text-gray-600">För avancerade användare eller andra CMS-system</p>
                </div>
            </div>

            <div class="p-6 bg-gradient-to-r from-gray-50 to-white rounded-xl border border-gray-200/50">
                <p class="text-sm text-gray-700 mb-4">Lägg till följande kod i sidhuvudet (före &lt;/head&gt;):</p>
                <div class="bg-gray-900 text-gray-100 p-4 rounded-lg overflow-auto">
                    <pre class="text-sm font-mono"><code>&lt;script&gt;
  window.WEBBI_SITE_KEY = '{{ $siteKey }}';
  window.WEBBI_TRACK_URL = '{{ rtrim($trackUrl,'/') }}';
&lt;/script&gt;
&lt;script src="{{ rtrim($trackUrl,'/') }}/lead-tracker.js" defer&gt;&lt;/script&gt;</code></pre>
                </div>
            </div>
        </div>

        <!-- Test tracking -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100/50 p-8">
            <div class="flex items-center space-x-4 mb-6">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Testa lead tracking</h2>
                    <p class="text-sm text-gray-600">Verifiera att allt fungerar korrekt</p>
                </div>
            </div>

            <div class="space-y-6">
                <div class="p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200/50">
                    <h3 class="font-semibold text-blue-900 mb-2">Så här testar du:</h3>
                    <ol class="space-y-2 text-sm text-blue-800">
                        <li class="flex items-start">
                            <span class="w-5 h-5 bg-blue-500 text-white text-xs rounded-full flex items-center justify-center mr-2 mt-0.5 flex-shrink-0">1</span>
                            <span>Öppna din sajt i en ny flik/webbläsarfönster</span>
                        </li>
                        <li class="flex items-start">
                            <span class="w-5 h-5 bg-blue-500 text-white text-xs rounded-full flex items-center justify-center mr-2 mt-0.5 flex-shrink-0">2</span>
                            <span>Navigera mellan ett par sidor</span>
                        </li>
                        <li class="flex items-start">
                            <span class="w-5 h-5 bg-blue-500 text-white text-xs rounded-full flex items-center justify-center mr-2 mt-0.5 flex-shrink-0">3</span>
                            <span>Klicka på knappar eller länkar märkta med <code class="px-1 bg-white bg-opacity-60 rounded">data-lead-cta="..."</code></span>
                        </li>
                        <li class="flex items-start">
                            <span class="w-5 h-5 bg-blue-500 text-white text-xs rounded-full flex items-center justify-center mr-2 mt-0.5 flex-shrink-0">4</span>
                            <span>Kom tillbaka hit och klicka "Börja lyssna" för att se om data kommer in</span>
                        </li>
                    </ol>
                </div>

                <div class="flex items-center justify-between p-4 bg-gradient-to-r from-purple-50 to-indigo-50 rounded-xl border border-purple-200/50">
                    <div class="flex items-center space-x-4">
                        <button wire:click="listen" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold rounded-lg hover:from-purple-700 hover:to-indigo-700 focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-all duration-200 shadow-md hover:shadow-lg">
                            @if($listening)
                                <svg class="w-4 h-4 mr-2 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 14.142M6.343 6.343a9 9 0 000 12.728m2.121-10.607a5 5 0 000 7.07"/>
                                </svg>
                                Lyssnar...
                            @else
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                </svg>
                                Börja lyssna
                            @endif
                        </button>

                        @if($listening)
                            <div class="flex items-center space-x-2">
                                <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                                <span class="text-sm text-purple-700">Övervakar inkommande events...</span>
                            </div>
                        @endif
                    </div>

                    <div class="text-right">
                        <div class="text-xs text-purple-700">Senaste event</div>
                        <div class="text-sm font-semibold text-purple-900">
                            {{ $lastEventAt ?? 'Ingen aktivitet än' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
