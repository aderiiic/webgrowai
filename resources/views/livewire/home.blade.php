
<div class="space-y-6">
    <!-- Förenklad header -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl">
        <div class="px-6 py-8">
            <h1 class="text-2xl font-bold text-white mb-1">
                Hej {{ auth()->user()->name }}!
            </h1>
            <p class="text-blue-100">
                Vi hoppas att du får en bra dag! Vi finns alltid tillgängliga för att
                hjälpa dig: <a href="mailto:info@webbi.se" class="text-blue-200 hover:underline">Mejla oss</a>
            </p>
        </div>
    </div>

    <!-- Interna nyheter och kommande uppdateringar -->

    <!-- Utbyggda alternativ (dold som standard) -->
    <div id="more-actions" class="hidden bg-white rounded-xl shadow-sm border p-6">
        <h3 class="font-medium text-gray-900 mb-3">Fler verktyg</h3>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <a href="{{ route('settings.social') }}" class="compact-link">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2M7 4h10M7 4L5.5 6M17 4l1.5 2"/>
                </svg>
                Sociala kanaler
            </a>
            <a href="{{ route('leads.index') }}" class="compact-link">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7"/>
                </svg>
                Leads
            </a>
            <a href="{{ route('publications.index') }}" class="compact-link">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5"/>
                </svg>
                Publiceringar
            </a>
        </div>
    </div>

    <!-- Viktiga cards (bara de mest relevanta) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm border">
            <div class="p-6">
                @livewire('dashboard.active-customer-card')
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border">
            <div class="p-6">
                @livewire('dashboard.seo-health-card')
            </div>
        </div>
    </div>

    <!-- Senaste aktivitet (förenklad) -->
    <div class="bg-white rounded-xl shadow-sm border">
        <div class="p-6">
            @livewire('dashboard.weekly-digest-history')
        </div>
    </div>

    <style>
        .simple-action-btn {
            @apply p-4 border border-gray-200 rounded-lg hover:border-gray-300 hover:shadow-sm transition-all duration-200;
        }

        .compact-link {
            @apply flex items-center space-x-2 p-3 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition-colors;
        }
    </style>
</div>
