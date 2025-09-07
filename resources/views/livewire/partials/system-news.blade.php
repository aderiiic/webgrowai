<div>
    @if($items->isNotEmpty())
        <div class="mb-6">
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl border border-gray-200 shadow-sm">
                <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V7a2 2 0 012-2h9l5 5v8a2 2 0 01-2 2z"/>
                        </svg>
                        <span class="text-sm font-semibold text-gray-900">Interna nyheter & uppdateringar</span>
                    </div>
                    @can('admin')
                        <a href="{{ route('admin.news.index') }}" class="text-xs text-indigo-700 hover:underline">Hantera</a>
                    @endcan
                </div>
                <div class="p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($items as $n)
                            @php
                                $color = match ($n->type) {
                                    'feature' => 'bg-emerald-100 text-emerald-800',
                                    'bugfix'  => 'bg-rose-100 text-rose-800',
                                    default   => 'bg-slate-100 text-slate-800',
                                };
                            @endphp
                            <div class="rounded-xl border border-gray-200 bg-white p-4 hover:border-indigo-200 transition">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="px-2 py-0.5 rounded-full text-[11px] {{ $color }}">{{ ucfirst($n->type) }}</span>
                                        @if($n->is_pinned)
                                            <span class="px-2 py-0.5 rounded-full text-[11px] bg-amber-100 text-amber-800">Fäst</span>
                                        @endif
                                    </div>
                                    <span class="text-xs text-gray-500">{{ $n->published_at?->diffForHumans() }}</span>
                                </div>
                                <h3 class="mt-2 text-sm font-bold text-gray-900">{{ $n->title }}</h3>
                                @if($n->tagsArray)
                                    <div class="mt-2 flex flex-wrap gap-1">
                                        @foreach($n->tagsArray as $t)
                                            <span class="px-2 py-0.5 rounded-full text-[11px] bg-gray-100 text-gray-700">{{ $t }}</span>
                                        @endforeach
                                    </div>
                                @endif
                                @if($n->body_md)
                                    <div class="mt-2 text-sm text-gray-700 prose prose-sm max-w-none line-clamp-3">
                                        {!! \Illuminate\Support\Str::of($n->body_md)->markdown() !!}
                                    </div>
                                @endif
                                <div class="mt-3">
                                    <button type="button"
                                            wire:click="open({{ $n->id }})"
                                            class="inline-flex items-center px-3 py-1.5 text-xs rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 transition">
                                        Läs mer
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal --}}
    @if($show)
        <div x-data
             x-init="
                const onEsc = e => { if (e.key === 'Escape') { $wire.close() } };
                window.addEventListener('keydown', onEsc);
                $watch('$wire.show', v => { if (!v) window.removeEventListener('keydown', onEsc) });
             "
             class="fixed inset-0 z-50">
            <!-- Backdrop -->
            <div class="absolute inset-0 bg-black/40" wire:click="close"></div>

            <!-- Dialog -->
            <div class="absolute inset-0 flex items-center justify-center p-4">
                <div class="w-full max-w-3xl bg-white rounded-2xl shadow-2xl border border-gray-200 overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100 flex items-start justify-between">
                        <div>
                            <div class="flex items-center gap-2">
                                @if(($active['type'] ?? null) === 'feature')
                                    <span class="px-2 py-0.5 rounded-full text-[11px] bg-emerald-100 text-emerald-800">Feature</span>
                                @elseif(($active['type'] ?? null) === 'bugfix')
                                    <span class="px-2 py-0.5 rounded-full text-[11px] bg-rose-100 text-rose-800">Bugfix</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[11px] bg-slate-100 text-slate-800">Info</span>
                                @endif
                                @if(($active['is_pinned'] ?? false) === true)
                                    <span class="px-2 py-0.5 rounded-full text-[11px] bg-amber-100 text-amber-800">Fäst</span>
                                @endif
                            </div>
                            <h3 class="mt-2 text-base font-bold text-gray-900">
                                {{ $active['title'] ?? 'Meddelande' }}
                            </h3>
                            @if(!empty($active['published_at']))
                                <div class="text-xs text-gray-500 mt-0.5">Publicerad {{ $active['published_at'] }}</div>
                            @endif
                        </div>
                        <button type="button" wire:click="close" class="p-2 rounded-full hover:bg-gray-100 text-gray-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <div class="p-5">
                        <div class="prose prose-sm max-w-none text-gray-800">
                            {!! \Illuminate\Support\Str::of($active['body_md'] ?? '')->markdown() !!}
                        </div>
                    </div>

                    <div class="px-5 py-3 border-t border-gray-100 flex items-center justify-end">
                        <button type="button" wire:click="close" class="px-4 py-2 rounded-lg bg-gray-100 text-gray-800 hover:bg-gray-200 transition">
                            Stäng
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
