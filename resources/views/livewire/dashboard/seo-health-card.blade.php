<div class="border rounded-lg p-4 bg-white shadow-sm"
     wire:poll.10s="loadLatest">
    <div class="flex items-center justify-between gap-3 flex-wrap">
        <h2 class="text-lg font-semibold">SEO Health</h2>

        <div class="flex items-center gap-3 flex-wrap">
            <!-- Månadsbadges (kundnivå) -->
            <span class="inline-flex items-center gap-2 border rounded-full px-3 py-1 text-xs bg-indigo-50 text-indigo-700 border-indigo-200">
        Genereringar ({{ now()->format('Y-m') }}): <span class="font-semibold">{{ $monthGenerateTotal }}</span>
      </span>
            <span class="inline-flex items-center gap-2 border rounded-full px-3 py-1 text-xs bg-emerald-50 text-emerald-700 border-emerald-200">
        Publicerade till WP: <span class="font-semibold">{{ $monthPublishTotal }}</span>
      </span>
            <span class="inline-flex items-center gap-2 border rounded-full px-3 py-1 text-xs bg-amber-50 text-amber-700 border-amber-200">
        Audits: <span class="font-semibold">{{ $monthAuditTotal }}</span>
      </span>

            <!-- Site-väljare + åtgärder -->
            <div class="flex items-center gap-2">
                <label class="text-xs text-gray-600">Sajt</label>
                <select wire:model.live="siteId" class="select select-bordered select-sm">
                    @foreach($sites as $s)
                        <option value="{{ $s->id }}">{{ $s->name }}</option>
                    @endforeach
                </select>
            </div>

            <button class="btn btn-sm" wire:click="runAudit">Kör audit</button>
            <button class="btn btn-sm" wire:click="runAuditAll">Kör alla sajter</button>
            @if($latest)
                <a href="{{ route('seo.audit.detail', $latest->id) }}" class="btn btn-sm">Senaste detaljer</a>
            @endif
            <a href="{{ route('seo.audit.history') }}" class="btn btn-sm">Historik</a>
        </div>
    </div>

    @if(session('success'))
        <div class="mt-3 text-sm text-green-700 bg-green-50 border border-green-200 rounded px-3 py-2">
            {{ session('success') }}
        </div>
    @endif

    <div wire:loading wire:target="siteId,loadLatest" class="mt-3 text-xs text-gray-500">
        Laddar data för vald sajt...
    </div>

    @if($latest)
        <div class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4"
             wire:loading.class="opacity-50" wire:target="siteId,loadLatest">
            <div class="rounded border p-3">
                <div class="text-xs text-gray-500">Performance</div>
                <div class="text-xl font-semibold">{{ $latest->lighthouse_performance ?? '—' }}</div>
            </div>
            <div class="rounded border p-3">
                <div class="text-xs text-gray-500">Accessibility</div>
                <div class="text-xl font-semibold">{{ $latest->lighthouse_accessibility ?? '—' }}</div>
            </div>
            <div class="rounded border p-3">
                <div class="text-xs text-gray-500">Best Practices</div>
                <div class="text-xl font-semibold">{{ $latest->lighthouse_best_practices ?? '—' }}</div>
            </div>
            <div class="rounded border p-3">
                <div class="text-xs text-gray-500">SEO</div>
                <div class="text-xl font-semibold">{{ $latest->lighthouse_seo ?? '—' }}</div>
            </div>
        </div>

        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4"
             wire:loading.class="opacity-50" wire:target="siteId,loadLatest">
            <div class="rounded border p-3">
                <div class="text-xs text-gray-500">Titelproblem</div>
                <div class="text-lg font-semibold">{{ $latest->title_issues }}</div>
            </div>
            <div class="rounded border p-3">
                <div class="text-xs text-gray-500">Meta-problem</div>
                <div class="text-lg font-semibold">{{ $latest->meta_issues }}</div>
            </div>
        </div>

        <div class="mt-2 text-xs text-gray-500"
             wire:loading.class="opacity-50" wire:target="siteId,loadLatest">
            Senaste körning: {{ $latest->created_at->diffForHumans() }}
        </div>
    @else
        <div class="mt-2 text-sm text-gray-600">Ingen audit ännu. Välj sajt och kör en.</div>
    @endif
</div>
