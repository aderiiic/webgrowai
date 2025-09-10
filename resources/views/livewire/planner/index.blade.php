<div x-data="{ openPanel: @js(!is_null($selected)) }" class="max-w-7xl mx-auto">
    <div class="mb-6 flex items-center justify-between flex-wrap gap-3">
        <div class="flex items-center gap-3">
            <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                <svg class="w-8 h-8 mr-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2z"/>
                </svg>
                Planera & Publicera
            </h1>
            <div class="inline-flex rounded-lg overflow-hidden border">
                <button wire:click="setView('timeline')" class="px-3 py-1.5 text-sm {{ $view === 'timeline' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700' }}">Tidslinje</button>
                <button wire:click="setView('calendar')" class="px-3 py-1.5 text-sm {{ $view === 'calendar' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700' }}">Kalender</button>
            </div>
        </div>
        <a href="{{ route('ai.list') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50">
            Till AI Innehåll
        </a>
    </div>

    <!-- Filterrad -->
    <div class="mb-4 grid grid-cols-1 md:grid-cols-5 gap-3">
        <div>
            <label class="block text-xs text-gray-600 mb-1">Sajt</label>
            <select wire:model.live="siteId" class="w-full px-3 py-2 border rounded-lg">
                <option value="">Alla sajter</option>
                @php $sites = auth()->user()?->customer?->sites()->orderBy('name')->get(['id','name']) ?? collect(); @endphp
                @foreach($sites as $s)
                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs text-gray-600 mb-1">Kanal</label>
            <select wire:model.live="channel" class="w-full px-3 py-2 border rounded-lg">
                <option value="all">Alla</option>
                <option value="wp">WordPress</option>
                <option value="shopify">Shopify</option>
                <option value="facebook">Facebook</option>
                <option value="instagram">Instagram</option>
                <option value="linkedin">LinkedIn</option>
            </select>
        </div>
        <div>
            <label class="block text-xs text-gray-600 mb-1">Status</label>
            <select wire:model.live="status" class="w-full px-3 py-2 border rounded-lg">
                <option value="upcoming">Kommande (30 dagar)</option>
                <option value="processing">Pågår</option>
                <option value="published">Publicerad</option>
                <option value="failed">Misslyckad</option>
                <option value="cancelled">Avbruten</option>
                <option value="all">Alla</option>
            </select>
        </div>
        <div class="md:col-span-2 flex items-end justify-end gap-2">
            @if($view === 'calendar')
                <div class="hidden md:flex items-center gap-2">
                    <button wire:click="prevWeek" class="px-3 py-2 border rounded-lg bg-white hover:bg-gray-50">Föregående</button>
                    <button wire:click="today" class="px-3 py-2 border rounded-lg bg-white hover:bg-gray-50">Idag</button>
                    <button wire:click="nextWeek" class="px-3 py-2 border rounded-lg bg-white hover:bg-gray-50">Nästa</button>
                </div>
                <div class="text-xs text-gray-600">
                    Vecka: {{ $weekStart->translatedFormat('j M') }} – {{ $weekEnd->translatedFormat('j M Y') }}
                </div>
            @else
                <div class="text-xs text-gray-500">Visar {{ count($items) }} poster</div>
            @endif
        </div>
    </div>

    @php
        $statusBadge = function(string $s) {
            return match($s) {
                'published'  => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'label' => 'Publicerad'],
                'processing' => ['bg' => 'bg-amber-50',  'text' => 'text-amber-700',  'label' => 'Pågår'],
                'queued','scheduled' => ['bg' => 'bg-sky-50', 'text' => 'text-sky-700', 'label' => $s === 'scheduled' ? 'Schemalagd' : 'Köad'],
                'failed'     => ['bg' => 'bg-rose-50',    'text' => 'text-rose-700',    'label' => 'Misslyckad'],
                'cancelled'  => ['bg' => 'bg-gray-100',   'text' => 'text-gray-700',   'label' => 'Avbruten'],
                default      => ['bg' => 'bg-gray-50',    'text' => 'text-gray-700',   'label' => ucfirst($s)],
            };
        };
        $targetIcon = function(string $t) {
            return match($t) {
                'wp' => 'M10 1.25A8.75 8.75 0 1018.75 10 8.76 8.76 0 0010 1.25z',
                'shopify' => 'M6 2a2 2 0 00-2 2v1H3a1 1 0 00-1 .8L1 9a2 2 0 002 2h14',
                'facebook' => 'M11 2h3a1 1 0 011 1v3h-2a1 1 0 00-1 1v2h3',
                'instagram' => 'M7 2h6a5 5 0 015 5v6a5 5 0 01-5 5H7',
                'linkedin' => 'M4 3h12a1 1 0 011 1v12a1 1 0 01-1 1H4',
                default => 'M5 3h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z',
            };
        };
    @endphp

    @if($view === 'timeline')
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-4">
                @php
                    $grouped = collect($items)->groupBy(function($r) {
                        return $r['scheduled_at'] ? \Illuminate\Support\Str::of($r['scheduled_at'])->limit(10) : 'Utan datum';
                    });
                @endphp
                @forelse($grouped as $day => $rows)
                    <div>
                        <div class="sticky top-0 bg-white/70 backdrop-blur z-10 py-2">
                            <h3 class="text-sm font-semibold text-gray-700">
                                {{ $day === 'Utan datum' ? 'Utan datum' : \Illuminate\Support\Carbon::parse($day)->translatedFormat('l d M Y') }}
                            </h3>
                        </div>
                        <div class="space-y-3">
                            @foreach($rows as $r)
                                @php $badge = $statusBadge($r['status']); @endphp
                                <button
                                    wire:click="select({{ $r['id'] }})"
                                    @click="openPanel = true"
                                    class="w-full text-left p-4 bg-white border rounded-xl hover:shadow transition group">
                                    <div class="flex items-start justify-between">
                                        <div class="flex items-start gap-3">
                                            <svg class="w-5 h-5 text-indigo-600 mt-0.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path d="{{ $targetIcon($r['target']) }}"/>
                                            </svg>
                                            <div>
                                                <div class="font-medium text-gray-900 line-clamp-1">{{ $r['title'] }}</div>
                                                <div class="text-xs text-gray-600 mt-0.5">
                                                    <span class="text-gray-500">Sajt:</span> {{ $r['site'] ?: '—' }}
                                                    <span class="mx-1 text-gray-300">•</span>
                                                    <span class="text-gray-500">Kanal:</span> {{ ucfirst($r['target']) }}
                                                    @if($r['scheduled_at'])
                                                        <span class="mx-1 text-gray-300">•</span>
                                                        <span class="text-gray-500">Tid:</span> {{ \Illuminate\Support\Carbon::parse($r['scheduled_at'])->format('Y-m-d H:i') }}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <span class="inline-flex items-center px-2 py-1 text-xs rounded-full {{ $badge['bg'] }} {{ $badge['text'] }}">
                                            {{ $badge['label'] }}
                                        </span>
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center bg-white border rounded-2xl">
                        <p class="text-gray-600">Inga publiceringar matchar dina filter.</p>
                    </div>
                @endforelse
            </div>

            <div x-show="openPanel" x-transition class="lg:col-span-1">
                @include('livewire.planner.partials.detail-panel')
            </div>
        </div>
    @else
        <!-- Kalendervy (veckoläge) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2">
                <div class="bg-white border rounded-2xl overflow-hidden">
                    <div class="grid grid-cols-7 gap-px bg-gray-200">
                        @foreach($weekDays as $d)
                            @php
                                $rows = collect($items)->filter(fn($r) => $r['scheduled_at'] && \Illuminate\Support\Carbon::parse($r['scheduled_at'])->isSameDay($d))->sortBy('scheduled_at')->values();
                            @endphp
                            <div class="bg-gray-50 p-2">
                                <div class="flex items-center justify-between">
                                    <div class="text-xs text-gray-500 uppercase">{{ $d->translatedFormat('D') }}</div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-[11px] px-1.5 py-0.5 rounded bg-white border text-gray-600">{{ $rows->count() }}</span>
                                        <button wire:click="startQuickPlan('{{ $d->toDateString() }}')" @click="openPanel=true" class="text-[11px] text-indigo-600 hover:underline">Lägg till</button>
                                    </div>
                                </div>
                                <div class="text-xs text-gray-400">{{ $d->format('Y-m-d') }}</div>

                                <div class="mt-2">
                                    @forelse($rows as $r)
                                        @php $badge = $statusBadge($r['status']); @endphp
                                        <button
                                            wire:click="select({{ $r['id'] }})"
                                            @click="openPanel = true"
                                            class="w-full text-left mb-2 last:mb-0 p-2 rounded-lg border bg-white hover:bg-gray-50">
                                            <div class="flex items-start gap-2">
                                                <svg class="w-4 h-4 text-indigo-600 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="{{ $targetIcon($r['target']) }}"/>
                                                </svg>
                                                <div class="min-w-0">
                                                    <div class="text-xs font-medium text-gray-900 truncate">
                                                        {{ \Illuminate\Support\Carbon::parse($r['scheduled_at'])->format('H:i') }}
                                                        <span class="mx-1 text-gray-300">•</span>{{ $r['title'] }}
                                                    </div>
                                                    <div class="text-[11px] text-gray-600 truncate">{{ $r['site'] ?: '—' }}</div>
                                                </div>
                                                <span class="ml-auto inline-flex items-center px-1.5 py-0.5 text-[10px] rounded {{ $badge['bg'] }} {{ $badge['text'] }}">
                                                    {{ $badge['label'] }}
                                                </span>
                                            </div>
                                        </button>
                                    @empty
                                        <div class="text-[11px] text-gray-400">—</div>
                                    @endforelse
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div x-show="openPanel" x-transition class="lg:col-span-1">
                @include('livewire.planner.partials.detail-panel', ['readyContents' => $readyContents])
            </div>
        </div>
    @endif
</div>
