
<!DOCTYPE html>
<html lang="{{ app()->getLocale() === 'en' ? 'en' : 'sv' }}">
<head>
    @php
        $appName    = 'WebGrow AI';
        $baseUrl    = url('/');
        $pageTitle  = isset($title) ? trim($title) : $appName;
        $desc       = isset($description) && $description ? strip_tags($description) : 'WebGrow AI automatiserar SEO, CRO och publicering till WordPress & Shopify. Få fler leads och mer trafik med modern AI och smarta arbetsflöden.';
        $canonical  = url()->current();
        $ogImage    = $ogImage ?? 'https://webbiab.s3.eu-north-1.amazonaws.com/webgrowai/WebGrowAI_transparent.png';
        $siteName   = $appName;
        $noindex    = isset($noindex) ? (bool)$noindex : !app()->environment('production');

        $isEn = request()->segment(1) === 'en';
        $path = trim(request()->path(), '/');
        if ($isEn) {
            $path = ltrim(preg_replace('/^en\/?/i', '', $path), '/');
        }
        $svUrl = $path ? url('/'.$path) : url('/');
        $enUrl = url('/en'.($path ? '/'.$path : ''));

        $ogLocale = app()->getLocale() === 'en' ? 'en_US' : 'sv_SE';
    @endphp

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-9QG4WL88BW"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-9QG4WL88BW');
    </script>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $desc }}">
    <meta name="robots" content="{{ $noindex ? 'noindex, nofollow' : 'index, follow' }}">
    <link rel="canonical" href="{{ $canonical }}">

    <!-- Favicons -->
    <link rel="icon" type="image/png" href="https://webbiab.s3.eu-north-1.amazonaws.com/webgrowai/WebGrowAI_iconTransparent.png" sizes="32x32">
    <link rel="apple-touch-icon" href="https://webbiab.s3.eu-north-1.amazonaws.com/webgrowai/WebGrowAI_iconTransparent.png">
    <meta name="theme-color" content="#4f46e5">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:locale" content="{{ $ogLocale }}">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $desc }}">
    <meta property="og:url" content="{{ $canonical }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:image:alt" content="WebGrow AI">
    <meta property="og:image:type" content="image/png">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $desc }}">
    <meta name="twitter:image" content="{{ $ogImage }}">

    <!-- Performance hints -->
    <link rel="preconnect" href="https://webbiab.s3.eu-north-1.amazonaws.com" crossorigin>
    <link rel="dns-prefetch" href="//webbiab.s3.eu-north-1.amazonaws.com">
    <link rel="preconnect" href="https://unpkg.com" crossorigin>
    <link rel="dns-prefetch" href="//unpkg.com">

    @vite(['resources/css/app.css','resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        @keyframes scroll {
            0% {
                transform: translateX(0);
            }
            100% {
                transform: translateX(-50%);
            }
        }

        @keyframes float1 {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            33% { transform: translateY(-8px) rotate(1deg); }
            66% { transform: translateY(4px) rotate(-0.5deg); }
        }

        @keyframes float2 {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            25% { transform: translateY(-6px) rotate(-1deg); }
            75% { transform: translateY(6px) rotate(1deg); }
        }

        @keyframes float3 {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            40% { transform: translateY(-10px) rotate(0.5deg); }
            80% { transform: translateY(2px) rotate(-1deg); }
        }

        @keyframes float4 {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-5px) rotate(-0.5deg); }
            75% { transform: translateY(8px) rotate(1deg); }
        }

        @keyframes float5 {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            30% { transform: translateY(-12px) rotate(1deg); }
            60% { transform: translateY(3px) rotate(-0.5deg); }
        }

        @keyframes float6 {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            45% { transform: translateY(-7px) rotate(-1deg); }
            85% { transform: translateY(5px) rotate(0.5deg); }
        }

        .animate-scroll {
            animation: scroll 40s linear infinite;
        }

        .animate-float-1 {
            animation: float1 6s ease-in-out infinite;
        }

        .animate-float-2 {
            animation: float2 7s ease-in-out infinite;
        }

        .animate-float-3 {
            animation: float3 8s ease-in-out infinite;
        }

        .animate-float-4 {
            animation: float4 5.5s ease-in-out infinite;
        }

        .animate-float-5 {
            animation: float5 6.5s ease-in-out infinite;
        }

        .animate-float-6 {
            animation: float6 7.5s ease-in-out infinite;
        }
    </style>

    <script id="Cookiebot" src="https://consent.cookiebot.com/uc.js" data-cbid="aefaf1d4-8589-4f86-b031-4023368b8312" data-blockingmode="auto" type="text/javascript"></script>
