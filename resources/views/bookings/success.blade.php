@extends('layouts.app')

@section('title', 'Booking Successful')

@section('content')
<div class="container mx-auto p-6 space-y-6">

    <!-- Success Header -->
    <div class="text-center">
        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        <h1 class="mt-4 text-2xl font-bold text-gray-900">Booking Successful!</h1>
        <p class="mt-2 text-gray-600">Your booking has been confirmed and payment processed successfully.</p>
    </div>

    <!-- Booking Summary -->
    <div class="bg-white rounded-lg shadow-md p-6 space-y-4">
        <h2 class="text-lg font-semibold text-gray-800">Booking Summary</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p><span class="font-medium">Booking ID:</span> #{{ $booking->id }}</p>
                <p><span class="font-medium">Date:</span> {{ $booking->created_at->format('M j, Y g:i A') }}</p>
                <p><span class="font-medium">Status:</span> {{ ucfirst($booking->status) }}</p>
            </div>
            <div>
                <p><span class="font-medium">Customer Name:</span> {{ $booking->customer_name }}</p>
                <p><span class="font-medium">Email:</span> {{ $booking->customer_email }}</p>
                <p><span class="font-medium">Phone:</span> {{ $booking->customer_phone }}</p>
            </div>
        </div>

        <div class="bg-gray-50 rounded-lg p-4 mt-4">
            <h3 class="font-medium text-gray-900 mb-2">Activity Details</h3>
            <p><span class="font-medium">Title:</span> {{ $booking->activity->title }}</p>
            <p><span class="font-medium">Date & Time:</span> {{ $booking->activity->datetime ? $booking->activity->datetime->format('M j, Y g:i A') : 'TBD' }}</p>
            <p><span class="font-medium">Venue:</span> {{ $booking->activity->venue ?? 'TBD' }}</p>
            <p><span class="font-medium">Participants:</span> {{ $booking->participants ?? 1 }}</p>
        </div>

        <div class="bg-gray-50 rounded-lg p-4 mt-4">
            <h3 class="font-medium text-gray-900 mb-2">Payment Details</h3>
            <p><span class="font-medium">Amount Paid:</span> KES {{ number_format($booking->amount, 2) }}</p>
            <p><span class="font-medium">Payment Method:</span> {{ ucfirst($booking->payment_method ?? 'N/A') }}</p>
            @if($booking->transaction_id)
                <p><span class="font-medium">Transaction ID:</span> {{ $booking->transaction_id }}</p>
            @endif
        </div>

        @if($booking->notes)
        <div class="bg-yellow-50 rounded-lg p-4 mt-4">
            <h3 class="font-medium text-gray-900 mb-2">Additional Notes</h3>
            <p>{{ $booking->notes }}</p>
        </div>
        @endif
    </div>

    <!-- Action Buttons -->
    <div class="flex items-center justify-center space-x-4 mt-6">
        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-6 py-3 border border-gray-300 shadow-sm text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
            Back to Dashboard
        </a>
        <a href="{{ route('bookings.show', $booking) }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700">
            View Booking Details
        </a>
    </div>

</div>
@endsection
