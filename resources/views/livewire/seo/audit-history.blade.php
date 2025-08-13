<div class="max-w-5xl mx-auto py-10 space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold">SEO Audit – Historik</h1>
        <a href="{{ route('seo.audit.run') }}" class="btn btn-primary btn-sm">Kör ny audit</a>
    </div>

    <div class="space-y-3">
        @forelse($audits as $a)
            <div class="border rounded p-3 flex items-center justify-between">
                <div class="min-w-0">
                    <div class="text-sm text-gray-600">
                        Site #{{ $a->site_id }} • {{ $a->created_at->format('Y-m-d H:i') }}
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-6 gap-2 mt-2 text-sm">
                        <div><span class="text-gray-500">Perf:</span> <span class="font-medium">{{ $a->lighthouse_performance ?? '—' }}</span></div>
                        <div><span class="text-gray-500">Acc:</span> <span class="font-medium">{{ $a->lighthouse_accessibility ?? '—' }}</span></div>
                        <div><span class="text-gray-500">BP:</span> <span class="font-medium">{{ $a->lighthouse_best_practices ?? '—' }}</span></div>
                        <div><span class="text-gray-500">SEO:</span> <span class="font-medium">{{ $a->lighthouse_seo ?? '—' }}</span></div>
                        <div><span class="text-gray-500">Titlar:</span> <span class="font-medium">{{ $a->title_issues }}</span></div>
                        <div><span class="text-gray-500">Meta:</span> <span class="font-medium">{{ $a->meta_issues }}</span></div>
                    </div>
                </div>
                <a href="{{ route('seo.audit.detail', $a->id) }}" class="btn btn-sm">Detaljer</a>
            </div>
        @empty
            <div class="text-sm text-gray-600">Ingen audit ännu.</div>
        @endforelse
    </div>

    <div>
        {{ $audits->links() }}
    </div>
</div>
