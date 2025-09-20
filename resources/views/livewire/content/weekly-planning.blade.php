<div class="space-y-4 lg:space-y-6">
    <!-- Förbättrad header med mobilanpassning -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl lg:text-2xl font-bold text-gray-900 flex items-center">
                <svg class="w-6 h-6 lg:w-7 lg:h-7 mr-2 lg:mr-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 002 2z"/>
                </svg>
                Veckoplanering
            </h1>
            <p class="text-sm text-gray-600 mt-1">Översikt över pågående och kommande veckors innehållsplanering</p>
        </div>
        <a href="{{ route('settings.weekly') }}" class="inline-flex items-center px-3 lg:px-4 py-2 text-sm bg-indigo-600 text-white rounded-lg lg:rounded-xl hover:bg-indigo-700 transition whitespace-nowrap">
            <svg class="w-4 h-4 mr-1 lg:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Inställningar
        </a>
    </div>

    @php
        $today = now()->dayOfWeek;
        $isCurrentWeekMonday = $today >= 1 && $today <= 4;
        $isCurrentWeekFriday = $today >= 5;

        $cards = [
            'monday' => [
                'title' => 'Måndagsplanering',
                'badge' => $isCurrentWeekMonday ? 'Pågående vecka' : 'Kommande måndag',
                'color' => $isCurrentWeekMonday ? 'from-green-50 to-emerald-50' : 'from-blue-50 to-indigo-50',
                'text' => $isCurrentWeekMonday ? 'text-green-700' : 'text-blue-700',
                'border' => $isCurrentWeekMonday ? 'border-green-200/60' : 'border-blue-200/60',
                'status' => $isCurrentWeekMonday ? 'current' : 'upcoming'
            ],
            'friday' => [
                'title' => 'Fredagssammanfattning',
                'badge' => $isCurrentWeekFriday ? 'Pågående vecka' : 'Kommande fredag',
                'color' => $isCurrentWeekFriday ? 'from-green-50 to-emerald-50' : 'from-purple-50 to-pink-50',
                'text' => $isCurrentWeekFriday ? 'text-green-700' : 'text-purple-700',
                'border' => $isCurrentWeekFriday ? 'border-green-200/60' : 'border-purple-200/60',
                'status' => $isCurrentWeekFriday ? 'current' : 'upcoming'
            ],
        ];
    @endphp

    @foreach($cards as $tag => $meta)
        <div class="bg-white rounded-xl lg:rounded-2xl shadow-lg border border-gray-200/60 overflow-hidden">
            <!-- Förbättrad mobilheader -->
            <div class="p-4 lg:p-6 border-b border-gray-100 bg-gradient-to-r {{ $meta['color'] }}">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-3 min-w-0">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-3">
                            <span class="inline-flex items-center px-2.5 py-1.5 rounded-full text-xs lg:text-sm font-semibold {{ $meta['text'] }} {{ $meta['border'] }} bg-white/80 whitespace-nowrap">
                                {{ $meta['badge'] }}
                            </span>
                            <h2 class="text-lg lg:text-xl font-semibold text-gray-900">{{ $meta['title'] }}</h2>
                        </div>
                        @if(!empty($latest[$tag]['date']))
                            <div class="flex items-center gap-2 text-xs lg:text-sm text-gray-600">
                                <svg class="w-3 h-3 lg:w-4 lg:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>Senast uppdaterad: {{ $latest[$tag]['date'] }}</span>
                            </div>
                        @endif
                    </div>

                    <a href="{{ route('settings.weekly') }}" class="px-3 py-1.5 text-sm rounded-lg bg-white/80 text-gray-700 hover:bg-white transition whitespace-nowrap self-start sm:self-auto">
                        Justera inställningar
                    </a>
                </div>
            </div>

            @if(!empty($latest[$tag]))
                <div class="p-4 lg:p-6">
                    <!-- Mobilanpassad grid -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6 mb-4 lg:mb-6">
                        <!-- Kampanjförslag -->
                        <div class="bg-gray-50 rounded-lg lg:rounded-xl border border-gray-200 overflow-hidden">
                            <div class="px-3 lg:px-4 py-2 lg:py-3 border-b border-gray-200 bg-white flex items-center justify-between">
                                <h3 class="font-semibold text-gray-900 flex items-center text-sm lg:text-base">
                                    <svg class="w-4 h-4 lg:w-5 lg:h-5 mr-1 lg:mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                    </svg>
                                    Kampanjförslag
                                </h3>
                            </div>
                            <div class="p-3 lg:p-4">
                                <div class="prose prose-sm max-w-none text-gray-800 max-h-48 lg:max-h-screen overflow-auto custom-scrollbar">
                                    {!! \Illuminate\Support\Str::of($latest[$tag]['campaigns'] ?? 'Inga kampanjförslag tillgängliga än')->markdown() !!}
                                </div>
                            </div>
                        </div>

                        <!-- Aktuella ämnen -->
                        <div class="bg-gray-50 rounded-lg lg:rounded-xl border border-gray-200 overflow-hidden">
                            <div class="px-3 lg:px-4 py-2 lg:py-3 border-b border-gray-200 bg-white flex items-center justify-between">
                                <h3 class="font-semibold text-gray-900 flex items-center text-sm lg:text-base">
                                    <svg class="w-4 h-4 lg:w-5 lg:h-5 mr-1 lg:mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                    Aktuella ämnen
                                </h3>
                                @if(!empty($latest[$tag]['topics']['list']))
                                    <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">{{ count($latest[$tag]['topics']['list']) }} förslag</span>
                                @endif
                            </div>

                            <div class="p-3 lg:p-4">
                                @if(!empty($latest[$tag]['topics']['list']))
                                    <div class="space-y-2 mb-4">
                                        @foreach($latest[$tag]['topics']['list'] as $topic)
                                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-3 p-2 lg:p-3 rounded-lg border border-gray-200 bg-white hover:border-indigo-300 hover:bg-indigo-50/50 transition">
                                                <span class="text-sm font-medium text-gray-800 flex-1 break-words" title="{{ $topic }}">{{ $topic }}</span>
                                                <a href="{{ route('ai.compose', ['title' => $topic]) }}"
                                                   class="inline-flex items-center px-2 lg:px-3 py-1 lg:py-1.5 rounded-md text-xs lg:text-sm bg-indigo-600 text-white hover:bg-indigo-700 transition whitespace-nowrap self-start sm:self-auto"
                                                   title="Skapa innehåll om '{{ $topic }}'">
                                                    <svg class="w-3 h-3 lg:w-4 lg:h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                    Skapa
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>

                                    @if(!empty($latest[$tag]['topics']['md']))
                                        <details class="group">
                                            <summary class="text-sm text-gray-600 cursor-pointer hover:text-indigo-600 flex items-center gap-2 p-2 rounded border border-transparent hover:border-gray-200 hover:bg-gray-50 transition">
                                                <svg class="w-4 h-4 group-open:rotate-90 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                </svg>
                                                Visa detaljerad beskrivning
                                            </summary>
                                            <div class="prose prose-sm max-w-none text-gray-800 mt-3 p-3 bg-gray-50 rounded-lg max-h-48 overflow-auto custom-scrollbar">
                                                {!! \Illuminate\Support\Str::of($latest[$tag]['topics']['md'])->markdown() !!}
                                            </div>
                                        </details>
                                    @endif
                                @else
                                    <div class="text-center py-6">
                                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                        </svg>
                                        <p class="text-gray-600 text-sm">Inga ämnesförslag tillgängliga än</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Nästa veckas plan -->
                    <div class="bg-gradient-to-r from-amber-50 to-yellow-50 rounded-lg lg:rounded-xl border border-amber-200/60 overflow-hidden">
                        <div class="px-3 lg:px-4 py-2 lg:py-3 border-b border-amber-200 bg-white/80 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                            <h3 class="font-semibold text-gray-900 flex items-center text-sm lg:text-base">
                                <svg class="w-4 h-4 lg:w-5 lg:h-5 mr-1 lg:mr-2 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                </svg>
                                @if($tag === 'monday')
                                    @if($meta['status'] === 'current')
                                        Plan för resten av veckan
                                    @else
                                        Plan för nästa vecka
                                    @endif
                                @else
                                    @if($meta['status'] === 'current')
                                        Veckans sammanfattning och nästa steg
                                    @else
                                        Kommande veckas mål
                                    @endif
                                @endif
                            </h3>
                            <span class="text-xs text-amber-700 bg-amber-100 px-2 py-1 rounded-full whitespace-nowrap self-start sm:self-auto">Planering</span>
                        </div>
                        <div class="p-3 lg:p-4">
                            <div class="prose prose-sm max-w-none text-gray-800 max-h-48 lg:max-h-64 overflow-auto custom-scrollbar">
                                {!! \Illuminate\Support\Str::of($latest[$tag]['next_week'] ?? 'Ingen planering tillgänglig än')->markdown() !!}
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="p-8 lg:p-12">
                    <div class="text-center">
                        <svg class="w-12 h-12 lg:w-16 lg:h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <h3 class="text-base lg:text-lg font-medium text-gray-900 mb-2">Ingen data tillgänglig för {{ $meta['title'] }}</h3>
                        <p class="text-gray-600 mb-6 text-sm">Kör en test från inställningar eller invänta nästa automatiska körning.</p>
                        <a href="{{ route('settings.weekly') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Gå till inställningar
                        </a>
                    </div>
                </div>
            @endif
        </div>
    @endforeach

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f8fafc; border-radius: 8px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 8px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* Förbättrade fokus-styles för tillgänglighet */
        .focus\:ring-2:focus { box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.5); }

        /* Animationer för bättre interaktion */
        .transition { transition: all 0.15s ease-in-out; }

        /* Responsiv typografi */
        @media (max-width: 640px) {
            .text-2xl { font-size: 1.5rem; }
            .text-xl { font-size: 1.25rem; }
            .prose { font-size: 0.875rem; }
        }

        /* Förbättra läsbarhet på små skärmar */
        @media (max-width: 768px) {
            .custom-scrollbar { max-height: 200px; }
        }
    </style>
</div>
