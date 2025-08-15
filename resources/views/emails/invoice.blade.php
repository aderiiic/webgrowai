<!doctype html>
<html lang="sv">
<body style="font-family: system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, sans-serif; color:#111827;">
<h1 style="font-size:18px; margin:0 0 8px;">Faktura {{ $invoice->period }}</h1>
<p style="margin:0 0 16px;">Hej {{ $invoice->customer->contact_name ?? $invoice->customer->name }},</p>
<p style="margin:0 0 16px;">
    Här kommer fakturan för {{ $invoice->period }}. Totalt:
    <strong>{{ number_format(($invoice->total_amount ?? 0)/100, 2, ',', ' ') }} kr</strong>.
</p>
<p style="margin:0 0 16px;">Fakturan bifogas som fil. Återkom om du har frågor.</p>
<p style="margin:0 0 16px;">Vänliga hälsningar<br>WebGrow AI</p>
</body>
</html>
