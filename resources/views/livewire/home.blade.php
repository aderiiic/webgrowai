<?php /* PHP (Blade) */ ?>
<div class="space-y-6">
    <h1 class="text-2xl font-semibold">Dashboard</h1>

    <!-- Snabbåtgärder -->
    <div class="bg-white border rounded p-4">
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('seo.audit.run') }}" class="btn btn-sm">Kör SEO Audit</a>
            <a href="{{ route('seo.keywords.fetch') }}" class="btn btn-sm">Hämta rankingar</a>
            <a href="{{ route('seo.keywords.analyze') }}" class="btn btn-sm">AI-analys (SEO)</a>
            <a href="{{ route('cro.analyze.run') }}" class="btn btn-sm">Kör CRO-analys</a>
            <a href="{{ route('marketing.newsletter') }}" class="btn btn-sm">Skapa nyhetsbrev</a>
            <a href="{{ route('settings.social') }}" class="btn btn-sm">Koppla sociala kanaler</a>
        </div>
    </div>

    <!-- Översiktspanel(er) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @livewire('dashboard.active-customer-card')
        @livewire('dashboard.seo-health-card')
    </div>

    @livewire('dashboard.sites-audit-chips')

    <!-- Ny historikmodul(er) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @livewire('dashboard.weekly-digest-history')
        <!-- Här kan vi lägga annan historik/nytt -->
        <div class="border rounded p-4 bg-white">
            <h2 class="text-lg font-medium mb-2">Snabblänkar</h2>
            <ul class="list-disc pl-5 text-sm space-y-1">
                <li><a class="text-indigo-600 hover:underline" href="{{ route('seo.keywords.index') }}">Nyckelordsförslag</a></li>
                <li><a class="text-indigo-600 hover:underline" href="{{ route('cro.suggestions.index') }}">CRO-förslag</a></li>
                <li><a class="text-indigo-600 hover:underline" href="{{ route('leads.index') }}">Leadlista</a></li>
                <li><a class="text-indigo-600 hover:underline" href="{{ route('publications.index') }}">Publiceringar</a></li>
            </ul>
        </div>
    </div>
</div>
