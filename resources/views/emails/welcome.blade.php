@component('mail::message')
    # Välkommen till WebGrow AI!

    Hej {{ $user->name ?? 'där' }},

    Vad roligt att du är igång. Här är några snabba tips för att komma vidare:

    - Lägg till din första sajt under Sajter.
    - Koppla din WordPress- eller Shopify‑integration för bästa upplevelse.
    - Besök vår kom‑igång‑sida för guider: {{ route('get-started') }}

    Behöver du hjälp? Svara gärna på det här mailet eller kontakta oss på support@example.com.

    Trevlig dag!
    — WebGrow AI
@endcomponent
