@extends('layouts.app')

@section('title', 'Booking Details')

@section('content')
<div class="container mx-auto p-6 space-y-6">

    <!-- Booking Header -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Booking #{{ $booking->id }}</h1>
                <p class="text-gray-600 mt-1">Booked on {{ $booking->created_at->format('M j, Y g:i A') }}</p>
            </div>
            <div class="text-right">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                    @if($booking->status === 'confirmed') bg-green-100 text-green-800
                    @elseif($booking->status === 'pending') bg-yellow-100 text-yellow-800
                    @elseif($booking->status === 'cancelled') bg-red-100 text-red-800
                    @else bg-gray-100 text-gray-800 @endif">
                    {{ ucfirst($booking->status) }}
                </span>
            </div>
        </div>
    </div>

    <!-- Booking Details -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Customer Information -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Customer Information</h2>
            <div class="space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Name</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $booking->customer_name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $booking->customer_email }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Phone</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $booking->customer_phone }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Participants</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $booking->participants ?? 1 }}</p>
                </div>
            </div>
        </div>

        <!-- Activity Information -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Activity Information</h2>
            <div class="space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Activity</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $booking->activity->title }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $booking->activity->description }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Date & Time</label>
                    <p class="mt-1 text-sm text-gray-900">
                        {{ $booking->activity->datetime ? $booking->activity->datetime->format('M j, Y g:i A') : 'TBD' }}
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Venue</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $booking->activity->venue ?? 'TBD' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Provider</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $booking->activity->provider->name ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Information -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Payment Information</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Total Amount</label>
                <p class="mt-1 text-lg font-semibold text-gray-900">KES {{ number_format($booking->amount, 2) }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Amount Paid</label>
                <p class="mt-1 text-lg font-semibold text-green-600">KES {{ number_format($booking->amount_paid, 2) }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Payment Method</label>
                <p class="mt-1 text-sm text-gray-900">{{ ucfirst($booking->payment_method ?? 'N/A') }}</p>
            </div>
        </div>

        @if($booking->transaction_id)
        <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700">Transaction ID</label>
            <p class="mt-1 text-sm text-gray-900 font-mono">{{ $booking->transaction_id }}</p>
        </div>
        @endif

        @if($booking->amount_paid < $booking->amount)
        <div class="mt-4 p-4 bg-yellow-50 rounded-lg">
            <p class="text-sm text-yellow-800">
                <strong>Outstanding Balance:</strong> KES {{ number_format($booking->amount - $booking->amount_paid, 2) }}
            </p>
        </div>
        @endif
    </div>

    <!-- Additional Notes -->
    @if($booking->notes)
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Additional Notes</h2>
        <p class="text-gray-700">{{ $booking->notes }}</p>
    </div>
    @endif

    <!-- Action Buttons -->
    <div class="flex items-center justify-between">
        <a href="{{ route('bookings.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
            ← Back to Bookings
        </a>

        <div class="flex space-x-3">
            @if($booking->status === 'confirmed' && $booking->scheduled_at && $booking->scheduled_at->isFuture())
            <form method="POST" action="{{ route('bookings.cancel', $booking) }}" class="inline">
                @csrf
                @method('POST')
                <button type="submit" onclick="return confirm('Are you sure you want to cancel this booking?')"
                        class="inline-flex items-center px-4 py-2 border border-red-300 shadow-sm text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50">
                    Cancel Booking
                </button>
            </form>
            @endif

            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700">
                Back to Dashboard
            </a>
        </div>
    </div>

</div>
@endsection
