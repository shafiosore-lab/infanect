<div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100 overflow-hidden">
    <div class="p-6">
        <div class="flex items-start justify-between mb-4">
            <div class="p-3 bg-gradient-to-br from-green-400 to-green-600 rounded-xl shadow-lg">
                <i class="fas fa-calendar-alt text-2xl text-white"></i>
            </div>
            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                24h scheduled
            </span>
        </div>
        <div class="space-y-2">
            <h3 class="text-gray-600 text-sm font-medium uppercase tracking-wider">Weekly Activities</h3>
            <p class="text-4xl font-bold text-gray-900">{{ $metrics['weeklyActivities'] ?? 32 }}</p>
            <p class="text-sm text-gray-500">This week's sessions</p>
        </div>
        <div class="mt-4 bg-gray-100 rounded-full h-2">
            <div class="bg-gradient-to-r from-green-400 to-green-600 h-2 rounded-full" style="width: 80%"></div>
        </div>
    </div>
</div>
