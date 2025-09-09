<!-- Quick Actions -->
<div class="p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>

    <div class="space-y-3">
        <!-- Book a Service -->
        <a href="{{ route('activities.index') }}" class="flex items-center space-x-3 w-full p-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
            </div>
            <div>
                <div class="text-sm font-medium text-gray-900">Book a Service</div>
                <div class="text-xs text-gray-500">Find childcare services</div>
            </div>
        </a>

        <!-- Start Learning -->
        <a href="{{ route('parenting-modules.index') }}" class="flex items-center space-x-3 w-full p-3 bg-green-50 hover:bg-green-100 rounded-lg transition-colors">
            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
            <div>
                <div class="text-sm font-medium text-gray-900">Start Learning</div>
                <div class="text-xs text-gray-500">Access parenting modules</div>
            </div>
        </a>

        <!-- AI Chat -->
        <a href="{{ route('ai-chat.index') }}" class="flex items-center space-x-3 w-full p-3 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors">
            <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
            </div>
            <div>
                <div class="text-sm font-medium text-gray-900">AI Assistant</div>
                <div class="text-xs text-gray-500">Get parenting advice</div>
            </div>
        </a>

        <!-- View Bookings -->
        <a href="{{ route('bookings.index') }}" class="flex items-center space-x-3 w-full p-3 bg-orange-50 hover:bg-orange-100 rounded-lg transition-colors">
            <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center">
                <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </div>
            <div>
                <div class="text-sm font-medium text-gray-900">My Bookings</div>
                <div class="text-xs text-gray-500">Manage your appointments</div>
            </div>
        </a>

        <!-- Audio Content -->
        <a href="#audio" class="flex items-center space-x-3 w-full p-3 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition-colors">
            <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path>
                </svg>
            </div>
            <div>
                <div class="text-sm font-medium text-gray-900">Audio Content</div>
                <div class="text-xs text-gray-500">Listen to parenting tips</div>
            </div>
        </a>
    </div>
</div>
