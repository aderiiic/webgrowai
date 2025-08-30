
<div>
    <div class="max-w-7xl mx-auto space-y-8">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                <svg class="w-8 h-8 mr-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                Publiceringar
                @if($activeSiteId)
                    <span class="ml-2 text-lg text-gray-600">(Sajt #{{ $activeSiteId }})</span>
                @endif
            </h1>
        </div>

        <!-- Site filter info -->
        @if($activeSiteId)
            <div class="p-4 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl">
                <div class="flex items-center space-x-3">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm font-medium text-blue-800">
                        Visar endast publiceringar för vald sajt. Byt sajt i topbar för att se andra publiceringar.
                    </p>
                </div>
            </div>
        @else
            <div class="p-4 bg-gradient-to-r from-amber-50 to-yellow-50 border border-amber-200 rounded-xl">
                <div class="flex items-center space-x-3">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.314 15.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                    <p class="text-sm font-medium text-amber-800">
                        Ingen specifik sajt vald. Visar publiceringar för alla sajter. Välj en sajt i topbar för att filtrera.
                    </p>
                </div>
            </div>
        @endif

        <!-- Resten av innehållet förblir oförändrat -->
        <!-- Filters -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100/50 p-6">
            <div class="flex flex-wrap items-center gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kanal</label>
                    <select wire:model.live="target" class="px-4 py-2 bg-white border border-gray-300 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200">
                        <option value="">Alla kanaler</option>
                        <option value="wp">WordPress</option>
                        <option value="facebook">Facebook</option>
                        <option value="instagram">Instagram</option>
                        <option value="linkedin">LinkedIn</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select wire:model.live="status" class="px-4 py-2 bg-white border border-gray-300 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200">
                        <option value="">Alla statusar</option>
                        <option value="queued">Köad</option>
                        <option value="processing">Pågår</option>
                        <option value="published">Publicerad</option>
                        <option value="failed">Misslyckad</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Success notification -->
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

        <!-- Publications list -->
        <div class="space-y-4">
            @forelse($pubs as $p)
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100/50 p-6 hover:shadow-2xl transition-all duration-200">
                    <div class="flex items-start justify-between">
                        <div class="flex-1 min-w-0 flex gap-4">
                            {{-- Liten thumbnail om bild användes --}}
                            @php
                                $thumbImg = null;
                                if (!empty($p->payload['image_asset_id'])) {
                                    $thumbImg = route('assets.thumb', $p->payload['image_asset_id']);
                                } elseif (!empty($p->payload['image_url'])) {
                                    $thumbImg = $p->payload['image_url'];
                                }
                            @endphp
                            @if($thumbImg)
                                <div class="w-16 h-16 flex-none rounded-lg overflow-hidden border">
                                    <img src="{{ $thumbImg }}" alt="thumb" class="w-full h-full object-cover">
                                </div>
                            @endif

                            <div class="flex-1">
                                <!-- Status and target -->
                                <div class="flex items-center space-x-3 mb-3">
                                    <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                        @if($p->target === 'wp') bg-blue-100 text-blue-800 border border-blue-200
                                        @elseif($p->target === 'facebook') bg-blue-100 text-blue-800 border border-blue-200
                                        @elseif($p->target === 'instagram') bg-pink-100 text-pink-800 border border-pink-200
                                        @elseif($p->target === 'linkedin') bg-sky-100 text-sky-800 border border-sky-200
                                        @else bg-gray-100 text-gray-800 border border-gray-200 @endif">
                                        {{ strtoupper($p->target) }}
                                    </div>

                                    <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                        @if($p->status === 'published') bg-emerald-100 text-emerald-800 border border-emerald-200
                                        @elseif($p->status === 'processing') bg-blue-100 text-blue-800 border border-blue-200
                                        @elseif($p->status === 'queued') bg-yellow-100 text-yellow-800 border border-yellow-200
                                        @elseif($p->status === 'failed') bg-red-100 text-red-800 border border-red-200
                                        @else bg-gray-100 text-gray-800 border border-gray-200 @endif">
                                        {{ strtoupper($p->status) }}
                                    </div>
                                </div>

                                <!-- Content info -->
                                <div class="mb-3">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-1">
                                        {{ $p->content?->title ?? '(Utan titel)' }}
                                    </h3>
                                    <div class="flex items-center text-sm text-gray-600 space-x-4">
                                        @if($p->scheduled_at)
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                Schemalagd: {{ $p->scheduled_at->format('Y-m-d H:i') }}
                                            </div>
                                        @else
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                Skapad: {{ $p->created_at->format('Y-m-d H:i') }}
                                            </div>
                                        @endif
                                        @if($p->external_id)
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                                </svg>
                                                Extern ID: {{ $p->external_id }}
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Message / payload -->
                                @if($p->message || $p->payload)
                                    <div class="p-3 rounded-xl text-sm
                                        @if($p->status === 'failed') bg-red-50 text-red-700 border border-red-200
                                        @else bg-gray-50 text-gray-700 border border-gray-200 @endif">
                                        @if($p->message)
                                            <div class="mb-1">{{ $p->message }}</div>
                                        @endif
                                        @if($p->payload && isset($p->payload['text']))
                                            <div class="text-gray-600 line-clamp-2">
                                                <span class="font-medium">Text:</span> {{ $p->payload['text'] }}
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center space-x-3 ml-6">
                            @if($p->status === 'failed')
                                <button wire:click="retry({{ $p->id }})" class="inline-flex items-center px-3 py-2 bg-gradient-to-r from-orange-600 to-red-600 text-white font-semibold rounded-xl hover:from-orange-700 hover:to-red-700 focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition-all duration-200 shadow-lg hover:shadow-xl text-sm">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    Kör om
                                </button>
                            @endif

                            @if($p->status === 'queued')
                                <button
                                    onclick="if(!confirm('Avbryt denna publicering?')) return false;"
                                    wire:click="cancel({{ $p->id }})"
                                    class="inline-flex items-center px-3 py-2 bg-gray-100 text-gray-800 font-semibold rounded-xl hover:bg-gray-200 focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 transition-all duration-200 shadow-sm text-sm">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Avbryt
                                </button>
                            @endif

                            @if($p->payload)
                                <details class="relative">
                                    <summary class="cursor-pointer inline-flex items-center px-3 py-2 bg-white border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 text-sm">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        Payload
                                    </summary>
                                    <div class="absolute right-0 top-full mt-2 w-96 max-w-sm bg-white border border-gray-200 rounded-xl shadow-xl z-10">
                                        <pre class="p-4 text-xs bg-gray-50 rounded-xl overflow-auto max-h-64">{{ json_encode($p->payload, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}</pre>
                                    </div>
                                </details>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <div class="w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">
                        @if($activeSiteId)
                            Inga publiceringar för vald sajt
                        @else
                            Inga publiceringar ännu
                        @endif
                    </h3>
                    <p class="text-gray-600">
                        @if($activeSiteId)
                            Skapa och publicera innehåll för denna sajt för att se det här.
                        @else
                            Skapa och publicera innehåll för att se det här.
                        @endif
                    </p>
                </div>
            @endforelse
        </div>

        @if($pubs->hasPages())
            <div class="space-y-2">
                <div class="flex justify-center">
                    {{ $pubs->links('pagination::simple-tailwind') }}
                </div>
                <p class="text-center text-xs text-gray-500">
                    Visar {{ $pubs->firstItem() }}–{{ $pubs->lastItem() }} av {{ $pubs->total() }} resultat
                </p>
            </div>
        @endif
    </div>
</div>
