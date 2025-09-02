
<div>
    <div class="max-w-5xl mx-auto space-y-8" @if($listening) wire:poll.10s="listen" @endif>
        <!-- Header -->
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                <svg class="w-8 h-8 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Installera besökarspårning
            </h1>
            <a href="{{ route('onboarding') }}?step=4" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">
                ← Tillbaka till onboarding
            </a>
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
                    <h2 class="text-xl font-bold text-gray-900">Din sajt: {{ $siteName }}</h2>
                    <p class="text-sm text-gray-600">Välj hur du vill installera spårningen</p>
                </div>
            </div>

            <!-- Platform buttons -->
            <div class="grid md:grid-cols-3 gap-4 mb-6">
                <button onclick="scrollToSection('wordpress')" class="p-4 bg-blue-50 border-2 border-blue-200 rounded-xl hover:bg-blue-100 transition text-left">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M21.469 6.825c.84 1.537 1.318 3.3 1.318 5.175 0 3.979-2.156 7.456-5.363 9.325l3.295-9.527c.615-1.54.82-2.771.82-3.864 0-.405-.026-.78-.07-1.11m-7.981.105c.647-.03 1.232-.105 1.232-.105.582-.075.514-.93-.067-.899 0 0-1.755.135-2.88.135-1.064 0-2.85-.15-2.85-.15-.584-.03-.661.854-.075.884 0 0 .54.061 1.125.09l1.68 4.605-2.37 7.08L5.354 6.9c.649-.03 1.234-.1 1.234-.1.585-.075.516-.93-.065-.896 0 0-1.746.138-2.874.138-.2 0-.438-.008-.69-.015C4.911 3.15 8.235 1.215 12 1.215c2.809 0 5.365 1.072 7.286 2.833-.046-.003-.091-.009-.141-.009-1.06 0-1.812.923-1.812 1.914 0 .89.513 1.643 1.06 2.531.411.72.89 1.643.89 2.977 0 .915-.354 1.994-.821 3.479l-1.075 3.585-3.9-11.61.001.014z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="font-semibold text-blue-900">WordPress</div>
                            <div class="text-sm text-blue-700">Plugin (enklast)</div>
                        </div>
                    </div>
                </button>

                <button onclick="scrollToSection('shopify')" class="p-4 bg-green-50 border-2 border-green-200 rounded-xl hover:bg-green-100 transition text-left">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M15.337 2.001c-0.907,0-1.76,0.655-2.332,1.603-0.458-0.174-0.985-0.266-1.562-0.266-2.156,0-4.003,1.299-5.229,3.284-1.117-0.426-2.162-0.37-2.805,0.273-0.764,0.763-0.764,2.212,0,2.975l8.834,8.834c0.381,0.381,0.893,0.596,1.438,0.596s1.057-0.215,1.438-0.596l8.834-8.834c0.764-0.763,0.764-2.212,0-2.975-0.643-0.643-1.688-0.699-2.805-0.273-1.226-1.985-3.073-3.284-5.229-3.284-0.577,0-1.104,0.092-1.562,0.266C17.097,2.656,16.244,2.001,15.337,2.001z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="font-semibold text-green-900">Shopify</div>
                            <div class="text-sm text-green-700">JavaScript-kod</div>
                        </div>
                    </div>
                </button>

                <button onclick="scrollToSection('custom')" class="p-4 bg-purple-50 border-2 border-purple-200 rounded-xl hover:bg-purple-100 transition text-left">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="font-semibold text-purple-900">Annan sajt</div>
                            <div class="text-sm text-purple-700">JavaScript-kod</div>
                        </div>
                    </div>
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="p-4 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl border border-purple-200/50">
                    <div class="text-xs font-medium text-purple-700 mb-2">Site Key (behövs för alla metoder)</div>
                    <div class="flex items-center space-x-2">
                        <input class="flex-1 px-3 py-2 bg-white border border-purple-300 rounded-lg text-sm font-mono text-purple-900 focus:ring-2 focus:ring-purple-500 focus:border-purple-500" value="{{ $siteKey }}" readonly>
                        <button onclick="copyToClipboard('{{ $siteKey }}')" class="px-3 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 text-sm">
                            Kopiera
                        </button>
                    </div>
                </div>

                <div class="p-4 bg-gradient-to-r from-orange-50 to-red-50 rounded-xl border border-orange-200/50">
                    <div class="text-xs font-medium text-orange-700 mb-2">Track URL</div>
                    <div class="flex items-center space-x-2">
                        <input class="flex-1 px-3 py-2 bg-white border border-orange-300 rounded-lg text-sm font-mono text-orange-900 focus:ring-2 focus:ring-orange-500 focus:border-orange-500" value="https://webgrowai.se" readonly>
                        <button onclick="copyToClipboard('https://webgrowai.se')" class="px-3 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 text-sm">
                            Kopiera
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- WordPress plugin method -->
        <div id="wordpress" class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100/50 p-8">
            <div class="flex items-center space-x-4 mb-6">
                <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M21.469 6.825c.84 1.537 1.318 3.3 1.318 5.175 0 3.979-2.156 7.456-5.363 9.325l3.295-9.527c.615-1.54.82-2.771.82-3.864 0-.405-.026-.78-.07-1.11m-7.981.105c.647-.03 1.232-.105 1.232-.105.582-.075.514-.93-.067-.899 0 0-1.755.135-2.88.135-1.064 0-2.85-.15-2.85-.15-.584-.03-.661.854-.075.884 0 0 .54.061 1.125.09l1.68 4.605-2.37 7.08L5.354 6.9c.649-.03 1.234-.1 1.234-.1.585-.075.516-.93-.065-.896 0 0-1.746.138-2.874.138-.2 0-.438-.008-.69-.015C4.911 3.15 8.235 1.215 12 1.215c2.809 0 5.365 1.072 7.286 2.833-.046-.003-.091-.009-.141-.009-1.06 0-1.812.923-1.812 1.914 0 .89.513 1.643 1.06 2.531.411.72.89 1.643.89 2.977 0 .915-.354 1.994-.821 3.479l-1.075 3.585-3.9-11.61.001.014z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">WordPress Plugin (Rekommenderat)</h2>
                    <p class="text-sm text-gray-600">Enklaste sättet - ladda ner och installera plugin</p>
                </div>
            </div>

            <div class="space-y-6">
                <div class="p-6 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200/50">
                    <h3 class="font-semibold text-blue-900 mb-4">Så här gör du:</h3>
                    <ol class="space-y-3 text-sm text-blue-800">
                        <li class="flex items-start">
                            <span class="w-6 h-6 bg-blue-500 text-white text-xs rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">1</span>
                            <span>Ladda ner pluginet "Webbi Lead Tracker" genom att klicka på knappen nedan</span>
                        </li>
                        <li class="flex items-start">
                            <span class="w-6 h-6 bg-blue-500 text-white text-xs rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">2</span>
                            <span>Gå till din WordPress admin → Plugins → Lägg till nytt → Ladda upp plugin</span>
                        </li>
                        <li class="flex items-start">
                            <span class="w-6 h-6 bg-blue-500 text-white text-xs rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">3</span>
                            <span>Välj zip-filen du laddade ner och klicka "Installera nu"</span>
                        </li>
                        <li class="flex items-start">
                            <span class="w-6 h-6 bg-blue-500 text-white text-xs rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">4</span>
                            <div>
                                <span>Aktivera pluginet och gå till Inställningar → Webbi Lead Tracker</span>
                                <div class="mt-2 ml-4 text-xs bg-white/60 p-2 rounded">
                                    <strong>Fyll i:</strong><br>
                                    Site Key: <code>{{ $siteKey }}</code><br>
                                    Track URL: <code>https://webgrowai.se</code>
                                </div>
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

        <!-- Shopify method -->
        <div id="shopify" class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100/50 p-8">
            <div class="flex items-center space-x-4 mb-6">
                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M15.337 2.001c-0.907,0-1.76,0.655-2.332,1.603-0.458-0.174-0.985-0.266-1.562-0.266-2.156,0-4.003,1.299-5.229,3.284-1.117-0.426-2.162-0.37-2.805,0.273-0.764,0.763-0.764,2.212,0,2.975l8.834,8.834c0.381,0.381,0.893,0.596,1.438,0.596s1.057-0.215,1.438-0.596l8.834-8.834c0.764-0.763,0.764-2.212,0-2.975-0.643-0.643-1.688-0.699-2.805-0.273-1.226-1.985-3.073-3.284-5.229-3.284-0.577,0-1.104,0.092-1.562,0.266C17.097,2.656,16.244,2.001,15.337,2.001z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Shopify</h2>
                    <p class="text-sm text-gray-600">Lägg till JavaScript-kod i din Shopify-butik</p>
                </div>
            </div>

            <div class="space-y-6">
                <div class="p-6 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border border-green-200/50">
                    <h3 class="font-semibold text-green-900 mb-4">Så här gör du:</h3>
                    <ol class="space-y-3 text-sm text-green-800">
                        <li class="flex items-start">
                            <span class="w-6 h-6 bg-green-500 text-white text-xs rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">1</span>
                            <span>Gå till din Shopify admin → Online Store → Themes</span>
                        </li>
                        <li class="flex items-start">
                            <span class="w-6 h-6 bg-green-500 text-white text-xs rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">2</span>
                            <span>Klicka "Actions" → "Edit code" på ditt aktiva tema</span>
                        </li>
                        <li class="flex items-start">
                            <span class="w-6 h-6 bg-green-500 text-white text-xs rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">3</span>
                            <span>Öppna theme.liquid under "Layout"</span>
                        </li>
                        <li class="flex items-start">
                            <span class="w-6 h-6 bg-green-500 text-white text-xs rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">4</span>
                            <span>Lägg till koden nedan precis före &lt;/head&gt;</span>
                        </li>
                    </ol>
                </div>

                <div class="p-6 bg-gray-50 rounded-xl border border-gray-200">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-sm text-gray-700 font-medium">Kod att klistra in:</p>
                        <button onclick="copyCode('shopify-code')" class="px-3 py-1 bg-green-600 text-white rounded text-xs hover:bg-green-700">
                            Kopiera kod
                        </button>
                    </div>
                    <div class="bg-gray-900 text-gray-100 p-4 rounded-lg overflow-auto" id="shopify-code">
                        <pre class="text-sm font-mono"><code>&lt;script&gt;
  window.WEBBI_SITE_KEY = '{{ $siteKey }}';
  window.WEBBI_TRACK_URL = 'https://webgrowai.se';
