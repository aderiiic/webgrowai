@component('mail::message')
    # Hej och välkommen till WebGrow AI! 🎉

    Hej **{{ $user->name }}**,

    Vi är glada att kunna välkomna dig och **{{ $customer->company_name ?? $customer->name }}** till WebGrow AI! Ett konto har skapats åt dig av vår administratör och du kan nu komma igång med modern SEO, publicering och konverteringsoptimering.

    ## 🎁 Din kostnadsfria testperiod

    Du har fått **14 dagars kostnadsfri testperiod** där du kan utforska alla funktioner utan kostnad. Under denna tid har du full tillgång till plattformen.

    ## Sätt ditt lösenord

    För att komma igång behöver du först sätta ett säkert lösenord för ditt konto:

    @component('mail::button', ['url' => $reset_url])
        Sätt mitt lösenord
    @endcomponent

    ## Dina kontouppgifter

    - **E-post:** {{ $user->email }}
    - **Företag:** {{ $customer->company_name ?? $customer->name }}
    - **Kontaktperson:** {{ $customer->contact_name }}
    - **Status:** 14 dagars kostnadsfri testperiod

    ## Vad kan du göra nu?

    När du har satt ditt lösenord kan du logga in och börja utforska:

    - ✅ **SEO-analys och optimering**
    - ✅ **Innehållspublicering**
    - ✅ **Konverteringsanalys**
    - ✅ **Sociala medier-integration**
    - ✅ **AI-assisterad content creation**
    - ✅ **Omfattande analytics**

    ## 💡 Om din testperiod

    Din kostnadsfria testperiod löper i 14 dagar från idag. Under denna tid:
    - Ingen fakturering sker
    - Du har full tillgång till alla funktioner
    - Du kan avsluta när som helst
    - Ingen bindningstid

    ## Behöver du hjälp?

    Vi finns här för att hjälpa dig komma igång! Kontakta oss gärna:
    - **Support:** info@webbi.se

    Välkommen ombord och tack för att du valde WebGrow AI!

    Vänliga hälsningar,<br>
    **WebGrow AI-teamet**

    ---

    <small>Om du inte begärde detta konto, vänligen kontakta oss omedelbart på support@webgrowai.com</small>
@endcomponent
