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

    <!-- Receipt Download Section -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-green-50 to-emerald-50 border-b flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-800">Booking Receipt</h2>
            <button onclick="downloadReceipt()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Download Receipt
            </button>
        </div>
        <div class="p-6">
            @include('bookings.partials.receipt')
        </div>
    </div>

    <!-- More Activities Section -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b">
            <h2 class="text-lg font-semibold text-gray-800">More Activities You Might Like</h2>
        </div>
        <div class="p-6">
            @php
                $relatedActivities = \App\Models\Activity::with('provider')
                    ->where('category', $booking->activity->category)
                    ->where('id', '!=', $booking->activity_id)
                    ->where('datetime', '>', now())
                    ->limit(3)
                    ->get();
            @endphp

            @if($relatedActivities->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($relatedActivities as $activity)
                        <div class="bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition-colors">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1">
                                    <h3 class="text-lg font-medium text-gray-900">{{ $activity->title }}</h3>
                                    <p class="text-sm text-gray-600 mt-1 line-clamp-2">{{ Str::limit($activity->description, 60) }}</p>
                                </div>
                                <div class="text-right ml-4">
                                    <div class="text-lg font-bold text-gray-900">KES {{ number_format($activity->price, 2) }}</div>
                                </div>
                            </div>

                            <div class="space-y-2 mb-4">
                                <div class="flex items-center text-sm text-gray-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    {{ $activity->datetime ? $activity->datetime->format('M j, Y g:i A') : 'TBD' }}
                                </div>
                                <div class="flex items-center text-sm text-gray-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    {{ $activity->venue ?? 'TBD' }}
                                </div>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ ucfirst($activity->category) }}
                                </span>
                                <a href="{{ route('bookings.create', ['activity' => $activity->id]) }}"
                                   class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                    Book Now
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No related activities found</h3>
                    <p class="mt-1 text-sm text-gray-500">Check back later for more activities.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex items-center justify-center space-x-4">
        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-6 py-3 border border-gray-300 shadow-sm text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Back to Dashboard
        </a>
        <a href="{{ route('bookings.show', $booking) }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            View Booking Details
        </a>
    </div>

</div>

<script>
function downloadReceipt() {
    const receipt = document.getElementById('receiptContent');
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Booking Receipt #${{ $booking->id }}</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                .receipt-header, .receipt-section { margin-bottom: 20px; }
                .receipt-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
                .activity-card, .payment-details, .provider-info { background: #f9f9f9; padding: 15px; border-radius: 8px; }
                .receipt-footer { text-align: center; border-top: 1px solid #ccc; padding-top: 20px; font-size: 12px; color: #666; }
                @media print { body { margin: 0; } }
            </style>
        </head>
        <body>
            ${receipt.innerHTML}
        </body>
        </html>
    `);
    printWindow.document.close();
    printWindow.print();
}
</script>
@endsection
