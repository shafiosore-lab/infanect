<!-- Additional Stats Cards -->
<div class="bg-white rounded-xl p-6 shadow-md">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-900">Upcoming Bookings</h3>
        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
        </svg>
    </div>
    <div class="text-3xl font-bold text-indigo-600">{{ $stats['upcoming_bookings'] ?? 0 }}</div>
    <div class="text-sm text-gray-500 mt-1">Next 7 days</div>
</div>

<div class="bg-white rounded-xl p-6 shadow-md">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-900">Total Spent</h3>
        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
        </svg>
    </div>
    <div class="text-3xl font-bold text-emerald-600">${{ number_format($stats['total_spent'] ?? 0, 2) }}</div>
    <div class="text-sm text-gray-500 mt-1">All time</div>
</div>

<div class="bg-white rounded-xl p-6 shadow-md">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-900">Reviews Given</h3>
        <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
        </svg>
    </div>
    <div class="text-3xl font-bold text-amber-600">{{ $stats['reviews_given'] ?? 0 }}</div>
    <div class="text-sm text-gray-500 mt-1">Total reviews</div>
</div>
