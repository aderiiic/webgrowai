
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
    <div class="max-w-7xl mx-auto px-4 py-16">
        <!-- Main footer content -->
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
            <!-- Company info -->
            <div class="lg:col-span-1">
                <div class="flex items-center mb-4">
                    <img src="https://webbiab.s3.eu-north-1.amazonaws.com/webgrowai/WebGrowAI_transparent.png" alt="WebGrow AI" class="h-8 w-auto filter brightness-0 invert opacity-80">
                </div>
                <p class="text-slate-300 leading-relaxed mb-6">
                    {{ __('homepage.footer_desc') }}
                </p>

                <!-- Social links -->
                <div class="flex items-center space-x-3">
                    <a href="#" class="p-2 bg-slate-800 hover:bg-indigo-600 text-slate-300 hover:text-white rounded-lg transition-colors duration-200">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                        </svg>
                    </a>
                    <a href="#" class="p-2 bg-slate-800 hover:bg-indigo-600 text-slate-300 hover:text-white rounded-lg transition-colors duration-200">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.224.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.402.162-1.499-.698-2.436-2.888-2.436-4.649 0-3.785 2.75-7.262 7.929-7.262 4.163 0 7.398 2.967 7.398 6.931 0 4.136-2.607 7.464-6.227 7.464-1.216 0-2.357-.631-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24.009 12.017 24.009c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641.001.017 0z.017 0"/>
                        </svg>
                    </a>
                    <a href="#" class="p-2 bg-slate-800 hover:bg-indigo-600 text-slate-300 hover:text-white rounded-lg transition-colors duration-200">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                        </svg>
                    </a>
                    <a href="#" class="p-2 bg-slate-800 hover:bg-indigo-600 text-slate-300 hover:text-white rounded-lg transition-colors duration-200">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Product links -->
            <div>
                <h4 class="font-bold text-white mb-4">{{ __('homepage.footer_column_1_title') }}</h4>
                <ul class="space-y-3">
                    <li>
                        <a href="{{ url('/#features') }}" class="text-slate-300 hover:text-indigo-400 transition-colors duration-200 flex items-center group">
                            <svg class="w-4 h-4 mr-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            {{ __('homepage.footer_column_1_1') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/#pricing') }}" class="text-slate-300 hover:text-indigo-400 transition-colors duration-200 flex items-center group">
                            <svg class="w-4 h-4 mr-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            {{ __('homepage.footer_column_1_2') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/#faq') }}" class="text-slate-300 hover:text-indigo-400 transition-colors duration-200 flex items-center group">
                            <svg class="w-4 h-4 mr-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            {{ __('homepage.footer_column_1_3') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/#testimonials') }}" class="text-slate-300 hover:text-indigo-400 transition-colors duration-200 flex items-center group">
                            <svg class="w-4 h-4 mr-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            {{ __('homepage.footer_column_1_4') }}
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Resources links -->
            <div>
                <h4 class="font-bold text-white mb-4">{{ __('homepage.footer_column_2_title') }}</h4>
                <ul class="space-y-3">
                    <li>
                        <a href="{{ route('news.index') }}" class="text-slate-300 hover:text-indigo-400 transition-colors duration-200 flex items-center group">
                            <svg class="w-4 h-4 mr-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            {{ __('homepage.footer_column_2_1') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('login') }}" class="text-slate-300 hover:text-indigo-400 transition-colors duration-200 flex items-center group">
                            <svg class="w-4 h-4 mr-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            {{ __('homepage.footer_column_2_2') }}
                        </a>
                    </li>
                    @if(Route::has('register'))
                        <li>
                            <a href="{{ route('register') }}" class="text-slate-300 hover:text-indigo-400 transition-colors duration-200 flex items-center group" data-lead-cta="footer_register">
                                <svg class="w-4 h-4 mr-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                {{ __('homepage.footer_column_2_3') }}
                            </a>
                        </li>
                    @endif
                </ul>
            </div>

            <!-- Contact & Support -->
            <div>
                <h4 class="font-bold text-white mb-4">{{ __('homepage.footer_column_3_title') }}</h4>
                <ul class="space-y-3">
                    <li>
                        <a href="{{ url('/#pricing') }}" class="text-slate-300 hover:text-indigo-400 transition-colors duration-200 flex items-center group" data-lead-cta="footer_book_demo">
                            <svg class="w-4 h-4 mr-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            {{ __('homepage.footer_column_3_title') }}
                        </a>
                    </li>
                    <li class="text-slate-300">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            info@webbi.se
                        </div>
                    </li>
                </ul>

                <!-- CTA in footer -->
                <div class="mt-6 p-4 bg-gradient-to-r from-indigo-600/20 to-purple-600/20 rounded-xl border border-indigo-500/30">
                    <p class="text-sm text-indigo-300 font-medium mb-2">{{ __('homepage.footer_column_3_cta_title') }}</p>
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center text-sm font-semibold text-indigo-400 hover:text-indigo-300 transition-colors duration-200"
                       data-lead-cta="footer_cta_register">
                        {{ __('homepage.footer_column_3_cta_14_days') }}
                        <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Bottom section -->
        <div class="border-t border-slate-700 pt-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="text-sm text-slate-400">
                    © {{ date('Y') }} {{ __('homepage.footer_copyright') }}
                </div>
                <div class="flex items-center gap-6 text-sm">
                    <a href="#" class="text-slate-400 hover:text-slate-300 transition-colors duration-200">{{ __('homepage.footer_privacy_policy') }}</a>
                    <a href="#" class="text-slate-400 hover:text-slate-300 transition-colors duration-200">{{ __('homepage.footer_terms') }}</a>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- This site is converting visitors into subscribers and customers with OptinMonster - https://optinmonster.com -->
<script>(function(d,u,ac){var s=d.createElement('script');s.type='text/javascript';s.src='https://a.omappapi.com/app/js/api.min.js';s.async=true;s.dataset.user=u;s.dataset.account=ac;d.getElementsByTagName('head')[0].appendChild(s);})(document,344322,364204);</script>
<!-- / https://optinmonster.com -->
</body>
</html>
