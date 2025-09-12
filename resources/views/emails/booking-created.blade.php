<p>Hello,</p>

<p>Your booking (ID: {{ $booking->id }}) has been created. Details:</p>
<ul>
    <li>Service: {{ $booking->service->name ?? 'N/A' }}</li>
    <li>Start: {{ $booking->start_at }}</li>
    <li>End: {{ $booking->end_at }}</li>
    <li>Amount: {{ $booking->currency }} {{ number_format($booking->amount_paid, 2) }}</li>
</ul>

<p>Please complete payment to confirm the booking.</p>

<p>Thanks</p>
