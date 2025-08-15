<div class="max-w-3xl mx-auto py-10 space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Nyckelordsförslag</h1>
        <a href="{{ route('seo.keywords.index') }}" class="btn btn-sm">Tillbaka</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="border rounded p-4 bg-white space-y-4">
        <div>
            <div class="text-xs text-gray-500">Titel</div>
            <div class="text-sm">
                <div><span class="text-gray-500">Nu:</span> {{ $current['title'] ?? '—' }}</div>
                <div><span class="text-gray-500">Föreslagen:</span> <strong>{{ $suggested['title'] ?? '—' }}</strong></div>
            </div>
        </div>

        <div>
            <div class="text-xs text-gray-500">Meta-description</div>
            <div class="text-sm">
                <div><span class="text-gray-500">Nu:</span> {{ $current['meta'] ?? '—' }}</div>
                <div><span class="text-gray-500">Föreslagen:</span> <strong>{{ $suggested['meta'] ?? '—' }}</strong></div>
            </div>
        </div>

        <div>
            <div class="text-xs text-gray-500">Nya nyckelord</div>
            <div class="text-sm">
                {{ implode(', ', $suggested['keywords'] ?? []) ?: '—' }}
            </div>
        </div>
    </div>

    <div class="flex gap-2">
        @if($sug->status !== 'applied')
            <button class="btn btn-primary" wire:click="apply">Apply till WP</button>
            <button class="btn" wire:click="dismiss">Avfärda</button>
        @else
            <span class="text-sm text-green-700">Applicerat {{ $sug->applied_at?->diffForHumans() }}</span>
        @endif
    </div>
</div>
