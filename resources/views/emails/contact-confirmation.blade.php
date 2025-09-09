@component('mail::message')
    # Tack för din förfrågan!

    Hej {{ $name }},

    Tack för att du kontaktade oss! Vi har mottagit ditt meddelande och återkommer inom 24 timmar.

    @if(!empty($subject))
        **Angående:** {{ $subject }}
    @endif

    ## Ditt meddelande
    {{ $userMessage }}

    Om du har ytterligare frågor kan du alltid svara på detta mail eller kontakta oss på support@webgrow.se.

    @component('mail::button', ['url' => url('/')])
        Tillbaka till WebGrow AI
    @endcomponent

    Vänliga hälsningar,
    WebGrow AI Team
@endcomponent
