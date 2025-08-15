@extends('layouts.guest', ['title' => 'WebGrow AI – Mer trafik, fler leads, mindre handpåläggning'])

@section('content')
    <main x-data="{ demoOpen:false }">
        <!-- Hero -->
        <section class="bg-gradient-to-b from-gray-50 to-white">
            <div class="max-w-7xl mx-auto px-4 py-16 lg:py-24 grid md:grid-cols-2 gap-10 items-center">
                <div>
                    <h1 class="text-3xl md:text-5xl font-semibold leading-tight">
                        Mer trafik. Fler leads. Mindre handpåläggning.
                    </h1>
                    <p class="mt-4 text-gray-600 text-lg">
                        WebGrow AI sköter SEO‑förslag, CRO‑insikter, AI‑publicering till WordPress & sociala kanaler – på autopilot.
                    </p>
                    <div class="mt-6 flex flex-wrap gap-3">
                        @if(Route::has('register'))
                            <a href="{{ route('register') }}"
                               class="px-5 py-3 rounded bg-indigo-600 text-white hover:bg-indigo-700"
                               data-lead-cta="hero_register">Starta gratis</a>
                        @endif
                        <button @click="demoOpen=true"
                                class="px-5 py-3 rounded border hover:bg-white"
                                data-lead-cta="hero_book_demo">Boka demo</button>
                        <a href="#pricing"
                           class="px-5 py-3 rounded border hover:bg-white"
                           data-lead-cta="hero_see_pricing">Se priser</a>
                    </div>
                    <div class="mt-4 text-xs text-gray-500">
                        14 dagar gratis • Ingen bindningstid
                    </div>
                </div>
                <div class="bg-white rounded-lg border shadow-sm p-6">
                    <div class="text-sm text-gray-600">Exempel på flöden som körs:</div>
                    <ul class="mt-3 space-y-2 text-sm">
                        <li class="flex items-center gap-2">
                            <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                            AI föreslår meta‑titel/description för dina sidor (Apply → WP)
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                            CRO‑insikter: rubriker, CTA och formulärplacering
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                            Veckodigest med kampanjidéer & ämnen
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                            Publicera AI‑innehåll till WordPress/Facebook/Instagram
                        </li>
                    </ul>
                    <div class="mt-6">
                        <a href="{{ route('news.index') }}" class="text-indigo-600 hover:underline text-sm">Läs vad som är nytt →</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features -->
        <section id="features" class="max-w-7xl mx-auto px-4 py-14">
            <div class="grid md:grid-cols-3 gap-6">
                <div class="p-6 border rounded-lg bg-white hover:shadow-sm transition">
                    <h3 class="font-semibold">Nyckelordsoptimering</h3>
                    <p class="mt-2 text-sm text-gray-600">SerpAPI‑baserad rankingkoll, AI‑förslag på keywords & meta—Apply till WP.</p>
                </div>
                <div class="p-6 border rounded-lg bg-white hover:shadow-sm transition">
                    <h3 class="font-semibold">CRO‑insikter</h3>
                    <p class="mt-2 text-sm text-gray-600">Förbättringar på rubriker, CTA, formulär. Ett klick för att uppdatera sidor.</p>
                </div>
                <div class="p-6 border rounded-lg bg-white hover:shadow-sm transition">
                    <h3 class="font-semibold">AI‑publicering</h3>
                    <p class="mt-2 text-sm text-gray-600">Generera & schemalägg innehåll till WordPress, Facebook & Instagram.</p>
                </div>
            </div>
        </section>

        <!-- Pricing (in-page) -->
        <section id="pricing" class="bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 py-14">
                <h2 class="text-2xl font-semibold">Priser</h2>
                <p class="text-gray-600 mt-1">Börja litet och skala upp. 14 dagar gratis, ingen bindningstid.</p>

                <div class="grid md:grid-cols-3 gap-6 mt-6">
                    <!-- Starter -->
                    <div class="border rounded-lg p-6 bg-white">
                        <div class="text-sm text-gray-500">Starter</div>
                        <div class="mt-1 text-3xl font-semibold">490 kr<span class="text-base text-gray-500 font-normal">/mån</span></div>
                        <p class="mt-2 text-sm text-gray-600">För mindre sajter som vill igång snabbt.</p>
                        <ul class="mt-4 space-y-2 text-sm">
                            <li class="flex gap-2"><span class="text-emerald-600">✓</span>500 AI‑genereringar/mån</li>
                            <li class="flex gap-2"><span class="text-emerald-600">✓</span>50 WP‑publiceringar/mån</li>
                            <li class="flex gap-2"><span class="text-emerald-600">✓</span>2 SEO‑audits/mån</li>
                            <li class="flex gap-2"><span class="text-emerald-600">✓</span>Lead tracking: 5 000 events/mån</li>
                            <li class="flex gap-2"><span class="text-emerald-600">✓</span>1 sajt, 2 användare</li>
                        </ul>
                        <div class="mt-6">
                            @if(Route::has('register'))
                                <a href="{{ route('register') }}"
                                   class="w-full inline-flex items-center justify-center px-4 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-700"
                                   data-lead-cta="pricing_starter_register">Prova gratis</a>
                            @endif
                        </div>
                    </div>
                    <!-- Growth -->
                    <div class="border rounded-lg p-6 bg-white ring-2 ring-indigo-500">
                        <div class="text-sm text-gray-500">Growth</div>
                        <div class="mt-1 text-3xl font-semibold">1 490 kr<span class="text-base text-gray-500 font-normal">/mån</span></div>
                        <p class="mt-2 text-sm text-gray-600">För växande bolag med flera flöden.</p>
                        <ul class="mt-4 space-y-2 text-sm">
                            <li class="flex gap-2"><span class="text-emerald-600">✓</span>2 500 AI‑genereringar/mån</li>
                            <li class="flex gap-2"><span class="text-emerald-600">✓</span>200 WP‑publiceringar/mån</li>
                            <li class="flex gap-2"><span class="text-emerald-600">✓</span>8 SEO‑audits/mån</li>
                            <li class="flex gap-2"><span class="text-emerald-600">✓</span>Lead tracking: 25 000 events/mån</li>
                            <li class="flex gap-2"><span class="text-emerald-600">✓</span>3 sajter, 5 användare</li>
                        </ul>
                        <div class="mt-6 flex gap-2">
                            <button @click="demoOpen=true"
                                    class="w-full inline-flex items-center justify-center px-4 py-2 rounded border hover:bg-white"
                                    data-lead-cta="pricing_growth_demo">Boka demo</button>
                            @if(Route::has('register'))
                                <a href="{{ route('register') }}"
                                   class="w-full inline-flex items-center justify-center px-4 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-700"
                                   data-lead-cta="pricing_growth_register">Prova gratis</a>
                            @endif
                        </div>
                    </div>
                    <!-- Pro -->
                    <div class="border rounded-lg p-6 bg-white">
                        <div class="text-sm text-gray-500">Pro</div>
                        <div class="mt-1 text-3xl font-semibold">3 990 kr<span class="text-base text-gray-500 font-normal">/mån</span></div>
                        <p class="mt-2 text-sm text-gray-600">För byråer och större team.</p>
                        <ul class="mt-4 space-y-2 text-sm">
                            <li class="flex gap-2"><span class="text-emerald-600">✓</span>10 000 AI‑genereringar/mån</li>
                            <li class="flex gap-2"><span class="text-emerald-600">✓</span>1 000 WP‑publiceringar/mån</li>
                            <li class="flex gap-2"><span class="text-emerald-600">✓</span>30 SEO‑audits/mån</li>
                            <li class="flex gap-2"><span class="text-emerald-600">✓</span>Lead tracking: 100 000 events/mån</li>
                            <li class="flex gap-2"><span class="text-emerald-600">✓</span>10 sajter, 20 användare</li>
                        </ul>
                        <div class="mt-6">
                            <button @click="demoOpen=true"
                                    class="w-full inline-flex items-center justify-center px-4 py-2 rounded border hover:bg-white"
                                    data-lead-cta="pricing_pro_demo">Boka demo</button>
                        </div>
                    </div>
                </div>

                <div class="border rounded-lg p-5 bg-indigo-50 text-indigo-900 mt-6">
                    <div class="font-semibold">Early adopter–erbjudande</div>
                    <p class="text-sm">
                        50% rabatt i 3 månader + gratis onboarding (1h) för de första 20 kunderna. Nöjd–start garanti: avbryt inom 30 dagar → ingen debitering.
                    </p>
                </div>
                <div class="text-xs text-gray-500 mt-2">
                    Tillägg: AI 0,30 kr/st, WP‑publicering 0,80 kr/st, audit 99 kr/st, leads 0,001 kr/event. Alla priser exkl. moms.
                </div>
            </div>
        </section>

        <!-- Testimonials -->
        <section id="testimonials" class="max-w-7xl mx-auto px-4 py-14">
            <h2 class="text-2xl font-semibold">Vad säger kunderna?</h2>
            <div class="grid md:grid-cols-3 gap-6 mt-6">
                <div class="p-6 border rounded-lg bg-white hover:shadow-sm transition">
                    <p class="text-sm text-gray-700">“Vi ökade våra demo‑bokningar med 42% på två månader. Att kunna Apply:a förslag direkt till WordPress sparar oss timmar.”</p>
                    <div class="mt-4 text-sm font-medium">Sara, Marketing Lead</div>
                    <div class="text-xs text-gray-500">SaaS‑bolag</div>
                </div>
                <div class="p-6 border rounded-lg bg-white hover:shadow-sm transition">
                    <p class="text-sm text-gray-700">“SEO‑förslagen är konkreta och träffsäkra. Vi fick snabb effekt på flera viktiga sidor och bättre CTR.”</p>
                    <div class="mt-4 text-sm font-medium">Johan, E‑commerce Manager</div>
                    <div class="text-xs text-gray-500">E‑handel</div>
                </div>
                <div class="p-6 border rounded-lg bg-white hover:shadow-sm transition">
                    <p class="text-sm text-gray-700">“Veckodigesten ger teamet en tydlig plan och sparar massor av tid inför varje vecka.”</p>
                    <div class="mt-4 text-sm font-medium">Anna, Content Lead</div>
                    <div class="text-xs text-gray-500">Byrå</div>
                </div>
            </div>
        </section>

        <!-- FAQ -->
        <section id="faq" class="bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 py-14">
                <h2 class="text-2xl font-semibold">Vanliga frågor</h2>
                <div class="mt-6 space-y-3">
                    <details class="bg-white border rounded-lg p-4">
                        <summary class="cursor-pointer font-medium">Behöver jag låsa upp allt från start?</summary>
                        <p class="mt-2 text-sm text-gray-600">Nej, du kan börja på Starter och uppgradera när behoven växer.</p>
                    </details>
                    <details class="bg-white border rounded-lg p-4">
                        <summary class="cursor-pointer font-medium">Hur fungerar publiceringen till WordPress?</summary>
                        <p class="mt-2 text-sm text-gray-600">Du kopplar din WP med ett app‑lösenord. Därefter kan du Apply‑a AI‑förslag direkt till sidor/texter.</p>
                    </details>
                    <details class="bg-white border rounded-lg p-4">
                        <summary class="cursor-pointer font-medium">Är det bindningstid?</summary>
                        <p class="mt-2 text-sm text-gray-600">Ingen bindningstid. 14 dagar gratis – avsluta när som helst.</p>
                    </details>
                </div>
            </div>
        </section>

        <!-- Book a demo modal -->
        <div x-show="demoOpen" x-cloak class="fixed inset-0 bg-black/40 flex items-center justify-center p-4 z-50">
            <div @click.outside="demoOpen=false" class="bg-white rounded-lg max-w-md w-full p-6">
                <h3 class="text-xl font-semibold">Boka en demo</h3>
                <p class="text-sm text-gray-600 mt-1">Fyll i så återkommer vi snabbt.</p>
                <form class="mt-4 space-y-3" method="POST" action="{{ route('demo.request') }}">
                    @csrf
                    <input type="text" name="name" class="input input-bordered w-full" placeholder="Ditt namn" required>
                    <input type="email" name="email" class="input input-bordered w-full" placeholder="Din e‑post" required>
                    <input type="text" name="company" class="input input-bordered w-full" placeholder="Företag (valfritt)">
                    <textarea name="notes" rows="3" class="textarea textarea-bordered w-full" placeholder="Vad vill du fokusera på i demon? (valfritt)"></textarea>
                    <div class="flex items-center justify-end gap-2">
                        <button type="button" class="btn" @click="demoOpen=false">Avbryt</button>
                        <button type="submit" class="btn btn-primary" data-lead-cta="book_demo_submit">Skicka</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Blog teaser -->
        <section class="max-w-7xl mx-auto px-4 py-14">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-semibold">Senaste nytt</h3>
                <a href="{{ route('news.index') }}" class="text-indigo-600 hover:underline text-sm">Visa alla →</a>
            </div>
            <div class="grid md:grid-cols-3 gap-6 mt-6">
                @foreach(\App\Models\Post::query()->whereNotNull('published_at')->latest('published_at')->take(3)->get() as $post)
                    <a href="{{ route('news.show', $post->slug) }}" class="block p-5 border rounded-lg bg-white hover:shadow-sm">
                        <div class="text-xs text-gray-500">{{ optional($post->published_at)->format('Y-m-d') }}</div>
                        <div class="mt-1 font-semibold">{{ $post->title }}</div>
                        <p class="mt-2 text-sm text-gray-600 line-clamp-3">{{ $post->excerpt }}</p>
                    </a>
                @endforeach
            </div>
        </section>
    </main>
@endsection
