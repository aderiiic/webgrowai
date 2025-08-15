<div class="max-w-6xl mx-auto py-10 space-y-6">
    <div class="flex items-center justify-between flex-wrap gap-3">
        <h1 class="text-2xl font-semibold">Leads</h1>
        <div class="flex items-center gap-2">
            <label class="text-sm text-gray-600">Sajt</label>
            <select wire:model.live="siteId" class="select select-bordered select-sm">
                <option value="">Alla</option>
                @foreach($sites as $s)
                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                @endforeach
            </select>

            <label class="text-sm text-gray-600">Min score</label>
            <input type="number" min="0" max="100" wire:model.live="minScore" class="input input-bordered input-sm w-24">
        </div>
    </div>

    <div class="border rounded bg-white">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50">
                <tr class="text-left">
                    <th class="px-4 py-2">Lead</th>
                    <th class="px-4 py-2">Score</th>
                    <th class="px-4 py-2">Senast sedd</th>
                    <th class="px-4 py-2">Sessions</th>
                    <th class="px-4 py-2">Detalj</th>
                </tr>
                </thead>
                <tbody>
                @forelse($scores as $row)
                    @php $lead = $row->lead; @endphp
                    <tr class="border-t">
                        <td class="px-4 py-2">
                            @if($lead->email)
                                <span class="font-medium">{{ $lead->email }}</span>
                            @else
                                <span class="font-mono text-gray-600">{{ \Illuminate\Support\Str::limit($lead->visitor_id, 10, '') }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-2">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs {{ $row->score_norm >= 70 ? 'bg-emerald-50 text-emerald-700' : ($row->score_norm >= 40 ? 'bg-amber-50 text-amber-700' : 'bg-rose-50 text-rose-700') }}">{{ $row->score_norm }}</span>
                        </td>
                        <td class="px-4 py-2 text-gray-600">{{ $lead->last_seen?->diffForHumans() ?? '—' }}</td>
                        <td class="px-4 py-2">{{ $lead->sessions }}</td>
                        <td class="px-4 py-2">
                            <a class="btn btn-sm" href="{{ route('leads.detail', $lead->id) }}">Öppna</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-6 text-gray-600">Inga leads ännu.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3">
            {{ $scores->links() }}
        </div>
    </div>
</div>
