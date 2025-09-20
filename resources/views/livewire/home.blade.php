@php
    use App\Services\Billing\PlanService;
    use App\Support\CurrentCustomer;

    $currentCustomerService = app(CurrentCustomer::class);
    $planService = app(PlanService::class);

    $customer = $currentCustomerService->resolveDefaultForUser();
    $subscription = $customer ? $planService->getSubscription($customer) : null;
    $planId = $planService->getPlanId($subscription);
    $hasAccess = $customer ? $planService->hasAccess($customer) : false;

    // Kontrollera om användaren har Premium-funktioner (plan 2 eller 3)
    $hasPremiumAccess = $planId && in_array($planId, [2, 3]);
@endphp
<div class="space-y-8">
    <!-- Förenklad header med personlig hälsning -->
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

    <!-- Huvudfråga och åtgärdskort -->
    <div class="text-center">
        <h2 class="text-3xl font-bold text-gray-900 mb-2">Vad vill du göra nu?</h2>
        <p class="text-lg text-gray-600 mb-8">Välj det som passar dig bäst idag</p>

        <!-- Primära åtgärdskort -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <!-- Skapa texter -->
            <a href="{{ route('ai.compose') }}" class="action-card group hover:scale-105 transform transition-all duration-300">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-2xl p-8 shadow-xl hover:shadow-2xl">
                    <div class="flex flex-col items-center text-center">
                        <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mb-4 group-hover:bg-white/30 transition-colors">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-2">Skapa texter</h3>
                        <p class="text-blue-100">Skapa engagerande innehåll för sociala medier, bloggar och hemsidor</p>
                    </div>
                </div>
            </a>

            <!-- Generera bild -->
            <a href="{{ route('ai.images') }}" class="action-card group hover:scale-105 transform transition-all duration-300">
                <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-2xl p-8 shadow-xl hover:shadow-2xl relative">
                    <div class="flex flex-col items-center text-center">
                        <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mb-4 group-hover:bg-white/30 transition-colors">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m2-2l1.586-1.586a2 2 0 012.828 0L22 14M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-2">Generera bilder</h3>
                        <p class="text-purple-100">Skapa unika bilder med AI för dina inlägg och kampanjer</p>
                        <span class="absolute top-4 right-4 px-2 py-1 bg-yellow-400 text-black text-xs rounded-full font-medium">Beta</span>
                    </div>
                </div>
            </a>

            <!-- Planera & Publicera -->
            <a href="{{ route('planner.index') }}" class="action-card group hover:scale-105 transform transition-all duration-300">
                <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-2xl p-8 shadow-xl hover:shadow-2xl relative">
                    <div class="flex flex-col items-center text-center">
                        <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mb-4 group-hover:bg-white/30 transition-colors">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-2">Planera & Publicera</h3>
                        <p class="text-green-100">Planera ditt innehåll och publicera automatiskt</p>
                        <span class="absolute top-4 right-4 px-2 py-1 bg-violet-200 text-violet-800 text-xs rounded-full font-medium">Ny</span>
                    </div>
                </div>
            </a>
        </div>

        <!-- Sekundära åtgärdskort -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <!-- Veckoplanering -->
            <a href="{{ route('content.weekly') }}" class="secondary-action-card">
                <div class="bg-white rounded-xl p-6 shadow-md hover:shadow-lg border border-gray-200 hover:border-gray-300 transition-all">
                    <div class="flex flex-col items-center text-center">
                        <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center mb-3">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-1">Veckoplanering</h3>
                        <p class="text-sm text-gray-600">Planera veckan</p>
                    </div>
                </div>
            </a>

            <!-- Besöksstatistik -->
            <a href="{{ route('analytics.index') }}" class="secondary-action-card">
                <div class="bg-white rounded-xl p-6 shadow-md hover:shadow-lg border border-gray-200 hover:border-gray-300 transition-all">
                    <div class="flex flex-col items-center text-center">
                        <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center mb-3">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12h4l3 8 4-16 3 8h4"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-1">Besöksstatistik</h3>
                        <p class="text-sm text-gray-600">Se hur det går</p>
                    </div>
                </div>
            </a>

            <!-- Mina hemsidor -->
            <a href="{{ route('insights.index') }}" class="secondary-action-card">
                <div class="bg-white rounded-xl p-6 shadow-md hover:shadow-lg border border-red-400 hover:border-red-600 transition-all relative overflow-hidden">
                    <!-- Trending indikator -->
                    <div class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full animate-pulse"></div>

                    <div class="flex flex-col items-center text-center">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-100 to-indigo-100 rounded-full flex items-center justify-center mb-3">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-1">Content Insights</h3>
                        <p class="text-sm text-gray-600">Trender & idéer</p>
                    </div>
                </div>
            </a>

            <!-- Publicerat innehåll -->
            <a href="{{ route('publications.index') }}" class="secondary-action-card">
                <div class="bg-white rounded-xl p-6 shadow-md hover:shadow-lg border border-gray-200 hover:border-gray-300 transition-all">
                    <div class="flex flex-col items-center text-center">
                        <div class="w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center mb-3">
                            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-1">Publicerat innehåll</h3>
                        <p class="text-sm text-gray-600">Se allt innehåll</p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Kom igång sektion för nya användare -->
    <div class="bg-gradient-to-r from-indigo-50 to-blue-50 rounded-xl p-6 border border-indigo-200">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="font-semibold text-indigo-900 mb-1">Ny här?</h3>
                <p class="text-indigo-700">Lär dig hur du kommer igång med WebGrow AI på bara några minuter</p>
            </div>
            <a href="{{ route('get-started') }}" class="bg-indigo-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-indigo-700 transition-colors">
                Kom igång →
            </a>
        </div>
    </div>

    <!-- Systemnyerter (kompakt version) -->
    <div>
        @livewire('partials.system-news')
    </div>

    <!-- Viktiga kort för återkommande användare (mindre prominent) -->
    @if ($hasPremiumAccess)
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
    @endif

    <!-- Senaste aktivitet (förenklad) -->
    <div class="bg-white rounded-xl shadow-sm border">
        <div class="p-6">
            @livewire('dashboard.weekly-digest-history')
        </div>
    </div>

    @if ($hasPremiumAccess)
    <!-- Utbyggda alternativ (för avancerade användare) -->
    <div class="text-center">
        <button id="show-more-actions" class="text-gray-500 hover:text-gray-700 font-medium">
            Visa fler verktyg ↓
        </button>
    </div>

    <div id="more-actions" class="hidden bg-white rounded-xl shadow-sm border p-6">
        <h3 class="font-medium text-gray-900 mb-4 text-center">Avancerade verktyg</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <a href="{{ route('settings.social') }}" class="compact-link">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2M7 4h10M7 4L5.5 6M17 4l1.5 2"/>
                </svg>
                <div>
                    <div class="font-medium">Sociala kanaler</div>
                    <div class="text-xs text-gray-500">Koppla Facebook, Instagram</div>
                </div>
            </a>
            <a href="{{ route('leads.index') }}" class="compact-link">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7"/>
                </svg>
                <div>
                    <div class="font-medium">Leads</div>
                    <div class="text-xs text-gray-500">Hantera potentiella kunder</div>
                </div>
            </a>
            <a href="{{ route('dashboard') }}" class="compact-link">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                </svg>
                <div>
                    <div class="font-medium">Klassisk vy</div>
                    <div class="text-xs text-gray-500">Tillbaka till gamla dashboarden</div>
                </div>
            </a>
        </div>
    </div>
    @endif

    <style>
        .action-card {
            display: block;
            text-decoration: none;
        }

        .secondary-action-card {
            display: block;
            text-decoration: none;
        }

        .compact-link {
            @apply flex items-start space-x-3 p-4 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition-colors;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const showMoreBtn = document.getElementById('show-more-actions');
            const moreActions = document.getElementById('more-actions');

            if (showMoreBtn && moreActions) {
                showMoreBtn.addEventListener('click', function() {
                    moreActions.classList.toggle('hidden');
                    showMoreBtn.textContent = moreActions.classList.contains('hidden')
                        ? 'Visa fler verktyg ↓'
                        : 'Dölj verktyg ↑';
                });
            }
        });
    </script>
</div>
