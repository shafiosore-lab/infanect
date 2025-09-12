<!-- Top Services Section -->
<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="px-6 py-4 bg-gradient-to-r from-green-50 to-emerald-50 border-b">
        <div class="flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800">Top Services</h3>
            <a href="{{ route('services.index') }}" class="text-sm text-green-600 hover:text-green-800 font-medium">View All</a>
        </div>
    </div>
    <div class="p-6">
        @forelse($topServices ?? collect() as $service)
            <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-b-0">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-900">{{ $service->name }}</h4>
                        <p class="text-xs text-gray-500">{{ $service->user->name ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-sm font-semibold text-gray-900">${{ number_format($service->price, 2) }}</div>
                    <a href="{{ route('bookings.create.service', $service->id) }}"
                       class="text-xs text-green-600 hover:text-green-800 font-medium">
                        Book Now
                    </a>
                </div>
            </div>
        @empty
            <div class="text-center py-8">
                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-gray-500 text-sm">No services available at the moment.</p>
            </div>
        @endforelse
    </div>
</div>
