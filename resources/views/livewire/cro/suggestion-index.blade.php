<div class="max-w-6xl mx-auto py-10 space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Konverteringsförslag</h1>
        <a href="{{ route('cro.analyze.run') }}" class="btn btn-primary btn-sm">Kör analys</a>
    </div>

    <div class="flex items-center gap-2">
        <label class="text-sm text-gray-600">Status</label>
        <select wire:model.live="status" class="select select-bordered select-sm">
            <option value="new">Nya</option>
            <option value="applied">Applicerade</option>
            <option value="dismissed">Avfärdade</option>
            <option value="all">Alla</option>
        </select>
    </div>

    <div class="space-y-3">
        @forelse($sugs as $s)
            <div class="border rounded p-3 bg-white">
                <div class="flex items-center justify-between">
                    <div class="text-sm">
                        <div class="font-medium">{{ strtoupper($s->status) }} • {{ $s->wp_type }} #{{ $s->wp_post_id }}</div>
                        <div class="text-gray-600 truncate">{{ $s->url }}</div>
                        @if($s->insights)
                            <ul class="list-disc pl-5 mt-2 text-sm text-gray-700">
                                @foreach(($s->insights ?? []) as $i)
                                    <li>{{ $i }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                    <div class="flex items-center gap-2">
                        <a class="btn btn-sm" href="{{ route('cro.suggestion.detail', $s->id) }}">Detaljer</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-sm text-gray-600">Inga förslag ännu. Kör en analys.</div>
        @endforelse
    </div>

    <div>
        {{ $sugs->links() }}
    </div>
</div>
