<!-- Bookings Section -->
<section class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-xl font-semibold text-gray-800">My Bookings</h2>
        <p class="text-sm text-gray-600 mt-1">Manage your upcoming and past bookings</p>
    </div>

    <div class="p-6">
        <!-- Booking Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-blue-50 p-4 rounded-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-600">Total Bookings</p>
                        <p class="text-2xl font-bold text-blue-900">{{ $stats['my_bookings'] ?? 0 }}</p>
                    </div>
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>

            <div class="bg-green-50 p-4 rounded-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-green-600">Upcoming</p>
                        <p class="text-2xl font-bold text-green-900">{{ $stats['upcoming_bookings'] ?? 0 }}</p>
                    </div>
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>

            <div class="bg-purple-50 p-4 rounded-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-purple-600">Completed</p>
                        <p class="text-2xl font-bold text-purple-900">{{ $stats['completed_activities'] ?? 0 }}</p>
                    </div>
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>

            <div class="bg-yellow-50 p-4 rounded-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-yellow-600">Total Spent</p>
                        <p class="text-2xl font-bold text-yellow-900">${{ number_format($stats['total_spent'] ?? 0, 2) }}</p>
                    </div>
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Recent Bookings -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Bookings</h3>
            <div class="space-y-4">
                @if(isset($recentBookings) && $recentBookings->count() > 0)
                    @foreach($recentBookings as $booking)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h4 class="font-medium text-gray-900">
                                    {{ $booking->activity ? $booking->activity->title : ($booking->service ? $booking->service->name : 'Unknown') }}
                                </h4>
                                <p class="text-sm text-gray-600">
                                    {{ $booking->provider ? $booking->provider->name : 'N/A' }} •
                                    {{ $booking->scheduled_at ? $booking->scheduled_at->format('M d, Y H:i') : 'TBD' }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                @if($booking->status === 'confirmed') bg-green-100 text-green-800
                                @elseif($booking->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($booking->status === 'completed') bg-blue-100 text-blue-800
                                @elseif($booking->status === 'cancelled') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($booking->status) }}
                            </span>
                            @if($booking->status === 'confirmed' || $booking->status === 'pending')
                                <a href="{{ route('bookings.show', $booking->id) }}" class="text-blue-600 hover:text-blue-900 text-sm">View</a>
                            @endif
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-8 text-gray-500">
                        <svg class="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <p>No bookings found.</p>
                        <a href="{{ route('activities.index') }}" class="text-blue-600 hover:text-blue-900 mt-2 inline-block">Browse Activities</a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Upcoming Activities -->
        @if(isset($upcomingActivities) && $upcomingActivities->count() > 0)
        <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Upcoming Activities</h3>
            <div class="space-y-4">
                @foreach($upcomingActivities as $booking)
                <div class="flex items-center justify-between p-4 bg-green-50 rounded-lg border-l-4 border-green-400">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h4 class="font-medium text-gray-900">
                                {{ $booking->activity ? $booking->activity->title : 'Unknown Activity' }}
                            </h4>
                            <p class="text-sm text-gray-600">
                                {{ $booking->provider ? $booking->provider->name : 'N/A' }} •
                                {{ $booking->scheduled_at ? $booking->scheduled_at->format('M d, Y H:i') : 'TBD' }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-green-600 font-medium">
                            {{ $booking->scheduled_at ? $booking->scheduled_at->diffForHumans() : 'TBD' }}
                        </span>
                        <a href="{{ route('bookings.show', $booking->id) }}" class="text-green-600 hover:text-green-900 text-sm">View Details</a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>
