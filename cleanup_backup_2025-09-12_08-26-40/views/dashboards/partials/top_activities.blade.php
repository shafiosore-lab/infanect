<!-- Top Activities Section -->
<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b">
        <div class="flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800">Recent Activities</h3>
            <a href="{{ route('activities.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">View All</a>
        </div>
    </div>
    <div class="p-6">
        @forelse($topBondingActivities ?? collect() as $activity)
            <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-b-0">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-900">{{ $activity->title }}</h4>
                        <p class="text-xs text-gray-500">{{ $activity->provider->name ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-sm font-semibold text-gray-900">${{ number_format($activity->price, 2) }}</div>
                    <div class="text-xs text-gray-500">{{ $activity->datetime ? $activity->datetime->format('M j') : 'TBD' }}</div>
                    <a href="{{ route('bookings.create', ['activity' => $activity->id]) }}"
                       class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                        Book Now
                    </a>
                </div>
            </div>
        @empty
            <div class="text-center py-8">
                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                <p class="text-gray-500 text-sm">No activities available at the moment.</p>
            </div>
        @endforelse
    </div>
</div>
