<div class="max-w-2xl mx-auto py-10">
    <h1 class="text-2xl font-semibold mb-6">Onboarding</h1>

    @if($step === 1)
        <div class="space-y-4">
            <h2 class="text-xl font-medium">Steg 1: Skapa kundkonto</h2>
            <div>
                <label class="block text-sm font-medium">Företagsnamn</label>
                <input type="text" wire:model.defer="customer_name" class="input input-bordered w-full">
                @error('customer_name') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium">Kontaktmail (valfritt)</label>
                <input type="email" wire:model.defer="customer_email" class="input input-bordered w-full">
                @error('customer_email') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>
            <button wire:click="createCustomer" class="btn btn-primary">Fortsätt</button>
        </div>
    @elseif($step === 2)
        <div class="space-y-4">
            <h2 class="text-xl font-medium">Steg 2: Lägg till första sajten</h2>
            <div>
                <label class="block text-sm font-medium">Sajtnamn</label>
                <input type="text" wire:model.defer="site_name" class="input input-bordered w-full">
                @error('site_name') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium">URL (inkl. https)</label>
                <input type="url" wire:model.defer="site_url" class="input input-bordered w-full" placeholder="https://example.com">
                @error('site_url') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>
            <button wire:click="createSite" class="btn btn-primary">Fortsätt</button>
        </div>
    @elseif($step === 3)
        <div class="space-y-4">
            <h2 class="text-xl font-medium">Klart!</h2>
            <p>Du kan nu gå till din dashboard och börja använda WebGrow AI.</p>
            <a href="{{ route('dashboard') }}" class="btn btn-success" wire:click.prevent="finish">Gå till Dashboard</a>
        </div>
    @endif
</div>
