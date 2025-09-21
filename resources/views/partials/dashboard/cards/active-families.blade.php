<div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100 overflow-hidden">
    <div class="p-6">
        <div class="flex items-start justify-between mb-4">
            <div class="p-3 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl shadow-lg">
                <i class="fas fa-home text-2xl text-white"></i>
            </div>
            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                <i class="fas fa-arrow-up mr-1"></i>+15%
            </span>
        </div>
        <div class="space-y-2">
            <h3 class="text-gray-600 text-sm font-medium uppercase tracking-wider">Active Families</h3>
            <p class="text-4xl font-bold text-gray-900">{{ number_format($metrics['activeFamilies'] ?? 89) }}</p>
            <p class="text-sm text-gray-500">Currently engaged families</p>
        </div>
        <div class="mt-4 bg-gray-100 rounded-full h-2">
            <div class="bg-gradient-to-r from-blue-400 to-blue-600 h-2 rounded-full" style="width: 85%"></div>
        </div>
    </div>
</div>
