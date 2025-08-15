<div class="flex items-center gap-2">
    <label class="text-sm text-gray-600">Kund:</label>
    <select wire:model.change="customerId" class="select select-bordered select-sm max-w-xs">
        <option value="">Välj kund</option>
        @foreach($customers as $c)
            <option value="{{ $c->id }}">{{ $c->name }}</option>
        @endforeach
    </select>
</div>
