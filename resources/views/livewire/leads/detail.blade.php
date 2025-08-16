
<div>
    <div class="max-w-7xl mx-auto space-y-8">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                <svg class="w-8 h-8 mr-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Lead-detaljer
            </h1>
            <a href="{{ route('leads.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 hover:border-gray-400 focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition-all duration-200 shadow-sm hover:shadow-md">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Tillbaka till leads
            </a>
        </div>

        <!-- Lead overview cards -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Identity card -->
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100/50 p-6">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br {{ $lead->email ? 'from-emerald-500 to-teal-600' : 'from-gray-500 to-gray-600' }} rounded-xl flex items-center justify-center">
                        @if($lead->email)
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                            </svg>
                        @else
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        @endif
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Identifiering</h2>
                        <p class="text-sm text-gray-600">Lead-information och ursprung</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="p-4 bg-gradient-to-r from-gray-50 to-white rounded-xl border border-gray-200/50">
                        <div class="text-xs font-medium text-gray-600 mb-1">Identitet</div>
                        @if($lead->email)
                            <div class="text-lg font-semibold text-gray-900">{{ $lead->email }}</div>
                            <div class="text-sm text-emerald-600 flex items-center mt-1">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Registrerad användare
                            </div>
                        @else
                            <div class="text-lg font-mono text-gray-700">{{ \Illuminate\Support\Str::limit($lead->visitor_id, 15, '') }}</div>
                            <div class="text-sm text-amber-600 flex items-center mt-1">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Anonym besökare
                            </div>
                        @endif
                    </div>

                    <div class="p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200/50">
                        <div class="text-xs font-medium text-blue-700 mb-1">Sajt</div>
                        <div class="text-lg font-semibold text-blue-900">{{ $lead->site->name ?? '#'.$lead->site_id }}</div>
                    </div>
                </div>
            </div>

            <!-- Score card -->
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100/50 p-6">
                <div class="flex items-center space-x-4 mb-4">
                    @php
                        $scoreValue = $score->score_norm ?? 0;
                        $scoreColors = $scoreValue >= 70
                            ? ['bg' => 'from-emerald-500 to-teal-600', 'text' => 'text-emerald-800', 'cardBg' => 'from-emerald-50 to-teal-50', 'border' => 'border-emerald-200/50']
                            : ($scoreValue >= 40
                                ? ['bg' => 'from-amber-500 to-orange-600', 'text' => 'text-amber-800', 'cardBg' => 'from-amber-50 to-orange-50', 'border' => 'border-amber-200/50']
                                : ['bg' => 'from-red-500 to-pink-600', 'text' => 'text-red-800', 'cardBg' => 'from-red-50 to-pink-50', 'border' => 'border-red-200/50']);
                    @endphp
                    <div class="w-12 h-12 bg-gradient-to-br {{ $scoreColors['bg'] }} rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Lead Score</h2>
                        <p class="text-sm text-gray-600">Konverteringssannolikhet</p>
                    </div>
                </div>

                <div class="p-6 bg-gradient-to-r {{ $scoreColors['cardBg'] }} rounded-xl {{ $scoreColors['border'] }} border mb-4">
                    <div class="text-center">
                        <div class="text-4xl font-bold {{ $scoreColors['text'] }} mb-2">{{ $scoreValue }}</div>
                        <div class="text-sm {{ $scoreColors['text'] }} font-medium">
                            @if($scoreValue >= 70)
                                Hög sannolikhet
                            @elseif($scoreValue >= 40)
                                Medel sannolikhet
                            @else
                                Låg sannolikhet
                            @endif
                        </div>
                    </div>
                </div>

                @if($score?->breakdown)
                    <details class="group">
                        <summary class="cursor-pointer text-sm font-medium text-gray-700 hover:text-gray-900 flex items-center justify-between p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                                Score-detaljer
                            </span>
                            <svg class="w-4 h-4 transition-transform group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </summary>
                        <div class="mt-3 p-4 bg-gray-100 rounded-xl">
                            <pre class="text-xs text-gray-700 overflow-auto whitespace-pre-wrap">{{ json_encode($score->breakdown, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}</pre>
                        </div>
                    </details>
                @endif
            </div>
        </div>

        <!-- Recent events -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100/50 p-6">
            <div class="flex items-center space-x-4 mb-6">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Senaste aktivitet</h2>
                    <p class="text-sm text-gray-600">Händelser och interaktioner</p>
                </div>
            </div>

            <div class="space-y-3">
                @forelse($events as $ev)
                    @php
                        $eventTypeColors = [
                            'pageview' => ['bg' => 'from-blue-50 to-indigo-50', 'border' => 'border-blue-200/50', 'icon' => 'bg-blue-500'],
                            'click' => ['bg' => 'from-green-50 to-emerald-50', 'border' => 'border-green-200/50', 'icon' => 'bg-green-500'],
                            'form' => ['bg' => 'from-purple-50 to-pink-50', 'border' => 'border-purple-200/50', 'icon' => 'bg-purple-500'],
                            'default' => ['bg' => 'from-gray-50 to-slate-50', 'border' => 'border-gray-200/50', 'icon' => 'bg-gray-500'],
                        ];
                        $eventColors = $eventTypeColors[strtolower($ev->type)] ?? $eventTypeColors['default'];
                    @endphp

                    <div class="p-4 bg-gradient-to-r {{ $eventColors['bg'] }} rounded-xl {{ $eventColors['border'] }} border">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start space-x-3 flex-1">
                                <div class="w-8 h-8 {{ $eventColors['icon'] }} rounded-lg flex items-center justify-center flex-shrink-0">
                                    @if(strtolower($ev->type) === 'pageview')
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    @elseif(strtolower($ev->type) === 'click')
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10m0 0V6a2 2 0 00-2-2H9a2 2 0 00-2 2v2m10 0v10a2 2 0 01-2 2H9a2 2 0 01-2-2V8"/>
                                        </svg>
                                    @endif
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center space-x-3 mb-1">
                                        <span class="inline-flex items-center px-2 py-1 bg-white bg-opacity-60 border border-gray-200/50 rounded-full text-xs font-medium text-gray-700 uppercase">
                                            {{ $ev->type }}
                                        </span>
                                        <span class="text-xs text-gray-600 font-mono">
                                            {{ $ev->occurred_at->format('Y-m-d H:i:s') }}
                                        </span>
                                    </div>
                                    @if($ev->url)
                                        <div class="text-sm text-gray-700 break-all">
                                            <a href="{{ $ev->url }}" target="_blank" rel="noopener" class="hover:text-indigo-600 hover:underline">
                                                {{ \Illuminate\Support\Str::limit($ev->url, 100) }}
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Inga händelser ännu</h3>
                        <p class="text-gray-600">Händelser kommer att visas här när leadet interagerar mer med sajten.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
