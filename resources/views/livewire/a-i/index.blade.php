<div>
    <div class="max-w-7xl mx-auto space-y-8">
        <div id="li-modal" class="hidden fixed inset-0 z-[9999]">
            <div class="absolute inset-0 bg-black/40"></div>
            <div class="relative max-w-2xl mx-auto mt-20 bg-white rounded-2xl shadow-2xl border border-gray-200">
                <div class="p-4 border-b flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Förhandsgranskning</h3>
                    <button id="li-modal-close" class="p-2 rounded hover:bg-gray-100">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div class="p-6">
                    <pre id="li-modal-body" class="whitespace-pre-wrap text-sm text-gray-800"></pre>
                </div>
                <div class="p-4 border-t flex justify-end">
                    <button id="li-modal-close-2" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">Stäng</button>
                </div>
            </div>
        </div>

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

        <!-- LinkedIn: snabbgenerator av inläggsförslag -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100/50 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-sky-600 to-blue-700 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M4.98 3.5C4.98 4.88 3.86 6 2.5 6S0 4.88 0 3.5 1.12 1 2.5 1s2.48 1.12 2.48 2.5zM.5 8h4V24h-4V8zm7.5 0h3.8v2.2h.1c.5-1 1.7-2.2 3.6-2.2 3.8 0 4.5 2.5 4.5 5.8V24h-4V14.7c0-2.2 0-5-3-5s-3.4 2.3-3.4 4.9V24H8V8z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">LinkedIn – Förslag & snabbpublicering</h2>
                        <p class="text-sm text-gray-600">Förslag visas i max 5 dagar och rensas automatiskt</p>
                    </div>
                </div>
                <button id="li-refresh" class="inline-flex items-center px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm hover:bg-gray-50">
                    Uppdatera lista
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <input type="text" id="li-topic" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm" placeholder="Ämne (t.ex. CRM-tips)">
                <input type="text" id="li-tone" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm" placeholder="Tonalitet (valfritt)">
                <button id="li-generate" class="inline-flex items-center justify-center px-4 py-2 bg-sky-600 text-white font-medium rounded-lg hover:bg-sky-700 transition-colors text-sm">
                    Generera förslag
                </button>
            </div>

            <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-3" id="li-suggestions"></div>
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

                        @php
                            $pubs = $c->relationLoaded('publications') ? $c->publications : ($c->publications ?? collect());
                            $byTarget = [
                                'wp'       => $pubs->where('target','wp'),
                                'shopify'  => $pubs->where('target','shopify'),
                                'facebook' => $pubs->where('target','facebook'),
                                'instagram'=> $pubs->where('target','instagram'),
                                'linkedin' => $pubs->where('target','linkedin'),
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
                        @endphp

                        <div class="mt-3 flex items-center gap-4">
                            @foreach(['wp'=>'WordPress','shopify'=>'Shopify','facebook'=>'Facebook','instagram'=>'Instagram','linkedin'=>'LinkedIn'] as $t=>$label)
                                @php $st = $state($byTarget[$t]); @endphp
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4 {{ $statusToColor($st) }}" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                        @if($t === 'wp')
                                            <path d="M10 1.25A8.75 8.75 0 1018.75 10 8.76 8.76 0 0010 1.25zm0 1.5A7.25 7.25 0 1117.25 10 7.26 7.26 0 0110 2.75zM6.1 7.5l2.6 7.2.9-2.7-1.7-4.5H6.1zm4.2 0l2.6 7.2c1.5-.8 2.4-2.5 2.4-4.4 0-1.1-.4-2-.8-2.8h-1.9l-1.4 4.3-1-4.3H10.3z"/>
                                        @elseif($t === 'shopify')
                                            <!-- Enkel "bag" ikon som Shopify-markör -->
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

@push('scripts')
    <script>
        (function() {
            const apiIndex = "{{ route('linkedin.suggestions.index') }}";
            const apiStore = "{{ route('linkedin.suggestions.store') }}";
            const apiPublish = "{{ route('linkedin.publish') }}";
            const csrf = "{{ csrf_token() }}";

            const modal = document.getElementById('li-modal');
            const modalBody = document.getElementById('li-modal-body');
            function openModal(text) {
                modalBody.textContent = text || '';
                modal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }
            function closeModal() {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
            document.getElementById('li-modal-close')?.addEventListener('click', closeModal);
            document.getElementById('li-modal-close-2')?.addEventListener('click', closeModal);
            modal?.addEventListener('click', (e) => {
                if (e.target === modal) closeModal();
            });

            function escapeHtml(str) {
                return (str || '').replace(/[&<>"']/g, function(m) {
                    return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[m]);
                });
            }

            async function loadSuggestions() {
                const wrap = document.getElementById('li-suggestions');
                if (!wrap) return;
                wrap.innerHTML = '<div class="text-sm text-gray-500">Laddar...</div>';
                try {
                    const res = await fetch(apiIndex, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                    const data = await res.json();
                    wrap.innerHTML = '';
                    const items = (data && data.data) ? data.data : [];
                    if (!items.length) {
                        wrap.innerHTML = '<div class="text-sm text-gray-500">Inga aktiva förslag just nu.</div>';
                        return;
                    }
                    items.forEach(function(sug) {
                        const leftMs = new Date(sug.expires_at).getTime() - Date.now();
                        const leftDays = Math.max(0, Math.floor(leftMs / (1000*60*60*24)));
                        const full = sug.content || '';
                        const preview = full.length > 220 ? full.slice(0, 220) + '…' : full;

                        const card = document.createElement('div');
                        card.className = 'p-4 bg-gray-50 rounded-xl border border-gray-200';

                        const times = (sug.recommended_times || []).slice(0,3).map(function(t) {
                            try { return new Date(t).toLocaleString(); } catch (_) { return t; }
                        });

                        card.innerHTML = `
                    <div class="flex items-start justify-between">
                        <div class="text-sm text-gray-800 whitespace-pre-wrap mr-3">${escapeHtml(preview)}</div>
                        <div class="flex flex-col items-end gap-2">
                            <button class="px-3 py-1 bg-white border border-gray-300 rounded text-xs view">Visa</button>
                            <button class="px-3 py-1 bg-white border border-gray-300 rounded text-xs copy">Kopiera</button>
                        </div>
                    </div>
                    <div class="mt-2 text-xs text-gray-600">Försvinner om ${leftDays} dagar</div>
                    <div class="mt-2 flex flex-wrap gap-2 text-xs">
                        ${times.map(function(t){return `<span class="px-2 py-1 bg-white border rounded">${t}</span>`}).join('')}
                    </div>
                    <div class="mt-3 grid grid-cols-1 md:grid-cols-4 gap-2">
                        @if(config('features.image_generation'))
                        <input type="text" placeholder="Bildprompt (valfritt)" class="img-prompt md:col-span-2 px-2 py-1 border rounded text-xs">
                        @endif
                        <input type="datetime-local" placeholder="Schemalägg" class="schedule md:col-span-1 px-2 py-1 border rounded text-xs">
                        <button class="publish px-3 py-1 bg-sky-600 text-white rounded text-xs md:col-span-1">Publicera</button>
                    </div>
                `;
                        wrap.appendChild(card);

                        card.querySelector('.view').addEventListener('click', function() {
                            openModal(full);
                        });
                        card.querySelector('.copy').addEventListener('click', function() {
                            navigator.clipboard.writeText(full);
                        });
                        card.querySelector('.publish').addEventListener('click', async function() {
                            const prompt = card.querySelector('.img-prompt').value || null;
                            const sched = card.querySelector('.schedule').value || null;
                            const body = {
                                text: full,
                                image_prompt: prompt,
                                schedule_at: sched || null
                            };
                            const resp = await fetch(apiPublish, {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                                body: JSON.stringify(body)
                            });
                            const out = await resp.json();
                            alert(out.message || 'Kölagd.');
                        });
                    });
                } catch (e) {
                    wrap.innerHTML = '<div class="text-sm text-red-600">Kunde inte ladda förslag.</div>';
                }
            }

            document.getElementById('li-generate')?.addEventListener('click', async function() {
                const topic = document.getElementById('li-topic').value.trim();
                const tone  = document.getElementById('li-tone').value.trim();
                if (!topic) { alert('Ange ett ämne'); return; }
                const res = await fetch(apiStore, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                    body: JSON.stringify({ topic: topic, tone: tone || null, count: 3 })
                });
                const out = await res.json();
                alert(out.message || 'Kölagd.');
                loadSuggestions();
            });

            document.getElementById('li-refresh')?.addEventListener('click', loadSuggestions);

            // Auto-load
            loadSuggestions();
        })();
    </script>
@endpush