</head>
<body class="bg-slate-50 text-slate-900 antialiased">

<!-- Header (publik) -->
<header class="border-b border-slate-200/80 bg-white/90 backdrop-blur-xl supports-[backdrop-filter]:bg-white/80 sticky top-0 z-50 shadow-sm" x-data="{ mobileMenuOpen: false }">
    <div class="max-w-7xl mx-auto px-4 py-4">
        <div class="flex items-center justify-between">
            <!-- Logo -->
            <a href="{{ url('/') }}" class="flex items-center space-x-3 group">
                <img src="https://webbiab.s3.eu-north-1.amazonaws.com/webgrowai/WebGrowAI_transparent.png" alt="WebGrow AI" class="h-10 w-auto group-hover:scale-105 transition-transform duration-200">
            </a>

            <!-- Desktop Navigation -->
            <nav class="hidden lg:flex items-center space-x-8">
                <a href="{{ url('/#features') }}" class="text-slate-600 hover:text-indigo-600 font-medium transition-colors duration-200 relative group">
                    {{ __('homepage.nav_functions') }}
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-indigo-600 transition-all duration-200 group-hover:w-full"></span>
                </a>
                <a href="{{ url('/#pricing') }}" class="text-slate-600 hover:text-indigo-600 font-medium transition-colors duration-200 relative group">
                    {{ __('homepage.nav_pricing') }}
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-indigo-600 transition-all duration-200 group-hover:w-full"></span>
                </a>
                <a href="{{ url('/#testimonials') }}" class="text-slate-600 hover:text-indigo-600 font-medium transition-colors duration-200 relative group">
                    {{ __('homepage.nav_customers') }}
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-indigo-600 transition-all duration-200 group-hover:w-full"></span>
                </a>
                <a href="{{ url('/#faq') }}" class="text-slate-600 hover:text-indigo-600 font-medium transition-colors duration-200 relative group">
                    {{ __('homepage.nav_faq') }}
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-indigo-600 transition-all duration-200 group-hover:w-full"></span>
                </a>
                <a href="{{ route('news.index') }}" class="text-slate-600 hover:text-indigo-600 font-medium transition-colors duration-200 relative group">
                    {{ __('homepage.nav_news') }}
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-indigo-600 transition-all duration-200 group-hover:w-full"></span>
                </a>
                <a href="{{ url('/#kontakt') }}" class="text-slate-600 hover:text-indigo-600 font-medium transition-colors duration-200 relative group">
                    {{ __('homepage.nav_contact') }}
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-indigo-600 transition-all duration-200 group-hover:w-full"></span>
                </a>
            </nav>

            <!-- Language Switcher (Desktop) -->
            <div class="hidden lg:flex items-center gap-2 ml-4" title="Language">
                <a href="{{ $svUrl }}" class="inline-flex items-center p-1.5 rounded-md border transition-colors {{ app()->getLocale()==='sv' ? 'border-indigo-500 bg-indigo-50' : 'border-slate-200 hover:bg-slate-100' }}" aria-label="Svenska">
                    <!-- Sweden Flag -->
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 480" class="w-6 h-4">
                        <path fill="#006aa7" d="M0 0h640v480H0z"/>
                        <path fill="#fecc00" d="M0 186.2h640v107.6H0zM186.2 0h107.6v480H186.2z"/>
                    </svg>
                </a>
                <a href="{{ $enUrl }}" class="inline-flex items-center p-1.5 rounded-md border transition-colors {{ app()->getLocale()==='en' ? 'border-indigo-500 bg-indigo-50' : 'border-slate-200 hover:bg-slate-100' }}" aria-label="English">
                    <!-- UK Flag -->
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 60 30" class="w-6 h-4">
                        <clipPath id="s"><path d="M0,0 v30 h60 v-30 z"/></clipPath>
                        <clipPath id="t"><path d="M30,15 h30 v15 z v15 h-30 z h-30 v-15 z v-15 h30 z"/></clipPath>
                        <g clip-path="url(#s)">
                            <path d="M0,0 v30 h60 v-30 z" fill="#012169"/>
                            <path d="M0,0 L60,30 M60,0 L0,30" stroke="#fff" stroke-width="6"/>
                            <path d="M0,0 L60,30 M60,0 L0,30" clip-path="url(#t)" stroke="#C8102E" stroke-width="4"/>
                            <path d="M30,0 v30 M0,15 h60" stroke="#fff" stroke-width="10"/>
                            <path d="M30,0 v30 M0,15 h60" stroke="#C8102E" stroke-width="6"/>
                        </g>
                    </svg>
                </a>
            </div>

            <!-- Desktop CTA Buttons -->
            <div class="hidden lg:flex items-center space-x-3">
                <a href="{{ route('login') }}" class="px-4 py-2 text-slate-700 font-medium rounded-lg border border-slate-300 hover:bg-slate-50 hover:border-slate-400 transition-all duration-200">
                    {{ __('homepage.nav_login') }}
                </a>
                @if(Route::has('register'))
                    <a href="{{ route('register') }}"
                       class="group relative px-6 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-200"
                       data-lead-cta="header_register">
                        <span class="relative z-10">{{ __('homepage.nav_register') }}</span>
                        <div class="absolute inset-0 bg-gradient-to-r from-indigo-700 to-purple-700 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200"></div>
                    </a>
                @endif
            </div>

            <!-- Mobile menu button -->
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden p-2 text-slate-600 hover:text-slate-800 hover:bg-slate-100 rounded-lg transition-colors duration-200">
                <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
                <svg x-show="mobileMenuOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Mobile Navigation -->
        <div x-show="mobileMenuOpen" x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="lg:hidden mt-4 pb-4">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-lg p-6 space-y-4">
                <a href="{{ url('/#features') }}" class="block text-slate-700 font-medium py-2 border-b border-slate-100 hover:text-indigo-600 transition-colors duration-200">
                    Funktioner
                </a>
                <a href="{{ url('/#pricing') }}" class="block text-slate-700 font-medium py-2 border-b border-slate-100 hover:text-indigo-600 transition-colors duration-200">
                    Priser
                </a>
                <a href="{{ url('/#testimonials') }}" class="block text-slate-700 font-medium py-2 border-b border-slate-100 hover:text-indigo-600 transition-colors duration-200">
                    Kunder
                </a>
                <a href="{{ url('/#faq') }}" class="block text-slate-700 font-medium py-2 border-b border-slate-100 hover:text-indigo-600 transition-colors duration-200">
                    FAQ
                </a>
                <a href="{{ route('news.index') }}" class="block text-slate-700 font-medium py-2 border-b border-slate-100 hover:text-indigo-600 transition-colors duration-200">
                    Nyheter
                </a>
                <a href="{{ url('/#contact') }}" class="block text-slate-700 font-medium py-2 border-b border-slate-100 hover:text-indigo-600 transition-colors duration-200">
                    Kontakt
                </a>

                <!-- Mobile Language Switcher -->
                <div class="flex items-center gap-3 pt-2">
                    <a href="{{ $svUrl }}" class="inline-flex items-center p-1.5 rounded-md border transition-colors {{ app()->getLocale()==='sv' ? 'border-indigo-500 bg-indigo-50' : 'border-slate-200 hover:bg-slate-100' }}" aria-label="Svenska">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 480" class="w-7 h-5">
                            <path fill="#006aa7" d="M0 0h640v480H0z"/>
                            <path fill="#fecc00" d="M0 186.2h640v107.6H0zM186.2 0h107.6v480H186.2z"/>
                        </svg>
                    </a>
                    <a href="{{ $enUrl }}" class="inline-flex items-center p-1.5 rounded-md border transition-colors {{ app()->getLocale()==='en' ? 'border-indigo-500 bg-indigo-50' : 'border-slate-200 hover:bg-slate-100' }}" aria-label="English">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 60 30" class="w-7 h-5">
                            <clipPath id="s-m"><path d="M0,0 v30 h60 v-30 z"/></clipPath>
                            <clipPath id="t-m"><path d="M30,15 h30 v15 z v15 h-30 z h-30 v-15 z v-15 h30 z"/></clipPath>
                            <g clip-path="url(#s-m)">
                                <path d="M0,0 v30 h60 v-30 z" fill="#012169"/>
                                <path d="M0,0 L60,30 M60,0 L0,30" stroke="#fff" stroke-width="6"/>
                                <path d="M0,0 L60,30 M60,0 L0,30" clip-path="url(#t-m)" stroke="#C8102E" stroke-width="4"/>
                                <path d="M30,0 v30 M0,15 h60" stroke="#fff" stroke-width="10"/>
                                <path d="M30,0 v30 M0,15 h60" stroke="#C8102E" stroke-width="6"/>
                            </g>
                        </svg>
                    </a>
                </div>

                <!-- Mobile CTA buttons -->
                <div class="pt-4 space-y-3">
                    <a href="{{ route('login') }}" class="block w-full text-center px-4 py-3 text-slate-700 font-medium rounded-lg border border-slate-300 hover:bg-slate-50 transition-colors duration-200">
                        Logga in
                    </a>
                    @if(Route::has('register'))
                        <a href="{{ route('register') }}"
                           class="block w-full text-center px-4 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-lg hover:from-indigo-700 hover:to-purple-700 transition-colors duration-200 shadow-md"
                           data-lead-cta="header_register_mobile">
                            Prova gratis
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Page content -->
<main class="min-h-[60vh]">
    @yield('content')
