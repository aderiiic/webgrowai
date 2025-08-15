@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <h1 class="text-3xl font-semibold">Priser</h1>
        <p class="text-gray-600">Börja litet och skala upp. 14 dagar gratis, ingen bindningstid.</p>

        <div class="grid md:grid-cols-3 gap-6 mt-4">
            @php
                $tiers = [
                  [
                    'name' => 'Starter',
                    'price' => '490',
                    'desc' => 'För mindre sajter som vill igång snabbt.',
                    'features' => [
                      '500 AI‑genereringar/mån',
                      '50 WP‑publiceringar/mån',
                      '2 SEO‑audits/mån',
                      'Lead tracking: 5 000 events/mån',
                      '1 sajt, 2 användare',
                      'E‑postsupport',
                    ],
                  ],
                  [
                    'name' => 'Growth',
                    'price' => '1 490',
                    'desc' => 'För växande bolag med flera flöden.',
                    'highlight' => true,
                    'features' => [
                      '2 500 AI‑genereringar/mån',
                      '200 WP‑publiceringar/mån',
                      '8 SEO‑audits/mån',
                      'Lead tracking: 25 000 events/mån',
                      '3 sajter, 5 användare',
                      'Prioriterad support',
                    ],
                  ],
                  [
                    'name' => 'Pro',
                    'price' => '3 990',
                    'desc' => 'För byråer och större team.',
                    'features' => [
                      '10 000 AI‑genereringar/mån',
                      '1 000 WP‑publiceringar/mån',
                      '30 SEO‑audits/mån',
                      'Lead tracking: 100 000 events/mån',
                      '10 sajter, 20 användare',
                      'SLA & dedikerad kontakt',
                    ],
                  ],
                ];
            @endphp

            @foreach($tiers as $t)
                <div class="border rounded-lg p-6 bg-white {{ !empty($t['highlight']) ? 'ring-2 ring-indigo-500' : '' }}">
                    <div class="text-sm text-gray-500">{{ $t['name'] }}</div>
                    <div class="mt-1 text-3xl font-semibold">{{ $t['price'] }} kr<span class="text-base text-gray-500 font-normal">/mån</span></div>
                    <p class="mt-2 text-sm text-gray-600">{{ $t['desc'] }}</p>
                    <ul class="mt-4 space-y-2 text-sm">
                        @foreach($t['features'] as $f)
                            <li class="flex gap-2">
                                <span class="text-emerald-600">✓</span>
                                <span>{{ $f }}</span>
                            </li>
                        @endforeach
                    </ul>
                    <div class="mt-6">
                        @if(Route::has('register'))
                            <a href="{{ route('register') }}" class="w-full inline-flex items-center justify-center px-4 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-700">Prova gratis</a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="border rounded-lg p-5 bg-indigo-50 text-indigo-900">
            <div class="font-semibold">Early adopter–erbjudande</div>
            <p class="text-sm">
                50% rabatt i 3 månader + gratis onboarding (1h) för de första 20 kunderna. Nöjd–start garanti: avbryt inom 30 dagar → ingen debitering.
            </p>
        </div>

        <div class="text-xs text-gray-500">
            Tillägg: AI 0,30 kr/st, WP‑publicering 0,80 kr/st, audit 99 kr/st, leads 0,001 kr/event. Alla priser exkl. moms.
        </div>
    </div>
@endsection
