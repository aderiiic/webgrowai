<div class="max-w-3xl mx-auto py-10 space-y-6">
    <h1 class="text-2xl font-semibold">AI Composer</h1>

    <div class="space-y-4">
        <div>
            <label class="block text-sm font-medium">Mall</label>
            <select wire:model="template_id" class="select select-bordered w-full">
                <option value="">Välj mall...</option>
                @foreach($templates as $tpl)
                    <option value="{{ $tpl->id }}">{{ $tpl->name }}</option>
                @endforeach
            </select>
            @error('template_id') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium">Titel/Ämne</label>
                <input type="text" wire:model.defer="title" class="input input-bordered w-full">
                @error('title') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium">Längd</label>
                <select wire:model="tone" class="select select-bordered w-full">
                    <option value="short">Kort (GPT-4o-mini)</option>
                    <option value="long">Lång (Claude 3.5 Sonnet)</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium">Målgrupp</label>
                <input type="text" wire:model.defer="audience" class="input input-bordered w-full">
            </div>
            <div>
                <label class="block text-sm font-medium">Affärsmål</label>
                <input type="text" wire:model.defer="goal" class="input input-bordered w-full">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium">Nyckelord (komma-separerat)</label>
            <input type="text" wire:model.defer="keywords" class="input input-bordered w-full">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium">Varumärkesröst</label>
                <input type="text" wire:model.defer="brand_voice" class="input input-bordered w-full" placeholder="t.ex. saklig, personlig">
            </div>
            <div>
                <label class="block text-sm font-medium">Kopplad sajt (valfritt)</label>
                <select wire:model="site_id" class="select select-bordered w-full">
                    <option value="">Ingen</option>
                    @foreach($sites as $s)
                        <option value="{{ $s->id }}">{{ $s->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="flex gap-2">
            <button class="btn btn-primary" wire:click="submit">Generera</button>
            <a href="{{ route('ai.list') }}" class="btn">Avbryt</a>
        </div>
    </div>
</div>
