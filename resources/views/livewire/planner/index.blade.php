<div x-data="{
        openPanel: false,
        showInsights: JSON.parse(localStorage.getItem('planner_showInsights') ?? 'false')
    }" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <!-- Förbättrad header med gradient bakgrund -->
    <div class="mb-6 lg:mb-8 bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 rounded-2xl lg:rounded-3xl p-6 lg:p-8 text-white shadow-2xl">
        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4 lg:gap-6">
            <div class="flex items-center gap-3 lg:gap-4">
                <div class="w-12 h-12 lg:w-16 lg:h-16 bg-white/20 backdrop-blur rounded-xl lg:rounded-2xl flex items-center justify-center">
                    <svg class="w-6 h-6 lg:w-8 lg:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl lg:text-4xl font-bold mb-1 lg:mb-2">Planera & Publicera</h1>
                    <p class="text-white/80 text-sm lg:text-base">Hantera dina sociala medier och webbpublikationer</p>
                </div>

                @php
                    $activeSiteId = (int) ($siteId ?: 0);
                    $insights = null;
                    if ($activeSiteId > 0) {
                        $weekStart = $weekStart ?? \Illuminate\Support\Carbon::now()->startOfWeek(\Illuminate\Support\Carbon::MONDAY);
                        $insights = \App\Models\SiteInsight::where('site_id', $activeSiteId)
                            ->where('week_start', $weekStart->toDateString())
                            ->first();
                    }
                @endphp

                @if($insights)
                    <button
                        @click="showInsights = !showInsights; localStorage.setItem('planner_showInsights', JSON.stringify(showInsights))"
                        class="ml-0 lg:ml-6 mt-3 lg:mt-0 inline-flex items-center px-3 lg:px-4 py-2 rounded-xl lg:rounded-2xl transition-all duration-200 text-sm lg:text-base font-medium whitespace-nowrap"
                        :class="showInsights ? 'bg-white text-purple-600 shadow-lg' : 'bg-white/20 text-white hover:bg-white/30'">
                        <svg class="w-4 h-4 lg:w-5 lg:h-5 mr-2 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                             :class="showInsights ? 'rotate-180' : ''">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                        <span x-text="showInsights ? 'Dölj insights' : 'Visa insights'"></span>
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                    </button>
                @endif
            </div>

            <div class="flex flex-col sm:flex-row items-stretch gap-3 lg:gap-4 w-full sm:w-auto lg:w-auto">
                <div class="flex rounded-xl lg:rounded-2xl overflow-hidden bg-white/20 backdrop-blur border border-white/30">
                    <button wire:click="setView('timeline')" class="flex items-center gap-2 px-4 lg:px-6 py-2 lg:py-3 text-sm lg:text-base font-medium transition-all duration-200 whitespace-nowrap {{ $view === 'timeline' ? 'bg-white text-indigo-600 shadow-lg' : 'text-white hover:bg-white/20' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                        </svg>
                        Tidslinje
                    </button>
                    <button wire:click="setView('calendar')" class="flex items-center gap-2 px-4 lg:px-6 py-2 lg:py-3 text-sm lg:text-base font-medium transition-all duration-200 whitespace-nowrap {{ $view === 'calendar' ? 'bg-white text-indigo-600 shadow-lg' : 'text-white hover:bg-white/20' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2z"/>
                        </svg>
                        Kalender
                    </button>
                </div>
                <a href="{{ route('ai.list') }}" class="inline-flex items-center justify-center px-4 lg:px-6 py-2 lg:py-3 bg-white text-indigo-600 rounded-xl lg:rounded-2xl hover:bg-gray-50 text-sm lg:text-base font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 whitespace-nowrap">
                    <svg class="w-4 h-4 lg:w-5 lg:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                    <span class="hidden sm:inline">Innehåll</span>
                    <span class="sm:hidden">AI</span>
                </a>
            </div>
        </div>
    </div>

    @if($insights)
        @php $p = $insights->payload ?? []; @endphp
        <div class="mb-6 lg:mb-8 bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-200 rounded-2xl lg:rounded-3xl overflow-hidden shadow-lg" x-show="showInsights" x-collapse>
            <div class="p-4 lg:p-6">
                <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4 mb-4 lg:mb-6">
                    <div>
                        <div class="flex items-center gap-2 text-blue-600 mb-2">
                            <svg class="w-5 h-5 lg:w-6 lg:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            <span class="text-sm font-semibold uppercase tracking-wider">Vecka {{ $weekStart->isoWeek() }}</span>
                        </div>
                        <h3 class="text-xl lg:text-2xl font-bold text-blue-900">Veckans AI-rekommendationer</h3>
                    </div>
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                        <div class="flex items-center gap-2 text-xs lg:text-sm text-blue-600 bg-blue-100 px-3 py-2 rounded-xl">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="truncate">Uppdaterad: {{ $insights->generated_at?->diffForHumans() }}</span>
                        </div>
                        <button
                            @click="showInsights = false; localStorage.setItem('planner_showInsights', 'false')"
                            class="flex items-center gap-2 px-3 lg:px-4 py-2 bg-white border border-blue-200 text-blue-600 rounded-xl hover:bg-blue-50 transition-all duration-200 text-sm font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            <span class="hidden sm:inline">Stäng</span>
                        </button>
                    </div>
                </div>

                @if(!empty($p['summary']))
                    <div class="mb-4 lg:mb-6 p-4 bg-white rounded-2xl border border-blue-100">
                        <p class="text-blue-800 leading-relaxed text-sm lg:text-base">{{ $p['summary'] }}</p>
                    </div>
                @endif

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-6">
                    <div class="bg-white p-4 lg:p-6 rounded-2xl border border-blue-100 shadow-sm">
                        <div class="flex items-center gap-3 mb-4">
                            <svg class="w-6 h-6 lg:w-8 lg:h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <h4 class="font-bold text-blue-900 text-sm lg:text-base">Optimala tider</h4>
                        </div>
                        <div class="space-y-3">
                            @foreach(($p['timeslots'] ?? []) as $t)
                                <div class="p-3 bg-blue-50 rounded-xl">
                                    <div class="font-semibold text-blue-900 text-sm lg:text-base">{{ $t['dow'] ?? '' }} {{ $t['time'] ?? '' }}</div>
                                    <div class="text-xs lg:text-sm text-blue-700 mt-1 break-words">{{ $t['why'] ?? '' }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="bg-white p-4 lg:p-6 rounded-2xl border border-blue-100 shadow-sm">
                        <div class="flex items-center gap-3 mb-4">
                            <svg class="w-6 h-6 lg:w-8 lg:h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                            <h4 class="font-bold text-blue-900 text-sm lg:text-base">Föreslagna ämnen</h4>
                        </div>
                        <div class="space-y-3">
                            @foreach(($p['topics'] ?? []) as $t)
                                <div class="p-3 bg-purple-50 rounded-xl">
                                    <div class="font-semibold text-purple-900 text-sm lg:text-base break-words">{{ $t['title'] ?? '' }}</div>
                                    <div class="text-xs lg:text-sm text-purple-700 mt-1 break-words">{{ $t['why'] ?? '' }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="bg-white p-4 lg:p-6 rounded-2xl border border-blue-100 shadow-sm">
                        <div class="flex items-center gap-3 mb-4">
                            <svg class="w-6 h-6 lg:w-8 lg:h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <h4 class="font-bold text-blue-900 text-sm lg:text-base">Rekommenderade åtgärder</h4>
                        </div>
                        <div class="space-y-3">
                            @foreach(($p['actions'] ?? []) as $t)
                                <div class="p-3 bg-green-50 rounded-xl">
                                    <div class="font-semibold text-green-900 text-sm lg:text-base break-words">{{ $t['action'] ?? '' }}</div>
                                    <div class="text-xs lg:text-sm text-green-700 mt-1 break-words">{{ $t['why'] ?? '' }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                @if(!empty($p['rationale']))
                    <div class="mt-4 lg:mt-6 p-4 bg-indigo-100 rounded-2xl border border-indigo-200">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 lg:w-6 lg:h-6 text-indigo-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <h5 class="font-semibold text-indigo-900 mb-2 text-sm lg:text-base">AI-analys</h5>
                                <p class="text-xs lg:text-sm text-indigo-800 break-words">{{ $p['rationale'] }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Förbättrad filterrad -->
    <div class="mb-4 lg:mb-6 bg-white p-4 lg:p-6 rounded-2xl shadow-lg border border-gray-100">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 lg:gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"/>
                    </svg>
                    <span>Sajt</span>
                </label>
                <select wire:model.live="siteId" class="w-full px-3 py-2 lg:px-4 lg:py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all text-sm lg:text-base">
                    <option value="">Alla sajter</option>
                    @php $sites = auth()->user()?->customer?->sites()->orderBy('name')->get(['id','name']) ?? collect(); @endphp
                    @foreach($sites as $s)
                        <option value="{{ $s->id }}">{{ $s->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                    </svg>
                    <span>Kanal</span>
                </label>
                <select wire:model.live="channel" class="w-full px-3 py-2 lg:px-4 lg:py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all text-sm lg:text-base">
                    <option value="all">Alla kanaler</option>
                    <option value="wp">WordPress</option>
                    <option value="shopify">Shopify</option>
                    <option value="facebook">Facebook</option>
                    <option value="instagram">Instagram</option>
                    <option value="linkedin">LinkedIn</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <span>Status</span>
                </label>
                <select wire:model.live="status" class="w-full px-3 py-2 lg:px-4 lg:py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all text-sm lg:text-base">
                    <option value="all">Alla</option>
                    <option value="upcoming">Kommande (30 dagar)</option>
                    <option value="processing">Pågår</option>
                    <option value="published">Publicerad</option>
                    <option value="failed">Misslyckad</option>
                    <option value="cancelled">Avbruten</option>
                </select>
            </div>

            <div class="lg:col-span-2 flex flex-col justify-end">
                @if($view === 'calendar')
                    <div class="flex flex-col gap-3">
                        <!-- Navigationsknappar -->
                        <div class="flex items-center justify-center gap-2">
                            <button wire:click="prevWeek" class="flex items-center gap-2 px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl transition-all duration-200 text-sm font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                                <span class="hidden sm:inline">Föregående</span>
                            </button>
                            <button wire:click="today" class="flex items-center gap-2 px-3 py-2 bg-indigo-100 hover:bg-indigo-200 text-indigo-700 rounded-xl transition-all duration-200 text-sm font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2z"/>
                                </svg>
                                Idag
                            </button>
                            <button wire:click="nextWeek" class="flex items-center gap-2 px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl transition-all duration-200 text-sm font-medium">
                                <span class="hidden sm:inline">Nästa</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>
                        <!-- Datumspan -->
                        <div class="flex items-center justify-center gap-2 text-sm font-medium text-gray-600 bg-gray-50 px-3 py-2 rounded-xl">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2z"/>
                            </svg>
                            <span>{{ $weekStart->translatedFormat('j M') }} – {{ $weekEnd->translatedFormat('j M Y') }}</span>
                        </div>
                    </div>
                @else
                    <div class="flex justify-end">
                        <div class="flex items-center gap-2 text-sm font-medium text-gray-600 bg-gray-50 px-3 py-2 lg:px-4 lg:py-2 rounded-xl">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            Visar {{ count($items) }} poster
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @php
        $statusBadge = function(string $s) {
            return match($s) {
                'published'  => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-800', 'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>', 'label' => 'Publicerad'],
                'processing' => ['bg' => 'bg-amber-100',  'text' => 'text-amber-800',  'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>', 'label' => 'Pågår'],
                'queued','scheduled' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2z"/>', 'label' => $s === 'scheduled' ? 'Schemalagd' : 'Köad'],
                'failed'     => ['bg' => 'bg-red-100',    'text' => 'text-red-800',    'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>', 'label' => 'Misslyckad'],
                'cancelled'  => ['bg' => 'bg-gray-100',   'text' => 'text-gray-800',   'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"/>', 'label' => 'Avbruten'],
                default      => ['bg' => 'bg-gray-100',    'text' => 'text-gray-800',   'svg' => '<circle cx="12" cy="12" r="3"/>', 'label' => ucfirst($s)],
            };
        };
        $targetIcon = function(string $t) {
            return match($t) {
                'wp' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>',
                'shopify' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>',
                'facebook' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/>',
                'instagram' => '<rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="m16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/>',
                'linkedin' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2z"/><circle cx="4" cy="4" r="2"/>',
                default => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>',
            };
        };
        $fmtNum = function($n) {
            if (!is_numeric($n)) return null;
            $n = (float)$n;
            if ($n >= 1000000) return number_format($n/1000000, 1) . 'M';
            if ($n >= 1000) return number_format($n/1000, 1) . 'k';
            return (string)(int)$n;
        };
    @endphp

    @if($view === 'timeline')
        <!-- Timeline-vy: Byt sidopanel mot modal med endast statistik & publiceringsdetaljer -->
        <div class="space-y-4 lg:space-y-6">
            @php
                $grouped = collect($items)->groupBy(function($r) {
                    return $r['scheduled_at'] ? \Illuminate\Support\Str::of($r['scheduled_at'])->limit(10) : 'Utan datum';
                });
            @endphp
            @forelse($grouped as $day => $rows)
                <div>
                    <div class="sticky top-4 bg-white/90 backdrop-blur-sm z-10 py-3 lg:py-4 mb-3 lg:mb-4 rounded-2xl border border-gray-200 shadow-sm">
                        <h3 class="text-base lg:text-lg font-bold text-gray-800 px-4 lg:px-6 flex items-center gap-3">
                            <svg class="w-5 h-5 lg:w-6 lg:h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2z"/>
                            </svg>
                            {{ $day === 'Utan datum' ? 'Utan datum' : \Illuminate\Support\Carbon::parse($day)->translatedFormat('l d M Y') }}
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-3 lg:gap-4">
                        @foreach($rows as $r)
                            @php $badge = $statusBadge($r['status']); @endphp
                            <button
                                wire:click="select({{ $r['id'] }})"
                                @click="openPanel = true"
                                class="w-full text-left p-4 lg:p-6 bg-white border border-gray-200 rounded-2xl hover:shadow-xl hover:border-indigo-300 transform hover:-translate-y-1 transition-all duration-200 group">
                                <div class="flex items-start justify-between gap-3 lg:gap-4">
                                    <div class="flex items-start gap-3 lg:gap-4 flex-1 min-w-0">
                                        <div class="flex-shrink-0 w-10 h-10 lg:w-12 lg:h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center text-white shadow-lg">
                                            <svg class="w-5 h-5 lg:w-6 lg:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                {!! $targetIcon($r['target']) !!}
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h4 class="font-bold text-gray-900 text-base lg:text-lg mb-2 group-hover:text-indigo-600 transition-colors break-words line-clamp-2">
                                                {{ $r['title'] }}
                                            </h4>
                                            <div class="flex flex-wrap items-center gap-2 lg:gap-4 text-sm text-gray-600">
                                                <div class="flex items-center gap-1.5">
                                                    <span class="w-2 h-2 bg-indigo-500 rounded-full"></span>
                                                    <span class="font-medium truncate">{{ $r['site'] ?: 'Ingen sajt' }}</span>
                                                </div>
                                                <div class="flex items-center gap-1.5">
                                                    <span class="w-2 h-2 bg-purple-500 rounded-full"></span>
                                                    <span>{{ ucfirst($r['target']) }}</span>
                                                </div>
                                                @if($r['scheduled_at'])
                                                    <div class="flex items-center gap-1.5">
                                                        <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                                        <span>{{ \Illuminate\Support\Carbon::parse($r['scheduled_at'])->format('H:i') }}</span>
                                                    </div>
                                                @endif
                                            </div>

                                            @if(($r['status'] ?? null) === 'published' && !empty($r['metrics']))
                                                @php
                                                    $mm = $r['metrics'];
                                                    $reach = $fmtNum($mm['reach'] ?? $mm['impressions'] ?? null);
                                                    $eng   = $fmtNum($mm['reactions'] ?? $mm['likes'] ?? null);
                                                @endphp
                                                @if($reach || $eng)
                                                    <div class="mt-2 flex items-center gap-2">
                                                        @if($reach)
                                                            <div class="inline-flex items-center gap-2 px-2 lg:px-3 py-1 lg:py-1.5 bg-emerald-100 text-emerald-800 rounded-xl text-xs lg:text-sm font-semibold">
                                                                <svg class="w-3 h-3 lg:w-4 lg:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                                </svg>
                                                                {{ $reach }}
                                                            </div>
                                                        @endif
                                                        @if($eng)
                                                            <div class="inline-flex items-center gap-2 px-2 lg:px-3 py-1 lg:py-1.5 bg-blue-100 text-blue-800 rounded-xl text-xs lg:text-sm font-semibold">
                                                                <svg class="w-3 h-3 lg:w-4 lg:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                                                </svg>
                                                                {{ $eng }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <div class="inline-flex items-center gap-2 px-3 lg:px-4 py-1.5 lg:py-2 text-xs lg:text-sm font-bold rounded-full {{ $badge['bg'] }} {{ $badge['text'] }}">
                                            <svg class="w-3 h-3 lg:w-4 lg:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                {!! $badge['svg'] !!}
                                            </svg>
                                            <span class="hidden sm:inline">{{ $badge['label'] }}</span>
                                        </div>
                                    </div>
                                </div>
                            </button>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="p-8 lg:p-12 text-center bg-white border-2 border-dashed border-gray-300 rounded-3xl">
                    <svg class="w-16 h-16 lg:w-20 lg:h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                    <h3 class="text-lg lg:text-xl font-semibold text-gray-700 mb-2">Inga publiceringar hittades</h3>
                    <p class="text-gray-500">Prova att ändra dina filter eller skapa nytt innehåll.</p>
                </div>
            @endforelse
        </div>
    @else
        <!-- Kalendervy: Full bredd kalender + modal för detaljer -->
        <div class="w-full">
            <div class="bg-white border border-gray-200 rounded-2xl lg:rounded-3xl overflow-hidden shadow-xl">
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 p-4 lg:p-6">
                    <h3 class="text-white text-lg lg:text-xl font-bold mb-2">Veckoöversikt</h3>
                    <p class="text-indigo-100 text-sm lg:text-base">{{ $weekStart->translatedFormat('j M') }} – {{ $weekEnd->translatedFormat('j M Y') }}</p>
                </div>
                <div class="grid grid-cols-7 gap-px bg-gray-200">
                    @foreach($weekDays as $d)
                        @php
                            $rows = collect($items)->filter(fn($r) => $r['scheduled_at'] && \Illuminate\Support\Carbon::parse($r['scheduled_at'])->isSameDay($d))->sortBy('scheduled_at')->values();
                            $isToday = $d->isToday();
                            $isPastDay = $d->copy()->endOfDay()->isPast();
                        @endphp
                        <div class="min-h-[180px] lg:min-h-[240px] {{ $isToday ? 'bg-indigo-50' : 'bg-white' }} p-2 lg:p-4">
                            <div class="flex items-center justify-between mb-2 lg:mb-3">
                                <div>
                                    <div class="text-xs font-bold uppercase text-gray-500">{{ $d->translatedFormat('D') }}</div>
                                    <div class="text-base lg:text-lg font-bold {{ $isToday ? 'text-indigo-600' : 'text-gray-900' }}">{{ $d->format('d') }}</div>
                                </div>
                                <div class="flex items-center gap-1 lg:gap-2">
                                    @if($rows->count() > 0)
                                        <span class="text-xs px-1.5 lg:px-2 py-0.5 lg:py-1 rounded-full {{ $isToday ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700' }} font-bold">
                                            {{ $rows->count() }}
                                        </span>
                                    @endif

                                    @if($isPastDay)
                                            <span class="text-xs px-1.5 lg:px-2 py-0.5 lg:py-1 text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed select-none" title="Kan inte lägga till i förflutna">
                                            <span class="hidden lg:inline">+ Lägg till</span>
                                            <span class="lg:hidden">+</span>
                                        </span>
                                    @else
                                        <!-- Viktigt: nollställ selected FÖRE startQuickPlan och stoppa klickbubbling -->
                                        <button
                                            wire:click="$set('selected', null); startQuickPlan('{{ $d->toDateString() }}')"
                                            @click.stop="openPanel = true"
                                            class="text-xs px-1.5 lg:px-2 py-0.5 lg:py-1 {{ $isToday ? 'text-indigo-600 hover:bg-indigo-100' : 'text-gray-500 hover:bg-gray-100' }} rounded-lg transition-colors font-medium">
                                            <span class="hidden lg:inline">+ Lägg till</span>
                                            <span class="lg:hidden">+</span>
                                        </button>
                                    @endif
                                </div>
                            </div>

                            <div class="space-y-1 lg:space-y-2">
                                @forelse($rows->take(4) as $r)
                                    @php $badge = $statusBadge($r['status']); @endphp
                                    <button
                                        wire:click="select({{ $r['id'] }})"
                                        @click="openPanel = true"
                                        class="w-full text-left p-2 lg:p-3 rounded-lg lg:rounded-xl border-2 border-gray-100 bg-white hover:border-indigo-300 hover:shadow-lg transform hover:scale-105 transition-all duration-200 group">
                                        <div class="flex items-start gap-1 lg:gap-2">
                                            <div class="flex-shrink-0">
                                                <svg class="w-4 h-4 lg:w-5 lg:h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    {!! $targetIcon($r['target']) !!}
                                                </svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="text-xs lg:text-sm font-bold text-gray-900 truncate group-hover:text-indigo-600 transition-colors">
                                                    {{ \Illuminate\Support\Carbon::parse($r['scheduled_at'])->format('H:i') }}
                                                </div>
                                                <div class="text-xs font-medium text-gray-600 break-words line-clamp-2 mt-0.5">{{ $r['title'] }}</div>
                                                <div class="text-xs text-gray-500 truncate">{{ $r['site'] ?: '—' }}</div>

                                                @if(($r['status'] ?? null) === 'published' && !empty($r['metrics']))
                                                    @php
                                                        $mm = $r['metrics'];
                                                        $reach = $fmtNum($mm['reach'] ?? $mm['impressions'] ?? null);
                                                        $eng   = $fmtNum($mm['reactions'] ?? $mm['likes'] ?? null);
                                                    @endphp
                                                    @if($reach || $eng)
                                                        <div class="mt-1 lg:mt-2 flex items-center gap-1 text-xs">
                                                            @if($reach)
                                                                <span class="inline-flex items-center gap-1 px-1 lg:px-1.5 py-0.5 rounded bg-emerald-100 text-emerald-700 font-bold">
                                                                    <svg class="w-2.5 h-2.5 lg:w-3 lg:h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                                    </svg>
                                                                    {{ $reach }}
                                                                </span>
                                                            @endif
                                                            @if($eng)
                                                                <span class="inline-flex items-center gap-1 px-1 lg:px-1.5 py-0.5 rounded bg-blue-100 text-blue-700 font-bold">
                                                                    <svg class="w-2.5 h-2.5 lg:w-3 lg:h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                                                    </svg>
                                                                    {{ $eng }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>
                                            <div class="flex-shrink-0">
                                                <svg class="w-3 h-3 lg:w-4 lg:h-4 {{ $badge['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    {!! $badge['svg'] !!}
                                                </svg>
                                            </div>
                                        </div>
                                    </button>
                                @empty
                                    <div class="text-center py-2 lg:py-4">
                                        <svg class="w-6 h-6 lg:w-8 lg:h-8 text-gray-300 mx-auto mb-1 lg:mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                        </svg>
                                        <div class="text-xs text-gray-400">Inga poster</div>
                                    </div>
                                @endforelse

                                @if($rows->count() > 4)
                                    <div class="text-center pt-1 lg:pt-2">
                                        <span class="text-xs text-gray-500 bg-gray-100 px-1.5 lg:px-2 py-0.5 lg:py-1 rounded-full font-medium">
                                            +{{ $rows->count() - 4 }} till
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Modal för KALENDERVY: använder detail-panel (enkel skapa/redigera-logik finns där nu) -->
    <div x-show="openPanel && '{{ $view }}' === 'calendar'"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
        <div class="flex min-h-screen items-center justify-center p-4">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="openPanel = false" wire:click="clearSelection"></div>
            <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="transform scale-95"
                 x-transition:enter-end="transform scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="transform scale-100"
                 x-transition:leave-end="transform scale-95">
                @include('livewire.planner.partials.detail-panel', ['readyContents' => $readyContents])
            </div>
        </div>
    </div>

    <!-- Modal för TIDSLINJE: minimalistisk detaljvy utan redigering -->
    <div x-show="openPanel && '{{ $view }}' === 'timeline'"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display:none;">
        <div class="flex min-h-screen items-center justify-center p-4">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="openPanel = false" wire:click="clearSelection"></div>

            <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-xl max-h-[90vh] flex flex-col">
                <div class="flex items-center justify-between px-4 py-3 border-b">
                    <h4 class="text-base lg:text-lg font-bold text-gray-900">Inlägg</h4>
                    <button class="flex items-center gap-2 px-3 py-2 text-sm text-gray-600 hover:text-gray-800 hover:bg-gray-50 rounded-xl" @click="openPanel=false" wire:click="clearSelection">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        <span class="hidden sm:inline">Stäng</span>
                    </button>
                </div>

                @if($selected)
                    @php
                        $badge = $statusBadge($selected['status']);
                        $m = $selected['metrics'] ?? null;
                        $isPublished = ($selected['status'] ?? null) === 'published';
                        $isSchedulable = in_array(($selected['status'] ?? null), ['queued','scheduled','processing'], true);
                    @endphp

                    <div class="flex-1 overflow-y-auto p-4 lg:p-6 space-y-4">
                        <!-- Header info -->
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-2xl">
                            <div class="font-semibold text-gray-900 truncate">{{ $selected['title'] }}</div>
                            <span class="inline-flex items-center gap-2 px-3 py-1.5 text-xs font-bold rounded-full {{ $badge['bg'] }} {{ $badge['text'] }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $badge['svg'] !!}</svg>
                                {{ $badge['label'] }}
                            </span>
                        </div>

                        <!-- Basdetaljer -->
                        <div class="grid grid-cols-1 gap-3">
                            <div class="bg-white border rounded-2xl p-4">
                                <div class="text-xs text-gray-600 mb-1">Planerad/Publicerad tid</div>
                                <div class="font-semibold text-gray-900">
                                    {{ $selected['scheduled_at'] ? \Illuminate\Support\Carbon::parse($selected['scheduled_at'])->format('Y-m-d H:i') : '—' }}
                                </div>
                            </div>

                            <div class="bg-white border rounded-2xl p-4">
                                <div class="text-xs text-gray-600 mb-1">Kanal & sajt</div>
                                <div class="text-sm text-gray-900">
                                    {{ ucfirst($selected['target']) }} · {{ $selected['site'] ?: 'Ingen sajt' }}
                                </div>
                                @if(!empty($selected['external_url']))
                                    <a class="mt-2 inline-flex items-center gap-2 text-indigo-600 hover:text-indigo-800 text-sm font-medium" href="{{ $selected['external_url'] }}" target="_blank" onclick="event.stopPropagation()">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                        </svg>
                                        Visa live
                                    </a>
                                @endif
                            </div>
                        </div>

                        @if($isSchedulable && !$isPublished)
                            <!-- Bild för publicering -->
                            <div class="bg-gradient-to-br from-orange-50 to-amber-50 p-4 rounded-2xl border border-orange-200">
                                <div class="flex items-center justify-between mb-3">
                                    <h5 class="text-sm font-bold text-orange-800">Bild för publicering</h5>
                                    <div class="flex items-center gap-2">
                                        <button x-data @click="$dispatch('media-picker:open')"
                                                class="px-3 py-2 bg-white border border-orange-200 text-orange-700 rounded-xl hover:bg-orange-50 text-sm font-medium">
                                            Välj bild
                                        </button>
                                        @if($quickImageId > 0)
                                            <button wire:click="applyImageToSelected"
                                                    class="px-3 py-2 bg-orange-600 text-white rounded-xl hover:bg-orange-700 text-sm font-semibold">
                                                Använd bild
                                            </button>
                                            <button wire:click="$set('quickImageId', 0)"
                                                    class="px-3 py-2 bg-white border border-orange-200 text-orange-700 rounded-xl hover:bg-orange-50 text-sm">
                                                Rensa val
                                            </button>
                                        @endif
                                        @if(!empty($selected['id']))
                                            <button wire:click="removeImageFromSelected"
                                                    class="px-3 py-2 bg-white border border-orange-200 text-orange-700 rounded-xl hover:bg-orange-50 text-sm">
                                                Ta bort koppling
                                            </button>
                                        @endif
                                    </div>
                                </div>

                                @if($quickImageId > 0)
                                    <div class="flex items-center gap-3 p-3 bg-white rounded-xl border border-orange-100">
                                        <img class="w-12 h-12 rounded-lg object-cover" src="{{ route('assets.thumb', $quickImageId) }}" alt="Vald bild">
                                        <div class="text-sm text-orange-800">Bild vald (ej kopplad än)</div>
                                    </div>
                                @else
                                    <p class="text-xs text-orange-700">Ingen bild vald.</p>
                                @endif
                            </div>

                            <!-- Hantera publicering -->
                            <div class="bg-gray-50 p-4 rounded-2xl space-y-3">
                                <h5 class="text-sm font-bold text-gray-900">Hantera publicering</h5>

                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Ny tid</label>
                                    <input type="datetime-local" wire:model.defer="rescheduleAt" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                                    @error('rescheduleAt')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                                    <button wire:click="reschedulePublication({{ (int)$selected['id'] }})"
                                            class="w-full inline-flex items-center justify-center gap-2 px-3 py-2 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700 text-sm font-semibold">
                                        Ändra tid
                                    </button>

                                    <button wire:click="cancelPublication({{ (int)$selected['id'] }})" onclick="return confirm('Avbryt denna publicering?')"
                                            class="w-full inline-flex items-center justify-center gap-2 px-3 py-2 rounded-xl bg-red-600 text-white hover:bg-red-700 text-sm font-semibold">
                                        Avbryt
                                    </button>

                                    <!-- Publicera nu: sätt tid till nu och återanvänd reschedulePublication -->
                                    <button
                                        x-data
                                        @click="$wire.rescheduleAt = new Date().toISOString().slice(0,16); $wire.reschedulePublication({{ (int)$selected['id'] }})"
                                        class="w-full inline-flex items-center justify-center gap-2 px-3 py-2 rounded-xl bg-emerald-600 text-white hover:bg-emerald-700 text-sm font-semibold">
                                        Publicera nu
                                    </button>
                                </div>

                                <p class="text-xs text-blue-700 bg-blue-50 border-l-4 border-blue-400 p-2 rounded-r-lg">
                                    Ändringar av tid respekteras av köade jobb. Om en process redan körs kan publiceringen hinna gå ut.
                                </p>
                            </div>
                        @endif

                        @if($isPublished)
                            <!-- Statistik (oförändrad) -->
                            <div class="bg-gradient-to-br from-green-50 to-emerald-50 border border-green-200 rounded-2xl p-4">
                                <div class="flex items-center gap-2 mb-3">
                                    <svg class="w-5 h-5 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                    <span class="font-bold text-green-800">Statistik</span>
                                </div>
                                @if($m)
                                    <div class="grid grid-cols-2 gap-2">
                                        @foreach([
                                            ['key'=>'impressions','label'=>'Impressions'],
                                            ['key'=>'reach','label'=>'Räckvidd'],
                                            ['key'=>'reactions','label'=>'Reaktioner'],
                                            ['key'=>'likes','label'=>'Likes'],
                                            ['key'=>'comments','label'=>'Kommentarer'],
                                            ['key'=>'shares','label'=>'Delningar'],
                                        ] as $metric)
                                            <div class="bg-white border border-green-100 rounded-xl p-3">
                                                <div class="text-xs text-green-700">{{ $metric['label'] }}</div>
                                                <div class="font-bold text-gray-900">{{ $m[$metric['key']] ?? '—' }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="mt-3 text-xs text-green-700">Senast uppdaterad: {{ $selected['metrics_at'] ?? '—' }}</div>
                                @else
                                    <div class="text-sm text-green-800">Ingen statistik hämtad ännu.</div>
                                @endif
                            </div>
                        @endif

                        @if(!empty($selected['message']))
                            <div class="bg-amber-50 border-l-4 border-amber-400 p-3 rounded-r-xl text-sm text-amber-800">
                                {{ $selected['message'] }}
                            </div>
                        @endif
                    </div>
                @else
                    <div class="p-8 text-center">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Välj en post</h3>
                        <p class="text-gray-600">Klicka på en post i listan för att se detaljer.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
