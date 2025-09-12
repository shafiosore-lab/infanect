@props(['booking'])

<div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
    <div class="p-4 border-b">
        <h2 class="text-lg font-semibold text-gray-900">{{ $booking->activity->title }}</h2>
        <p class="text-sm text-gray-500 mt-1">{{ Str::limit($booking->activity->description, 80) }}</p>
    </div>
    <div class="p-4 space-y-2">
        <div class="flex justify-between text-sm text-gray-700">
            <span>Date:</span>
            <span>{{ $booking->activity->datetime ? $booking->activity->datetime->format('M j, Y g:i A') : 'TBD' }}</span>
        </div>
        <div class="flex justify-between text-sm text-gray-700">
            <span>Participants:</span>
            <span>{{ $booking->participants ?? 1 }}</span>
        </div>
        <div class="flex justify-between text-sm text-gray-700">
            <span>Status:</span>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                @if($booking->status === 'confirmed') bg-green-100 text-green-800
                @elseif($booking->status === 'pending') bg-yellow-100 text-yellow-800
                @else bg-red-100 text-red-800 @endif">
                {{ ucfirst($booking->status) }}
            </span>
        </div>
        <div class="flex justify-between text-sm text-gray-700">
            <span>Amount:</span>
            <span>KES {{ number_format($booking->amount, 2) }}</span>
        </div>
    </div>
    <div class="p-4 border-t flex justify-between space-x-2">
        <a href="{{ route('bookings.show', $booking) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
            View
        </a>
        <button onclick="downloadReceipt({{ $booking->id }})" class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
            Receipt
        </button>
        @if($booking->status === 'pending')
            <form action="{{ route('bookings.destroy', $booking) }}" method="POST" class="inline-block">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                    Cancel
                </button>
            </form>
        @endif
    </div>
</div>
