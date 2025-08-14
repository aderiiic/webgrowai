<div class="max-w-5xl mx-auto py-10 space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Publiceringar</h1>
    </div>

    <div class="flex items-center gap-3">
        <div>
            <label class="text-sm text-gray-600">Kanal</label>
            <select wire:model.live="target" class="select select-bordered select-sm">
                <option value="">Alla</option>
                <option value="wp">WordPress</option>
                <option value="facebook">Facebook</option>
                <option value="instagram">Instagram</option>
            </select>
        </div>
        <div>
            <label class="text-sm text-gray-600">Status</label>
            <select wire:model.live="status" class="select select-bordered select-sm">
                <option value="">Alla</option>
                <option value="queued">Köad</option>
                <option value="processing">Pågår</option>
                <option value="published">Publicerad</option>
                <option value="failed">Misslyckad</option>
            </select>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="space-y-3">
        @forelse($pubs as $p)
            <div class="border rounded p-3">
                <div class="flex items-center justify-between">
                    <div class="text-sm">
                        <div class="font-medium">{{ strtoupper($p->target) }} • {{ strtoupper($p->status) }}</div>
                        <div class="text-gray-600">Innehåll: {{ $p->content?->title ?? '(utan titel)' }}</div>
                        <div class="text-xs text-gray-500">
                            @if($p->scheduled_at)
                                Schemalagd: {{ $p->scheduled_at->format('Y-m-d H:i') }}
                            @else
                                Skapad: {{ $p->created_at->format('Y-m-d H:i') }}
                            @endif
                            @if($p->external_id) • Extern ID: {{ $p->external_id }} @endif
                        </div>
                        @if($p->message)
                            <div class="text-xs mt-1 {{ $p->status==='failed' ? 'text-red-700' : 'text-gray-600' }}">{{ $p->message }}</div>
                        @endif
                    </div>
                    <div class="flex items-center gap-2">
                        @if($p->status === 'failed')
                            <button class="btn btn-sm" wire:click="retry({{ $p->id }})">Kör om</button>
                        @endif
                        @if($p->payload)
                            <details class="text-xs">
                                <summary class="cursor-pointer">Visa payload</summary>
                                <pre class="mt-1 bg-gray-50 p-2 rounded border max-w-xs overflow-auto">{{ json_encode($p->payload, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}</pre>
                            </details>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="text-sm text-gray-600">Inga publiceringar ännu.</div>
        @endforelse
    </div>

    <div>
        {{ $pubs->links() }}
    </div>
</div>
