<div>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 px-4 sm:px-6 lg:px-8 py-6">
        <div class="max-w-7xl mx-auto space-y-6">

            <!-- Header -->
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
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                        <a href="{{ route('planner.index') }}" class="inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 shadow-lg text-sm sm:text-base">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2z"/>
                            </svg>
                            Planera & Publicera
                        </a>
                        <a href="{{ route('ai.compose') }}" class="inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-semibold rounded-xl hover:from-emerald-700 hover:to-teal-700 transition-all duration-200 shadow-lg text-sm sm:text-base">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Skapa text
                        </a>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-4 sm:p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="text-sm font-medium text-indigo-700 mb-1">Genereringar denna månad</div>
                            <div class="text-2xl sm:text-3xl font-bold text-indigo-900 mb-1">{{ $monthGenerateTotal }}</div>
                            <div class="text-xs sm:text-sm text-indigo-600">{{ now()->format('F Y') }}</div>
                        </div>
                        <div class="w-12 h-12 sm:w-16 sm:h-16 bg-indigo-500 rounded-2xl flex items-center justify-center">
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
                        <div class="w-12 h-12 sm:w-16 sm:h-16 bg-emerald-500 rounded-2xl flex items-center justify-center">
                            <svg class="w-6 h-6 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M12 6v12m6-6H6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </div>

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

            <!-- Filters -->
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-4">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <!-- Type Filter -->
                    <div class="flex flex-wrap gap-2">
                        <button wire:click="$set('filterType', 'all')" class="px-3 py-1.5 rounded-lg text-sm font-medium transition-all {{ $filterType === 'all' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            Alla
                        </button>
                        <button wire:click="$set('filterType', 'social')" class="px-3 py-1.5 rounded-lg text-sm font-medium transition-all {{ $filterType === 'social' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            Social
                        </button>
                        <button wire:click="$set('filterType', 'blog')" class="px-3 py-1.5 rounded-lg text-sm font-medium transition-all {{ $filterType === 'blog' ? 'bg-emerald-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            Blogg
                        </button>
                        <button wire:click="$set('filterType', 'seo')" class="px-3 py-1.5 rounded-lg text-sm font-medium transition-all {{ $filterType === 'seo' ? 'bg-purple-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            SEO
                        </button>
                        <button wire:click="$set('filterType', 'product')" class="px-3 py-1.5 rounded-lg text-sm font-medium transition-all {{ $filterType === 'product' ? 'bg-amber-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            Produkt
                        </button>
                        <button wire:click="$set('filterType', 'multi')" class="px-3 py-1.5 rounded-lg text-sm font-medium transition-all {{ $filterType === 'multi' ? 'bg-orange-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            Multi
                        </button>
                    </div>

                    <!-- Archive Toggle -->
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model.live="showArchived" class="sr-only peer">
                        <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                        <span class="ms-3 text-sm font-medium text-gray-700">Visa arkiverade</span>
                    </label>
                </div>
            </div>

            <!-- Bulk Generations (Multi-texter) -->
            @if($bulkGenerations->isNotEmpty() && !$showArchived)
                <div class="space-y-4">
                    <h2 class="text-xl font-bold text-gray-900 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        Massgenererade texter
                    </h2>

                    @foreach($bulkGenerations as $bulk)
                        <a href="{{ route('ai.bulk.detail', $bulk->id) }}" class="block bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-6 hover:shadow-xl transition-all duration-200">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-orange-100 text-orange-700 border border-orange-200 text-sm font-medium">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                            </svg>
                                            Multi
                                        </span>
                                        <span class="text-sm text-gray-600">{{ $bulk->total_count }} texter</span>
                                        @if($bulk->status === 'processing')
                                            <span class="inline-flex items-center text-blue-600 text-sm">
                                                <span class="inline-block animate-pulse mr-1">●</span> Processar...
                                            </span>
                                        @elseif($bulk->status === 'done')
                                            <span class="text-green-600 text-sm">✓ Klar</span>
                                        @endif
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $bulk->template_text }}</h3>
                                    <p class="text-sm text-gray-600">Skapad {{ $bulk->created_at->diffForHumans() }}</p>
                                </div>
                            </div>

                            <!-- Progress Bar -->
                            <div class="mb-4">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm text-gray-600">Framsteg:</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $bulk->completed_count }} / {{ $bulk->total_count }}</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-orange-500 to-red-600 h-2 rounded-full transition-all duration-500" style="width: {{ $bulk->getProgressPercentage() }}%"></div>
                                </div>
                            </div>

                            <!-- Preview of first texts -->
                            @if($bulk->contents->isNotEmpty())
                                <div class="space-y-2">
                                    <p class="text-xs font-medium text-gray-600 uppercase">Exempel:</p>
                                    @foreach($bulk->contents->take(3) as $content)
                                        <div class="text-sm text-gray-700 truncate">• {{ $content->title }}</div>
                                    @endforeach
                                    @if($bulk->total_count > 3)
                                        <div class="text-sm text-indigo-600 font-medium">+ {{ $bulk->total_count - 3 }} till →</div>
                                    @endif
                                </div>
                            @endif
                        </a>
                    @endforeach
                </div>
            @endif

            <!-- Regular Content Grid -->
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
                            'failed' => 'Fel!'
                        ];

                        $typeDisplay = $c->getTypeDisplay();
                    @endphp

                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-4 sm:p-6 hover:shadow-xl transition-all duration-200">
                        <!-- Header with status and type badges -->
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center space-x-2 sm:space-x-3 flex-wrap gap-2">
                                <!-- Status Badge -->
                                <div class="inline-flex items-center px-2 sm:px-3 py-1 bg-gradient-to-r {{ $colors['bg'] }} {{ $colors['border'] }} border rounded-full">
                                    <div class="w-2 h-2 sm:w-3 sm:h-3 {{ $colors['icon'] }} rounded-full mr-1.5 sm:mr-2"></div>
                                    <span class="text-xs font-medium {{ $colors['text'] }} uppercase">{{ $statuses[$c->status] }}</span>
                                </div>

                                <!-- Type Badge -->
                                <div class="inline-flex items-center px-2 sm:px-3 py-1 rounded-full {{ $typeDisplay['bg'] }} {{ $typeDisplay['text'] }} border {{ $typeDisplay['border'] }} text-xs font-medium">
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $typeDisplay['icon'] }}"/>
                                    </svg>
                                    {{ $typeDisplay['label'] }}
                                </div>
                            </div>

                            <!-- Archive Button -->
                            <button
                                wire:click="toggleArchive({{ $c->id }})"
                                class="p-2 rounded-lg hover:bg-gray-100 transition-colors"
                                title="{{ $c->archived ? 'Avarkivera' : 'Arkivera' }}"
                            >
                                @if($c->archived)
                                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                                    </svg>
                                @endif
                            </button>
                        </div>

                        <!-- Content title -->
                        <div class="mb-4">
                            <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-2 line-clamp-2">
                                {{ $c->title ?: '(Ingen titel ännu)' }}
                            </h3>
                        </div>

                        <!-- Meta information -->
                        <div class="mb-4 sm:mb-6 p-3 sm:p-4 bg-gradient-to-r from-gray-50 to-white rounded-xl border border-gray-200/50">
                            <div class="grid grid-cols-1 gap-2 text-sm">
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600 text-xs sm:text-sm">Skapad:</span>
                                    <span class="font-medium text-gray-900 text-xs sm:text-sm">{{ $c->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Action button -->
                        <div class="flex justify-end">
                            <a href="{{ route('ai.detail', $c->id) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 shadow-lg text-sm">
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
                            <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2 sm:mb-3">
                                @if($showArchived)
                                    Inga arkiverade texter
                                @else
                                    Inget AI-innehåll ännu
                                @endif
                            </h3>
                            <p class="text-gray-600 mb-4 sm:mb-6 text-sm sm:text-base">
                                @if($showArchived)
                                    Du har inga arkiverade texter. Arkivera texter du inte behöver se i huvudlistan.
                                @else
                                    Börja skapa AI-genererat innehåll för dina sajter och sociala kanaler.
                                @endif
                            </p>
                            @if(!$showArchived)
                                <a href="{{ route('ai.compose') }}" class="inline-flex items-center px-4 sm:px-6 py-2 sm:py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 shadow-lg text-sm sm:text-base">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                    Skapa ditt första innehåll
                                </a>
                            @endif
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
</div>
