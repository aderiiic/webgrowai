{{-- resources/views/emails/contact-confirmation.blade.php --}}
@component('mail::message')
    # Tack för din förfrågan!

    Hej {{ $name }},

    Tack för att du kontaktade oss! Vi har mottagit ditt meddelande och återkommer inom 24 timmar.

    @if($subject)
        **Angående:** {{ $subject }}
    @endif

    ## Ditt meddelande:
    {{ $message }}

    Om du har ytterligare frågor kan du alltid svara på detta mail eller kontakta oss direkt på support@webgrow.se.

    @component('mail::button', ['url' => url('/')])
        Tillbaka till WebGrow AI
    @endcomponent

    Med vänliga hälsningar,<br>
    WebGrow AI Team
@endcomponent
