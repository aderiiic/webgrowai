<div x-data="{ showHelpModal: false }">
    <div class="max-w-5xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Massgenerering av AI-innehåll</h1>
                    <p class="mt-2 text-gray-600">Skapa flera versioner av samma text med olika variabler</p>
                </div>
                <!-- Help Button -->
                <button
                    type="button"
                    @click="showHelpModal = true"
                    class="flex items-center justify-center w-8 h-8 rounded-full bg-indigo-100 hover:bg-indigo-200 text-indigo-600 transition-colors"
                    title="Hur fungerar massgenerering?"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </button>
            </div>
            <a href="{{ route('ai.list') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">
                ← Tillbaka
            </a>
        </div>

        @if(!empty($qualityWarnings) && $showQualityTips)
            <div
                x-data="{ show: true }"
                x-show="show"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 transform -translate-y-2"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="bg-gradient-to-r from-emerald-50 to-teal-50 border-l-4 border-emerald-500 rounded-xl shadow-sm"
            >
                <div class="p-5">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-3 flex-1">
                            <h3 class="text-sm font-semibold text-emerald-900 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                Tips för högkvalitativa, Google-optimerade texter
                            </h3>
                            <div class="mt-3 text-sm text-emerald-800 space-y-3">
                                <div class="grid md:grid-cols-2 gap-4">
                                    <div class="bg-white/60 rounded-lg p-3 border border-emerald-100">
                                        <div class="flex items-start space-x-2 mb-2">
                                            <svg class="w-5 h-5 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            <p class="font-semibold text-emerald-900">GÖR</p>
                                        </div>
                                        <ul class="space-y-1.5 text-xs text-emerald-800">
                                            <li class="flex items-start">
                                                <span class="text-emerald-500 mr-2">•</span>
                                                <span>Skriv specifika, detaljerade instruktioner</span>
                                            </li>
                                            <li class="flex items-start">
                                                <span class="text-emerald-500 mr-2">•</span>
                                                <span>Använd variablerna naturligt i mallen</span>
                                            </li>
                                            <li class="flex items-start">
                                                <span class="text-emerald-500 mr-2">•</span>
                                                <span>Be om konkreta exempel och fördelar</span>
                                            </li>
                                            <li class="flex items-start">
                                                <span class="text-emerald-500 mr-2">•</span>
                                                <span>Specificera tonalitet (personlig, professionell, etc.)</span>
                                            </li>
                                            <li class="flex items-start">
                                                <span class="text-emerald-500 mr-2">•</span>
                                                <span>Inkludera relevant kontext om din verksamhet</span>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="bg-white/60 rounded-lg p-3 border border-red-100">
                                        <div class="flex items-start space-x-2 mb-2">
                                            <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                            <p class="font-semibold text-red-900">UNDVIK</p>
                                        </div>
                                        <ul class="space-y-1.5 text-xs text-red-800">
                                            <li class="flex items-start">
                                                <span class="text-red-500 mr-2">•</span>
                                                <span>Generiska fraser som "hög kvalitet"</span>
                                            </li>
                                            <li class="flex items-start">
                                                <span class="text-red-500 mr-2">•</span>
                                                <span>Be AI:n skriva om saker som inte existerar</span>
                                            </li>
                                            <li class="flex items-start">
                                                <span class="text-red-500 mr-2">•</span>
                                                <span>För korta instruktioner utan detaljer</span>
                                            </li>
                                            <li class="flex items-start">
                                                <span class="text-red-500 mr-2">•</span>
                                                <span>Upprepande meningar i mallen</span>
                                            </li>
                                            <li class="flex items-start">
                                                <span class="text-red-500 mr-2">•</span>
                                                <span>"Skriv inte samma text som sist" (varierar automatiskt)</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="bg-white/80 rounded-lg p-3 border border-emerald-200">
                                    <div class="flex items-start space-x-2 mb-2">
                                        <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                        </svg>
                                        <div class="flex-1">
                                            <p class="font-semibold text-emerald-900 text-xs mb-1">Exempel på bra instruktion</p>
                                            <p class="text-xs italic text-emerald-700 leading-relaxed">
                                                "Skriv en engagerande text på ca. 400 ord om fördelarna med att handla <code class="bg-emerald-100 px-1 rounded">@{{produkt}}</code> online hos oss istället för i fysiska butiker i <code class="bg-emerald-100 px-1 rounded">@{{stad}}</code>. Fokusera på bekvämlighet, prisfördelar och snabb leverans. Använd en personlig och vänlig ton. Inkludera konkreta exempel på hur kunder sparar tid och pengar."
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="ml-3">
                            <button
                                wire:click="closeQualityTips"
                                type="button"
                                class="inline-flex rounded-md text-emerald-600 hover:text-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 focus:ring-offset-emerald-50 transition-colors"
                                aria-label="Stäng tips"
                            >
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Help Modal -->
        <div
            x-show="showHelpModal"
            x-cloak
            @click="showHelpModal = false"
            @keydown.escape.window="showHelpModal = false"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto"
            style="display: none;"
        >
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" aria-hidden="true"></div>

            <!-- Modal Content -->
            <div class="flex min-h-screen items-center justify-center p-4">
                <div
                    @click.stop
                    x-show="showHelpModal"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform scale-95"
                    x-transition:enter-end="opacity-100 transform scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 transform scale-100"
                    x-transition:leave-end="opacity-0 transform scale-95"
                    class="relative bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto z-10 my-8"
                >
                    <div class="p-6">
                        <div class="flex items-center space-x-3 mb-6">
                            <div class="flex-shrink-0 w-12 h-12 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900">Så här använder du massgenerering</h2>
                                <p class="text-sm text-gray-600">Spara tid genom att skapa många texter samtidigt</p>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <!-- Step 1 -->
                            <div class="flex space-x-4">
                                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center">
                                    <span class="text-indigo-700 font-semibold">1</span>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900 mb-2">Skapa din textmall</h3>
                                    <p class="text-sm text-gray-700 mb-2">
                                        Skriv texten med <code class="bg-gray-100 px-2 py-0.5 rounded text-indigo-600">@{{variabel}}</code> där du vill ha dynamiskt innehåll.
                                    </p>
                                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
                                        <p class="text-sm font-mono text-gray-800">
                                            "Besök vår butik i <span class="text-indigo-600 font-semibold">@{{stad}}</span> och upptäck våra <span class="text-indigo-600 font-semibold">@{{produkt}}</span>!"
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 2 -->
                            <div class="flex space-x-4">
                                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center">
                                    <span class="text-indigo-700 font-semibold">2</span>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900 mb-2">Klistra in dina variabler</h3>
                                    <p class="text-sm text-gray-700 mb-2">
                                        Kopiera data från Excel/Google Sheets. Första raden ska innehålla kolumnnamn som matchar dina variabler.
                                    </p>
                                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
                                        <pre class="text-sm font-mono text-gray-800">stad	produkt
