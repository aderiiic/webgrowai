<div class="max-w-6xl mx-auto py-10 space-y-6">
    <h1 class="text-2xl font-semibold">Välkommen</h1>
    @livewire('dashboard.active-customer-card')

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @livewire('dashboard.seo-health-card')
        @livewire('dashboard.sites-audit-chips')
    </div>
    @livewire('dashboard.weekly-digest-history')
</div>
