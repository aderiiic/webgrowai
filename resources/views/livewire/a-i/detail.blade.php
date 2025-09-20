
@php
    // Säkerställ att användaren bara kan se innehåll för den aktuella sajten
    /** @var \App\Services\CurrentCustomer $currentCustomer */
    $currentCustomer = app(\App\Support\CurrentCustomer::class);
    $currentSite = $currentCustomer->getSite();

    if (!$currentSite) {
        abort(403, 'Saknar aktuell sajt.');
    }

    // Om $content har site_id, kontrollera att den matchar den aktuella sajten
    if (isset($content) && isset($content->site_id) && (int)$content->site_id !== (int)$currentSite->id) {
        abort(403, 'Otillåten åtkomst.');
    }
@endphp

<div>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 px-4 sm:px-6 lg:px-8 py-6">
        <div class="max-w-6xl mx-auto space-y-6">
            <!-- Header - Mobilvänlig -->
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-4 sm:p-6">
                <div class="flex flex-col space-y-4 sm:space-y-0 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-start sm:items-center space-x-3">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 line-clamp-2">
                                {{ $content->title ?: 'Min AI-genererade text' }}
                            </h1>
                            <p class="text-xs sm:text-sm text-gray-600 mt-1">AI-genererat innehåll</p>
                        </div>
                    </div>

                    <!-- Status & Actions -->
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-2 sm:space-y-0 sm:space-x-3">
                        @php
                            $statusColors = [
                                'ready' => ['bg' => 'from-green-50 to-emerald-50', 'border' => 'border-green-200/50', 'text' => 'text-green-800', 'label' => 'Klar'],
                                'completed' => ['bg' => 'from-green-50 to-emerald-50', 'border' => 'border-green-200/50', 'text' => 'text-green-800', 'label' => 'Klar'],
                                'processing' => ['bg' => 'from-blue-50 to-indigo-50', 'border' => 'border-blue-200/50', 'text' => 'text-blue-800', 'label' => 'Skapas'],
                                'draft' => ['bg' => 'from-yellow-50 to-amber-50', 'border' => 'border-yellow-200/50', 'text' => 'text-yellow-800', 'label' => 'Utkast'],
                                'error' => ['bg' => 'from-red-50 to-pink-50', 'border' => 'border-red-200/50', 'text' => 'text-red-800', 'label' => 'Fel'],
                            ];
                            $colors = $statusColors[strtolower($content->status)] ?? ['bg' => 'from-gray-50 to-slate-50', 'border' => 'border-gray-200/50', 'text' => 'text-gray-800', 'label' => $content->status];
                        @endphp

                        <div class="inline-flex items-center px-3 py-2 bg-gradient-to-r {{ $colors['bg'] }} {{ $colors['border'] }} border rounded-xl justify-center sm:justify-start">
                            <span class="text-sm font-medium {{ $colors['text'] }} uppercase">{{ $colors['label'] }}</span>
                        </div>

                        @php $mdReady = !empty($md); @endphp

                        <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                            @if(!$isLocked && $mdReady)
                                @if(!$isEditing)
                                    <button wire:click="startEditing"
                                            class="inline-flex items-center justify-center px-4 py-2 bg-white border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 hover:border-gray-400 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200 text-sm">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5h2m-1 0v14m-7-7h14"/>
                                        </svg>
                                        Redigera
                                    </button>
                                @else
                                    <button wire:click="cancelEditing"
                                            class="inline-flex items-center justify-center px-4 py-2 bg-white border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 hover:border-gray-400 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200 text-sm">
                                        Avbryt
                                    </button>
                                @endif
                            @endif

                            <a href="{{ route('ai.list') }}" class="inline-flex items-center justify-center px-4 py-2 bg-white border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 hover:border-gray-400 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200 text-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                                <span class="hidden sm:inline">Tillbaka</span>
                                <span class="sm:hidden">Tillbaka</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lås-banner när publicerad -->
            @if($isLocked)
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-4">
                    <div class="flex items-start space-x-3">
                        <svg class="w-5 h-5 text-gray-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <div>
                            <p class="text-sm text-gray-800 font-medium">Texten är publicerad</p>
                            <p class="text-sm text-gray-600">Den här texten kan inte längre redigeras eftersom den redan har publicerats.</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Publishing status overview - Mobilvänlig -->
            @php
                $pubs = $content->relationLoaded('publications') ? $content->publications : ($content->publications ?? collect());
                $byTarget = [
                    'wordpress' => $pubs->whereIn('target',['wp','wordpress']),
                    'shopify'   => $pubs->where('target','shopify'),
                    'facebook'  => $pubs->where('target','facebook'),
                    'instagram' => $pubs->where('target','instagram'),
                    'linkedin'  => $pubs->where('target','linkedin'),
                ];
                $state = function($col) {
                    if ($col->where('status','published')->count() > 0) return 'ok';
                    if ($col->whereIn('status',['queued','processing'])->count() > 0) return 'pending';
                    if ($col->where('status','failed')->count() > 0) return 'failed';
                    return 'none';
                };
            @endphp

            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-4 sm:p-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 space-y-2 sm:space-y-0">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        Publiceringstatus
                    </h3>
                    <span class="text-sm text-gray-600">Var har du delat texten?</span>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4">
                    @foreach([
                        'wordpress' => ['label' => 'Hemsida', 'icon' => 'M10 1.25A8.75 8.75 0 1018.75 10 8.76 8.76 0 0010 1.25zm0 1.5A7.25 7.25 0 1117.25 10 7.26 7.26 0 0110 2.75zM6.1 7.5l2.6 7.2.9-2.7-1.7-4.5H6.1zm4.2 0l2.6 7.2c1.5-.8 2.4-2.5 2.4-4.4 0-1.1-.4-2-.8-2.8h-1.9l-1.4 4.3-1-4.3H10.3z'],
                        'facebook' => ['label' => 'Facebook', 'icon' => 'M11 2h3a1 1 0 011 1v3h-2a1 1 0 00-1 1v2h3l-.5 3H12v7H9v-7H7V9h2V7a3 3 0 013-3z'],
                        'instagram' => ['label' => 'Instagram', 'icon' => 'M7 2h6a5 5 0 015 5v6a5 5 0 01-5 5H7a5 5 0 01-5-5V7a5 5 0 015-5zm0 2a3 3 0 00-3 3v6a3 3 0 003 3h6a3 3 0 003-3V7a3 3 0 00-3-3H7zm3 2.5A3.5 3.5 0 1110 13a3.5 3.5 0 010-7zM15 6.5a1 1 0 110 2 1 1 0 010-2z'],
                        'linkedin' => ['label' => 'LinkedIn', 'icon' => 'M4 3h12a1 1 0 011 1v12a1 1 0 01-1 1H4a1 1 0 01-1-1V4a1 1 0 011-1zm2 4h2v6H6V7zm4 0h2.2l.1 3.2h.1c.5-1.6 1.6-3.3 3.5-3.3 1.8 0 2.6 1.1 2.6 3.5V17h-2.2v-5.5c0-1.2-.4-2-1.5-2-1.2 0-1.8 1-2.1 2v5.5H10V7z'],
                        'shopify' => ['label' => 'Shopify', 'icon' => 'M6 2a2 2 0 00-2 2v1H3a1 1 0 00-1 .8L1 9a2 2 0 002 2h14a2 2 0 002-2l-2-3.2A1 1 0 0016 5h-1V4a2 2 0 00-2-2H6zm7 3H7V4a1 1 0 011-1h4a1 1 0 011 1v1z']
                    ] as $target => $info)
                        @php
                            $status = $state($byTarget[$target]);
                            $statusConfig = [
                                'ok' => ['color' => 'text-emerald-600 bg-emerald-50', 'label' => 'Publicerad', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                'pending' => ['color' => 'text-amber-600 bg-amber-50', 'label' => 'Pågår', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                                'failed' => ['color' => 'text-red-600 bg-red-50', 'label' => 'Misslyckad', 'icon' => 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                                'none' => ['color' => 'text-gray-400 bg-gray-50', 'label' => 'Inte publicerad', 'icon' => 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z']
                            ];
                            $config = $statusConfig[$status];
                        @endphp
                        <div class="text-center p-3 rounded-xl border border-gray-200 {{ $config['color'] }}">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 mx-auto mb-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="{{ $info['icon'] }}"/>
                            </svg>
                            <p class="text-xs font-medium mb-1">{{ $info['label'] }}</p>
                            <div class="flex items-center justify-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $config['icon'] }}"/>
                                </svg>
                                <p class="text-xs">{{ $config['label'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Flash Messages -->
            @if(session('success'))
                <div id="flash-success" class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-4">
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
                    </div>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-4">
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            @if($content->error)
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-4">
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-sm font-medium text-red-800">{{ $content->error }}</p>
                    </div>
                </div>
            @endif

            <!-- Innehåll: redigering eller förhandsvisning -->
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 overflow-hidden">
                @if(!empty($md))
                    <div class="p-4 sm:p-6 lg:p-8">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 pb-4 border-b border-gray-200 space-y-3 sm:space-y-0">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-lg sm:text-xl font-bold text-gray-900">Din text</h2>
                                    <p class="text-sm text-gray-600">Skapad med WebGrow</p>
                                </div>
                            </div>

                            <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                                @if($content->status === 'ready' && !$isLocked && !$isEditing)
                                    <button wire:click="startEditing"
                                            class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 text-white font-medium rounded-xl hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200 text-sm">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5h2m-1 0v14m-7-7h14"/>
                                        </svg>
                                        Redigera
                                    </button>
                                @endif

                                @if($content->status === 'ready' && !$isLocked && !$isEditing)
                                    <button wire:click="regenerate"
                                            wire:loading.attr="disabled"
                                            class="inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-medium rounded-xl hover:from-indigo-700 hover:to-purple-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200 disabled:opacity-50 text-sm">
                                        <svg wire:loading.remove class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                        </svg>
                                        <svg wire:loading class="animate-spin w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                        </svg>
                                        <span wire:loading.remove class="hidden sm:inline">Skapa om texten</span>
                                        <span wire:loading.remove class="sm:hidden">Skapa om</span>
                                        <span wire:loading class="hidden sm:inline">Skapar ny text...</span>
                                        <span wire:loading class="sm:hidden">Skapar...</span>
                                    </button>
                                @elseif($content->status === 'ready' && $isLocked)
                                    <div class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-500 rounded-xl text-sm">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                        </svg>
                                        <span class="hidden sm:inline">Texten är publicerad</span>
                                        <span class="sm:hidden">Publicerad</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        @if($isEditing && !$isLocked)
                            <div class="space-y-6">
                                <!-- Titel -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Titel</label>
                                    <input type="text"
                                           wire:model.defer="editTitle"
                                           class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                           placeholder="Titel (valfritt)">
                                </div>

                                <!-- Editor och förhandsvisning -->
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                    <div>
                                        <div class="flex items-center justify-between mb-2">
                                            <label class="block text-sm font-medium text-gray-700">Text (Markdown stöds)</label>
                                            <span class="text-xs text-gray-500 hidden sm:inline">Tips: Använd rubriker (##) och listor (-)</span>
                                        </div>
                                        <textarea
                                            wire:model.live.debounce.500ms="editBody"
                                            rows="12"
                                            class="w-full px-3 py-3 bg-white border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                            placeholder="Skriv eller redigera din text här..."></textarea>

                                        <div class="mt-4 flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                                            <button wire:click="saveEdits"
                                                    class="inline-flex items-center justify-center px-5 py-2.5 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                Spara
                                            </button>
                                            <button wire:click="cancelEditing"
                                                    class="inline-flex items-center justify-center px-4 py-2 bg-white border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50">
                                                Avbryt
                                            </button>
                                        </div>
                                    </div>

                                    <div class="lg:sticky lg:top-6">
                                        <div class="flex items-center justify-between mb-2">
                                            <p class="text-sm font-medium text-gray-700">Förhandsgranskning</p>
                                            <span class="text-xs text-gray-500 hidden sm:inline">Så här kommer texten att visas</span>
                                        </div>
                                        @php
                                            $previewMd = \Illuminate\Support\Str::of((string) ($editBody ?? ''))->markdown();
                                        @endphp
                                        <article class="prose prose-sm sm:prose-base max-w-none prose-indigo border border-gray-100 rounded-xl p-4 bg-gray-50/50 max-h-96 lg:max-h-[500px] overflow-y-auto">
                                            {!! $previewMd !!}
                                        </article>
                                    </div>
                                </div>
                            </div>
                        @else
                            <article class="prose prose-sm sm:prose-base lg:prose-lg max-w-none prose-indigo prose-headings:text-gray-900 prose-p:text-gray-700 prose-a:text-indigo-600 hover:prose-a:text-indigo-800 prose-strong:text-gray-900 prose-code:text-indigo-600 prose-pre:bg-gray-900 prose-pre:text-gray-100">
                                {!! $html !!}
                            </article>
                        @endif
                    </div>
                @else
                    <div class="p-8 sm:p-12 text-center">
                        <div class="w-16 h-16 bg-gradient-to-br from-indigo-100 to-purple-200 rounded-full flex items-center justify-center mx-auto mb-4 animate-pulse">
                            <svg class="w-8 h-8 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                            <span class="flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                </svg>
                                AI skapar din text...
                            </span>
                        </h3>
                        <p class="text-gray-600 text-sm sm:text-base">Detta tar vanligtvis 1–2 minuter. Du kan uppdatera sidan om en stund.</p>
                    </div>
                @endif
            </div>

            <!-- Image Selection - Mobilvänlig -->
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-4 sm:p-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 space-y-3 sm:space-y-0">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg sm:text-xl font-bold text-gray-900">Välj bild för sociala medier</h2>
                            <p class="text-sm text-gray-600">Instagram kräver alltid en bild. Övriga plattformar är valfritt.</p>
                        </div>
                    </div>
                    <div class="flex items-center text-sm">
                        @if($selectedImageAssetId)
                            <svg class="w-4 h-4 mr-1 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-emerald-600 font-medium">Bild vald</span>
                        @else
                            <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-gray-600">Ingen bild vald</span>
                        @endif
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row flex-wrap gap-3 items-stretch sm:items-center">
                    <button type="button"
                            class="flex-1 sm:flex-none inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-medium rounded-xl hover:from-emerald-700 hover:to-teal-700 transition-all duration-200"
                            x-data
                            @click="$dispatch('media-picker:open')">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="hidden sm:inline">Välj bild från bibliotek</span>
                        <span class="sm:hidden">Välj från bibliotek</span>
                    </button>

                    <button type="button"
                            class="flex-1 sm:flex-none inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-medium rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all duration-200"
                            wire:click="openGenModal">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        <span class="hidden sm:inline">Generera bild till texten</span>
                        <span class="sm:hidden">Generera bild</span>
                    </button>

                    @if($genQueued)
                        <div class="flex-1 sm:flex-none inline-flex items-center gap-2 px-3 py-2 bg-blue-50 text-blue-800 border border-blue-200 rounded-xl">
                            <svg class="w-4 h-4 animate-spin flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            <span class="text-sm hidden sm:inline">Bilden genereras – biblioteket uppdateras automatiskt.</span>
                            <span class="text-sm sm:hidden">Genererar bild...</span>
                        </div>
                    @endif
                </div>

                @if($selectedImageAssetId)
                    <div class="mt-4 flex items-center gap-3 p-3 bg-emerald-50 rounded-lg border border-emerald-200">
                        <img class="w-12 h-12 rounded-lg object-cover flex-shrink-0" src="{{ route('assets.thumb', $selectedImageAssetId) }}" alt="Vald bild">
                        <span class="text-sm text-emerald-800 font-medium flex items-center flex-1">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Bild vald
                        </span>
                        <button class="text-sm text-emerald-600 underline hover:no-underline flex-shrink-0" wire:click="$set('selectedImageAssetId', 0)">Ta bort</button>
                    </div>
                @endif
            </div>

            <!-- Publicering - Mobilvänligt grid -->
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                @if($currentProvider === 'wordpress')
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-4 sm:p-6">
                        <div class="flex items-center space-x-3 mb-6">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M21.469 6.825c.84 1.537 1.318 3.3 1.318 5.175 0 3.979-2.156 7.456-5.363 9.325l3.295-9.527c.615-1.54.82-2.771.82-3.864 0-.405-.026-.78-.07-1.11m-7.981.105c.647-.03 1.232-.105 1.232-.105.582-.075.514-.93-.067-.899 0 0-1.755.135-2.88.135-1.064 0-2.85-.15-2.85-.15-.584-.03-.661.854-.075.884 0 0 .54.061 1.125.09l1.68 4.605-2.37 7.08L5.354 6.9c.649-.03 1.234-.1 1.234-.1.585-.075.516-.93-.065-.896 0 0-1.746.138-2.874.138-.2 0-.438-.008-.69-.015C4.911 3.15 8.235 1.215 12 1.215c2.809 0 5.365 1.072 7.286 2.833-.046-.003-.091-.009-.141-.009-1.06 0-1.812.923-1.812 1.914 0 .89.513 1.643 1.06 2.531.411.72.89 1.643.89 2.977 0 .915-.354 1.994-.821 3.479l-1.075 3.585-3.9-11.61.001.014z"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-lg sm:text-xl font-bold text-gray-900 flex items-center">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Publicera på hemsidan
                                </h2>
                                <p class="text-sm text-gray-600">Lägg upp texten direkt på din WordPress-sajt</p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Vilken hemsida?</label>
                                <select wire:model="publishSiteId" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                                    @foreach($sites as $s)
                                        <option value="{{ $s->id }}">{{ $s->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                    <select wire:model="publishStatus" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" @if($publishQuotaReached) disabled @endif>
                                        <option value="draft">Utkast (syns inte för besökare)</option>
                                        <option value="publish">Publicera direkt</option>
                                        <option value="future">Schemalägg för senare</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Publiceringsdatum</label>
                                    <input type="datetime-local" wire:model="publishAt" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" @if($publishQuotaReached) disabled @endif>
                                </div>
                            </div>

                            <div class="flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-3 pt-4 border-t border-gray-200">
                                @if($mdReady && $publishSiteId)
                                    <button wire:click="quickDraft" class="inline-flex items-center justify-center px-4 py-2 bg-blue-100 text-blue-800 font-medium rounded-lg hover:bg-blue-200 transition-colors duration-200 text-sm {{ $publishQuotaReached ? 'opacity-50 cursor-not-allowed' : '' }}" @if($publishQuotaReached) disabled @endif>
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                        </svg>
                                        <span class="hidden sm:inline">Snabbt som utkast</span>
                                        <span class="sm:hidden">Snabbt som utkast</span>
                                    </button>
                                @else
                                    <div></div>
                                @endif

                                <button wire:click="publish" class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-indigo-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 shadow-lg hover:shadow-xl {{ (!$mdReady || $publishQuotaReached) ? 'opacity-50 cursor-not-allowed' : '' }}"
                                        @if(!$mdReady || $publishQuotaReached) disabled @endif>
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                    </svg>
                                    <span class="hidden sm:inline">Publicera på hemsida</span>
                                    <span class="sm:hidden">Publicera</span>
                                </button>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-4 sm:p-6">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-pink-500 to-purple-600 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg sm:text-xl font-bold text-gray-900 flex items-center">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2z"/>
                                </svg>
                                Dela på sociala medier
                            </h2>
                            <p class="text-sm text-gray-600">Publicera på Facebook eller Instagram</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Vilken plattform?</label>
                            <select wire:model="socialTarget" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-all duration-200" @if($publishQuotaReached) disabled @endif>
                                <option value="facebook">Facebook</option>
                                <option value="instagram">Instagram (kräver bild)</option>
                            </select>
                            @error('socialTarget')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">När ska det publiceras?</label>
                            <input type="datetime-local" wire:model="socialScheduleAt" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-all duration-200" @if($publishQuotaReached) disabled @endif>
                            <p class="text-xs text-gray-500 mt-1">Lämna tomt för omedelbar publicering</p>
                            @error('socialScheduleAt')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button wire:click="publishSocialNow"
                                class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-pink-600 to-purple-600 text-white font-semibold rounded-xl hover:from-pink-700 hover:to-purple-700 focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 transition-all duration-200 shadow-lg hover:shadow-xl {{ (empty($md) || $publishQuotaReached) ? 'opacity-50 cursor-not-allowed' : '' }}"
                                @if(empty($md) || $publishQuotaReached) disabled @endif>
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            Publicera nu
                        </button>
                    </div>
                </div>
            </div>

            <!-- LinkedIn - Mobilvänlig -->
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-4 sm:p-6">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-sky-600 to-blue-700 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M4.98 3.5C4.98 4.88 3.86 6 2.5 6S0 4.88 0 3.5 1.12 1 2.5 1s2.48 1.12 2.48 2.5zM.5 8h4V24h-4V8zm7.5 0h3.8v2.2h.1c.5-1 1.7-2.2 3.6-2.2 3.8 0 4.5 2.5 4.5 5.8V24h-4V14.7c0-2.2 0-5-3-5s-3.4 2.3-3.4 4.9V24H8V8z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"/>
                            </svg>
                            Dela på LinkedIn
                        </h3>
                        <p class="text-sm text-gray-600">Perfekt för professionella inlägg och nätverkande</p>
                    </div>
                </div>

                <div class="space-y-3">
                    <input type="text" wire:model.defer="liQuickText" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition-all duration-200" placeholder="Egen text för LinkedIn (lämna tomt för AI-texten)" @if($publishQuotaReached) disabled @endif>

                    <div class="flex justify-end">
                        <button wire:click="queueLinkedInQuick" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-sky-600 to-blue-700 text-white font-semibold rounded-xl hover:from-sky-700 hover:to-blue-800 focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 transition-all duration-200 shadow-lg hover:shadow-xl {{ (empty($md) || $publishQuotaReached) ? 'opacity-50 cursor-not-allowed' : '' }}"
                                @if(empty($md) || $publishQuotaReached) disabled @endif>
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            Publicera på LinkedIn
                        </button>
                    </div>
                </div>
            </div>

            <livewire:media-picker wire:model.live="selectedImageAssetId" />
        </div>
    </div>

    <!-- Generation Modal - Mobilvänlig -->
    <div x-data="{ genShow: @entangle('genShow') }" x-show="genShow" x-transition class="fixed inset-0 z-50 px-4" style="display:none;">
        <div class="absolute inset-0 bg-black/50" wire:click="$set('genShow', false)"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
                <div class="px-5 py-4 border-b">
                    <h3 class="text-lg font-bold text-gray-900">Generera bild till texten</h3>
                </div>

                <div class="p-5 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Format</label>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                            <label class="flex items-center gap-2 px-3 py-2 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" class="text-indigo-600" wire:model="genFormat" value="square">
                                <span class="text-sm">Kvadrat</span>
                            </label>
                            <label class="flex items-center gap-2 px-3 py-2 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" class="text-indigo-600" wire:model="genFormat" value="portrait">
                                <span class="text-sm">Porträtt</span>
                            </label>
                            <label class="flex items-center gap-2 px-3 py-2 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" class="text-indigo-600" wire:model="genFormat" value="landscape">
                                <span class="text-sm">Landskap</span>
                            </label>
                        </div>
                        @error('genFormat') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="p-3 bg-amber-50 border border-amber-200 rounded-lg text-amber-800 text-sm">
                        Är du säker? Du har {{ $genRemaining ?? '—' }} genereringar kvar. Efter denna har du {{ $genAfter ?? '—' }} kvar.
                    </div>

                    @if($genError)
                        <div class="p-3 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm">
                            {{ $genError }}
                        </div>
                    @endif
                </div>

                <div class="px-5 py-4 border-t flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-2">
                    <button class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50"
                            wire:click="$set('genShow', false)" @disabled($genBusy)>Avbryt</button>
                    <button class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 disabled:opacity-50"
                            wire:click="confirmGenerateImageForContent"
                            wire:loading.attr="disabled"
                            wire:target="confirmGenerateImageForContent">
                        <span wire:loading.remove wire:target="confirmGenerateImageForContent">Ja, generera bild</span>
                        <span wire:loading wire:target="confirmGenerateImageForContent">Köar...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        (function() {
            function scrollToFlash() {
                const el = document.getElementById('flash-success');
                if (!el) return;
                el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                el.classList.add('ring-2','ring-emerald-300');
                setTimeout(() => el.classList.remove('ring-2','ring-emerald-300'), 1200);
            }
            document.addEventListener('DOMContentLoaded', scrollToFlash);
            const observer = new MutationObserver(() => {
                if (document.getElementById('flash-success')) { scrollToFlash(); }
            });
            observer.observe(document.body, { childList: true, subtree: true });
        })();

        document.addEventListener('livewire:initialized', () => {
            let timer = null, polls = 0, maxPolls = 10;

            window.Livewire.on('ai-image-queued', () => {
                if (timer) { clearInterval(timer); timer = null; }
                polls = 0;
                timer = setInterval(() => {
                    polls++;
                    // Låt backend uppdatera ev. lista i bakgrunden (om komponenten exponerar en metod)
                    if (@this.loadMediaLibrary) { @this.call('loadMediaLibrary'); }
                    // När vi polllat ett tag, sluta
                    if (polls >= maxPolls) {
                        clearInterval(timer); timer = null;
                    }
                }, 6000);
                // Fallback stop efter 60s
                setTimeout(() => { if (timer) { clearInterval(timer); timer = null; } }, 60000);
            });
        });
    </script>
@endpush
