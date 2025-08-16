<div>
    <div class="max-w-7xl mx-auto space-y-8">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                <svg class="w-8 h-8 mr-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                </svg>
                AI Innehåll
            </h1>
            <a href="{{ route('ai.compose') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl hover:from-indigo-700 hover:to-purple-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transform hover:scale-[1.02] transition-all duration-200 shadow-lg hover:shadow-xl">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Nytt innehåll
            </a>
        </div>

        <!-- Stats cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-2xl border border-indigo-200/50 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm font-medium text-indigo-700">Genereringar denna månad</div>
                        <div class="text-3xl font-bold text-indigo-900">{{ $monthGenerateTotal }}</div>
                        <div class="text-sm text-indigo-600">{{ now()->format('F Y') }}</div>
                    </div>
                    <div class="w-16 h-16 bg-indigo-500 rounded-2xl flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-emerald-50 to-teal-50 rounded-2xl border border-emerald-200/50 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm font-medium text-emerald-700">Publicerade till WordPress</div>
                        <div class="text-3xl font-bold text-emerald-900">{{ $monthPublishTotal }}</div>
                        <div class="text-sm text-emerald-600">Denna månad</div>
                    </div>
                    <div class="w-16 h-16 bg-emerald-500 rounded-2xl flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M21.469 6.825c.84 1.537 1.318 3.3 1.318 5.175 0 3.979-2.156 7.456-5.363 9.325l3.295-9.527c.615-1.54.82-2.771.82-3.864 0-.405-.026-.78-.07-1.11m-7.981.105c.647-.03 1.232-.105 1.232-.105.582-.075.514-.93-.067-.899 0 0-1.755.135-2.88.135-1.064 0-2.85-.15-2.85-.15-.584-.03-.661.854-.075.884 0 0 .54.061 1.125.09l1.68 4.605-2.37 7.08L5.354 6.9c.649-.03 1.234-.1 1.234-.1.585-.075.516-.93-.065-.896 0 0-1.746.138-2.874.138-.2 0-.438-.008-.69-.015C4.911 3.15 8.235 1.215 12 1.215c2.809 0 5.365 1.072 7.286 2.833-.046-.003-.091-.009-.141-.009-1.06 0-1.812.923-1.812 1.914 0 .89.513 1.643 1.06 2.531.411.72.89 1.643.89 2.977 0 .915-.354 1.994-.821 3.479l-1.075 3.585-3.9-11.61.001.014z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @forelse($items as $c)
                @php
                    $statusColors = [
                        'completed' => ['bg' => 'from-green-50 to-emerald-50', 'border' => 'border-green-200/50', 'text' => 'text-green-800', 'icon' => 'bg-green-500'],
                        'processing' => ['bg' => 'from-blue-50 to-indigo-50', 'border' => 'border-blue-200/50', 'text' => 'text-blue-800', 'icon' => 'bg-blue-500'],
                        'draft' => ['bg' => 'from-yellow-50 to-amber-50', 'border' => 'border-yellow-200/50', 'text' => 'text-yellow-800', 'icon' => 'bg-yellow-500'],
                        'error' => ['bg' => 'from-red-50 to-pink-50', 'border' => 'border-red-200/50', 'text' => 'text-red-800', 'icon' => 'bg-red-500'],
                    ];
                    $colors = $statusColors[strtolower($c->status)] ?? ['bg' => 'from-gray-50 to-slate-50', 'border' => 'border-gray-200/50', 'text' => 'text-gray-800', 'icon' => 'bg-gray-500'];
                @endphp

                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100/50 p-6 hover:shadow-2xl transition-all duration-200">
                    <!-- Header with status -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="inline-flex items-center px-3 py-1 bg-gradient-to-r {{ $colors['bg'] }} {{ $colors['border'] }} border rounded-full">
                                <div class="w-3 h-3 {{ $colors['icon'] }} rounded-full mr-2"></div>
                                <span class="text-xs font-medium {{ $colors['text'] }} uppercase">{{ $c->status }}</span>
                            </div>

                            @if($c->provider)
                                <div class="inline-flex items-center px-2 py-1 bg-gradient-to-r from-purple-50 to-pink-50 border border-purple-200/50 rounded-full">
                                    <svg class="w-3 h-3 mr-1 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                    </svg>
                                    <span class="text-xs font-medium text-purple-700">{{ $c->provider }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Content title -->
                    <div class="mb-4">
                        <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2">
                            {{ $c->title ?: '(Ingen titel ännu)' }}
                        </h3>
                    </div>

                    <!-- Meta information -->
                    <div class="mb-6 p-4 bg-gradient-to-r from-gray-50 to-white rounded-xl border border-gray-200/50">
                        <div class="grid grid-cols-1 gap-2 text-sm">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Mall:</span>
                                <span class="font-medium text-gray-900">#{{ $c->template_id }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Skapad:</span>
                                <span class="font-medium text-gray-900">{{ $c->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Action button -->
                    <div class="flex justify-end">
                        <a href="{{ route('ai.detail', $c->id) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl hover:from-indigo-700 hover:to-purple-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transform hover:scale-[1.02] transition-all duration-200 shadow-lg hover:shadow-xl">
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
                    <div class="text-center py-16">
                        <div class="w-24 h-24 bg-gradient-to-br from-indigo-100 to-purple-200 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-12 h-12 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">Inget AI-innehåll ännu</h3>
                        <p class="text-gray-600 mb-6">Börja skapa AI-genererat innehåll för dina sajter och sociala kanaler.</p>
                        <a href="{{ route('ai.compose') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 shadow-lg">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Skapa ditt första innehåll
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($items->hasPages())
            <div class="flex justify-center">
                {{ $items->links() }}
            </div>
        @endif
    </div>
</div>
