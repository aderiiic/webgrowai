<div class="bg-white border rounded-3xl shadow-lg sticky top-4 overflow-hidden max-h-[90vh] flex flex-col">
    <!-- Elegant header med gradient -->
    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 p-4 lg:p-6 border-b border-gray-100 flex items-center justify-between flex-shrink-0">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 lg:w-10 lg:h-10 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-full flex items-center justify-center">
                <svg class="w-4 h-4 lg:w-5 lg:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <h4 class="font-bold text-lg lg:text-xl text-gray-900">Detaljer</h4>
        </div>
        <button class="flex items-center gap-2 px-3 py-2 text-sm text-gray-600 hover:text-gray-800 hover:bg-white/60 rounded-xl transition-all duration-200 flex-shrink-0" @click="openPanel=false" wire:click="clearSelection">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            <span class="hidden sm:inline">Stäng</span>
        </button>
    </div>

    @if($selected)
        @php
            $badge = match($selected['status']) {
                'published'  => ['bg'=>'bg-emerald-100','text'=>'text-emerald-800','svg'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>','label'=>'Publicerad'],
                'processing' => ['bg'=>'bg-amber-100','text'=>'text-amber-800','svg'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>','label'=>'Pågår'],
                'queued','scheduled' => ['bg'=>'bg-blue-100','text'=>'text-blue-800','svg'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2z"/>','label'=>$selected['status']==='scheduled'?'Schemalagd':'Köad'],
                'failed'     => ['bg'=>'bg-red-100','text'=>'text-red-800','svg'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>','label'=>'Misslyckad'],
                'cancelled'  => ['bg'=>'bg-gray-100','text'=>'text-gray-800','svg'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"/>','label'=>'Avbruten'],
                default      => ['bg'=>'bg-gray-100','text'=>'text-gray-800','svg'=>'<circle cx="12" cy="12" r="3"/>','label'=>ucfirst($selected['status'])],
            };
            $m = $selected['metrics'] ?? null;
            $isPublished = $selected['status'] === 'published';
            $stale = false;
            if ($isPublished && !empty($selected['metrics_at'])) {
                try { $stale = \Illuminate\Support\Carbon::parse($selected['metrics_at'])->lt(now()->subMinutes(2)); } catch (\Throwable $e) { $stale = false; }
            }
        @endphp

            <!-- Scrollbar innehåll -->
        <div class="flex-1 overflow-y-auto p-4 lg:p-6 space-y-4 lg:space-y-6" @if($isPublished && (!$m || $stale)) wire:poll.5s="reloadSelected" @endif>

            <!-- Status section med bättre responsivitet -->
            <div class="flex items-center justify-between p-3 lg:p-4 bg-gray-50 rounded-2xl gap-3">
                <span class="text-sm font-medium text-gray-700 flex-shrink-0">Status</span>
                <div class="flex items-center gap-2 min-w-0">
                    <svg class="w-4 h-4 lg:w-5 lg:h-5 {{ $badge['text'] }} flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        {!! $badge['svg'] !!}
                    </svg>
                    <span class="inline-flex items-center px-2 lg:px-3 py-1 lg:py-1.5 text-xs lg:text-sm font-medium rounded-full {{ $badge['bg'] }} {{ $badge['text'] }} truncate">
                        {{ $badge['label'] }}
                    </span>
                </div>
            </div>

            <!-- Titel och metadata med bättre radbrytning -->
            <div class="space-y-3">
                <h3 class="text-base lg:text-lg font-bold text-gray-900 leading-tight break-words">{{ $selected['title'] }}</h3>
                <div class="flex flex-col sm:flex-row sm:flex-wrap items-start sm:items-center gap-2 lg:gap-3 text-sm text-gray-600">
                    <div class="flex items-center gap-1.5 flex-shrink-0">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <span class="font-medium">{{ ucfirst($selected['target']) }}</span>
                    </div>
                    <span class="hidden sm:block w-1 h-1 bg-gray-300 rounded-full"></span>
                    <div class="flex items-center gap-1.5 min-w-0">
                        <svg class="w-4 h-4 text-purple-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"/>
                        </svg>
                        <span class="truncate">{{ $selected['site'] ?: 'Ingen sajt' }}</span>
                    </div>
                    @if(!empty($selected['external_url']))
                        <a href="{{ $selected['external_url'] }}" target="_blank" class="flex items-center gap-1.5 text-indigo-600 hover:text-indigo-800 font-medium transition-colors flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                            <span class="hidden lg:inline">Visa live</span>
                            <span class="lg:hidden">Live</span>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Info cards med förbättrad responsivitet -->
            <div class="grid grid-cols-1 gap-4">
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-4 rounded-2xl border border-blue-100">
                    <div class="flex items-center gap-2 text-blue-700 mb-2">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-xs font-semibold uppercase tracking-wider">Planerad tid</span>
                    </div>
                    <div class="font-bold text-gray-900 text-base lg:text-lg break-words">
                        {{ $selected['scheduled_at'] ? \Illuminate\Support\Carbon::parse($selected['scheduled_at'])->format('Y-m-d H:i') : 'Ingen tid' }}
                    </div>
                </div>

                <div class="bg-gradient-to-br from-purple-50 to-pink-50 p-4 rounded-2xl border border-purple-100">
                    <div class="flex items-center gap-2 text-purple-700 mb-2">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                        <span class="text-xs font-semibold uppercase tracking-wider">AI-innehåll</span>
                    </div>
                    <a href="{{ route('ai.detail', $selected['ai_content_id']) }}" class="font-bold text-purple-700 hover:text-purple-900 text-base lg:text-lg transition-colors">
                        Öppna →
                    </a>
                </div>
            </div>

            @if($selected['status'] === 'published')
                <!-- Statistik section med kompaktare design -->
                <div class="bg-gradient-to-br from-green-50 to-emerald-50 border border-green-200 rounded-2xl p-4 lg:p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 lg:w-8 lg:h-8 bg-green-600 rounded-lg flex items-center justify-center">
                                <svg class="w-3 h-3 lg:w-4 lg:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                            <h5 class="text-base lg:text-lg font-bold text-green-800">Statistik</h5>
                        </div>
                        <button wire:click="refreshMetrics({{ (int)$selected['id'] }})" class="flex items-center gap-2 px-3 py-2 bg-white border border-green-200 text-green-700 rounded-xl hover:bg-green-50 transition-all duration-200 text-sm font-medium flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Uppdatera
                        </button>
                    </div>
                    @if($m)
                        <div class="grid grid-cols-2 gap-3 mb-3">
                            @foreach([
                                ['key' => 'impressions', 'label' => 'Impressions', 'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>'],
                                ['key' => 'reach', 'label' => 'Räckvidd', 'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>'],
                                ['key' => 'reactions', 'label' => 'Reaktioner', 'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>'],
                                ['key' => 'likes', 'label' => 'Likes', 'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/>'],
                                ['key' => 'comments', 'label' => 'Kommentarer', 'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>'],
                                ['key' => 'shares', 'label' => 'Delningar', 'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>']
                            ] as $metric)
                                <div class="bg-white p-3 rounded-xl border border-green-100">
                                    <div class="flex items-center gap-2 mb-1">
                                        <svg class="w-4 h-4 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            {!! $metric['svg'] !!}
                                        </svg>
                                        <span class="text-xs font-medium text-green-700 truncate">{{ $metric['label'] }}</span>
                                    </div>
                                    <div class="font-bold text-gray-900 text-base lg:text-lg">{{ $m[$metric['key']] ?? '—' }}</div>
                                </div>
                            @endforeach
                        </div>
                        <div class="flex items-center gap-2 text-xs text-green-700 bg-green-100 px-3 py-2 rounded-lg">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2z"/>
                            </svg>
                            <span class="truncate">Senast uppdaterad: {{ $selected['metrics_at'] ?: ($m['updated_at'] ?? '—') }}</span>
                        </div>
                    @else
                        <div class="text-center py-6">
                            <svg class="w-12 h-12 lg:w-16 lg:h-16 text-green-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            <div class="text-green-800 font-medium mb-2">Ingen statistik hämtad ännu</div>
                            <p class="text-sm text-green-600">Klicka "Uppdatera" för att hämta senaste siffrorna</p>
                        </div>
                    @endif
                </div>
            @endif

            @if(!empty($selected['message']))
                <div class="bg-gradient-to-r from-amber-50 to-orange-50 border-l-4 border-amber-400 p-4 rounded-r-2xl">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                        <div class="min-w-0">
                            <h6 class="font-semibold text-amber-800 mb-1">Meddelande</h6>
                            <p class="text-sm text-amber-700 break-words">{{ $selected['message'] }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Hantera publicering section med förbättrade formulär -->
            <div class="bg-gray-50 p-4 lg:p-6 rounded-2xl space-y-4">
                <h5 class="text-base lg:text-lg font-bold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Hantera publicering
                </h5>

                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ny tid</label>
                        <input type="datetime-local" wire:model.defer="rescheduleAt" class="w-full px-3 py-2 lg:px-4 lg:py-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" />
                        @error('rescheduleAt')
                        <p class="text-xs text-red-600 mt-1 flex items-center gap-1">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                            <span class="break-words">{{ $message }}</span>
                        </p>
                        @enderror
                    </div>

                    <div class="flex flex-col gap-3">
                        @php
                            $canReschedule = $selected && in_array($selected['status'], ['queued','scheduled','processing']);
                            $canCancel = $selected && in_array($selected['status'], ['queued','scheduled','processing']);
                        @endphp

                        <button @if(!$canReschedule) disabled @endif wire:click="reschedulePublication({{ (int)$selected['id'] }})"
                                class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-xl font-medium transition-all duration-200 {{ $canReschedule ? 'bg-indigo-600 text-white hover:bg-indigo-700 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5' : 'bg-gray-200 text-gray-500 cursor-not-allowed' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Ändra tid
                        </button>

                        <button @if(!$canCancel) disabled @endif wire:click="cancelPublication({{ (int)$selected['id'] }})" onclick="return confirm('Avbryt denna publicering?')"
                                class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-xl font-medium transition-all duration-200 {{ $canCancel ? 'bg-red-600 text-white hover:bg-red-700 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5' : 'bg-gray-200 text-gray-500 cursor-not-allowed' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Avbryt
                        </button>
                    </div>

                    <div class="bg-blue-50 border-l-4 border-blue-400 p-3 rounded-r-xl">
                        <p class="text-xs text-blue-700 flex items-start gap-2">
                            <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="break-words">Ändringar av tid respekteras av köade jobb. Om en process redan körs kan publiceringen hinna gå ut.</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Snabbplanera section med förbättrad layout -->
            <div class="bg-gradient-to-br from-green-50 to-emerald-50 p-4 lg:p-6 rounded-2xl space-y-4 border border-green-200">
                <h5 class="text-base lg:text-lg font-bold text-green-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    Snabbplanera nytt
                </h5>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-green-700 mb-2">Innehåll</label>
                        <select wire:model.defer="quickContentId" class="w-full px-3 py-2 lg:px-4 lg:py-3 border border-green-300 rounded-xl text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white">
                            <option value="">Välj färdigt innehåll…</option>
                            @foreach(($readyContents ?? []) as $rc)
                                <option value="{{ $rc['id'] }}" class="break-words">#{{ $rc['id'] }} — {{ \Illuminate\Support\Str::limit($rc['title'], 40) }}</option>
                            @endforeach
                        </select>
                        @error('quickContentId')
                        <p class="text-xs text-red-600 mt-1 flex items-center gap-1">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                            <span class="break-words">{{ $message }}</span>
                        </p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-green-700 mb-2">Kanal</label>
                            <select wire:model.defer="quickTarget" class="w-full px-3 py-2 lg:px-4 lg:py-3 border border-green-300 rounded-xl text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white">
                                <option value="facebook">Facebook</option>
                                <option value="instagram">Instagram</option>
                                <option value="linkedin">LinkedIn</option>
                                <option value="wp">WordPress</option>
                                <option value="shopify">Shopify</option>
                            </select>
                            @error('quickTarget')
                            <p class="text-xs text-red-600 mt-1 flex items-center gap-1">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                </svg>
                                <span class="break-words">{{ $message }}</span>
                            </p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-green-700 mb-2">Tid</label>
                            <input type="datetime-local" wire:model.defer="quickScheduleAt" class="w-full px-3 py-2 lg:px-4 lg:py-3 border border-green-300 rounded-xl text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white" />
                            @error('quickScheduleAt')
                            <p class="text-xs text-red-600 mt-1 flex items-center gap-1">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                </svg>
                                <span class="break-words">{{ $message }}</span>
                            </p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button wire:click="createQuickPublication" class="flex items-center gap-2 px-4 lg:px-6 py-3 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span class="hidden sm:inline">Skapa schemalagd</span>
                            <span class="sm:hidden">Skapa</span>
                        </button>
                    </div>

                    <div class="bg-emerald-100 p-3 rounded-xl border border-emerald-200">
                        <p class="text-xs text-emerald-700 flex items-start gap-2">
                            <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="break-words">Använd AI-texten som grund. Bilder och finjustering kan läggas till i detaljsidan.</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="p-8 lg:p-12 text-center">
            <svg class="w-16 h-16 lg:w-20 lg:h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Välj en post</h3>
            <p class="text-gray-600">Klicka på en post i listan för att se detaljer och hantera den.</p>
        </div>
    @endif
</div>
