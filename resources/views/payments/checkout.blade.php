@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <div class="bg-white shadow rounded p-6">
        <h1 class="text-2xl font-bold mb-4">Checkout for Booking #{{ $booking->id }}</h1>
        <p>Amount: {{ $booking->currency }} {{ number_format($booking->amount_paid, 2) }}</p>

        <div class="mt-4">
            <p>Select a payment method (placeholder):</p>
            <a href="#" class="btn btn-primary">Pay with Card (Stripe)</a>
            <a href="#" class="btn btn-secondary">Pay with PayPal</a>
            <a href="#" class="btn btn-success">Pay with MPESA</a>
        </div>
    </div>
</div>
@endsection
