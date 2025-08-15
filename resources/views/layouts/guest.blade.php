<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'WebGrow AI' }}</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-slate-50 text-slate-900 antialiased">

<!-- Header (publik) -->
<header class="border-b border-slate-200/80 bg-white/90 backdrop-blur-xl supports-[backdrop-filter]:bg-white/80 sticky top-0 z-50 shadow-sm" x-data="{ mobileMenuOpen: false }">
    <div class="max-w-7xl mx-auto px-4 py-4">
        <div class="flex items-center justify-between">
            <!-- Logo -->
            <a href="{{ url('/') }}" class="flex items-center space-x-3 group">
                <div class="w-8 h-8 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-lg flex items-center justify-center group-hover:scale-105 transition-transform duration-200">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <span class="text-xl font-bold bg-gradient-to-r from-slate-800 to-blue-800 bg-clip-text text-transparent">WebGrow AI</span>
            </a>

            <!-- Desktop Navigation -->
            <nav class="hidden lg:flex items-center space-x-8">
                <a href="{{ url('/#features') }}" class="text-slate-600 hover:text-blue-600 font-medium transition-colors duration-200 relative group">
                    Funktioner
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-blue-600 transition-all duration-200 group-hover:w-full"></span>
                </a>
                <a href="{{ url('/#pricing') }}" class="text-slate-600 hover:text-blue-600 font-medium transition-colors duration-200 relative group">
                    Priser
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-blue-600 transition-all duration-200 group-hover:w-full"></span>
                </a>
                <a href="{{ url('/#testimonials') }}" class="text-slate-600 hover:text-blue-600 font-medium transition-colors duration-200 relative group">
                    Kunder
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-blue-600 transition-all duration-200 group-hover:w-full"></span>
                </a>
                <a href="{{ url('/#faq') }}" class="text-slate-600 hover:text-blue-600 font-medium transition-colors duration-200 relative group">
                    FAQ
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-blue-600 transition-all duration-200 group-hover:w-full"></span>
                </a>
                <a href="{{ route('news.index') }}" class="text-slate-600 hover:text-blue-600 font-medium transition-colors duration-200 relative group">
                    Nyheter
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-blue-600 transition-all duration-200 group-hover:w-full"></span>
                </a>
            </nav>

            <!-- Desktop CTA Buttons -->
            <div class="hidden lg:flex items-center space-x-3">
                <a href="{{ route('login') }}" class="px-4 py-2 text-slate-700 font-medium rounded-lg border border-slate-300 hover:bg-slate-50 hover:border-slate-400 transition-all duration-200">
                    Logga in
                </a>
                @if(Route::has('register'))
                    <a href="{{ route('register') }}"
                       class="group relative px-6 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-200"
                       data-lead-cta="header_register">
                        <span class="relative z-10">Prova gratis</span>
                        <div class="absolute inset-0 bg-gradient-to-r from-blue-700 to-indigo-700 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200"></div>
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
                <a href="{{ url('/#features') }}" class="block text-slate-700 font-medium py-2 border-b border-slate-100 hover:text-blue-600 transition-colors duration-200">
                    Funktioner
                </a>
                <a href="{{ url('/#pricing') }}" class="block text-slate-700 font-medium py-2 border-b border-slate-100 hover:text-blue-600 transition-colors duration-200">
                    Priser
                </a>
                <a href="{{ url('/#testimonials') }}" class="block text-slate-700 font-medium py-2 border-b border-slate-100 hover:text-blue-600 transition-colors duration-200">
                    Kunder
                </a>
                <a href="{{ url('/#faq') }}" class="block text-slate-700 font-medium py-2 border-b border-slate-100 hover:text-blue-600 transition-colors duration-200">
                    FAQ
                </a>
                <a href="{{ route('news.index') }}" class="block text-slate-700 font-medium py-2 border-b border-slate-100 hover:text-blue-600 transition-colors duration-200">
                    Nyheter
                </a>

                <!-- Mobile CTA buttons -->
                <div class="pt-4 space-y-3">
                    <a href="{{ route('login') }}" class="block w-full text-center px-4 py-3 text-slate-700 font-medium rounded-lg border border-slate-300 hover:bg-slate-50 transition-colors duration-200">
                        Logga in
                    </a>
                    @if(Route::has('register'))
                        <a href="{{ route('register') }}"
                           class="block w-full text-center px-4 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-colors duration-200 shadow-md"
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
<footer class="bg-white border-t border-slate-200">
    <div class="max-w-7xl mx-auto px-4 py-16">
        <!-- Main footer content -->
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
            <!-- Company info -->
            <div class="lg:col-span-1">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-8 h-8 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <span class="text-xl font-bold bg-gradient-to-r from-slate-800 to-blue-800 bg-clip-text text-transparent">WebGrow AI</span>
                </div>
                <p class="text-slate-600 leading-relaxed mb-6">
                    Automatiserad marknadsföring för WordPress – SEO, CRO och publicering som hjälper ditt företag att växa snabbare.
                </p>

                <!-- Social links -->
                <div class="flex items-center space-x-3">
                    <a href="#" class="p-2 bg-slate-100 hover:bg-blue-100 text-slate-600 hover:text-blue-600 rounded-lg transition-colors duration-200">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                        </svg>
                    </a>
                    <a href="#" class="p-2 bg-slate-100 hover:bg-blue-100 text-slate-600 hover:text-blue-600 rounded-lg transition-colors duration-200">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Product links -->
            <div>
                <h4 class="font-bold text-slate-800 mb-4">Produkt</h4>
                <ul class="space-y-3">
                    <li>
                        <a href="{{ url('/#features') }}" class="text-slate-600 hover:text-blue-600 transition-colors duration-200 flex items-center group">
                            <svg class="w-4 h-4 mr-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Funktioner
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/#pricing') }}" class="text-slate-600 hover:text-blue-600 transition-colors duration-200 flex items-center group">
                            <svg class="w-4 h-4 mr-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Priser
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/#faq') }}" class="text-slate-600 hover:text-blue-600 transition-colors duration-200 flex items-center group">
                            <svg class="w-4 h-4 mr-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Vanliga frågor
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/#testimonials') }}" class="text-slate-600 hover:text-blue-600 transition-colors duration-200 flex items-center group">
                            <svg class="w-4 h-4 mr-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Kundrecensioner
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Resources links -->
            <div>
                <h4 class="font-bold text-slate-800 mb-4">Resurser</h4>
                <ul class="space-y-3">
                    <li>
                        <a href="{{ route('news.index') }}" class="text-slate-600 hover:text-blue-600 transition-colors duration-200 flex items-center group">
                            <svg class="w-4 h-4 mr-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Nyheter & uppdateringar
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('login') }}" class="text-slate-600 hover:text-blue-600 transition-colors duration-200 flex items-center group">
                            <svg class="w-4 h-4 mr-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Logga in
                        </a>
                    </li>
                    @if(Route::has('register'))
                        <li>
                            <a href="{{ route('register') }}" class="text-slate-600 hover:text-blue-600 transition-colors duration-200 flex items-center group" data-lead-cta="footer_register">
                                <svg class="w-4 h-4 mr-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Skapa konto
                            </a>
                        </li>
                    @endif
                </ul>
            </div>

            <!-- Contact & Support -->
            <div>
                <h4 class="font-bold text-slate-800 mb-4">Kontakt</h4>
                <ul class="space-y-3">
                    <li>
                        <a href="{{ url('/#pricing') }}" class="text-slate-600 hover:text-blue-600 transition-colors duration-200 flex items-center group" data-lead-cta="footer_book_demo">
                            <svg class="w-4 h-4 mr-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Boka demo
                        </a>
                    </li>
                    <li class="text-slate-600">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            support@webgrow.se
                        </div>
                    </li>
                </ul>

                <!-- CTA in footer -->
                <div class="mt-6 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200">
                    <p class="text-sm text-blue-800 font-medium mb-2">Redo att komma igång?</p>
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center text-sm font-semibold text-blue-600 hover:text-blue-700 transition-colors duration-200"
                       data-lead-cta="footer_cta_register">
                        Starta gratis idag
                        <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Bottom section -->
        <div class="border-t border-slate-200 pt-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="text-sm text-slate-500">
                    © {{ date('Y') }} WebGrow AI. Alla rättigheter förbehållna.
                </div>
                <div class="flex items-center gap-6 text-sm">
                    <a href="#" class="text-slate-500 hover:text-slate-700 transition-colors duration-200">Integritetspolicy</a>
                    <a href="#" class="text-slate-500 hover:text-slate-700 transition-colors duration-200">Användarvillkor</a>
                    <div class="text-slate-400">
                        Byggt med ❤️ i Sverige
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

</body>
</html>
