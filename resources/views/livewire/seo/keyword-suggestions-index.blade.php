<div class="max-w-6xl mx-auto py-10 space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Nyckelordsförslag</h1>
        <div class="flex items-center gap-2">
            <a href="{{ route('seo.keywords.fetch') }}" class="btn btn-sm">Hämta rankingar</a>
            <a href="{{ route('seo.keywords.analyze') }}" class="btn btn-primary btn-sm">AI-analys</a>
        </div>
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
        @forelse($rows as $r)
            <div class="border rounded p-3 bg-white">
                <div class="flex items-center justify-between">
                    <div class="text-sm min-w-0">
                        <div class="font-medium truncate">{{ $r->url }}</div>
                        <div class="text-xs text-gray-500">Status: {{ strtoupper($r->status) }}</div>
                        @if($r->insights)
                            <ul class="list-disc pl-5 mt-2 text-sm text-gray-700">
                                @foreach(($r->insights ?? []) as $i)
                                    <li>{{ $i }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                    <a class="btn btn-sm" href="{{ route('seo.keywords.detail', $r->id) }}">Detaljer</a>
                </div>
            </div>
        @empty
            <div class="text-sm text-gray-600">Inga förslag ännu. Kör “Hämta rankingar” och sedan “AI-analys”.</div>
        @endforelse
    </div>

    <div>
        {{ $rows->links() }}
    </div>
</div>
