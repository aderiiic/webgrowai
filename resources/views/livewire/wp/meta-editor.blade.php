<div class="max-w-3xl mx-auto py-10 space-y-6">
    <h1 class="text-2xl font-semibold">SEO Meta – {{ $site->name }}</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="space-y-6">
        <div class="border rounded p-4 bg-gray-50">
            <div class="text-sm text-gray-600">Nuvarande</div>
            <div class="mt-2">
                <div class="text-sm"><span class="font-medium">Titel:</span> {{ $currentTitle ?: '—' }}</div>
                <div class="text-sm mt-1"><span class="font-medium">Excerpt:</span> {{ $currentExcerpt ?: '—' }}</div>
            </div>
        </div>

        <div class="border rounded p-4">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-medium">Förslag</h2>
                <button class="btn btn-sm" wire:click="generateSuggestions">Generera igen</button>
            </div>

            <div class="mt-4 space-y-4">
                <div>
                    <label class="block text-sm font-medium">Titel (<= 60 tecken rekommenderas)</label>
                    <input type="text" wire:model.defer="title" class="input input-bordered w-full">
                    <div class="text-xs text-gray-500 mt-1">{{ mb_strlen($title) }}/120 tecken</div>
                    @error('title') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium">Meta description (<= 155 tecken rekommenderas)</label>
                    <textarea rows="3" wire:model.defer="meta" class="textarea textarea-bordered w-full"></textarea>
                    <div class="text-xs text-gray-500 mt-1">{{ mb_strlen($meta) }}/200 tecken</div>
                    @error('meta') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                </div>

                <div class="flex gap-2">
                    <button class="btn btn-primary" wire:click="apply">Spara till WordPress</button>
                    <a href="{{ route('wp.posts.index', $site) }}" class="btn">Tillbaka</a>
                </div>
            </div>
        </div>

        <div class="border rounded p-4 bg-gray-50">
            <div class="text-sm text-gray-600">Tips</div>
            <ul class="list-disc ms-5 text-sm mt-2 text-gray-700">
                <li>Titel: håll den under 60 tecken och börja med viktigaste nyckelordet.</li>
                <li>Meta: 120–155 tecken, aktiv röst och en tydlig nytta.</li>
            </ul>
        </div>
    </div>
</div>
