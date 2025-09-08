<div>
    <div class="max-w-4xl mx-auto space-y-8">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                <svg class="w-8 h-8 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 2l6 2m-6-2l-6 2m12 0v10a2 2 0 01-2 2H6a2 2 0 01-2-2V9z"/>
                </svg>
                Veckodigest – Inställningar
            </h1>
            <div class="flex items-center space-x-3">
                @if($customer?->name)
                    <div class="inline-flex items-center px-3 py-1 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200/50 rounded-full">
                        <span class="text-sm font-medium text-blue-800">{{ $customer->name }}</span>
                    </div>
                @endif
                @if($activeSite?->name)
                    <div class="inline-flex items-center px-3 py-1 bg-gradient-to-r from-emerald-50 to-green-50 border border-emerald-200/60 rounded-full">
                        <span class="text-xs text-emerald-800 mr-1">Aktiv sajt:</span>
                        <span class="text-sm font-medium text-emerald-900">{{ $activeSite->name }}</span>
                    </div>
                @else
                    <div class="inline-flex items-center px-3 py-1 bg-yellow-50 border border-yellow-200 rounded-full">
                        <span class="text-sm text-yellow-800">Välj en aktiv sajt i toppbaren</span>
                    </div>
                @endif
                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 hover:border-gray-400 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Tillbaka
                </a>
            </div>
        </div>

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
            <div class="p-4 bg-red-50 border border-red-200 rounded-xl">
                <p class="text-sm text-red-800">{{ session('error') }}</p>
            </div>
        @endif

        <!-- Info card -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl border border-blue-200/50 p-6">
            <div class="flex items-start space-x-4">
                <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-blue-900 mb-1">AI-genererad veckodigest</h3>
                    <p class="text-blue-800 text-sm">
                        Inställningarna sparas per sajt. Om du lämnar ett fält tomt används kundens motsvarande värde som fallback.
                    </p>
                </div>
            </div>
        </div>

        <!-- Settings form -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100/50 p-8">
            @if(!$activeSite)
                <div class="p-4 mb-6 bg-yellow-50 border border-yellow-200 rounded-lg text-sm text-yellow-800">
                    Välj en aktiv sajt i toppbaren för att redigera sajtens inställningar.
                </div>
            @endif

            <div class="space-y-8">
                <!-- Recipients -->
                <div class="p-6 bg-gradient-to-r from-emerald-50 to-teal-50 rounded-xl border border-emerald-200/50">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 bg-emerald-500 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                            </svg>
                        </div>
                        <div>
                            <label class="text-lg font-semibold text-gray-900">E-postmottagare (per sajt)</label>
                            <p class="text-sm text-emerald-700">Komma-separerade e-postadresser som ska ta emot veckodigesten</p>
                        </div>
                    </div>
                    <textarea rows="3" wire:model.defer="weekly_recipients" class="w-full px-4 py-3 bg-white border border-emerald-300 rounded-xl text-gray-900 placeholder-gray-500 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200 resize-none" placeholder="namn1@example.com, namn2@example.com" @disabled(!$activeSite)></textarea>
                    @if(($effective['recipients'] ?? '') && ($weekly_recipients === ''))
                        <p class="mt-2 text-xs text-emerald-700">Ärver från kund: {{ $effective['recipients'] }}</p>
                    @endif
                    @if($recipientsPreview->isNotEmpty())
                        <div class="mt-3 p-3 bg-white bg-opacity-60 border border-emerald-200/50 rounded-lg">
                            <div class="text-xs font-medium text-emerald-700 mb-1">Förhandsgranskning av mottagare:</div>
                            <div class="text-sm text-emerald-800">{{ $recipientsPreview->implode(', ') }}</div>
                        </div>
                    @endif
                    @error('weekly_recipients')
                    <p class="mt-2 text-sm text-red-600 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <!-- Brand voice and audience -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="p-6 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl border border-purple-200/50">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="w-10 h-10 bg-purple-500 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10m0 0V6a2 2 0 00-2-2H9a2 2 0 00-2 2v2m10 0v10a2 2 0 01-2 2H9a2 2 0 01-2-2V8"/>
                                </svg>
                            </div>
                            <div>
                                <label class="text-lg font-semibold text-gray-900">Varumärkesröst (per sajt)</label>
                                <p class="text-sm text-purple-700">Hur ska kommunikationen låta?</p>
                            </div>
                        </div>
                        <input type="text" wire:model.defer="weekly_brand_voice" class="w-full px-4 py-3 bg-white border border-purple-300 rounded-xl text-gray-900 placeholder-gray-500 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200" placeholder="t.ex. professionell, hjälpsam, personlig" @disabled(!$activeSite)>
                        @if(($effective['brand_voice'] ?? '') && ($weekly_brand_voice === ''))
                            <p class="mt-2 text-xs text-purple-700">Ärver från kund: {{ $effective['brand_voice'] }}</p>
                        @endif
                        @error('weekly_brand_voice')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <div class="p-6 bg-gradient-to-r from-orange-50 to-red-50 rounded-xl border border-orange-200/50">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="w-10 h-10 bg-orange-500 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                            <div>
                                <label class="text-lg font-semibold text-gray-900">Målgrupp (per sajt)</label>
                                <p class="text-sm text-orange-700">Vem läser dina rapporter?</p>
                            </div>
                        </div>
                        <input type="text" wire:model.defer="weekly_audience" class="w-full px-4 py-3 bg-white border border-orange-300 rounded-xl text-gray-900 placeholder-gray-500 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200" placeholder="t.ex. SMB-beslutsfattare, marknadsansvariga" @disabled(!$activeSite)>
                        @if(($effective['audience'] ?? '') && ($weekly_audience === ''))
                            <p class="mt-2 text-xs text-orange-700">Ärver från kund: {{ $effective['audience'] }}</p>
                        @endif
                        @error('weekly_audience')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $message }}
                        </p>
                        @enderror
                    </div>
                </div>

                <!-- Business goal -->
                <div class="p-6 bg-gradient-to-r from-yellow-50 to-amber-50 rounded-xl border border-yellow-200/50">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 bg-yellow-500 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <label class="text-lg font-semibold text-gray-900">Affärsmål (per sajt)</label>
                            <p class="text-sm text-yellow-700">Vad vill du uppnå?</p>
                        </div>
                    </div>
                    <input type="text" wire:model.defer="weekly_goal" class="w-full px-4 py-3 bg-white border border-yellow-300 rounded-xl text-gray-900 placeholder-gray-500 focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-all duration-200" placeholder="t.ex. öka kvalificerade leads" @disabled(!$activeSite)>
                    @if(($effective['goal'] ?? '') && ($weekly_goal === ''))
                        <p class="mt-2 text-xs text-yellow-700">Ärver från kund: {{ $effective['goal'] }}</p>
                    @endif
                    @error('weekly_goal')
                    <p class="mt-2 text-sm text-red-600 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <!-- Keywords -->
                <div class="p-6 bg-gradient-to-r from-teal-50 to-cyan-50 rounded-xl border border-teal-200/50">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 bg-teal-500 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                        </div>
                        <div>
                            <label class="text-lg font-semibold text-gray-900">Nyckelord (per sajt)</label>
                            <p class="text-sm text-teal-700">Separera med komma</p>
                        </div>
                    </div>
                    <input type="text" wire:model.defer="weekly_keywords" class="w-full px-4 py-3 bg-white border border-teal-300 rounded-xl text-gray-900 placeholder-gray-500 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200" placeholder="t.ex. CRM, kundresa, automatisering" @disabled(!$activeSite)>
                    @if(($effective['keywords'] ?? '') && ($weekly_keywords === ''))
                        <p class="mt-2 text-xs text-teal-700">Ärver från kund: {{ $effective['keywords'] }}</p>
                    @endif
                    @error('weekly_keywords')
                    <p class="mt-2 text-sm text-red-600 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <!-- Action buttons -->
                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                    <button wire:click="sendTest" class="inline-flex items-center px-6 py-3 bg-white border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 hover:border-gray-400 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        Skicka testsammandrag
                    </button>

                    <button wire:click="save" class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-indigo-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transform hover:scale-[1.02] transition-all duration-200 shadow-lg hover:shadow-xl" @disabled(!$activeSite)>
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h2m0 0h9a2 2 0 002-2V9a2 2 0 00-2-2H9m8 0V5a2 2 0 00-2-2H9a2 2 0 00-2 2v2"/>
                        </svg>
                        Spara inställningar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
