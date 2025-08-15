<div class="max-w-3xl mx-auto py-10 space-y-6" @if($listening) wire:poll.10s="listen" @endif>
    <h1 class="text-2xl font-semibold">Onboarding – Lead Tracking</h1>

    <div class="border rounded p-4 bg-white space-y-3">
        <div class="text-sm text-gray-600">Sajt</div>
        <div class="font-medium">{{ $siteName }}</div>

        <div class="text-sm text-gray-600 mt-2">Site Key</div>
        <div class="flex items-center gap-2">
            <input class="input input-bordered input-sm w-full" value="{{ $siteKey }}" readonly>
        </div>

        <div class="text-sm text-gray-600 mt-2">Track URL</div>
        <div class="flex items-center gap-2">
            <input class="input input-bordered input-sm w-full" value="{{ rtrim($trackUrl,'/') }}" readonly>
        </div>
    </div>

    <div class="border rounded p-4 bg-white space-y-4">
        <h2 class="text-lg font-medium">Rekommenderat: WordPress-plugin</h2>
        <ol class="list-decimal pl-5 space-y-2 text-sm">
            <li>Ladda ner pluginet “Webbi Lead Tracker” (zip).</li>
            <li>Installera & aktivera i WordPress admin.</li>
            <li>Inställningar → Webbi Lead Tracker:
                <ul class="list-disc pl-5">
                    <li>Ange Site Key: <code>{{ $siteKey }}</code></li>
                    <li>Track URL: <code>{{ rtrim($trackUrl,'/') }}</code></li>
                    <li>(Valfritt) Kryssa “Kräv cookie‑samtycke” om ni använder cookie‑banner och sätt <code>window.WebbiConsent = true</code> vid accept.</li>
                </ul>
            </li>
        </ol>
        <div>
            <a class="btn btn-sm" href="{{ route('downloads.webbi-lead-tracker') }}">Ladda ner plugin</a>
        </div>
    </div>

    <div class="border rounded p-4 bg-white space-y-3">
        <h2 class="text-lg font-medium">Alternativ: Manuell inklistring</h2>
        <p class="text-sm text-gray-600">Lägg detta i sidhuvudet (före &lt;/head&gt;):</p>
        <pre class="text-xs bg-gray-50 p-2 rounded border overflow-auto">
&lt;script&gt;
  window.WEBBI_SITE_KEY = '{{ $siteKey }}';
  window.WEBBI_TRACK_URL = '{{ rtrim($trackUrl,'/') }}';
&lt;/script&gt;
&lt;script src="{{ rtrim($trackUrl,'/') }}/lead-tracker.js" defer&gt;&lt;/script&gt;</pre>
    </div>

    <div class="border rounded p-4 bg-white space-y-3">
        <h2 class="text-lg font-medium">Testa tracking</h2>
        <p class="text-sm text-gray-600">Öppna sajten i en annan flik, navigera ett par sidor och klicka en CTA (data-lead-cta="...").</p>
        <div class="flex items-center gap-3">
            <button class="btn btn-primary btn-sm" wire:click="listen">{{ $listening ? 'Lyssnar...' : 'Börja lyssna' }}</button>
            <div class="text-sm text-gray-600">Senaste event: {{ $lastEventAt ?? '—' }}</div>
        </div>
    </div>
</div>
