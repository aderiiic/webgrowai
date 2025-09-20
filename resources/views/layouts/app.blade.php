@php
    $plans = app(\App\Services\Billing\PlanService::class);
    $current = app(\App\Support\CurrentCustomer::class);
    $customer = $current->get();
    $canAddSite = false;
    if ($customer) {
        $quota = $plans->getQuota($customer, 'sites'); // null = obegränsat
        $count = $customer->sites()->count();
        $canAddSite = ($quota === null) || ($count < $quota);
    }

    $currentProvider = null;
    if ($customer) {
        $siteId = $current->getSiteId();
        if ($siteId && $customer->sites()->whereKey($siteId)->exists()) {
            $integration = \App\Models\Integration::where('site_id', $siteId)->first();
            $currentProvider = $integration?->provider;
        }
    }
@endphp
    <!doctype html>
<html lang="sv">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>WebGrow AI - Din marknadsassistent redo för att växa med dig</title>
    <link rel="icon" type="image/png" href="https://webbiab.s3.eu-north-1.amazonaws.com/webgrowai/WebGrowAI_iconTransparent.png" sizes="32x32">
    @vite(['resources/css/app.css','resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 text-gray-900 antialiased">
<div class="min-h-screen flex">
    <!-- Desktop Sidebar -->
    <aside class="hidden lg:block w-72 bg-white/80 backdrop-blur-lg border-r border-gray-200/50 shadow-xl flex-shrink-0 flex flex-col">
        <!-- Header -->
        <div class="px-6 py-6 border-b border-gray-100">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 group">
                <img src="https://webbiab.s3.eu-north-1.amazonaws.com/webgrowai/WebGrowAI_transparent.png" alt="WebGrow AI" class="h-10 w-auto group-hover:scale-105 transition-transform duration-200">
            </a>

            @php($activeCustomer = app(\App\Support\CurrentCustomer::class)->get())
            @if($activeCustomer)
                <div class="mt-4">
                    <div class="flex items-center space-x-2 px-3 py-2 bg-gradient-to-r from-emerald-50 to-teal-50 rounded-xl border border-emerald-200/50">
                        <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></div>
                        <span class="text-sm font-medium text-emerald-800 truncate" title="{{ $activeCustomer->name }}">
                            {{ \Illuminate\Support\Str::limit($activeCustomer->name, 28) }}
                        </span>
                    </div>

                    @auth
                        <div class="mt-3">
                            <a href="{{ route('billing.portal') }}"
                               class="inline-flex items-center px-3 py-2 rounded-lg text-sm bg-indigo-600 text-white hover:bg-indigo-700">
                                Hantera fakturering
                            </a>
                        </div>
                    @endauth
                </div>
            @endif
        </div>

        <!-- Navigation -->
        <nav class="p-4 space-y-6 overflow-y-auto flex-1">
            @include('partials.navigation-menu')
        </nav>
    </aside>

    <!-- Mobile menu overlay -->
    <div id="mobile-menu-overlay" class="fixed inset-0 z-40 bg-black bg-opacity-50 hidden lg:hidden"></div>

    <!-- Mobile Sidebar -->
    <aside id="mobile-sidebar" class="fixed top-0 left-0 w-80 h-full bg-white shadow-2xl transform -translate-x-full transition-transform duration-300 ease-in-out z-50 lg:hidden">
        <!-- Mobile Header -->
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
                <img src="https://webbiab.s3.eu-north-1.amazonaws.com/webgrowai/WebGrowAI_transparent.png" alt="WebGrow AI" class="h-8 w-auto">
            </a>
            <button id="close-mobile-menu" class="p-2 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        @if($activeCustomer)
            <div class="px-6 py-4 border-b border-gray-100">
                <div class="flex items-center space-x-2 px-3 py-2 bg-gradient-to-r from-emerald-50 to-teal-50 rounded-xl border border-emerald-200/50">
                    <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></div>
                    <span class="text-sm font-medium text-emerald-800 truncate" title="{{ $activeCustomer->name }}">
                        {{ \Illuminate\Support\Str::limit($activeCustomer->name, 24) }}
                    </span>
                </div>
            </div>
        @endif

        <!-- Mobile Navigation -->
        <nav class="p-4 space-y-6 overflow-y-auto h-full pb-20">
            @include('partials.navigation-menu')
        </nav>
    </aside>

    <!-- Mobile menu toggle button -->
    <button id="mobile-menu-toggle" class="fixed top-4 left-4 z-30 lg:hidden p-2 bg-white rounded-lg shadow-lg hover:shadow-xl transition-shadow">
        <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
    </button>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col min-w-0">
        <!-- Top Navigation -->
        @include('navigation-menu')

        @auth
            @livewire('partials.trial-badge')
            @livewire('partials.usage-banner')
            <livewire:components.news-popup />
        @endauth

        <!-- Page Content -->
        <main class="flex-1 px-4 sm:px-6 lg:px-8 py-6 lg:py-8 lg:pl-8 pl-16">
            <div class="max-w-7xl mx-auto">
                @if(session('success'))
                    <div class="mb-6 p-4 bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-200 rounded-xl">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                {{ $slot ?? '' }}
                @yield('content')
            </div>
        </main>
    </div>
</div>

<!-- Mobile menu JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
        const mobileSidebar = document.getElementById('mobile-sidebar');
        const mobileMenuOverlay = document.getElementById('mobile-menu-overlay');
        const closeMobileMenu = document.getElementById('close-mobile-menu');

        function openMobileMenu() {
            mobileSidebar.classList.remove('-translate-x-full');
            mobileMenuOverlay.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeMobileMenuFunc() {
            mobileSidebar.classList.add('-translate-x-full');
            mobileMenuOverlay.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        if (mobileMenuToggle) {
            mobileMenuToggle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                openMobileMenu();
            });
        }

        if (closeMobileMenu) {
            closeMobileMenu.addEventListener('click', closeMobileMenuFunc);
        }

        if (mobileMenuOverlay) {
            mobileMenuOverlay.addEventListener('click', closeMobileMenuFunc);
        }

        // Stäng menyn när man klickar på en länk
        const mobileNavLinks = mobileSidebar.querySelectorAll('a');
        mobileNavLinks.forEach(link => {
            link.addEventListener('click', closeMobileMenuFunc);
        });
    });
</script>

@livewireScripts
@stack('scripts')
</body>
</html>
