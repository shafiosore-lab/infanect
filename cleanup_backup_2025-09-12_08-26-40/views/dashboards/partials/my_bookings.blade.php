<!-- My Bookings Section -->
<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="px-6 py-4 bg-gradient-to-r from-red-50 to-pink-50 border-b">
        <div class="flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800">My Bookings</h3>
            <a href="{{ route('bookings.index') }}" class="text-sm text-red-600 hover:text-red-800 font-medium">View All</a>
        </div>
    </div>
    <div class="p-6">
        @forelse($bookings ?? collect() as $booking)
            <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-b-0">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-900">
                            {{ $booking->service->name ?? $booking->activity->title ?? 'Booking' }}
                        </h4>
                        <p class="text-xs text-gray-500">
                            {{ $booking->service->user->name ?? $booking->activity->provider->name ?? 'Provider' }}
                        </p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-sm font-semibold text-gray-900">${{ number_format($booking->amount_paid ?? 0, 2) }}</div>
                    <div class="text-xs text-gray-500 mb-1">{{ $booking->created_at->format('M j') }}</div>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                        {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-800' :
                           ($booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                           ($booking->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                        {{ ucfirst($booking->status ?? 'pending') }}
                    </span>
                </div>
            </div>
        @empty
            <div class="text-center py-8">
                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <p class="text-gray-500 text-sm">No bookings found.</p>
                <a href="{{ route('services.index') }}" class="text-sm text-red-600 hover:text-red-800 font-medium mt-2 inline-block">
                    Browse Services
                </a>
            </div>
        @endforelse
    </div>
</div>
