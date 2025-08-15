<div class="max-w-3xl mx-auto py-10 space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Förslag – {{ $sug->url }}</h1>
        <a href="{{ route('cro.suggestions.index') }}" class="btn btn-sm">Tillbaka</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="border rounded p-4 bg-white space-y-4">
        <div>
            <div class="text-xs text-gray-500">Rubrik</div>
            <div class="text-sm">
                <div><span class="text-gray-500">Nu:</span> {{ $s['title']['current'] ?? '—' }}</div>
                <div><span class="text-gray-500">Föreslagen:</span> <strong>{{ $s['title']['suggested'] ?? '—' }}</strong></div>
                @if(!empty($s['title']['subtitle']))
                    <div><span class="text-gray-500">Underrubrik:</span> {{ $s['title']['subtitle'] }}</div>
                @endif
            </div>
        </div>

        <div>
            <div class="text-xs text-gray-500">CTA</div>
            <div class="text-sm">
                <div><span class="text-gray-500">Nu:</span> {{ $s['cta']['current'] ?? '—' }}</div>
                <div><span class="text-gray-500">Föreslagen:</span> <strong>{{ $s['cta']['suggested'] ?? '—' }}</strong></div>
                <div><span class="text-gray-500">Placering:</span> {{ $s['cta']['placement'] ?? '—' }}</div>
            </div>
        </div>

        <div>
            <div class="text-xs text-gray-500">Formulär</div>
            <div class="text-sm">
                <div><span class="text-gray-500">Nu:</span> {{ $s['form']['current'] ?? '—' }}</div>
                @if(!empty($s['form']['suggested']))
                    <div><span class="text-gray-500">Föreslagen placering:</span> {{ $s['form']['suggested']['placement'] ?? '—' }}</div>
                    <div><span class="text-gray-500">Fält:</span> {{ implode(', ', $s['form']['suggested']['fields'] ?? []) }}</div>
                @endif
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
