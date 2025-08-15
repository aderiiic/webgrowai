<?php /* PHP (Blade) */ ?>
    <!doctype html>
<html lang="sv">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-100 text-gray-900">
<div class="min-h-screen flex">
    <!-- Sidebar -->
    <aside class="hidden md:block w-64 bg-white border-r">
        <div class="px-4 py-4 border-b">
            <a href="{{ route('dashboard') }}" class="text-lg font-semibold">WebGrow AI</a>
            @php($activeCustomer = app(\App\Support\CurrentCustomer::class)->get())
            @if($activeCustomer)
                <div class="mt-3">
            <span class="inline-flex items-center gap-2 rounded-full bg-indigo-50 text-indigo-700 px-3 py-1 text-xs font-medium border border-indigo-200">
              <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" viewBox="0 0 20 20" fill="currentColor"><path d="M10 3a7 7 0 100 14 7 7 0 000-14zM8.28 10.53l-1.5-1.5a.75.75 0 011.06-1.06l.97.97 3.35-3.35a.75.75 0 111.06 1.06l-3.88 3.88a.75.75 0 01-1.06 0z" /></svg>
              <span class="truncate max-w-[160px]" title="{{ $activeCustomer->name }}">{{ \Illuminate\Support\Str::limit($activeCustomer->name, 26) }}</span>
            </span>
                </div>
            @endif
        </div>

        <nav class="p-3 space-y-4">
            <div>
                <div class="text-xs uppercase text-gray-500 px-2 mb-1">Översikt</div>
                <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-700' : 'hover:bg-gray-50' }}">Dashboard</a>
                <a href="{{ route('sites.index') }}" class="block px-3 py-2 rounded {{ request()->routeIs('sites.*') ? 'bg-indigo-50 text-indigo-700' : 'hover:bg-gray-50' }}">Sajter</a>
            </div>

            <div>
                <div class="text-xs uppercase text-gray-500 px-2 mb-1">SEO</div>
                <a href="{{ route('seo.audit.history') }}" class="block px-3 py-2 rounded {{ request()->routeIs('seo.audit.*') ? 'bg-indigo-50 text-indigo-700' : 'hover:bg-gray-50' }}">SEO Audits</a>
                <a href="{{ route('seo.keywords.index') }}" class="block px-3 py-2 rounded {{ request()->routeIs('seo.keywords.*') ? 'bg-indigo-50 text-indigo-700' : 'hover:bg-gray-50' }}">Nyckelordsförslag</a>
                <a href="{{ route('cro.suggestions.index') }}" class="block px-3 py-2 rounded {{ request()->routeIs('cro.*') ? 'bg-indigo-50 text-indigo-700' : 'hover:bg-gray-50' }}">CRO-förslag</a>
            </div>

            <div>
                <div class="text-xs uppercase text-gray-500 px-2 mb-1">Leads</div>
                <a href="{{ route('leads.index') }}" class="block px-3 py-2 rounded {{ request()->routeIs('leads.*') ? 'bg-indigo-50 text-indigo-700' : 'hover:bg-gray-50' }}">Leadlista</a>
            </div>

            <div>
                <div class="text-xs uppercase text-gray-500 px-2 mb-1">Publicering</div>
                <a href="{{ route('publications.index') }}" class="block px-3 py-2 rounded {{ request()->routeIs('publications.*') ? 'bg-indigo-50 text-indigo-700' : 'hover:bg-gray-50' }}">Publiceringar</a>
                <a href="{{ route('ai.list') }}" class="block px-3 py-2 rounded {{ request()->routeIs('ai.*') ? 'bg-indigo-50 text-indigo-700' : 'hover:bg-gray-50' }}">AI Innehåll</a>
            </div>

            <div>
                <div class="text-xs uppercase text-gray-500 px-2 mb-1">Marknad</div>
                <a href="{{ route('marketing.newsletter') }}" class="block px-3 py-2 rounded {{ request()->routeIs('marketing.newsletter') ? 'bg-indigo-50 text-indigo-700' : 'hover:bg-gray-50' }}">Nyhetsbrev</a>
                <a href="{{ route('marketing.mailchimp.history') }}" class="block px-3 py-2 rounded {{ request()->routeIs('marketing.mailchimp.history') ? 'bg-indigo-50 text-indigo-700' : 'hover:bg-gray-50' }}">MC Kampanjhistorik</a>
            </div>

            <div>
                <div class="text-xs uppercase text-gray-500 px-2 mb-1">Inställningar</div>
                <a href="{{ route('settings.weekly') }}" class="block px-3 py-2 rounded {{ request()->routeIs('settings.weekly') ? 'bg-indigo-50 text-indigo-700' : 'hover:bg-gray-50' }}">Veckodigest</a>
                <a href="{{ route('settings.social') }}" class="block px-3 py-2 rounded {{ request()->routeIs('settings.social') ? 'bg-indigo-50 text-indigo-700' : 'hover:bg-gray-50' }}">Sociala kanaler</a>
                <a href="{{ route('settings.mailchimp') }}" class="block px-3 py-2 rounded {{ request()->routeIs('settings.mailchimp') ? 'bg-indigo-50 text-indigo-700' : 'hover:bg-gray-50' }}">Mailchimp</a>
            </div>

            @can('admin')
                <div>
                    <div class="text-xs uppercase text-gray-500 px-2 mb-1">Admin</div>
                    <a href="{{ route('admin.usage.index') }}" class="block px-3 py-2 rounded {{ request()->routeIs('admin.usage.*') ? 'bg-indigo-50 text-indigo-700' : 'hover:bg-gray-50' }}">Usage</a>
                    <a href="{{ route('admin.plans.index') }}" class="block px-3 py-2 rounded {{ request()->routeIs('admin.plans.*') ? 'bg-indigo-50 text-indigo-700' : 'hover:bg-gray-50' }}">Planer</a>
                </div>
            @endcan
        </nav>
    </aside>

    <!-- Main -->
    <div class="flex-1 flex flex-col">
        <!-- Topbar: återanvänd Jetstreams navigation-menu för snygg profilmeny -->
        @include('navigation-menu')

        <!-- Content -->
        <main class="max-w-7xl mx-auto w-full px-4 py-6">
            @if(session('success'))
                <div class="mb-4 text-sm text-green-700 bg-green-50 border border-green-200 rounded px-3 py-2">
                    {{ session('success') }}
                </div>
            @endif
            {{ $slot ?? '' }}
            @yield('content')
        </main>
    </div>
</div>
@livewireScripts
</body>
</html>
