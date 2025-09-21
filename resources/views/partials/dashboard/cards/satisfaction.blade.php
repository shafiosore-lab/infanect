<div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100 overflow-hidden">
    <div class="p-6">
        <div class="flex items-start justify-between mb-4">
            <div class="p-3 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-xl shadow-lg">
                <i class="fas fa-smile text-2xl text-white"></i>
            </div>
            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                Excellent
            </span>
        </div>
        <div class="space-y-2">
            <h3 class="text-gray-600 text-sm font-medium uppercase tracking-wider">Satisfaction</h3>
            <p class="text-4xl font-bold text-gray-900">{{ $metrics['satisfactionRate'] ?? '96%' }}</p>
            <p class="text-sm text-gray-500">Family feedback score</p>
        </div>
        <div class="mt-4 bg-gray-100 rounded-full h-2">
            <div class="bg-gradient-to-r from-yellow-400 to-orange-500 h-2 rounded-full" style="width: 96%"></div>
        </div>
    </div>
</div>
