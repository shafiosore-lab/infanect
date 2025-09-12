<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Receipt #{{ $payment->id }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        .header { text-align:center; margin-bottom:20px; }
        .section { margin-bottom:15px; }
        table { width:100%; border-collapse: collapse; }
        td, th { padding:8px; border:1px solid #ddd; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Infanect Receipt</h1>
        <p>Receipt #: {{ $payment->id }} | Date: {{ $payment->created_at }}</p>
    </div>

    <div class="section">
        <h3>Booking Details</h3>
        <table>
            <tr><th>Service</th><td>{{ $booking->service->name ?? 'N/A' }}</td></tr>
            <tr><th>Provider</th><td>{{ $booking->provider->name ?? 'N/A' }}</td></tr>
            <tr><th>Client</th><td>{{ $booking->client->name ?? 'N/A' }}</td></tr>
            <tr><th>Start</th><td>{{ $booking->start_at }}</td></tr>
            <tr><th>End</th><td>{{ $booking->end_at }}</td></tr>
        </table>
    </div>

    <div class="section">
        <h3>Payment</h3>
        <table>
            <tr><th>Amount</th><td>{{ $payment->currency }} {{ number_format($payment->amount, 2) }}</td></tr>
            <tr><th>Gateway</th><td>{{ $payment->gateway ?? 'N/A' }}</td></tr>
            <tr><th>Status</th><td>{{ $payment->status }}</td></tr>
        </table>
    </div>

    <p>Thank you for your payment.</p>
</body>
</html>
