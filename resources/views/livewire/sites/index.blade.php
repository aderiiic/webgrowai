<div class="max-w-5xl mx-auto py-10 space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Sajter</h1>
        <a href="{{ route('sites.create') }}" class="btn btn-primary">Lägg till sajt</a>
    </div>

    @if(session('success'))
        <div class="mt-3 text-sm text-green-700 bg-green-50 border border-green-200 rounded px-3 py-2">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mt-3 text-sm text-white bg-red-600 border border-red-700 rounded px-3 py-2">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @forelse($sites as $site)
            @php $latest = $latestBySite[$site->id] ?? null; @endphp
            <div class="border rounded p-4 space-y-2">
                <div class="font-medium">{{ $site->name }}</div>
                <div class="text-sm text-gray-600 truncate">{{ $site->url }}</div>

                <div class="text-xs text-gray-600">
                    Site Key: <span class="font-mono">{{ $site->public_key }}</span>
                </div>

                <div class="flex gap-2 pt-2 flex-wrap">
                    <form method="POST" action="{{ route('sites.cro.analyze', $site) }}">
                        @csrf
                        <button class="btn btn-sm">Kör CRO-analys</button>
                    </form>
                </div>

                <div class="text-xs text-gray-700 mt-2 flex items-center justify-between">
                    <div>
                        Senaste audit:
                        @if($latest)
                            <span class="font-medium">
                                {{ \Illuminate\Support\Carbon::parse($latest['created_at'])->diffForHumans() }}
                            </span>
                            • Perf:
                            <span class="font-semibold">{{ $latest['lighthouse_performance'] ?? '—' }}</span>
                        @else
                            <span class="text-gray-500">—</span>
                        @endif
                    </div>
                    <form method="POST" action="{{ route('sites.seo.audit.run', $site) }}">
                        @csrf
                        <button class="btn btn-xs">Kör audit</button>
                    </form>
                </div>

                <div class="flex gap-2 pt-2">
                    <a href="{{ route('sites.edit', $site) }}" class="btn btn-sm">Redigera</a>
                    <a href="{{ route('sites.wordpress', $site) }}" class="btn btn-sm">WordPress</a>
                    <a href="{{ route('wp.posts.index', $site) }}" class="btn btn-sm">WP-inlägg</a>
                </div>
            </div>
        @empty
            <p>Inga sajter ännu.</p>
        @endforelse
    </div>
</div>
