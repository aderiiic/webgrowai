@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 flex items-center justify-center px-4 py-12">
        <div class="max-w-2xl w-full">
            <!-- Blurred background card -->
            <div class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 overflow-hidden">
                <!-- Lock icon header -->
                <div class="bg-gradient-to-br from-indigo-600 to-purple-600 p-8 text-center">
                    <div class="w-20 h-20 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center mx-auto mb-4 animate-pulse">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <h1 class="text-3xl font-bold text-white mb-2">Funktionen är låst</h1>
                    <p class="text-indigo-100">Uppgradera ditt abonnemang för att få tillgång</p>
                </div>

                <!-- Content -->
                <div class="p-8 text-center space-y-6">
                    @if(session('error'))
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg text-left">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-yellow-400 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                <p class="text-sm text-yellow-700 font-medium">{{ session('error') }}</p>
                            </div>
                        </div>
                    @endif

                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 border border-indigo-100">
                        <p class="text-lg text-gray-700 mb-4">
                            @if($feature === 'ai.bulk_generate')
                                <strong>Massgenerering</strong> är tillgänglig från <strong>Growth-planen</strong> och uppåt.
                                <br><br>
                                <span class="text-base text-gray-600">
                                    Med massgenerering kan du:
                                </span>
                            @elseif($feature === 'ai.social_media')
                                <strong>Sociala medier-generering</strong> kräver en aktiv prenumeration.
                            @elseif($feature === 'ai.blog')
                                <strong>Blogg-generering</strong> kräver en aktiv prenumeration.
                            @elseif($feature === 'ai.seo_optimize')
                                <strong>SEO-optimering</strong> kräver en aktiv prenumeration.
                            @elseif($feature === 'ai.product')
                                <strong>Produkttext-generering</strong> kräver en aktiv prenumeration.
                            @else
                                Denna funktion kräver en högre prenumerationsplan.
                            @endif
                        </p>
                    </div>

                    @if($feature === 'ai.bulk_generate')
                        <!-- Bulk-specific benefits -->
                        <div class="grid grid-cols-1 gap-3 text-left bg-gray-50 rounded-xl p-4">
                            <div class="flex items-start space-x-3">
                                <svg class="w-6 h-6 text-indigo-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                <div>
                                    <p class="text-gray-900 font-medium">Generera upp till 25-50 texter samtidigt</p>
                                    <p class="text-sm text-gray-600">Beroende på din plan (Growth: 25, Pro: 50)</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <svg class="w-6 h-6 text-indigo-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div>
                                    <p class="text-gray-900 font-medium">Spara timmar av arbete</p>
                                    <p class="text-sm text-gray-600">Automatisera repetitiva innehållsuppgifter</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <svg class="w-6 h-6 text-indigo-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div>
                                    <p class="text-gray-900 font-medium">Konsekvent kvalitet</p>
                                    <p class="text-sm text-gray-600">Alla texter följer samma mall och stil</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- General benefits -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-left">
                            <div class="flex items-start space-x-3">
                                <svg class="w-6 h-6 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span class="text-gray-700">Generera innehåll snabbare</span>
                            </div>
                            <div class="flex items-start space-x-3">
                                <svg class="w-6 h-6 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span class="text-gray-700">Spara tid och resurser</span>
                            </div>
                            <div class="flex items-start space-x-3">
                                <svg class="w-6 h-6 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span class="text-gray-700">Nå fler kanaler samtidigt</span>
                            </div>
                            <div class="flex items-start space-x-3">
                                <svg class="w-6 h-6 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span class="text-gray-700">Få mer värde för pengarna</span>
                            </div>
                        </div>
                    @endif

                    <!-- CTA Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-center pt-4">
                        <a href="{{ route('billing.pricing') }}" class="group inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <svg class="w-5 h-5 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            Uppgradera nu
                        </a>
                        <a href="{{ route('ai.list') }}" class="inline-flex items-center justify-center px-8 py-4 bg-white text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-all duration-200 border-2 border-gray-200 hover:border-gray-300">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Tillbaka till AI
                        </a>
                    </div>

                    <div class="text-sm text-gray-500 pt-4 border-t border-gray-200">
                        Har du frågor om våra planer?
                        <a href="mailto:info@webbi.se" class="text-indigo-600 hover:text-indigo-700 underline font-medium">Kontakta oss</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
