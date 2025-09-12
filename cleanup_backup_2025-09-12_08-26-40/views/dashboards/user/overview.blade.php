<!-- User Metrics Dashboard -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6" id="user-metrics">
    <div class="bg-white rounded-xl p-6 shadow-md">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">My Bookings</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['my_bookings'] ?? 0 }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl p-6 shadow-md">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Upcoming</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['upcoming_bookings'] ?? 0 }}</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl p-6 shadow-md">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Completed</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['completed_activities'] ?? 0 }}</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl p-6 shadow-md">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Total Spent</p>
                <p class="text-2xl font-bold text-gray-900">${{ number_format($stats['total_spent'] ?? 0, 2) }}</p>
            </div>
            <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Wellness Score Section -->
<div class="bg-white rounded-xl p-6 shadow-md" id="wellness-section">
    <h2 class="text-xl font-semibold text-gray-800 mb-6">Wellness Score</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6" id="wellness-scores">
        <!-- Mental Health -->
        <div class="text-center">
            <div class="relative w-20 h-20 mx-auto mb-2">
                <svg class="w-20 h-20 transform -rotate-90" viewBox="0 0 36 36">
                    <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                          fill="none" stroke="#E5E7EB" stroke-width="2"/>
                    <path id="mental-health-path" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                          fill="none" stroke="#3B82F6" stroke-width="2" stroke-dasharray="0, 100"/>
                </svg>
                <div class="absolute inset-0 flex items-center justify-center">
                    <span id="mental-health-score" class="text-lg font-semibold text-gray-900">0</span>
                </div>
            </div>
            <p class="text-sm text-gray-600">Mental Health</p>
        </div>

        <!-- Physical Health -->
        <div class="text-center">
            <div class="relative w-20 h-20 mx-auto mb-2">
                <svg class="w-20 h-20 transform -rotate-90" viewBox="0 0 36 36">
                    <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                          fill="none" stroke="#E5E7EB" stroke-width="2"/>
                    <path id="physical-health-path" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                          fill="none" stroke="#10B981" stroke-width="2" stroke-dasharray="0, 100"/>
                </svg>
                <div class="absolute inset-0 flex items-center justify-center">
                    <span id="physical-health-score" class="text-lg font-semibold text-gray-900">0</span>
                </div>
            </div>
            <p class="text-sm text-gray-600">Physical Health</p>
        </div>

        <!-- Social Wellness -->
        <div class="text-center">
            <div class="relative w-20 h-20 mx-auto mb-2">
                <svg class="w-20 h-20 transform -rotate-90" viewBox="0 0 36 36">
                    <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                          fill="none" stroke="#E5E7EB" stroke-width="2"/>
                    <path id="social-wellness-path" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                          fill="none" stroke="#F59E0B" stroke-width="2" stroke-dasharray="0, 100"/>
                </svg>
                <div class="absolute inset-0 flex items-center justify-center">
                    <span id="social-wellness-score" class="text-lg font-semibold text-gray-900">0</span>
                </div>
            </div>
            <p class="text-sm text-gray-600">Social Wellness</p>
        </div>

        <!-- Learning Growth -->
        <div class="text-center">
            <div class="relative w-20 h-20 mx-auto mb-2">
                <svg class="w-20 h-20 transform -rotate-90" viewBox="0 0 36 36">
                    <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                          fill="none" stroke="#E5E7EB" stroke-width="2"/>
                    <path id="learning-growth-path" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                          fill="none" stroke="#8B5CF6" stroke-width="2" stroke-dasharray="0, 100"/>
                </svg>
                <div class="absolute inset-0 flex items-center justify-center">
                    <span id="learning-growth-score" class="text-lg font-semibold text-gray-900">0</span>
                </div>
            </div>
            <p class="text-sm text-gray-600">Learning Growth</p>
        </div>
    </div>
</div>

<!-- Recent Engagements -->
@if(isset($engagements) && $engagements->count() > 0)
<div class="bg-white rounded-xl p-6 shadow-md">
    <h2 class="text-xl font-semibold text-gray-800 mb-6">Recent Engagements</h2>
    <div class="space-y-4">
        @foreach($engagements->take(5) as $engagement)
        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
            <div>
                <h3 class="font-medium text-gray-900">{{ $engagement['title'] }}</h3>
                <p class="text-sm text-gray-600">{{ $engagement['description'] }}</p>
            </div>
            <span class="text-sm text-gray-500">{{ $engagement['date'] }}</span>
        </div>
        @endforeach
    </div>
</div>
@endif