Malmö	Soffor
Göteborg	Bord
Uppsala	Stolar</pre>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 3 -->
                            <div class="flex space-x-4">
                                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center">
                                    <span class="text-indigo-700 font-semibold">3</span>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900 mb-2">Välj inställningar och generera</h3>
                                    <p class="text-sm text-gray-700">
                                        Välj innehållstyp och textlängd. AI:n kommer att skapa unika, professionella texter för varje rad i din data.
                                    </p>
                                </div>
                            </div>

                            <!-- Result Example -->
                            <div class="bg-gradient-to-br from-green-50 to-emerald-50 border border-green-200 rounded-lg p-4">
                                <div class="flex items-start space-x-3">
                                    <svg class="w-5 h-5 text-green-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div>
                                        <h4 class="font-semibold text-green-900 mb-2">Resultat</h4>
                                        <p class="text-sm text-green-800 mb-2">Du får 3 unika AI-genererade texter:</p>
                                        <ul class="text-sm text-green-700 space-y-1">
                                            <li>✓ En professionell text om Malmö och Soffor</li>
                                            <li>✓ En professionell text om Göteborg och Bord</li>
                                            <li>✓ En professionell text om Uppsala och Stolar</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Tips -->
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="flex items-start space-x-3">
                                    <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div>
                                        <h4 class="font-semibold text-blue-900 mb-1">Tips</h4>
                                        <ul class="text-sm text-blue-800 space-y-1">
                                            <li>• Använd enkla variabelnamn utan mellanslag eller specialtecken</li>
                                            <li>• Kopiera direkt från Excel/Google Sheets för bäst resultat</li>
                                            <li>• Förhandsvisningen visar hur första texten kommer att se ut</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button
                                type="button"
                                @click="showHelpModal = false"
                                class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors"
                            >
                                Jag förstår!
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($errors->has('general'))
            <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                <p class="text-sm text-red-800">{{ $errors->first('general') }}</p>
            </div>
        @endif

        <form wire:submit="submit" class="space-y-6">
            <!-- Template Text -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <label class="block text-sm font-medium text-gray-900 mb-2">
                    Textmall <span class="text-red-500">*</span>
                </label>
                <p class="text-sm text-gray-600 mb-3">
                    Använd <code class="bg-gray-100 px-2 py-0.5 rounded">@{{variabel}}</code> för platshållare
                </p>
                <textarea
                    wire:model.blur="template_text"
                    rows="4"
                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                    placeholder="Besök vår butik i @{{stad}} för att upptäcka @{{produkt}}!"
                ></textarea>
                @error('template_text')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror

                @if(!empty($qualityWarnings))
                    <div class="mt-3 space-y-2">
                        @foreach($qualityWarnings as $warning)
                            @php
                                $isWarning = str_starts_with($warning, 'Varning:');
                                $isObs = str_starts_with($warning, 'OBS:');
                                $isTip = str_starts_with($warning, 'Tips:');
                            @endphp
                            <div class="flex items-start space-x-2 text-sm rounded-lg p-3 {{ $isWarning || $isObs ? 'text-amber-800 bg-amber-50 border border-amber-200' : 'text-blue-800 bg-blue-50 border border-blue-200' }}">
                                <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if($isWarning || $isObs)
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    @elseif($isTip)
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                    @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    @endif
                                </svg>
                                <span class="flex-1">{{ $warning }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <label class="block text-sm font-medium text-gray-900">
                        Egen titelmall
                    </label>
                    <button
                        type="button"
                        wire:click="$toggle('use_custom_title')"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            @if($use_custom_title)
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            @else
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            @endif
                        </svg>
                        {{ $use_custom_title ? 'Använd automatisk titel' : 'Skriv egen titel' }}
                    </button>
                </div>

                @if($use_custom_title)
                    <div x-data="{ show: false }"
                         x-init="setTimeout(() => show = true, 50)"
                         x-show="show"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform -translate-y-2"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                    >
                        <p class="text-sm text-gray-600 mb-3">
                            Skapa en egen titelmall. Du kan använda samma variabler som i textmallen, t.ex. <code class="bg-gray-100 px-2 py-0.5 rounded">@{{stad}}</code> och <code class="bg-gray-100 px-2 py-0.5 rounded">@{{produkt}}</code>
                        </p>
                        <textarea
                            wire:model.blur="custom_title_template"
                            rows="2"
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="I vår butik i @{{stad}} hittar du @{{produkt}}"
                        ></textarea>
                        @error('custom_title_template')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                @else
                    <p class="text-sm text-gray-500 italic">
                        Titeln genereras automatiskt av AI baserat på din text
                    </p>
                @endif
            </div>

            <!-- Variables Input -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <label class="block text-sm font-medium text-gray-900 mb-2">
                    Variabeldata (CSV-format) <span class="text-red-500">*</span>
                </label>
                <p class="text-sm text-gray-600 mb-3">
                    Klistra in data med första raden som kolumnnamn (tab- eller kommaseparerad)
                </p>
                <textarea
                    wire:model.blur="variables_input"
                    rows="8"
                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 font-mono text-sm"
                    placeholder="stad,	produkt&#10;Malmö,	Soffor&#10;Göteborg,	Bord&#10;Uppsala,	Stolar"
                ></textarea>
                @error('variables_input')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror

                <!-- Preview -->
                @if($previewText)
                    <div class="mt-4 p-4 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg border border-indigo-200">
                        @if($previewTitle)
                            <div class="mb-3">
                                <h4 class="text-xs font-medium text-gray-600 mb-1">Förhandsvisning av titel:</h4>
                                <p class="text-sm font-semibold text-gray-900">{{ $previewTitle }}</p>
                            </div>
                        @endif
                        <h4 class="text-xs font-medium text-gray-600 mb-1">Förhandsvisning av text (första raden):</h4>
                        <p class="text-sm text-gray-800">{{ $previewText }}</p>
                    </div>
                @endif

                @if($estimatedCount > 0)
                    <div class="mt-4 flex items-center space-x-4 text-sm">
                        <span class="text-gray-600">
                            <strong class="text-gray-900">{{ $estimatedCount }}</strong> texter kommer att genereras
                        </span>
                        <span class="text-gray-600">
                            Kostnad: <strong class="text-indigo-600">{{ $estimatedCost }}</strong> krediter
                        </span>
                    </div>
                @endif
            </div>

            <!-- Content Type & Settings -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Content Type -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <label class="block text-sm font-medium text-gray-900 mb-3">
                        Typ av innehåll
                    </label>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="radio" wire:model.live="content_type" value="social" class="text-indigo-600 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700">Sociala medier</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" wire:model.live="content_type" value="blog" class="text-indigo-600 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700">Blogg/Artiklar</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" wire:model.live="content_type" value="newsletter" class="text-indigo-600 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700">Nyhetsbrev</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" wire:model.live="content_type" value="multi" class="text-indigo-600 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700">Generisk/Flera kanaler</span>
                        </label>
                    </div>
                </div>

                <!-- Tone -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <label class="block text-sm font-medium text-gray-900 mb-3">
                        Textlängd
                    </label>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="radio" wire:model.live="tone" value="short" class="text-indigo-600 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700">Kort (10 krediter/text)</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" wire:model.live="tone" value="long" class="text-indigo-600 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700">Lång (50 krediter/text)</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Site Selection -->
            @if($sites->isNotEmpty())
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <label class="block text-sm font-medium text-gray-900 mb-2">
                        Webbplats (valfritt)
                    </label>
                    <select wire:model="site_id" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Ingen webbplats</option>
                        @foreach($sites as $site)
                            <option value="{{ $site->id }}">{{ $site->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            <!-- Plan Limit Info -->
            <div class="bg-gradient-to-br from-amber-50 to-orange-50 border border-amber-200 rounded-xl p-4">
                <div class="flex items-start space-x-3">
                    <svg class="w-5 h-5 text-amber-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="flex-1">
                        <h4 class="text-sm font-medium text-amber-900">Din plan tillåter max {{ $maxTexts }} texter per batch</h4>
                        <p class="mt-1 text-sm text-amber-700">
                            Uppgradera till Growth (100 texter) eller Pro (200 texter) för större volymer.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-end space-x-4">
                <a href="{{ route('ai.list') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Avbryt
                </a>
                <button
                    type="submit"
                    class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed"
                    @if($estimatedCount === 0 || $estimatedCount > $maxTexts) disabled @endif
                >
                    Generera {{ $estimatedCount }} texter
                </button>
            </div>
        </form>
    </div>
</div>
