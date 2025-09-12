<!-- Recent Bookings -->
<div class="p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-900">Recent Bookings</h3>
        <a href="{{ route('bookings.index') }}" class="text-sm text-blue-600 hover:text-blue-800">View all</a>
    </div>

    <div class="space-y-4">
        @php
            $recentBookings = auth()->user()->bookings()
                ->with(['service', 'provider'])
                ->orderBy('created_at', 'desc')
                ->take(3)
                ->get();
        @endphp

        @forelse($recentBookings as $booking)
            <div class="flex items-center space-x-4 p-3 bg-gray-50 rounded-lg">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-medium text-gray-900">{{ $booking->service->name ?? 'Service' }}</div>
                    <div class="text-sm text-gray-500">{{ $booking->provider->name ?? 'Provider' }}</div>
                    <div class="text-xs text-gray-400">{{ $booking->created_at->diffForHumans() }}</div>
                </div>
                <div class="flex-shrink-0">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        @if($booking->status === 'completed') bg-green-100 text-green-800
                        @elseif($booking->status === 'confirmed') bg-blue-100 text-blue-800
                        @elseif($booking->status === 'pending') bg-yellow-100 text-yellow-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        {{ ucfirst($booking->status) }}
                    </span>
                </div>
            </div>
        @empty
            <div class="text-center py-8">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No bookings yet</h3>
                <p class="mt-1 text-sm text-gray-500">Your recent bookings will appear here.</p>
                <div class="mt-6">
                    <a href="{{ route('activities.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        Book a Service
                    </a>
                </div>
            </div>
        @endforelse
    </div>
</div>
