<!-- User Metrics Cards -->
<div class="bg-white rounded-xl p-6 shadow-md">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-900">Total Sessions</h3>
        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
        </svg>
    </div>
    <div class="text-3xl font-bold text-blue-600">{{ $stats['total_sessions'] ?? 0 }}</div>
    <div class="text-sm text-gray-500 mt-1">All time sessions</div>
</div>

<div class="bg-white rounded-xl p-6 shadow-md">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-900">Completed</h3>
        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
    </div>
    <div class="text-3xl font-bold text-green-600">{{ $stats['completed_sessions'] ?? 0 }}</div>
    <div class="text-sm text-gray-500 mt-1">Completed sessions</div>
</div>

<div class="bg-white rounded-xl p-6 shadow-md">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-900">Active Modules</h3>
        <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
        </svg>
    </div>
    <div class="text-3xl font-bold text-purple-600">{{ $stats['active_modules'] ?? 0 }}</div>
    <div class="text-sm text-gray-500 mt-1">In progress</div>
</div>

<div class="bg-white rounded-xl p-6 shadow-md">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-900">This Month</h3>
        <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
        </svg>
    </div>
    <div class="text-3xl font-bold text-orange-600">{{ $stats['this_month_sessions'] ?? 0 }}</div>
    <div class="text-sm text-gray-500 mt-1">Sessions this month</div>
</div>