&lt;/script&gt;
&lt;script src="https://webgrowai.se/lead-tracker.js" defer&gt;&lt;/script&gt;</code></pre>
                    </div>
                </div>
            </div>
        </div>

        <!-- Custom/Other method -->
        <div id="custom" class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100/50 p-8">
            <div class="flex items-center space-x-4 mb-6">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Anpassad webbplats</h2>
                    <p class="text-sm text-gray-600">För andra CMS-system eller anpassade webbplatser</p>
                </div>
            </div>

            <div class="space-y-6">
                <div class="p-6 bg-gradient-to-r from-purple-50 to-indigo-50 rounded-xl border border-purple-200/50">
                    <h3 class="font-semibold text-purple-900 mb-4">Så här gör du:</h3>
                    <ol class="space-y-3 text-sm text-purple-800">
                        <li class="flex items-start">
                            <span class="w-6 h-6 bg-purple-500 text-white text-xs rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">1</span>
                            <span>Hitta där du kan redigera HTML-koden för din webbplats</span>
                        </li>
                        <li class="flex items-start">
                            <span class="w-6 h-6 bg-purple-500 text-white text-xs rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">2</span>
                            <span>Leta upp &lt;head&gt;-sektionen</span>
                        </li>
                        <li class="flex items-start">
                            <span class="w-6 h-6 bg-purple-500 text-white text-xs rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">3</span>
                            <span>Lägg till koden nedan precis före &lt;/head&gt;</span>
                        </li>
                    </ol>
                </div>

                <div class="p-6 bg-gray-50 rounded-xl border border-gray-200">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-sm text-gray-700 font-medium">Kod att klistra in:</p>
                        <button onclick="copyCode('custom-code')" class="px-3 py-1 bg-purple-600 text-white rounded text-xs hover:bg-purple-700">
                            Kopiera kod
                        </button>
                    </div>
                    <div class="bg-gray-900 text-gray-100 p-4 rounded-lg overflow-auto" id="custom-code">
                        <pre class="text-sm font-mono"><code>&lt;script&gt;
  window.WEBBI_SITE_KEY = '{{ $siteKey }}';
  window.WEBBI_TRACK_URL = 'https://webgrowai.se';
