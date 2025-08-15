<div class="max-w-3xl space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold">{{ $post ? 'Redigera inlägg' : 'Nytt inlägg' }}</h1>
        <div class="flex items-center gap-2">
            <button class="btn btn-sm" wire:click="save">Spara</button>
            <button class="btn btn-sm btn-primary" wire:click="publish">Publicera</button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="space-y-3 bg-white border rounded p-4">
        <div>
            <label class="block text-sm text-gray-600">Titel</label>
            <input type="text" class="input input-bordered w-full" wire:model.defer="title">
            @error('title') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
        </div>
        <div>
            <label class="block text-sm text-gray-600">Slug (valfritt)</label>
            <input type="text" class="input input-bordered w-full" wire:model.defer="slug" placeholder="auto från titel">
            @error('slug') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
        </div>
        <div>
            <label class="block text-sm text-gray-600">Utdrag (max 300 tecken)</label>
            <textarea class="textarea textarea-bordered w-full" rows="3" wire:model.defer="excerpt"></textarea>
            @error('excerpt') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
        </div>
        <div>
            <label class="block text-sm text-gray-600">Innehåll (Markdown)</label>
            <textarea class="textarea textarea-bordered w-full" rows="12" wire:model.defer="body_md"></textarea>
            @error('body_md') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
        </div>
    </div>
</div>
