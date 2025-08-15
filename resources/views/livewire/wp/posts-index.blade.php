<div class="max-w-5xl mx-auto py-10 space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold">WordPress-inlägg – {{ $site->name }}</h1>
        <a class="btn btn-primary" href="{{ route('wp.posts.create', $site) }}">Nytt inlägg</a>
    </div>

    <div class="flex items-center gap-3">
        <label class="text-sm text-gray-600">Status</label>
        <select wire:model.live="status" class="select select-bordered select-sm">
            <option value="any">Alla</option>
            <option value="publish">Publicerade</option>
            <option value="draft">Utkast</option>
            <option value="future">Schemalagda</option>
            <option value="pending">Väntande</option>
        </select>
    </div>

    <div class="space-y-3">
        @forelse($posts as $p)
            <div class="border rounded p-3 flex items-center justify-between">
                <div class="min-w-0">
                    <div class="font-medium truncate">{{ $p['title']['rendered'] ?: '(utan titel)' }}</div>
                    <div class="text-xs text-gray-600">Status: {{ $p['status'] }} • ID: {{ $p['id'] }}</div>
                </div>
                <div class="flex gap-2 shrink-0">
                    <a class="btn btn-sm" href="{{ route('wp.posts.edit', [$site, 'postId' => $p['id']]) }}">Redigera</a>
                    <a class="btn btn-sm" href="{{ route('wp.posts.meta', [$site, 'postId' => $p['id']]) }}">SEO meta</a>
                    @if(!empty($p['link']))
                        <a class="btn btn-sm" href="{{ $p['link'] }}" target="_blank" rel="noopener">Öppna</a>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-sm text-gray-600">Inga inlägg hittades.</div>
        @endforelse
    </div>
</div>
