<div>
    <div class="max-w-6xl mx-auto space-y-8">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                <svg class="w-8 h-8 mr-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                {{ $content->title ?: 'AI Innehåll' }}
            </h1>
            <div class="flex items-center space-x-4">
                @php
                    $statusColors = [
                        'completed' => ['bg' => 'from-green-50 to-emerald-50', 'border' => 'border-green-200/50', 'text' => 'text-green-800'],
                        'processing' => ['bg' => 'from-blue-50 to-indigo-50', 'border' => 'border-blue-200/50', 'text' => 'text-blue-800'],
                        'draft' => ['bg' => 'from-yellow-50 to-amber-50', 'border' => 'border-yellow-200/50', 'text' => 'text-yellow-800'],
                        'error' => ['bg' => 'from-red-50 to-pink-50', 'border' => 'border-red-200/50', 'text' => 'text-red-800'],
                    ];
                    $colors = $statusColors[strtolower($content->status)] ?? ['bg' => 'from-gray-50 to-slate-50', 'border' => 'border-gray-200/50', 'text' => 'text-gray-800'];
                @endphp
                <div class="inline-flex items-center px-4 py-2 bg-gradient-to-r {{ $colors['bg'] }} {{ $colors['border'] }} border rounded-xl">
                    <span class="text-sm font-medium {{ $colors['text'] }} uppercase">{{ $content->status }}</span>
                    @if($content->provider)
                        <span class="mx-2 text-gray-400">•</span>
                        <span class="text-sm font-medium {{ $colors['text'] }}">{{ $content->provider }}</span>
                    @endif
                </div>
                <a href="{{ route('ai.list') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 hover:border-gray-400 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200 shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Tillbaka
                </a>
            </div>
        </div>

        @php
            $pubs = $content->relationLoaded('publications') ? $content->publications : ($content->publications ?? collect());
            // Stöd både 'wp' och 'wordpress'
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
            $statusToColor = fn($s) => match($s) {
                'ok' => 'text-emerald-600',
                'pending' => 'text-amber-500',
                'failed' => 'text-red-600',
                default => 'text-gray-400',
            };
            $statusToLabel = fn($s) => match($s) {
                'ok' => 'Publicerad',
                'pending' => 'Köad/Pågår',
                'failed' => 'Misslyckad',
                default => 'Inte publicerad',
            };
            $isShopify = ($currentProvider ?? null) === 'shopify';
        @endphp

        <div class="mt-2 flex items-center gap-4">
            @foreach(['wordpress'=>'WordPress','shopify'=>'Shopify','facebook'=>'Facebook','instagram'=>'Instagram','linkedin'=>'LinkedIn'] as $t=>$label)
                @php $st = $state($byTarget[$t]); @endphp
                <div class="flex items-center gap-1" title="{{ $label }} – {{ $statusToLabel($st) }}">
                    <svg class="w-4 h-4 {{ $statusToColor($st) }}" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        @if($t === 'wordpress')
                            <path d="M10 1.25A8.75 8.75 0 1018.75 10 8.76 8.76 0 0010 1.25zm0 1.5A7.25 7.25 0 1117.25 10 7.26 7.26 0 0110 2.75zM6.1 7.5l2.6 7.2.9-2.7-1.7-4.5H6.1zm4.2 0l2.6 7.2c1.5-.8 2.4-2.5 2.4-4.4 0-1.1-.4-2-.8-2.8h-1.9l-1.4 4.3-1-4.3H10.3z"/>
                        @elseif($t === 'shopify')
                            <path d="M6 2a2 2 0 00-2 2v1H3a1 1 0 00-1 .8L1 9a2 2 0 002 2h14a2 2 0 002-2l-2-3.2A1 1 0 0016 5h-1V4a2 2 0 00-2-2H6zm7 3H7V4a1 1 0 011-1h4a1 1 0 011 1v1zM3 12v4a2 2 0 002 2h10a2 2 0 002-2v-4H3z"/>
                        @elseif($t === 'facebook')
                            <path d="M11 2h3a1 1 0 011 1v3h-2a1 1 0 00-1 1v2h3l-.5 3H12v7H9v-7H7V9h2V7a3 3 0 013-3z"/>
                        @elseif($t === 'instagram')
                            <path d="M7 2h6a5 5 0 015 5v6a5 5 0 01-5 5H7a5 5 0 01-5-5V7a5 5 0 015-5zm0 2a3 3 0 00-3 3v6a3 3 0 003 3h6a3 3 0 003-3V7a3 3 0 00-3-3H7zm3 2.5A3.5 3.5 0 1110 13a3.5 3.5 0 010-7zM15 6.5a1 1 0 110 2 1 1 0 010-2z"/>
                        @else
                            <path d="M4 3h12a1 1 0 011 1v12a1 1 0 01-1 1H4a1 1 0 01-1-1V4a1 1 0 011-1zm2 4h2v6H6V7zm4 0h2.2l.1 3.2h.1c.5-1.6 1.6-3.3 3.5-3.3 1.8 0 2.6 1.1 2.6 3.5V17h-2.2v-5.5c0-1.2-.4-2-1.5-2-1.2 0-1.8 1-2.1 2v5.5H10V7z"/>
                        @endif
                    </svg>
                    <span class="text-xs text-gray-600">{{ $label }}</span>
                </div>
            @endforeach
        </div>

        <!-- Success/Error -->
        @if(session('success'))
            <div id="flash-success" class="p-4 bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-200 rounded-xl">
                <div class="flex items-center space-x-3">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if($content->error)
            <div class="p-4 bg-gradient-to-r from-red-50 to-pink-50 border border-red-200 rounded-xl">
                <div class="flex items-center space-x-3">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm font-medium text-red-800">{{ $content->error }}</p>
                </div>
            </div>
        @endif

        @if($publishQuotaReached)
            <div class="p-4 bg-red-50 border border-red-200 rounded-xl">
                <div class="flex items-center space-x-3">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm font-medium text-red-800">
                        Kvot för publiceringar uppnådd
                        @if(!is_null($publishQuotaLimit))
                            ({{ $publishQuotaUsed }} / {{ $publishQuotaLimit }})
                        @endif
                        – uppgradera plan eller begär extraanvändning.
                    </p>
                </div>
            </div>
        @endif

        @php $mdReady = !empty($md); @endphp

            <!-- Förhandsvisning -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100/50 overflow-hidden">
            @if($mdReady)
                <div class="p-8">
                    <article class="prose prose-lg max-w-none prose-indigo prose-headings:text-gray-900 prose-p:text-gray-700 prose-a:text-indigo-600 hover:prose-a:text-indigo-800 prose-strong:text-gray-900 prose-code:text-indigo-600 prose-pre:bg-gray-900 prose-pre:text-gray-100">
                        {!! $html !!}
                    </article>
                </div>
            @else
                <div class="p-12 text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-indigo-100 to-purple-200 rounded-full flex items-center justify-center mx-auto mb-4 animate-pulse">
                        <svg class="w-8 h-8 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Innehåll genereras...</h3>
                    <p class="text-gray-600">Detta kan ta upp till några minuter. Uppdatera sidan om en stund.</p>
                </div>
            @endif
        </div>

        <!-- Bildsektion: ENDAST bildbank -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100/50 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M4 5a2 2 0 012-2h6l2 2h4a2 2 0 012 2v2H4V5zm0 4h20v8a2 2 0 01-2 2H6l-2-2H2a2 2 0 01-2-2V9h4z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Bild från bildbank</h2>
                        <p class="text-sm text-gray-600">Välj en bild att använda vid publicering.</p>
                    </div>
                </div>
                <div class="text-sm text-gray-600">
                    @if($selectedImageAssetId)
                        Vald bild: #{{ $selectedImageAssetId }}
                    @else
                        Ingen bild vald
                    @endif
                </div>
            </div>

            <div class="flex flex-wrap gap-3 items-center">
                <button type="button"
                        class="px-3 py-2 border rounded-lg"
                        x-data
                        @click="$dispatch('media-picker:open')">
                    Öppna mediaväljare
                </button>

                @if($selectedImageAssetId)
                    <div class="flex items-center gap-3">
                        <img class="w-16 h-16 rounded-lg border object-cover" src="{{ route('assets.thumb', $selectedImageAssetId) }}" alt="Vald bild">
                        <button class="text-sm text-gray-600 underline" wire:click="$set('selectedImageAssetId', 0)">Rensa val</button>
                    </div>
                @endif

                <div class="text-xs text-gray-500">
                    Instagram kräver att en bild är vald.
                </div>
            </div>
        </div>

        <!-- WordPress/Shopify publishing -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100/50 p-6">
                <div class="flex items-center space-x-4 mb-6">
                    <div class="w-12 h-12 {{ $isShopify ? 'bg-gradient-to-br from-emerald-500 to-teal-600' : 'bg-gradient-to-br from-emerald-500 to-teal-600' }} rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                            @if($isShopify)
                                <path d="M7 3a2 2 0 00-2 2v1H4a1 1 0 00-.96.72L2 10a2 2 0 002 2h16a2 2 0 002-2l-2.2-3.3A1 1 0 0019 6h-2V5a2 2 0 00-2-2H7zm8 3H9V5a1 1 0 011-1h4a1 1 0 011 1v1zM4 13v5a2 2 0 002 2h12a2 2 0 002-2v-5H4z"/>
                            @else
                                <path d="M21.469 6.825c.84 1.537 1.318 3.3 1.318 5.175 0 3.979-2.156 7.456-5.363 9.325l3.295-9.527c.615-1.54.82-2.771.82-3.864 0-.405-.026-.78-.07-1.11m-7.981.105c.647-.03 1.232-.105 1.232-.105.582-.075.514-.93-.067-.899 0 0-1.755.135-2.88.135-1.064 0-2.85-.15-2.85-.15-.584-.03-.661.854-.075.884 0 0 .54.061 1.125.09l1.68 4.605-2.37 7.08L5.354 6.9c.649-.03 1.234-.1 1.234-.1.585-.075.516-.93-.065-.896 0 0-1.746.138-2.874.138-.2 0-.438-.008-.69-.015C4.911 3.15 8.235 1.215 12 1.215c2.809 0 5.365 1.072 7.286 2.833-.046-.003-.091-.009-.141-.009-1.06 0-1.812.923-1.812 1.914 0 .89.513 1.643 1.06 2.531.411.72.89 1.643.89 2.977 0 .915-.354 1.994-.821 3.479l-1.075 3.585-3.9-11.61.001.014z"/>
                            @endif
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">
                            {{ $isShopify ? 'Shopify' : 'WordPress' }}
                        </h2>
                        <p class="text-sm text-gray-600">
                            {{ $isShopify ? 'Publicera direkt till din Shopify-butik' : 'Publicera direkt till din WordPress-sajt' }}
                        </p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Sajt</label>
                            <select wire:model="publishSiteId" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200">
                                @foreach($sites as $s)
                                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                <select wire:model="publishStatus" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200" @if($publishQuotaReached) disabled @endif>
                                    <option value="draft">Utkast</option>
                                    <option value="publish">Publicera</option>
                                    <option value="future">Schemalägg</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Publiceringstid</label>
                                <input type="datetime-local" wire:model="publishAt" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200" @if($publishQuotaReached) disabled @endif>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                        @php $mdReady = !empty($md); @endphp
                        @if($mdReady && $publishSiteId)
                            <button wire:click="quickDraft" class="inline-flex items-center px-4 py-2 bg-emerald-100 text-emerald-800 font-medium rounded-lg hover:bg-emerald-200 transition-colors duration-200 text-sm {{ $publishQuotaReached ? 'opacity-50 cursor-not-allowed' : '' }}" @if($publishQuotaReached) disabled @endif>
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                Snabb-utkast
                            </button>
                        @else
                            <div></div>
                        @endif

                        <button wire:click="publish" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-semibold rounded-xl hover:from-emerald-700 hover:to-teal-700 focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition-all duration-200 shadow-lg hover:shadow-xl {{ (!$mdReady || $publishQuotaReached) ? 'opacity-50 cursor-not-allowed' : '' }}"
                                @if(!$mdReady || $publishQuotaReached) disabled @endif>
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                            Köa publicering
                        </button>
                    </div>
                </div>
            </div>

            <!-- Social media publishing -->
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100/50 p-6">
                <div class="flex items-center space-x-4 mb-6">
                    <div class="w-12 h-12 bg-gradient-to-br from-pink-500 to-purple-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2m0 0V1a1 1 0 011-1h2a1 1 0 011 1v2m0 0v2a1 1 0 01-1 1H8a1 1 0 01-1-1V4z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Sociala kanaler</h2>
                        <p class="text-sm text-gray-600">Publicera till Facebook och Instagram</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kanal</label>
                        <select wire:model="socialTarget" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-all duration-200" @if($publishQuotaReached) disabled @endif>
                            <option value="facebook">Facebook</option>
                            <option value="instagram">Instagram</option>
                        </select>
                        @error('socialTarget')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Publiceringstid (valfritt)</label>
                        <input type="datetime-local" wire:model="socialScheduleAt" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-all duration-200" @if($publishQuotaReached) disabled @endif>
                        @error('socialScheduleAt')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="text-xs text-gray-600 mb-4 p-3 bg-gray-50 rounded-lg">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Lämna publiceringstid tom för omedelbar publicering.
                </div>

                <div class="flex justify-end space-x-3">
                    <button wire:click="publishSocialNow"
                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-pink-600 to-purple-600 text-white font-semibold rounded-xl hover:from-pink-700 hover:to-purple-700 focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 transition-all duration-200 shadow-lg hover:shadow-xl {{ (!$mdReady || $publishQuotaReached) ? 'opacity-50 cursor-not-allowed' : '' }}"
                            @if(!$mdReady || $publishQuotaReached) disabled @endif>
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        Publicera nu
                    </button>

                    <button wire:click="queueSocial"
                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-yellow-500 to-orange-600 text-white font-semibold rounded-xl hover:from-yellow-600 hover:to-orange-700 focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition-all duration-200 shadow-lg hover:shadow-xl {{ (!$mdReady || $publishQuotaReached) ? 'opacity-50 cursor-not-allowed' : '' }}"
                            @if(!$mdReady || $publishQuotaReached) disabled @endif>
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3"/>
                        </svg>
                        Schemalägg
                    </button>
                </div>
            </div>
        </div>


        <!-- Snabb publicering – LinkedIn -->
        <div class="mt-8 bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100/50 p-6">
            <div class="flex items-center space-x-3 mb-4">
                <div class="w-10 h-10 bg-gradient-to-br from-sky-600 to-blue-700 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M4.98 3.5C4.98 4.88 3.86 6 2.5 6S0 4.88 0 3.5 1.12 1 2.5 1s2.48 1.12 2.48 2.5zM.5 8h4V24h-4V8zm7.5 0h3.8v2.2h.1c.5-1 1.7-2.2 3.6-2.2 3.8 0 4.5 2.5 4.5 5.8V24h-4V14.7c0-2.2 0-5-3-5s-3.4 2.3-3.4 4.9V24H8V8z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">LinkedIn publicering</h3>
                    <p class="text-sm text-gray-600">Publicera direkt till LinkedIn. Bild kan väljas via bildbanken ovan.</p>
                </div>
            </div>

            <div class="space-y-3">
                <div class="grid grid-cols-1 gap-3">
                    <input type="text" wire:model.defer="liQuickText" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm" placeholder="Inläggstext (lämna tom för att använda AI-innehåll)" @if($publishQuotaReached) disabled @endif>
                </div>

                <div class="flex justify-end">
                    <button wire:click="queueLinkedInQuick" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-sky-600 to-blue-700 text-white font-semibold rounded-xl hover:from-sky-700 hover:to-blue-800 focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 transition-all duration-200 shadow-lg hover:shadow-xl {{ (!$mdReady || $publishQuotaReached) ? 'opacity-50 cursor-not-allowed' : '' }}"
                            @if(!$mdReady || $publishQuotaReached) disabled @endif>
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        Publicera till LinkedIn
                    </button>
                </div>
            </div>
        </div>

        <livewire:media-picker wire:model.live="selectedImageAssetId" />
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
    </script>
@endpush
