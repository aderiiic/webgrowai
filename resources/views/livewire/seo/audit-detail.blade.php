<div class="max-w-5xl mx-auto py-10 space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold">SEO Audit – Detaljer</h1>
        <a href="{{ route('seo.audit.history') }}" class="btn btn-sm">Till historik</a>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-6 gap-2">
        <div class="border rounded p-3">
            <div class="text-xs text-gray-500">Performance</div>
            <div class="text-xl font-semibold">{{ $audit->lighthouse_performance ?? '—' }}</div>
        </div>
        <div class="border rounded p-3">
            <div class="text-xs text-gray-500">Accessibility</div>
            <div class="text-xl font-semibold">{{ $audit->lighthouse_accessibility ?? '—' }}</div>
        </div>
        <div class="border rounded p-3">
            <div class="text-xs text-gray-500">Best Practices</div>
            <div class="text-xl font-semibold">{{ $audit->lighthouse_best_practices ?? '—' }}</div>
        </div>
        <div class="border rounded p-3">
            <div class="text-xs text-gray-500">SEO</div>
            <div class="text-xl font-semibold">{{ $audit->lighthouse_seo ?? '—' }}</div>
        </div>
        <div class="border rounded p-3">
            <div class="text-xs text-gray-500">Titelproblem</div>
            <div class="text-xl font-semibold">{{ $audit->title_issues }}</div>
        </div>
        <div class="border rounded p-3">
            <div class="text-xs text-gray-500">Meta-problem</div>
            <div class="text-xl font-semibold">{{ $audit->meta_issues }}</div>
        </div>
    </div>

    <div class="flex items-center gap-3">
        <label class="text-sm">Filter</label>
        <select wire:model.live="filter" class="select select-bordered select-sm">
            <option value="all">Alla</option>
            <option value="title">Titel</option>
            <option value="meta">Meta</option>
            <option value="lighthouse">Lighthouse</option>
        </select>
    </div>

    <div class="space-y-3">
        @foreach($items as $it)
            <div class="border rounded p-3">
                <div class="flex items-center justify-between gap-3">
                    <div class="min-w-0">
                        <div class="text-xs text-gray-500 uppercase">{{ $it->type }}</div>
                        <div class="text-sm truncate">
                            <a href="{{ $it->page_url }}" class="text-indigo-600 hover:underline" target="_blank" rel="noopener">{{ $it->page_url }}</a>
                        </div>
                        <div class="mt-1">{{ $it->message }}</div>
                        @if($it->data)
                            <pre class="mt-2 text-xs bg-gray-50 p-2 rounded border overflow-auto">{{ json_encode($it->data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}</pre>
                        @endif
                    </div>

                    @php $postId = $it->data['post_id'] ?? null; @endphp
                    @if($postId)
                        <div class="shrink-0">
                            <a class="btn btn-sm" href="{{ route('wp.posts.meta', [$it->audit->site_id, 'postId' => $postId]) }}">Apply meta i WP</a>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <div>
        {{ $items->links() }}
    </div>
</div>