</main>

<!-- Footer -->
<footer class="bg-slate-900 text-white">
    <div class="max-w-7xl mx-auto px-4 py-12">
        <div class="grid md:grid-cols-4 gap-8">
            <!-- Company -->
            <div>
                <h3 class="text-lg font-bold mb-4">WebGrow AI</h3>
                <p class="text-slate-400 text-sm mb-4">
                    Moderna AI-verktyg för SEO, content och konverteringsoptimering.
                </p>
                <p class="text-slate-500 text-xs">
                    Drivs av <a href="https://www.webbi.se" target="_blank" rel="noopener" class="text-slate-400 hover:text-white underline">Webbi AB</a><br>
                    Org.nr: 559331-3140
                </p>
            </div>

            <!-- Product -->
            <div>
                <h4 class="font-semibold mb-4">Produkt</h4>
                <ul class="space-y-2 text-sm text-slate-400">
                    <li><a href="{{ url('/#features') }}" class="hover:text-white transition-colors">Funktioner</a></li>
                    <li><a href="{{ url('/#pricing') }}" class="hover:text-white transition-colors">Priser</a></li>
                    <li><a href="{{ route('register') }}" class="hover:text-white transition-colors">Prova gratis</a></li>
                </ul>
            </div>

            <!-- Company -->
            <div>
                <h4 class="font-semibold mb-4">Företag</h4>
                <ul class="space-y-2 text-sm text-slate-400">
                    <li><a href="https://www.webbi.se" target="_blank" rel="noopener" class="hover:text-white transition-colors">Om Webbi AB</a></li>
                    <li><a href="{{ route('privacy') }}" class="hover:text-white transition-colors">Integritetspolicy</a></li>
                    <li><a href="{{ route('terms') }}" class="hover:text-white transition-colors">Användarvillkor</a></li>
                </ul>
            </div>

            <!-- Follow Us -->
            <div>
                <h4 class="font-semibold mb-4">Följ oss</h4>
                <div class="flex space-x-4">
                    <a href="https://www.facebook.com/webgrowai" target="_blank" rel="noopener" class="w-10 h-10 bg-slate-800 hover:bg-blue-600 rounded-lg flex items-center justify-center transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>
                    <a href="https://www.instagram.com/webgrow_ai/" target="_blank" rel="noopener" class="w-10 h-10 bg-slate-800 hover:bg-pink-600 rounded-lg flex items-center justify-center transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                    </a>
                    <a href="https://www.linkedin.com/showcase/108839813/" target="_blank" rel="noopener" class="w-10 h-10 bg-slate-800 hover:bg-blue-700 rounded-lg flex items-center justify-center transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                        </svg>
                    </a>
                </div>
                <div class="mt-4">
                    <a href="https://www.webbi.se" target="_blank" rel="noopener" class="text-sm text-slate-400 hover:text-white transition-colors flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                        </svg>
                        webbi.se
                    </a>
                </div>
            </div>
        </div>

        <div class="border-t border-slate-800 mt-8 pt-8 text-center text-sm text-slate-500">
            <p>&copy; {{ date('Y') }} Webbi AB. Alla rättigheter förbehållna.</p>
        </div>
    </div>
</footer>

<!-- This site is converting visitors into subscribers and customers with OptinMonster - https://optinmonster.com -->
<script>(function(d,u,ac){var s=d.createElement('script');s.type='text/javascript';s.src='https://a.omappapi.com/app/js/api.min.js';s.async=true;s.dataset.user=u;s.dataset.account=ac;d.getElementsByTagName('head')[0].appendChild(s);})(document,344322,364204);</script>
<!-- / https://optinmonster.com -->
</body>
</html>
