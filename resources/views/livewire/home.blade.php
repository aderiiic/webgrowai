
<div class="space-y-6">
    <!-- Förenklad header -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl">
        <div class="px-6 py-8">
            <h1 class="text-2xl font-bold text-white mb-1">
                Hej {{ auth()->user()->name }}!
            </h1>
            <p class="text-blue-100">
                Välj vad du vill göra idag
            </p>
        </div>
    </div>

    <!-- Mest använda åtgärder (max 4) -->
    <div class="bg-white rounded-xl shadow-sm border p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Kom igång</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <a href="{{ route('seo.audit.run') }}" class="simple-action-btn">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">SEO Kontroll</p>
                        <p class="text-sm text-gray-500">Analysera din webbplats</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('seo.keywords.fetch_analyze') }}" class="simple-action-btn">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1721 9z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Nyckelord</p>
                        <p class="text-sm text-gray-500">Hitta nya möjligheter</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('cro.analyze.run') }}" class="simple-action-btn">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Konvertering</p>
                        <p class="text-sm text-gray-500">Förbättra dina resultat</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('marketing.newsletter') }}" class="simple-action-btn">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 7.89a2 2 0 002.83 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Nyhetsbrev</p>
                        <p class="text-sm text-gray-500">Skapa kampanjer</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Länk till fler alternativ -->
        <div class="mt-4 pt-4 border-t border-gray-100">
            <button class="text-sm text-blue-600 hover:text-blue-700 font-medium" onclick="document.getElementById('more-actions').classList.toggle('hidden')">
                Visa fler alternativ ↓
            </button>
        </div>
    </div>

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
