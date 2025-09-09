<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="px-6 py-4 bg-gradient-to-r from-green-50 to-emerald-50 border-b">
        <h2 class="text-lg font-semibold text-gray-800">Available Activities</h2>
        <p class="text-sm text-gray-600 mt-1">Choose an activity to book</p>
    </div>
    <div class="p-6">
        @if($availableActivities->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($availableActivities as $activity)
                    <div class="bg-gray-50 rounded-lg shadow p-4 hover:bg-gray-100 transition">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1">
                                <h3 class="text-lg font-medium text-gray-900">{{ $activity->title }}</h3>
                                <p class="text-sm text-gray-600 mt-1 line-clamp-2">{{ Str::limit($activity->description, 80) }}</p>
                            </div>
                            <div class="text-right ml-4">
                                <div class="text-lg font-bold text-gray-900">KES {{ number_format($activity->price, 2) }}</div>
                            </div>
                        </div>

                        <div class="space-y-2 mb-4 text-sm text-gray-500">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                {{ $activity->datetime ? $activity->datetime->format('M j, Y g:i A') : 'TBD' }}
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                {{ $activity->venue ?? 'TBD' }}
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                {{ $activity->slots ?? 0 }} slot{{ $activity->slots == 1 ? '' : 's' }} available
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ ucfirst($activity->category) }}
                            </span>
                            <a href="{{ route('bookings.create', ['activity' => $activity->id]) }}"
                               class="inline-flex items-center px-3 py-1.5 text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                Book Now
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $availableActivities->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No activities available</h3>
                <p class="mt-1 text-sm text-gray-500">There are currently no bookable activities available.</p>
                <div class="mt-6">
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        Back to Dashboard
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
