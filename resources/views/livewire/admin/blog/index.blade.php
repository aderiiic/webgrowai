<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Nyheter (Blogg)</h1>
        <a href="{{ route('admin.blog.edit', 0) }}" class="btn btn-sm btn-primary">Nytt inlägg</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="flex items-center gap-2">
        <input type="text" wire:model.live="q" class="input input-bordered input-sm w-64" placeholder="Sök titel...">
    </div>

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($posts as $p)
            <div class="border rounded p-4 bg-white space-y-2">
                <div class="text-xs text-gray-500">{{ $p->published_at ? 'Publicerad '.$p->published_at->format('Y-m-d') : 'Utkast' }}</div>
                <div class="font-medium">{{ $p->title }}</div>
                <div class="text-xs text-gray-500 truncate">{{ $p->slug }}</div>
                <div class="flex items-center gap-2 pt-2">
                    <a href="{{ route('admin.blog.edit', $p->id) }}" class="btn btn-sm">Redigera</a>
                    @if($p->published_at)
                        <button class="btn btn-sm" wire:click="unpublish({{ $p->id }})">Avpublicera</button>
                    @else
                        <button class="btn btn-sm" wire:click="publish({{ $p->id }})">Publicera</button>
                    @endif
                    <button class="btn btn-sm" wire:click="delete({{ $p->id }})">Radera</button>
                </div>
            </div>
        @endforeach
    </div>

    <div>{{ $posts->links() }}</div>
</div>
