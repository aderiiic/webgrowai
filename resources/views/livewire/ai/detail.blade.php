<div class="max-w-5xl mx-auto py-10 space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold">{{ $content->title ?: 'AI Innehåll' }}</h1>
        <div class="text-sm text-gray-500">Status: {{ strtoupper($content->status) }} • Provider: {{ $content->provider ?: '—' }}</div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($content->error)
        <div class="alert alert-error text-sm">{{ $content->error }}</div>
    @endif

    @php $mdReady = !empty($md); @endphp

    @if($mdReady)
        <article class="prose max-w-none">
            {!! \Illuminate\Support\Str::of($md)->markdown() !!}
        </article>
    @else
        <div class="text-gray-600 text-sm">Innehåll genereras... uppdatera sidan om en stund.</div>
    @endif

    <!-- Exportera som utkast -->
    <div class="border rounded p-4 bg-gray-50 space-y-3">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-medium">Exportera som utkast</h2>
            <a href="{{ route('ai.export', $content->id) }}" class="btn btn-sm" @disabled(!$mdReady)>Ladda ner Markdown</a>
        </div>
    </div>

    <!-- WordPress publicering -->
    <div class="border rounded p-4 bg-gray-50 space-y-3">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-medium">Publicera till WordPress</h2>
            @if($mdReady && $publishSiteId)
                <button class="btn btn-sm" wire:click="quickDraft">Publicera som utkast</button>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <div>
                <label class="block text-sm text-gray-600">Sajt</label>
                <select wire:model="publishSiteId" class="select select-bordered w-full">
                    @foreach($sites as $s)
                        <option value="{{ $s->id }}">{{ $s->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm text-gray-600">Status</label>
                <select wire:model="publishStatus" class="select select-bordered w-full">
                    <option value="draft">Utkast</option>
                    <option value="publish">Publicera</option>
                    <option value="future">Schemalägg</option>
                </select>
            </div>
            <div>
                <label class="block text-sm text-gray-600">Publiceringstid (vid schemaläggning)</label>
                <input type="datetime-local" wire:model="publishAt" class="input input-bordered w-full">
            </div>
        </div>

        <div>
            <button class="btn btn-primary" wire:click="publish" @disabled(!$mdReady)">Köa publicering</button>
            @unless($mdReady)
                <span class="text-xs text-gray-500 ms-2">Innehållet är inte klart ännu.</span>
            @endunless
        </div>
    </div>

    <!-- Social publicering -->
    <div class="border rounded p-4 bg-gray-50 space-y-3">
        <h2 class="text-lg font-medium">Publicera till Sociala kanaler</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <div>
                <label class="block text-sm text-gray-600">Kanal</label>
                <select wire:model="socialTarget" class="select select-bordered w-full">
                    <option value="facebook">Facebook</option>
                    <option value="instagram">Instagram</option>
                </select>
            </div>
            <div>
                <label class="block text-sm text-gray-600">Publiceringstid (valfritt)</label>
                <input type="datetime-local" wire:model="socialScheduleAt" class="input input-bordered w-full">
                <p class="text-xs text-gray-500 mt-1">Lämna tomt för omedelbar publicering.</p>
            </div>
        </div>

        <div>
            <button class="btn btn-primary" wire:click="queueSocial" @disabled(!$mdReady)">Köa till {{ ucfirst($socialTarget) }}</button>
        </div>
    </div>

    <div class="flex gap-2">
        <a href="{{ route('ai.list') }}" class="btn">Tillbaka</a>
    </div>
</div>
