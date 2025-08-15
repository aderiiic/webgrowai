<div class="flex items-center gap-2">
    <div class="hidden md:flex items-center gap-2">
        <label class="text-xs text-gray-500">Kund</label>
        <select wire:model.live="customerId" class="select select-bordered select-xs">
            <option value="">—</option>
            @foreach($customers as $c)
                <option value="{{ $c->id }}">{{ $c->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="hidden md:flex items-center gap-2">
        <label class="text-xs text-gray-500">Sajt</label>
        <select wire:model.live="siteId" class="select select-bordered select-xs" @disabled(!$customerId)>
            <option value="">—</option>
            @foreach($sites as $s)
                <option value="{{ $s->id }}">{{ $s->name }}</option>
            @endforeach
        </select>
    </div>
</div>
