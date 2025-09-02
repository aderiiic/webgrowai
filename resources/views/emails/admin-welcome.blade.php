<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="utf-8">
    <title>Välkommen till WebGrow AI</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body style="margin:0; padding:0; background-color:#f6f7fb;">
<table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="background:#f6f7fb;">
    <tr>
        <td align="center" style="padding:24px;">
            <table role="presentation" cellpadding="0" cellspacing="0" width="600" style="max-width:600px; background:#ffffff; border-radius:12px; overflow:hidden;">
                <tr>
                    <td style="padding:24px; text-align:center; background:linear-gradient(135deg,#4f46e5,#7c3aed); color:#fff;">
                        <h1 style="margin:0; font-family:Arial,Helvetica,sans-serif; font-size:22px; line-height:1.3;">
                            Hej och välkommen till WebGrow AI! 🎉
                        </h1>
                    </td>
                </tr>

                <tr>
                    <td style="padding:24px; font-family:Arial,Helvetica,sans-serif; color:#111827; font-size:14px; line-height:1.6;">
                        <p style="margin:0 0 12px;">Hej <strong>{{ $user->name }}</strong>,</p>

                        <p style="margin:0 0 12px;">
                            Vi är glada att välkomna dig och <strong>{{ $customer->company_name ?? $customer->name }}</strong> till WebGrow AI!
                            Ett konto har skapats åt dig och du kan nu komma igång med modern SEO, publicering och konverteringsoptimering.
                        </p>

                        <div style="background:#eff6ff; border:1px solid #bfdbfe; border-radius:8px; padding:16px; margin:16px 0;">
                            <h3 style="margin:0 0 8px; font-size:14px; color:#1e40af;">🎁 Din kostnadsfria testperiod</h3>
                            <p style="margin:0; color:#1e3a8a;">
                                Du har fått <strong>14 dagars kostnadsfri testperiod</strong> där du kan utforska alla funktioner utan kostnad.
                            </p>
                        </div>

                        <h3 style="margin:16px 0 8px; font-size:14px; color:#111827;">Sätt ditt lösenord</h3>
                        <p style="margin:0 0 16px;">
                            Klicka på knappen nedan för att välja ditt lösenord:
                        </p>

                        <p style="margin:0 0 20px; text-align:center;">
                            <a href="{{ $reset_url }}"
                               style="display:inline-block; background:#4f46e5; color:#ffffff; text-decoration:none; padding:12px 18px; border-radius:8px; font-weight:bold;">
                                Sätt mitt lösenord
                            </a>
                        </p>

                        <div style="background:#f9fafb; border:1px solid #e5e7eb; border-radius:8px; padding:16px; margin:16px 0;">
                            <h4 style="margin:0 0 8px; font-size:13px; color:#111827;">Dina kontouppgifter</h4>
                            <ul style="margin:0; padding-left:18px; color:#374151;">
                                <li><strong>E‑post:</strong> {{ $user->email }}</li>
                                <li><strong>Företag:</strong> {{ $customer->company_name ?? $customer->name }}</li>
                                @if(!empty($customer->contact_name))
                                    <li><strong>Kontaktperson:</strong> {{ $customer->contact_name }}</li>
                                @endif
                                <li><strong>Status:</strong> 14 dagars kostnadsfri testperiod</li>
                            </ul>
                        </div>

                        <h3 style="margin:16px 0 8px; font-size:14px; color:#111827;">Behöver du hjälp?</h3>
                        <p style="margin:0 0 12px;">
                            Vi finns här för att hjälpa dig komma igång:
                        </p>
                        <ul style="margin:0 0 16px; padding-left:18px;">
                            <li>E‑post: <a href="mailto:info@webbi.se">info@webbi.se</a></li>
                        </ul>

                        <p style="margin:0;">
                            Vänliga hälsningar,<br>
                            <strong>{{ config('app.name') }}‑teamet</strong>
                        </p>

                        <hr style="border:none; border-top:1px solid #e5e7eb; margin:20px 0;">

                        <p style="margin:0; font-size:12px; color:#6b7280;">
                            Om du har problem med knappen ovan, kopiera och klistra in följande länk i din webbläsare:<br>
                            <a href="{{ $reset_url }}" style="color:#4f46e5; word-break:break-all;">{{ $reset_url }}</a>
                        </p>
                    </td>
                </tr>

                <tr>
                    <td style="padding:12px; text-align:center; background:#f9fafb; color:#6b7280; font-family:Arial,Helvetica,sans-serif; font-size:12px;">
                        © {{ date('Y') }} {{ config('app.name') }}
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
