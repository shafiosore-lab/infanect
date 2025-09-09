<!-- Top Providers Section -->
<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="px-6 py-4 bg-gradient-to-r from-purple-50 to-pink-50 border-b">
        <div class="flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800">Top Providers</h3>
            <a href="{{ route('providers.index') }}" class="text-sm text-purple-600 hover:text-purple-800 font-medium">View All</a>
        </div>
    </div>
    <div class="p-6">
        @forelse($topProviders ?? collect() as $provider)
            <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-b-0">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-900">{{ $provider->name }}</h4>
                        <p class="text-xs text-gray-500">{{ $provider->specialization ?? 'Service Provider' }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="flex items-center text-xs text-gray-500 mb-1">
                        <svg class="w-3 h-3 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                        </svg>
                        {{ number_format($provider->rating ?? 0, 1) }}
                    </div>
                    <a href="{{ route('providers.show', $provider->id) }}"
                       class="text-xs text-purple-600 hover:text-purple-800 font-medium">
                        View Profile
                    </a>
                </div>
            </div>
        @empty
            <div class="text-center py-8">
                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <p class="text-gray-500 text-sm">No providers available at the moment.</p>
            </div>
        @endforelse
    </div>
</div>
