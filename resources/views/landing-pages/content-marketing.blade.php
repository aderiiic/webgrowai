
@extends('layouts.guest', ['title' => 'Automatiserad Innehållsmarknadsföring – Från idé till publicering | WebGrow AI'])

@section('content')
    <main x-data="{ demoOpen: false }">
        <!-- Hero Section -->
        <section class="relative min-h-[600px] overflow-hidden bg-gradient-to-br from-emerald-900 via-green-900 to-teal-900">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml;utf8,%3Csvg%20width%3D%2260%22%20height%3D%2260%22%20viewBox%3D%220%200%2060%2060%22%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%3E%3Cg%20fill%3D%22none%22%20fill-rule%3D%22evenodd%22%3E%3Cg%20fill%3D%22%23ffffff%22%20fill-opacity%3D%220.05%22%3E%3Ccircle%20cx%3D%227%22%20cy%3D%227%22%20r%3D%221%22/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-40"></div>

            <div class="absolute inset-0 bg-gradient-to-r from-emerald-600/20 to-green-600/20"></div>

            <div class="relative z-10 max-w-7xl mx-auto px-4 py-20">
                <div class="grid lg:grid-cols-2 gap-12 items-center">
                    <!-- Left Content -->
                    <div class="text-white">
                        <div class="inline-flex items-center px-4 py-2 bg-white/10 backdrop-blur-md rounded-full border border-white/20 mb-6">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            <span class="text-sm font-semibold">{{ __('landing.content_hero_badge') }}</span>
                        </div>

                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold mb-6 leading-tight">
                            {{ __('landing.content_hero_title_1') }}<br>
                            <span class="bg-gradient-to-r from-emerald-400 to-green-400 bg-clip-text text-transparent">
                                {{ __('landing.content_hero_title_2') }}
                            </span><br>
                            {{ __('landing.content_hero_title_3') }}
                        </h1>

                        <p class="text-xl text-slate-200 mb-8 leading-relaxed">
                            {{ __('landing.content_hero_subtitle') }}
                        </p>

                        <div class="flex flex-col sm:flex-row gap-4 mb-8">
                            <a href="{{ route('register') }}" class="group inline-flex items-center justify-center px-8 py-4 bg-white text-emerald-900 font-bold rounded-xl shadow-2xl hover:shadow-3xl hover:scale-105 transition-all duration-300">
                                {{ __('landing.content_cta_start') }}
                                <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                            </a>

                            <button @click="demoOpen = true" class="inline-flex items-center justify-center px-8 py-4 bg-white/10 backdrop-blur-md text-white font-semibold rounded-xl border-2 border-white/40 hover:bg-white/20 transition-all duration-300">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ __('landing.content_cta_demo') }}
                            </button>
                        </div>

                        <div class="flex flex-wrap gap-4 text-sm text-slate-300">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>{{ __('landing.trial_info') }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>{{ __('landing.trial_info_2') }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>{{ __('landing.trial_info_3') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Right Visual - Automation Workflow -->
                    <div class="relative">
                        <div class="relative bg-white/10 backdrop-blur-xl rounded-2xl border border-white/20 p-8 shadow-2xl">
                            <div class="space-y-4">
                                <div class="flex items-center gap-3 pb-4 border-b border-white/10">
                                    <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-green-600 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-white">Innehållsautomation</div>
                                        <div class="text-sm text-slate-400">Kör automatiskt</div>
                                    </div>
                                </div>

                                <div class="space-y-3">
                                    <div class="flex items-center gap-3 bg-emerald-500/20 rounded-lg p-3 border border-emerald-500/30">
                                        <div class="w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center flex-shrink-0">
                                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        <div class="text-white text-sm flex-1">
                                            <div class="font-semibold">Planering klar</div>
                                            <div class="text-slate-300 text-xs">15 inlägg schemalagda</div>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-3 bg-white/5 rounded-lg p-3 border border-white/10 animate-pulse">
                                        <div class="w-8 h-8 bg-gradient-to-br from-emerald-400 to-green-500 rounded-full flex items-center justify-center flex-shrink-0">
                                            <svg class="w-4 h-4 text-white animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                            </svg>
                                        </div>
                                        <div class="text-white text-sm flex-1">
                                            <div class="font-semibold">Genererar innehåll...</div>
                                            <div class="text-slate-300 text-xs">Texter + bilder</div>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-3 bg-white/5 rounded-lg p-3 border border-white/10">
                                        <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center flex-shrink-0">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                        <div class="text-white text-sm flex-1">
                                            <div class="font-semibold">Nästa publicering</div>
                                            <div class="text-slate-300 text-xs">Imorgon kl 09:00</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="absolute -top-4 -right-4 w-20 h-20 bg-gradient-to-br from-emerald-400 to-green-500 rounded-2xl rotate-12 animate-float-1 opacity-80"></div>
                        <div class="absolute -bottom-6 -left-6 w-16 h-16 bg-gradient-to-br from-green-400 to-teal-500 rounded-full animate-float-2 opacity-60"></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features -->
        <section class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-slate-800 mb-4">
                        {{ __('landing.content_features_title') }}
                    </h2>
                    <p class="text-xl text-slate-600 max-w-3xl mx-auto">
                        {{ __('landing.content_features_subtitle') }}
                    </p>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div class="group text-center p-6 bg-gradient-to-br from-emerald-50 to-green-50 rounded-2xl border border-emerald-200/50 hover:shadow-xl transition-all duration-300">
                        <div class="w-16 h-16 bg-gradient-to-r from-emerald-500 to-green-600 rounded-xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800 mb-2">{{ __('landing.content_feature_1_title') }}</h3>
                        <p class="text-sm text-slate-600">{{ __('landing.content_feature_1_desc') }}</p>
                    </div>

                    <div class="group text-center p-6 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl border border-blue-200/50 hover:shadow-xl transition-all duration-300">
                        <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800 mb-2">{{ __('landing.content_feature_2_title') }}</h3>
                        <p class="text-sm text-slate-600">{{ __('landing.content_feature_2_desc') }}</p>
                    </div>

                    <div class="group text-center p-6 bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl border border-purple-200/50 hover:shadow-xl transition-all duration-300">
                        <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-600 rounded-xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800 mb-2">{{ __('landing.content_feature_3_title') }}</h3>
                        <p class="text-sm text-slate-600">{{ __('landing.content_feature_3_desc') }}</p>
                    </div>

                    <div class="group text-center p-6 bg-gradient-to-br from-orange-50 to-red-50 rounded-2xl border border-orange-200/50 hover:shadow-xl transition-all duration-300">
                        <div class="w-16 h-16 bg-gradient-to-r from-orange-500 to-red-600 rounded-xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800 mb-2">{{ __('landing.content_feature_4_title') }}</h3>
                        <p class="text-sm text-slate-600">{{ __('landing.content_feature_4_desc') }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Results/Benefits -->
        <section class="py-20 bg-gradient-to-br from-slate-50 to-emerald-50">
            <div class="max-w-7xl mx-auto px-4">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-slate-800 mb-4">
                        {{ __('landing.content_benefits_title') }}
                    </h2>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <div class="bg-white rounded-2xl p-8 shadow-lg text-center">
                        <div class="text-5xl font-bold text-emerald-600 mb-2">+127%</div>
                        <h3 class="text-xl font-bold text-slate-800 mb-2">{{ __('landing.content_benefit_1_title') }}</h3>
                        <p class="text-slate-600">{{ __('landing.content_benefit_1_desc') }}</p>
                    </div>

                    <div class="bg-white rounded-2xl p-8 shadow-lg text-center">
                        <div class="text-5xl font-bold text-indigo-600 mb-2">12h</div>
                        <h3 class="text-xl font-bold text-slate-800 mb-2">{{ __('landing.content_benefit_2_title') }}</h3>
                        <p class="text-slate-600">{{ __('landing.content_benefit_2_desc') }}</p>
                    </div>

                    <div class="bg-white rounded-2xl p-8 shadow-lg text-center">
                        <div class="text-5xl font-bold text-purple-600 mb-2">+89%</div>
                        <h3 class="text-xl font-bold text-slate-800 mb-2">{{ __('landing.content_benefit_3_title') }}</h3>
                        <p class="text-slate-600">{{ __('landing.content_benefit_3_desc') }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Workflow -->
        <section class="py-20 bg-white">
            <div class="max-w-5xl mx-auto px-4">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-slate-800 mb-4">
                        {{ __('landing.content_workflow_title') }}
                    </h2>
                </div>

                <div class="relative">
                    <!-- Connection line -->
                    <div class="hidden md:block absolute top-16 left-0 right-0 h-0.5 bg-gradient-to-r from-emerald-300 via-green-400 to-emerald-300"></div>

                    <div class="grid md:grid-cols-4 gap-8 relative">
                        <div class="text-center">
                            <div class="w-20 h-20 bg-gradient-to-br from-emerald-500 to-green-600 text-white rounded-full flex items-center justify-center mx-auto mb-4 text-2xl font-bold shadow-lg relative z-10">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                            <p class="text-slate-700 font-medium">{{ __('landing.content_workflow_step_1') }}</p>
                        </div>

                        <div class="text-center">
                            <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-indigo-600 text-white rounded-full flex items-center justify-center mx-auto mb-4 text-2xl font-bold shadow-lg relative z-10">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <p class="text-slate-700 font-medium">{{ __('landing.content_workflow_step_2') }}</p>
                        </div>

                        <div class="text-center">
                            <div class="w-20 h-20 bg-gradient-to-br from-purple-500 to-pink-600 text-white rounded-full flex items-center justify-center mx-auto mb-4 text-2xl font-bold shadow-lg relative z-10">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <p class="text-slate-700 font-medium">{{ __('landing.content_workflow_step_3') }}</p>
                        </div>

                        <div class="text-center">
                            <div class="w-20 h-20 bg-gradient-to-br from-orange-500 to-red-600 text-white rounded-full flex items-center justify-center mx-auto mb-4 text-2xl font-bold shadow-lg relative z-10">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                            </div>
                            <p class="text-slate-700 font-medium">{{ __('landing.content_workflow_step_4') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Final CTA -->
        <section class="py-20 bg-gradient-to-br from-emerald-900 via-green-900 to-teal-900 relative overflow-hidden">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.05"%3E%3Ccircle cx="7" cy="7" r="1"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-40"></div>

            <div class="relative max-w-4xl mx-auto px-4 text-center">
                <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">
                    {{ __('landing.content_cta_final') }}
                </h2>
                <p class="text-xl text-slate-300 mb-12">
                    {{ __('landing.content_cta_final_subtitle') }}
                </p>

                <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-10 py-5 bg-white text-emerald-900 font-bold rounded-xl shadow-2xl hover:shadow-3xl hover:scale-105 transition-all duration-300 text-lg">
                    {{ __('landing.content_cta_start') }}
                    <svg class="w-6 h-6 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>
        </section>

        <!-- Demo Modal (reused from first page) -->
        <div x-show="demoOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="demoOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" @click="demoOpen = false"></div>

                <div x-show="demoOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                        <div class="flex items-start justify-between">
                            <h3 class="text-2xl font-bold text-gray-900 mb-6">Boka demonstration</h3>
                            <button @click="demoOpen = false" class="text-gray-400 hover:text-gray-600">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        <form action="{{ route('demo.request') }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Namn *</label>
                                <input type="text" name="name" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">E-post *</label>
                                <input type="email" name="email" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Företag</label>
                                <input type="text" name="company" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Meddelande (valfritt)</label>
                                <textarea name="notes" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" placeholder="Vad vill du veta mer om?"></textarea>
                            </div>
                            <div class="flex gap-3 pt-4">
                                <button type="button" @click="demoOpen = false" class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors">Avbryt</button>
                                <button type="submit" class="flex-1 px-6 py-3 bg-gradient-to-r from-emerald-600 to-green-600 text-white font-semibold rounded-xl hover:from-emerald-700 hover:to-green-700 transition-colors">Skicka förfrågan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <style>
        @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-10px); } }
        .animate-float-1 { animation: float 3s ease-in-out infinite; }
        .animate-float-2 { animation: float 3s ease-in-out infinite 1s; }
    </style>
@endsection
