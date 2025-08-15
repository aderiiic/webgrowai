<!doctype html>
<html lang="sv">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, sans-serif; color:#111827; font-size: 13px; }
        .row { display:flex; gap:20px; }
        .col { flex:1; }
        .muted { color:#6b7280; }
        table { width:100%; border-collapse: collapse; }
        th, td { text-align:left; padding:6px 8px; border-bottom:1px solid #e5e7eb; }
        .total { font-weight:600; }
        .right { text-align:right; }
    </style>
</head>
<body>
<h1 style="font-size:20px; margin:0 0 12px;">Faktura #{{ $invoice->id }}</h1>
<div class="muted">Period: {{ $invoice->period }}</div>

<div class="row" style="margin-top:14px;">
    <div class="col">
        <div style="font-weight:600;">Fakturamottagare</div>
        <div>{{ $invoice->customer->name }}</div>
        @if($invoice->customer->billing_address)
            <div>{{ $invoice->customer->billing_address }}</div>
            <div>{{ $invoice->customer->billing_zip }} {{ $invoice->customer->billing_city }}</div>
            <div>{{ $invoice->customer->billing_country }}</div>
        @endif
        @if($invoice->customer->billing_email)
            <div class="muted">{{ $invoice->customer->billing_email }}</div>
        @endif
    </div>
    <div class="col">
        <div><span class="muted">Status:</span> {{ strtoupper($invoice->status) }}</div>
        @if($invoice->due_date)
            <div><span class="muted">Förfallodatum:</span> {{ $invoice->due_date->format('Y-m-d') }}</div>
        @endif
    </div>
</div>

<h2 style="margin-top:18px; font-size:16px;">Specifikation</h2>
<table>
    <thead>
    <tr>
        <th>Rad</th>
        <th class="right">Antal</th>
        <th class="right">Pris</th>
        <th class="right">Belopp</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>Plan</td>
        <td class="right">1</td>
        <td class="right">{{ number_format(($invoice->plan_amount ?? 0)/100, 2, ',', ' ') }} kr</td>
        <td class="right">{{ number_format(($invoice->plan_amount ?? 0)/100, 2, ',', ' ') }} kr</td>
    </tr>
    @foreach(($invoice->lines ?? []) as $line)
        <tr>
            <td>{{ $line['type'] ?? 'Tillägg' }}</td>
            <td class="right">{{ $line['qty'] ?? 0 }}</td>
            <td class="right">{{ number_format(($line['unit'] ?? 0)/100, 2, ',', ' ') }} kr</td>
            <td class="right">{{ number_format(($line['amount'] ?? 0)/100, 2, ',', ' ') }} kr</td>
        </tr>
    @endforeach
    <tr>
        <td class="total" colspan="3">Totalt</td>
        <td class="right total">{{ number_format(($invoice->total_amount ?? 0)/100, 2, ',', ' ') }} kr</td>
    </tr>
    </tbody>
</table>
</body>
</html>
