<div class="max-w-6xl mx-auto py-10 space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold">Veckovis planering & tips</h2>
        <div class="flex items-center gap-2">
            <label class="text-sm">Visa:</label>
            <select wire:model="filterTag" class="select select-bordered select-sm">
                <option value="">Alla</option>
                <option value="monday">Måndag</option>
                <option value="friday">Fredag</option>
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @forelse($plans as $plan)
            <div class="border rounded p-4 bg-white space-y-2">
                <div class="text-xs text-gray-500">
                    {{ ucfirst($plan->run_tag) }} • {{ $plan->run_date->format('Y-m-d') }} • {{ $plan->type }}
                    @if($plan->emailed_at)
                        <span class="ms-2 inline-flex items-center px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 text-2xs border border-emerald-200">mailat</span>
                    @endif
                </div>
                <div class="font-medium">{{ $plan->title }}</div>
                <div class="prose prose-sm max-w-none">
                    {!! \Illuminate\Support\Str::of($plan->content_md ?? '—')->markdown() !!}
                </div>
            </div>
        @empty
            <div class="text-sm text-gray-600">Ingen historik ännu.</div>
        @endforelse
    </div>

    <div>
        {{ $plans->links() }}
    </div>
</div>
