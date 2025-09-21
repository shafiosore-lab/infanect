<div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100 overflow-hidden">
    <div class="p-6">
        <div class="flex items-start justify-between mb-4">
            <div class="p-3 bg-gradient-to-br from-purple-400 to-purple-600 rounded-xl shadow-lg">
                <i class="fas fa-chart-line text-2xl text-white"></i>
            </div>
            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                <i class="fas fa-arrow-up mr-1"></i>+22%
            </span>
        </div>
        <div class="space-y-2">
            <h3 class="text-gray-600 text-sm font-medium uppercase tracking-wider">Revenue</h3>
            <p class="text-4xl font-bold text-gray-900">KSh {{ number_format($metrics['totalRevenue'] ?? 154200, 0) }}K</p>
            <p class="text-sm text-gray-500">Monthly earnings</p>
        </div>
        <div class="mt-4 bg-gray-100 rounded-full h-2">
            <div class="bg-gradient-to-r from-purple-400 to-purple-600 h-2 rounded-full" style="width: 78%"></div>
        </div>
    </div>
</div>
