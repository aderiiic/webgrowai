<div class="border rounded-lg p-4 bg-white shadow-sm" wire:poll.10s>
    <div class="flex items-center justify-between">
        <h2 class="text-lg font-semibold">SEO Health</h2>
        <div class="flex items-center gap-2">
            @if($latest)
                <a href="{{ route('seo.audit.detail', $latest->id) }}" class="btn btn-sm">Senaste detaljer</a>
            @endif
            <a href="{{ route('seo.audit.history') }}" class="btn btn-sm">Historik</a>
            <a href="{{ route('seo.audit.run') }}" class="btn btn-sm">Kör ny audit</a>
        </div>
    </div>

    @if(session('success'))
        <div class="mt-3 text-sm text-green-700 bg-green-50 border border-green-200 rounded px-3 py-2">
            {{ session('success') }}
        </div>
    @endif

    @if($latest)
        <div class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-3">
            <div class="rounded border p-3">
                <div class="text-xs text-gray-500">Performance</div>
                <div class="text-xl font-semibold">{{ $latest->lighthouse_performance ?? 0 }}</div>
            </div>
            <div class="rounded border p-3">
                <div class="text-xs text-gray-500">Accessibility</div>
                <div class="text-xl font-semibold">{{ $latest->lighthouse_accessibility ?? 0 }}</div>
            </div>
            <div class="rounded border p-3">
                <div class="text-xs text-gray-500">Best Practices</div>
                <div class="text-xl font-semibold">{{ $latest->lighthouse_best_practices ?? 0 }}</div>
            </div>
            <div class="rounded border p-3">
                <div class="text-xs text-gray-500">SEO</div>
                <div class="text-xl font-semibold">{{ $latest->lighthouse_seo ?? 0 }}</div>
            </div>
        </div>

        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-3">
            <div class="rounded border p-3">
                <div class="text-xs text-gray-500">Titelproblem</div>
                <div class="text-lg font-semibold">{{ $latest->title_issues }}</div>
            </div>
            <div class="rounded border p-3">
                <div class="text-xs text-gray-500">Meta-problem</div>
                <div class="text-lg font-semibold">{{ $latest->meta_issues }}</div>
            </div>
        </div>

        <div class="mt-2 text-xs text-gray-500">
            Senaste körning: {{ $latest->created_at->diffForHumans() }}
        </div>
    @else
        <div class="mt-2 text-sm text-gray-600">Ingen audit ännu. Kör en för att se resultat.</div>
    @endif
</div>
