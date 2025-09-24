@component('mail::message')
    # Välkommen till WebGrow AI!

    Hej {{ $user->name ?? 'där' }},

    Vad roligt att du är igång. Här är några snabba tips för att komma vidare:

    - Lägg till din första sajt under Sajter.
    - Koppla din sida för bästa upplevelse om du inte redan har gjort det.
    - Besök vår kom‑igång‑sida för guider: {{ route('get-started') }}

    Behöver du hjälp? Svara gärna på det här mailet eller kontakta oss på info@webbi.se.

    Trevlig dag!
    — WebGrow AI
@endcomponent
