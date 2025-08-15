<div class="max-w-5xl mx-auto py-10 space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold">AI Innehåll</h1>
        <div class="flex items-center gap-3">
      <span class="inline-flex items-center gap-2 border rounded-full px-3 py-1 text-xs bg-indigo-50 text-indigo-700 border-indigo-200">
        Genereringar ({{ now()->format('Y-m') }}): <span class="font-semibold">{{ $monthGenerateTotal }}</span>
      </span>
            <span class="inline-flex items-center gap-2 border rounded-full px-3 py-1 text-xs bg-emerald-50 text-emerald-700 border-emerald-200">
        Publicerade till WP: <span class="font-semibold">{{ $monthPublishTotal }}</span>
      </span>
            <a href="{{ route('ai.compose') }}" class="btn btn-primary">Nytt innehåll</a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($items as $c)
            <div class="border rounded p-4 space-y-2">
                <div class="text-sm text-gray-500">{{ strtoupper($c->status) }} • {{ $c->provider ?: '—' }}</div>
                <div class="font-medium truncate">{{ $c->title ?: '(utan titel)' }}</div>
                <div class="text-xs text-gray-500">Mall #{{ $c->template_id }} • {{ $c->created_at->diffForHumans() }}</div>
                <a class="btn btn-sm" href="{{ route('ai.detail', $c->id) }}">Öppna</a>
            </div>
        @endforeach
    </div>

    <div>
        {{ $items->links() }}
    </div>
</div>