&lt;/script&gt;
&lt;script src="https://webgrowai.se/lead-tracker.js" defer&gt;&lt;/script&gt;</code></pre>
                    </div>
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
                    <h2 class="text-xl font-bold text-gray-900">Testa att det fungerar</h2>
                    <p class="text-sm text-gray-600">Se om vi kan ta emot data från din sajt</p>
                </div>
            </div>

            <div class="space-y-6">
                <div class="p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200/50">
                    <h3 class="font-semibold text-blue-900 mb-2">Så här testar du:</h3>
                    <ol class="space-y-2 text-sm text-blue-800">
                        <li class="flex items-start">
                            <span class="w-5 h-5 bg-blue-500 text-white text-xs rounded-full flex items-center justify-center mr-2 mt-0.5 flex-shrink-0">1</span>
                            <span>Öppna din sajt i en ny flik</span>
                        </li>
                        <li class="flex items-start">
                            <span class="w-5 h-5 bg-blue-500 text-white text-xs rounded-full flex items-center justify-center mr-2 mt-0.5 flex-shrink-0">2</span>
                            <span>Klicka på några sidor och knappar</span>
                        </li>
                        <li class="flex items-start">
                            <span class="w-5 h-5 bg-blue-500 text-white text-xs rounded-full flex items-center justify-center mr-2 mt-0.5 flex-shrink-0">3</span>
                            <span>Kom tillbaka hit och klicka "Börja lyssna"</span>
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
                                <span class="text-sm text-purple-700">Lyssnar efter besök...</span>
                            </div>
                        @endif
                    </div>

                    <div class="text-right">
                        <div class="text-xs text-purple-700">Senaste besök</div>
                        <div class="text-sm font-semibold text-purple-900">
                            {{ $lastEventAt ?? 'Inget besök än' }}
                        </div>
                    </div>
                </div>

                <div class="text-center">
                    <a href="{{ route('onboarding') }}?step=4" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl hover:from-indigo-700 hover:to-purple-700 shadow-lg transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                        Fortsätt onboarding
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function scrollToSection(section) {
            document.getElementById(section).scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }

        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                // Could add a toast notification here
                alert('Kopierat!');
            });
        }

        function copyCode(elementId) {
            const element = document.getElementById(elementId);
            const text = element.textContent;
            navigator.clipboard.writeText(text).then(function() {
                alert('Kod kopierad!');
            });
        }
    </script>
</div>
