<div class="max-w-4xl mx-auto py-10 space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Lead</h1>
        <a href="{{ route('leads.index') }}" class="btn btn-sm">Tillbaka</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="border rounded p-3 bg-white">
            <div class="text-xs text-gray-500">Identifiering</div>
            <div class="mt-1">{{ $lead->email ?? ('Anonym: '.\Illuminate\Support\Str::limit($lead->visitor_id, 10, '')) }}</div>
            <div class="text-xs text-gray-500 mt-2">Site</div>
            <div class="mt-1">{{ $lead->site->name ?? ('#'.$lead->site_id) }}</div>
        </div>
        <div class="border rounded p-3 bg-white">
            <div class="text-xs text-gray-500">Score</div>
            <div class="mt-1 text-xl font-semibold">{{ $score->score_norm ?? 0 }}</div>
            @if($score?->breakdown)
                <pre class="mt-2 text-xs bg-gray-50 p-2 rounded border">{{ json_encode($score->breakdown, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}</pre>
            @endif
        </div>
    </div>

    <div class="border rounded p-3 bg-white">
        <div class="text-sm font-medium mb-2">Senaste händelser</div>
        <div class="space-y-2">
            @foreach($events as $ev)
                <div class="text-sm">
                    <span class="font-mono text-xs text-gray-500">{{ $ev->occurred_at->format('Y-m-d H:i') }}</span>
                    • <span class="uppercase text-gray-600">{{ $ev->type }}</span>
                    @if($ev->url)
                        • <span class="text-gray-700">{{ \Illuminate\Support\Str::limit($ev->url, 80) }}</span>
                    @endif
                </div>
            @endforeach
            @if($events->isEmpty())
                <div class="text-sm text-gray-600">Inga händelser ännu.</div>
            @endif
        </div>
    </div>
</div>
