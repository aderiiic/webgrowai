<div class="max-w-4xl space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">{{ $news ? 'Redigera nyhet' : 'Ny intern nyhet' }}</h1>
        <a href="{{ route('admin.news.index') }}" class="px-3 py-2 text-sm bg-white border rounded-xl hover:bg-gray-50">Till lista</a>
    </div>

    <div class="bg-white rounded-2xl border p-6 space-y-6">
        <div>
            <label class="block text-sm font-medium text-gray-700">Titel</label>
            <input type="text" wire:model.defer="title" class="mt-1 w-full px-4 py-2 bg-white border rounded-xl">
            @error('title') <p class="text-sm text-rose-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700">Innehåll (Markdown)</label>
                <textarea rows="10" wire:model.defer="body_md" class="mt-1 w-full px-4 py-2 bg-white border rounded-xl"></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Förhandsvisning</label>
                <div class="mt-1 p-4 bg-gray-50 border rounded-xl prose prose-sm max-w-none">
                    {!! \Illuminate\Support\Str::of($body_md ?: '_Ingen text_')->markdown() !!}
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700">Typ</label>
                <select wire:model="type" class="mt-1 w-full px-4 py-2 bg-white border rounded-xl">
                    <option value="info">Info</option>
                    <option value="feature">Feature</option>
                    <option value="bugfix">Bugfix</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Taggar (komma-separerat)</label>
                <input type="text" wire:model.defer="tags" class="mt-1 w-full px-4 py-2 bg-white border rounded-xl" placeholder="release, billing, ai">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Publicerad</label>
                <input type="datetime-local" wire:model="published_at" class="mt-1 w-full px-4 py-2 bg-white border rounded-xl">
            </div>
        </div>

        <div class="flex items-center gap-6">
            <label class="inline-flex items-center">
                <input type="checkbox" wire:model="is_pinned" class="mr-2">
                <span class="text-sm text-gray-800">Fäst överst</span>
            </label>
            <label class="inline-flex items-center">
                <input type="checkbox" wire:model="is_public" class="mr-2">
                <span class="text-sm text-gray-800">Publik (framtida)</span>
            </label>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.news.index') }}" class="px-4 py-2 bg-white border rounded-xl">Avbryt</a>
            <button wire:click="save" class="px-5 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700">Spara</button>
        </div>
    </div>
</div>
