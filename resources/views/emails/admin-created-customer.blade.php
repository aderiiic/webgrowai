@component('mail::message')
    # Hej och välkommen till WebGrow AI! 🎉

    Hej {{ $user->name }},

    Vi är glada att välkomna dig och {{ $customer->company_name }} till WebGrow AI! Ditt konto har skapats av vår administratör och du är nu redo att komma igång med modern SEO, publicering och konverteringsoptimering.

    ## Dina kontouppgifter
    - **E-post:** {{ $user->email }}
    - **Företag:** {{ $customer->company_name }}
    - **Kontaktperson:** {{ $customer->contact_name }}

    ## Sätt ditt lösenord

    För att komma igång behöver du sätta ett eget lösenord för ditt konto:

    @component('mail::button', ['url' => $resetUrl])
        Sätt mitt lösenord
    @endcomponent

    Den här länken är giltig i 60 minuter. Efter att du har satt ditt lösenord kan du logga in på ditt konto och börja utforska alla funktioner.

    ## Vad händer nu?

    1. **Sätt ditt lösenord** med länken ovan
    2. **Logga in** på din dashboard
    3. **Utforska** alla funktioner under din kostnadsfria testperiod
    4. **Kontakta oss** om du har några frågor

    Din kostnadsfria testperiod löper fram till **{{ $customer->trial_ends_at->format('d/m Y') }}**. Under denna tid har du full tillgång till alla funktioner utan kostnad.

    ## Behöver du hjälp?

    Vår support är här för att hjälpa dig komma igång:
    - E-post: info@webbi.se

    Vi ser fram emot att hjälpa dig växa ditt företag online!

    Vänliga hälsningar,<br>
    {{ config('app.name') }} Team

    ---

    <small>Om du har problem med knappen ovan, kopiera och klistra in följande länk i din webbläsare:<br>
        {{ $resetUrl }}</small>
@endcomponent
