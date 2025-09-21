<div class="bg-white rounded-3xl shadow-lg border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-chart-area text-blue-600 mr-3"></i>
                    Family Engagement Trends
                </h3>
                <p class="text-sm text-gray-600 mt-1">Monthly participation patterns and growth metrics</p>
            </div>
            <div class="flex items-center space-x-2">
                <button class="px-4 py-2 text-xs font-semibold bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors shadow-sm">
                    6 Months
                </button>
                <button class="px-4 py-2 text-xs font-semibold text-gray-600 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors">
                    1 Year
                </button>
            </div>
        </div>
    </div>
    <div class="p-8">
        <div class="h-80 w-full">
            <canvas id="engagementTrendsChart" class="w-full h-full"></canvas>
        </div>
    </div>
</div>
