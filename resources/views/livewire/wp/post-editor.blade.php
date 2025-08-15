<div class="max-w-3xl mx-auto py-10 space-y-6">
    <h1 class="text-2xl font-semibold">{{ $postId ? 'Redigera inlägg' : 'Nytt inlägg' }}</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="space-y-4">
        <div>
            <label class="block text-sm font-medium">Titel</label>
            <input type="text" wire:model.defer="title" class="input input-bordered w-full">
            @error('title') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium">Innehåll (HTML eller Markdown)</label>
            <textarea rows="12" wire:model.defer="content" class="textarea textarea-bordered w-full"></textarea>
            @error('content') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center gap-3">
            <label class="text-sm">Status</label>
            <select wire:model="status" class="select select-bordered select-sm">
                <option value="draft">Utkast</option>
                <option value="publish">Publicera</option>
                <option value="pending">Väntande</option>
                <option value="future">Schemalagd</option>
            </select>
        </div>

        <div class="flex gap-2">
            <button wire:click="save" class="btn btn-primary">Spara</button>
            <a href="{{ route('wp.posts.index', $site) }}" class="btn">Tillbaka</a>
        </div>
    </div>
</div>
