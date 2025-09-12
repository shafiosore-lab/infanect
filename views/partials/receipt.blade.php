<div id="receiptContent" class="space-y-4">

    <!-- Receipt Header -->
    <div class="text-center border-b pb-4">
        <h3 class="text-xl font-bold text-gray-900">Infanect Receipt</h3>
        <p class="text-sm text-gray-600">Booking Confirmation</p>
        <p class="text-xs text-gray-500">Receipt #{{ $booking->id }}</p>
    </div>

    <!-- Booking Details -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <h4 class="font-medium text-gray-900 mb-2">Booking Information</h4>
            <div class="space-y-1 text-sm">
                <p><span class="font-medium">Booking ID:</span> #{{ $booking->id }}</p>
                <p><span class="font-medium">Date:</span> {{ $booking->created_at->format('M j, Y g:i A') }}</p>
                <p><span class="font-medium">Status:</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        {{ ucfirst($booking->status) }}
                    </span>
                </p>
            </div>
        </div>
        <div>
            <h4 class="font-medium text-gray-900 mb-2">Customer Details</h4>
            <div class="space-y-1 text-sm">
                <p><span class="font-medium">Name:</span> {{ $booking->customer_name }}</p>
                <p><span class="font-medium">Email:</span> {{ $booking->customer_email }}</p>
                <p><span class="font-medium">Phone:</span> {{ $booking->customer_phone }}</p>
            </div>
        </div>
    </div>

    <!-- Activity Details -->
    <div class="bg-gray-50 rounded-lg p-4">
        <h4 class="font-medium text-gray-900 mb-2">Activity Details</h4>
        <div class="flex items-start space-x-4">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
            <div class="flex-1">
                <h5 class="text-lg font-medium text-gray-900">{{ $booking->activity->title }}</h5>
                <p class="text-sm text-gray-600">{{ $booking->activity->description }}</p>
                <div class="mt-2 flex items-center space-x-4 text-sm text-gray-500">
                    <span>📅 {{ $booking->activity->datetime ? $booking->activity->datetime->format('M j, Y g:i A') : 'TBD' }}</span>
                    <span>📍 {{ $booking->activity->venue ?? 'TBD' }}</span>
                    <span>👥 {{ $booking->participants ?? 1 }} participant(s)</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Details -->
    <div class="bg-gray-50 rounded-lg p-4">
        <h4 class="font-medium text-gray-900 mb-2">Payment Details</h4>
        <div class="space-y-2">
            <div class="flex justify-between text-sm">
                <span>Activity Price:</span>
                <span>KES {{ number_format($booking->activity->price, 2) }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span>Participants:</span>
                <span>{{ $booking->participants ?? 1 }}</span>
            </div>
            <div class="flex justify-between text-sm font-medium">
                <span>Total Amount:</span>
                <span>KES {{ number_format($booking->amount, 2) }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span>Payment Method:</span>
                <span>{{ ucfirst($booking->payment_method ?? 'N/A') }}</span>
            </div>
            @if($booking->transaction_id)
            <div class="flex justify-between text-sm">
                <span>Transaction ID:</span>
                <span>{{ $booking->transaction_id }}</span>
            </div>
            @endif
        </div>
    </div>

    <!-- Provider Information -->
    <div class="bg-gray-50 rounded-lg p-4">
        <h4 class="font-medium text-gray-900 mb-2">Service Provider</h4>
        <div class="flex items-center space-x-3">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
            </div>
            <div>
                <p class="font-medium text-gray-900">{{ $booking->activity->provider->name ?? 'N/A' }}</p>
                <p class="text-sm text-gray-600">{{ $booking->activity->provider->email ?? '' }}</p>
            </div>
        </div>
    </div>

    <!-- Notes -->
    @if($booking->notes)
    <div class="bg-yellow-50 rounded-lg p-4">
        <h4 class="font-medium text-gray-900 mb-2">Additional Notes</h4>
        <p class="text-sm text-gray-700">{{ $booking->notes }}</p>
    </div>
    @endif

    <!-- Footer -->
    <div class="text-center text-xs text-gray-500 border-t pt-4">
        <p>Thank you for choosing Infanect!</p>
        <p>This receipt was generated on {{ now()->format('M j, Y \a\t g:i A') }}</p>
    </div>

</div>
