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
<body class="bg-white text-gray-900 antialiased">

<!-- Header (publik) -->
<header class="border-b bg-white/80 backdrop-blur supports-[backdrop-filter]:bg-white/60 sticky top-0 z-30">
    <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
        <a href="{{ url('/') }}" class="text-xl font-semibold">WebGrow AI</a>
        <nav class="hidden md:flex items-center gap-6 text-sm">
            <a href="{{ url('/#features') }}" class="hover:text-indigo-600">Funktioner</a>
            <a href="{{ url('/#pricing') }}" class="hover:text-indigo-600">Priser</a>
            <a href="{{ url('/#testimonials') }}" class="hover:text-indigo-600">Kunder</a>
            <a href="{{ url('/#faq') }}" class="hover:text-indigo-600">FAQ</a>
            <a href="{{ route('news.index') }}" class="hover:text-indigo-600">Nyheter</a>
        </nav>
        <div class="flex items-center gap-3">
            <a href="{{ route('login') }}" class="px-4 py-2 rounded border hover:bg-gray-50 text-sm">Logga in</a>
            @if(Route::has('register'))
                <a href="{{ route('register') }}"
                   class="px-4 py-2 rounded bg-indigo-600 text-white text-sm hover:bg-indigo-700"
                   data-lead-cta="header_register">Prova gratis</a>
            @endif
        </div>
    </div>
</header>

<!-- Page content -->
<main class="min-h-[60vh]">
    @yield('content')
</main>

<!-- Footer -->
<footer class="border-t bg-white">
    <div class="max-w-7xl mx-auto px-4 py-10 grid md:grid-cols-4 gap-6 text-sm">
        <div>
            <div class="text-lg font-semibold">WebGrow AI</div>
            <p class="text-gray-600 mt-2">Automatiserad marknadsföring för WordPress – SEO, CRO, publicering.</p>
        </div>
        <div>
            <div class="font-semibold">Produkt</div>
            <ul class="mt-2 space-y-1">
                <li><a href="{{ url('/#features') }}" class="hover:underline">Funktioner</a></li>
                <li><a href="{{ url('/#pricing') }}" class="hover:underline">Priser</a></li>
                <li><a href="{{ url('/#faq') }}" class="hover:underline">FAQ</a></li>
            </ul>
        </div>
        <div>
            <div class="font-semibold">Resurser</div>
            <ul class="mt-2 space-y-1">
                <li><a href="{{ route('news.index') }}" class="hover:underline">Nyheter</a></li>
                <li><a href="{{ route('login') }}" class="hover:underline">Logga in</a></li>
                @if(Route::has('register'))
                    <li><a href="{{ route('register') }}" class="hover:underline" data-lead-cta="footer_register">Skapa konto</a></li>
                @endif
            </ul>
        </div>
        <div>
            <div class="font-semibold">Kontakt</div>
            <ul class="mt-2 space-y-1">
                <li><a href="{{ url('/#pricing') }}" class="hover:underline" data-lead-cta="footer_book_demo">Boka demo</a></li>
            </ul>
        </div>
    </div>
    <div class="border-t">
        <div class="max-w-7xl mx-auto px-4 py-4 text-xs text-gray-500 flex items-center justify-between">
            <div>© {{ date('Y') }} WebGrow AI</div>
            <div>Byggt med Laravel + Tailwind</div>
        </div>
    </div>
</footer>

</body>
</html>
