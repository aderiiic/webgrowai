<div>
    <div class="max-w-7xl mx-auto space-y-8">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                <svg class="w-8 h-8 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                SEO Audit – Detaljer
            </h1>
            <a href="{{ route('seo.audit.history') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 hover:border-gray-400 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200 shadow-sm hover:shadow-md">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Till historik
            </a>
        </div>

        <!-- Metrics overview -->
        <div class="grid grid-cols-2 md:grid-cols-6 gap-4">
            <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl border border-green-200/50 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm font-medium text-green-700">Performance</div>
                        <div class="text-2xl font-bold text-green-900">{{ $audit->lighthouse_performance ?? '—' }}</div>
                    </div>
                    <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl border border-blue-200/50 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm font-medium text-blue-700">Accessibility</div>
                        <div class="text-2xl font-bold text-blue-900">{{ $audit->lighthouse_accessibility ?? '—' }}</div>
                    </div>
                    <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl border border-purple-200/50 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm font-medium text-purple-700">Best Practices</div>
                        <div class="text-2xl font-bold text-purple-900">{{ $audit->lighthouse_best_practices ?? '—' }}</div>
                    </div>
                    <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-orange-50 to-red-50 rounded-xl border border-orange-200/50 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm font-medium text-orange-700">SEO Score</div>
                        <div class="text-2xl font-bold text-orange-900">{{ $audit->lighthouse_seo ?? '—' }}</div>
                    </div>
                    <div class="w-10 h-10 bg-orange-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-yellow-50 to-amber-50 rounded-xl border border-yellow-200/50 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm font-medium text-yellow-700">Titelproblem</div>
                        <div class="text-2xl font-bold text-yellow-900">{{ $audit->title_issues }}</div>
                    </div>
                    <div class="w-10 h-10 bg-yellow-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-red-50 to-pink-50 rounded-xl border border-red-200/50 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm font-medium text-red-700">Meta-problem</div>
                        <div class="text-2xl font-bold text-red-900">{{ $audit->meta_issues }}</div>
                    </div>
                    <div class="w-10 h-10 bg-red-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100/50 p-6">
            <div class="flex items-center space-x-4">
                <label class="text-sm font-medium text-gray-700">Filtrera resultat:</label>
                <select wire:model.live="filter" class="px-4 py-2 bg-white border border-gray-300 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200">
                    <option value="all">Alla problem</option>
                    <option value="title">Titelproblem</option>
                    <option value="meta">Meta-problem</option>
                    <option value="lighthouse">Lighthouse-problem</option>
                </select>
            </div>
        </div>

        <!-- Audit items -->
        <div class="space-y-4">
            @foreach($items as $it)
                @php
                    $typeColors = [
                        'title' => ['bg' => 'from-yellow-50 to-amber-50', 'border' => 'border-yellow-200/50', 'text' => 'text-yellow-800', 'icon' => 'bg-yellow-500'],
                        'meta' => ['bg' => 'from-red-50 to-pink-50', 'border' => 'border-red-200/50', 'text' => 'text-red-800', 'icon' => 'bg-red-500'],
                        'lighthouse' => ['bg' => 'from-blue-50 to-indigo-50', 'border' => 'border-blue-200/50', 'text' => 'text-blue-800', 'icon' => 'bg-blue-500'],
                    ];
                    $colors = $typeColors[$it->type] ?? ['bg' => 'from-gray-50 to-gray-100', 'border' => 'border-gray-200/50', 'text' => 'text-gray-800', 'icon' => 'bg-gray-500'];
                @endphp

                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100/50 p-6 hover:shadow-2xl transition-all duration-200">
                    <div class="flex items-start justify-between">
                        <div class="flex-1 min-w-0">
                            <!-- Type badge -->
                            <div class="flex items-center space-x-3 mb-3">
                                <div class="inline-flex items-center px-3 py-1 bg-gradient-to-r {{ $colors['bg'] }} {{ $colors['border'] }} border rounded-full">
                                    <div class="w-4 h-4 {{ $colors['icon'] }} rounded-full flex items-center justify-center mr-2">
                                        @if($it->type === 'title')
                                            <svg class="w-2 h-2 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a1 1 0 011-1h14a1 1 0 110 2H3a1 1 0 01-1-1zM2 15a1 1 0 011-1h14a1 1 0 110 2H3a1 1 0 01-1-1z"/>
                                            </svg>
                                        @elseif($it->type === 'meta')
                                            <svg class="w-2 h-2 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"/>
                                            </svg>
                                        @else
                                            <svg class="w-2 h-2 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        @endif
                                    </div>
                                    <span class="text-xs font-medium {{ $colors['text'] }} uppercase">{{ $it->type }}</span>
                                </div>
                            </div>

                            <!-- URL -->
                            <div class="mb-3">
                                <a href="{{ $it->page_url }}" target="_blank" rel="noopener" class="flex items-center text-indigo-600 hover:text-indigo-800 hover:underline">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                    <span class="truncate">{{ $it->page_url }}</span>
                                </a>
                            </div>

                            <!-- Message -->
                            <div class="mb-4 p-3 bg-gray-50 rounded-xl">
                                <p class="text-gray-800">{{ $it->message }}</p>
                            </div>

                            <!-- Data (if present) -->
                            @if($it->data)
                                <details class="mb-4">
                                    <summary class="cursor-pointer text-sm font-medium text-gray-700 hover:text-gray-900 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        Tekniska detaljer
                                    </summary>
                                    <div class="mt-2 p-3 bg-gray-100 rounded-xl">
                                        <pre class="text-xs text-gray-700 overflow-auto">{{ json_encode($it->data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}</pre>
                                    </div>
                                </details>
                            @endif
                        </div>

                        <!-- Action button -->
                        @php $postId = $it->data['post_id'] ?? null; @endphp
                        @if($postId)
                            <div class="ml-6 flex-shrink-0">
                                <a href="{{ route('wp.posts.meta', [$it->audit->site_id, 'postId' => $postId]) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-semibold rounded-xl hover:from-emerald-700 hover:to-teal-700 focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transform hover:scale-[1.02] transition-all duration-200 shadow-lg hover:shadow-xl">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Fixa i WordPress
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($items->hasPages())
            <div class="flex justify-center">
                {{ $items->links() }}
            </div>
        @endif
    </div>
</div>
