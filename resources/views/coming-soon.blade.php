@extends('layouts.guest', ['title' => 'WebGrow AI – Öppning snart'])

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Header -->
        <div class="text-center mb-12">
            <div class="w-20 h-20 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-2xl">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                WebGrow AI
                <span class="bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">öppnar snart</span>
            </h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto leading-relaxed">
                Vi förbereder något extraordinärt inom AI-driven SEO, innehållsgenerering och konverteringsoptimering.
                Registrering öppnar inom kort.
            </p>
        </div>

        <!-- Coming Soon Card -->
        <div class="bg-white/90 backdrop-blur-sm rounded-3xl shadow-2xl border border-gray-100/60 p-8 md:p-12 mb-8">
            <div class="text-center mb-8">
                <div class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-orange-100 to-amber-100 text-orange-800 text-sm font-semibold rounded-full border border-orange-200 mb-6">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Lansering inom kort
                </div>
                <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4">
                    Vill du vara med i tidig beta?
                </h2>
                <p class="text-lg text-gray-600 mb-8 max-w-2xl mx-auto">
                    Vi söker ett begränsat antal företag som vill testa WebGrow AI innan den officiella lanseringen.
                    Som beta-användare får du exklusiv tillgång och möjlighet att påverka produktens utveckling.
                </p>
            </div>

            <!-- Beta Benefits -->
            <div class="grid md:grid-cols-3 gap-6 mb-10">
                <div class="text-center p-6 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl border border-blue-100">
                    <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Tidig tillgång</h3>
                    <p class="text-sm text-gray-600">Använd alla funktioner innan allmänheten</p>
                </div>

                <div class="text-center p-6 bg-gradient-to-br from-emerald-50 to-teal-50 rounded-2xl border border-emerald-100">
                    <div class="w-12 h-12 bg-emerald-600 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Specialpris</h3>
                    <p class="text-sm text-gray-600">Exklusiva priser för beta-användare</p>
                </div>

                <div class="text-center p-6 bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl border border-purple-100">
                    <div class="w-12 h-12 bg-purple-600 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Direkt kontakt</h3>
                    <p class="text-sm text-gray-600">Personlig support och påverka utvecklingen</p>
                </div>
            </div>

            <!-- CTA -->
            <div class="text-center">
                <p class="text-gray-700 mb-6">
                    Intresserad av att vara med redan från start? Hör av dig så återkommer vi snart!
                </p>
                <a href="mailto:info@webbi.se?subject=Beta-intresse%20WebGrow%20AI&body=Hej!%0A%0AJag%20är%20intresserad%20av%20att%20testa%20WebGrow%20AI%20i%20beta.%0A%0AFöretagsnamn:%20%0AWebbsida:%20%0AKontaktperson:%20%0ATelefon:%20%0A%0AMvh"
                   class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold text-lg rounded-xl hover:from-indigo-700 hover:to-purple-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transform hover:scale-[1.02] transition-all duration-200 shadow-xl hover:shadow-2xl">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Ansök om beta-tillgång
                </a>
                <p class="text-sm text-gray-500 mt-4">
                    Vi återkommer inom 24 timmar med mer information
                </p>
            </div>
        </div>

        <!-- What's Coming -->
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white/60 backdrop-blur-sm rounded-2xl p-6 border border-gray-100/60">
                <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-red-500 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900 mb-2">AI-innehåll</h3>
                <p class="text-sm text-gray-600">Automatisk generering av SEO-optimerat innehåll</p>
            </div>

            <div class="bg-white/60 backdrop-blur-sm rounded-2xl p-6 border border-gray-100/60">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900 mb-2">SEO-analys</h3>
                <p class="text-sm text-gray-600">Djupgående teknisk och innehållsanalys</p>
            </div>

            <div class="bg-white/60 backdrop-blur-sm rounded-2xl p-6 border border-gray-100/60">
                <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900 mb-2">CRO-optimering</h3>
                <p class="text-sm text-gray-600">AI-driven konverteringsoptimering</p>
            </div>

            <div class="bg-white/60 backdrop-blur-sm rounded-2xl p-6 border border-gray-100/60">
                <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-500 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900 mb-2">Marknadsföring</h3>
                <p class="text-sm text-gray-600">Automatiserad e-post och sociala medier</p>
            </div>
        </div>

        <!-- Login Link -->
        <div class="text-center">
            <div class="inline-flex items-center px-4 py-2 bg-white/80 backdrop-blur-sm rounded-xl border border-gray-200/80 text-sm text-gray-600">
                Har du redan ett konto?
                <a href="{{ route('login') }}" class="ml-2 text-indigo-600 hover:text-indigo-800 font-medium">
                    Logga in här
                </a>
            </div>
        </div>
    </div>
@endsection
