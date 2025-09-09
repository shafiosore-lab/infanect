@extends('layouts.app')

@section('title', $provider->name . ' - Provider Profile')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900">
    <!-- Hero Section with Futuristic Design -->
    <div class="relative overflow-hidden">
        <!-- Animated Background -->
        <div class="absolute inset-0 bg-gradient-to-r from-blue-600/20 via-purple-600/20 to-pink-600/20 animate-pulse"></div>
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%239C92AC" fill-opacity="0.1"%3E%3Ccircle cx="30" cy="30" r="2"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-20"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="text-center">
                <!-- Provider Avatar with Glow Effect -->
                <div class="relative inline-block mb-8">
                    <div class="w-32 h-32 bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 rounded-full p-1 animate-spin-slow">
                        <div class="w-full h-full bg-white rounded-full flex items-center justify-center">
                            <svg class="w-16 h-16 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                            </svg>
                        </div>
                    </div>
                    <!-- Status Indicator -->
                    <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-green-500 border-4 border-slate-900 rounded-full animate-pulse"></div>
                </div>

                <!-- Provider Name and Title -->
                <h1 class="text-4xl md:text-6xl font-bold text-white mb-4 bg-gradient-to-r from-blue-400 via-purple-400 to-pink-400 bg-clip-text text-transparent">
                    {{ $provider->name }}
                </h1>

                <!-- Service Type Badge -->
                <div class="inline-flex items-center px-6 py-3 bg-white/10 backdrop-blur-sm border border-white/20 rounded-full text-white mb-6">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    {{ ucfirst($provider->service_type ?? __('dashboard.service_provider')) }}
                </div>

                <!-- Rating Display -->
                <div class="flex items-center justify-center space-x-4 mb-8">
                    <div class="flex items-center space-x-1">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-6 h-6 {{ $i <= round($avgRating ?? 0) ? 'text-yellow-400' : 'text-gray-400' }}" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                        @endfor
                    </div>
                    <span class="text-white text-lg font-semibold">{{ number_format($avgRating ?? 0, 1) }}</span>
                    <span class="text-white/70">({{ $provider->reviews->count() }} reviews)</span>
                </div>

                <!-- International Reach Indicator -->
                <div class="flex items-center justify-center space-x-6 text-white/80">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"></path>
                        </svg>
                        <span>{{ __('dashboard.global_reach') }}</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2m-9 0h10m-9 0V1m10 3V1m0 3l1 1v16a2 2 0 01-2 2H6a2 2 0 01-2-2V5l1-1z"></path>
                        </svg>
                        <span>{{ $provider->total_reviews ?? 0 }} {{ __('dashboard.happy_clients') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Left Column - Provider Details -->
            <div class="lg:col-span-2 space-y-8">

                <!-- About Section -->
                <div class="bg-white/10 backdrop-blur-lg border border-white/20 rounded-2xl p-8">
                    <h2 class="text-2xl font-bold text-white mb-6 flex items-center">
                        <svg class="w-6 h-6 mr-3 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ __('dashboard.about_provider', ['name' => $provider->name]) }}
                    </h2>

                    @if($provider->bio)
                        <p class="text-white/90 text-lg leading-relaxed mb-6">{{ $provider->bio }}</p>
                    @else
                        <p class="text-white/70 text-lg leading-relaxed mb-6">Professional service provider dedicated to delivering exceptional experiences and building lasting relationships with clients worldwide.</p>
                    @endif

                    <!-- Service Details -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-white/5 rounded-xl p-4">
                            <h3 class="text-white font-semibold mb-2">{{ __('dashboard.service_type') }}</h3>
                            <p class="text-white/80">{{ ucfirst($provider->service_type ?? __('dashboard.general_services')) }}</p>
                        </div>
                        <div class="bg-white/5 rounded-xl p-4">
                            <h3 class="text-white font-semibold mb-2">{{ __('dashboard.experience') }}</h3>
                            <p class="text-white/80">{{ __('dashboard.years_experience', ['count' => $provider->experience_years ?? 5]) }}</p>
                        </div>
                        <div class="bg-white/5 rounded-xl p-4">
                            <h3 class="text-white font-semibold mb-2">{{ __('dashboard.location') }}</h3>
                            <p class="text-white/80">{{ $provider->city ?? __('dashboard.international') }}, {{ $provider->country ?? __('dashboard.international') }}</p>
                        </div>
                        <div class="bg-white/5 rounded-xl p-4">
                            <h3 class="text-white font-semibold mb-2">{{ __('dashboard.languages') }}</h3>
                            <p class="text-white/80">{{ $provider->languages ?? 'English, French, Spanish' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Reviews Section -->
                <div class="bg-white/10 backdrop-blur-lg border border-white/20 rounded-2xl p-8">
                    <h2 class="text-2xl font-bold text-white mb-6 flex items-center">
                        <svg class="w-6 h-6 mr-3 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                        </svg>
                        {{ __('dashboard.client_reviews') }}
                    </h2>

                    @if($recentReviews->count() > 0)
                        <div class="space-y-6">
                            @foreach($recentReviews as $review)
                                <div class="bg-white/5 rounded-xl p-6 border border-white/10">
                                    <div class="flex items-start justify-between mb-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center">
                                                <span class="text-white font-semibold text-sm">
                                                    {{ substr($review->reviewer_name ?? 'Anonymous', 0, 1) }}
                                                </span>
                                            </div>
                                            <div>
                                                <h4 class="text-white font-semibold">{{ $review->reviewer_name ?? 'Anonymous' }}</h4>
                                                <div class="flex items-center space-x-1">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-400' }}" fill="currentColor" viewBox="0 0 24 24">
                                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                                        </svg>
                                                    @endfor
                                                </div>
                                            </div>
                                        </div>
                                        <span class="text-white/60 text-sm">{{ $review->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-white/90 leading-relaxed">{{ $review->comment }}</p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 text-white/30 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <p class="text-white/70 text-lg">{{ __('dashboard.no_reviews_yet') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Column - Contact & Booking -->
            <div class="space-y-8">

                <!-- Contact Card -->
                <div class="bg-white/10 backdrop-blur-lg border border-white/20 rounded-2xl p-8">
                    <h3 class="text-xl font-bold text-white mb-6 flex items-center">
                        <svg class="w-5 h-5 mr-3 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        {{ __('dashboard.contact_information') }}
                    </h3>

                    <div class="space-y-4">
                        @if($provider->email)
                            <div class="flex items-center space-x-3 text-white/90">
                                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                <span>{{ $provider->email }}</span>
                            </div>
                        @endif

                        @if($provider->phone)
                            <div class="flex items-center space-x-3 text-white/90">
                                <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                <span>{{ $provider->phone }}</span>
                            </div>
                        @endif

                        @if($provider->city)
                            <div class="flex items-center space-x-3 text-white/90">
                                <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span>{{ $provider->city }}, {{ $provider->country ?? __('dashboard.international') }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Booking Card -->
                <div class="bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 rounded-2xl p-8 text-white">
                    <h3 class="text-xl font-bold mb-4">{{ __('dashboard.ready_to_book') }}</h3>
                    <p class="text-white/90 mb-6">{{ __('dashboard.book_now_description', ['name' => $provider->name]) }}</p>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-white/80">{{ __('dashboard.starting_price') }}</span>
                            <span class="text-2xl font-bold">${{ number_format($provider->base_price ?? 50, 0) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-white/80">{{ __('dashboard.response_time') }}</span>
                            <span class="font-semibold">{{ __('dashboard.less_than_2_hours') }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-white/80">{{ __('dashboard.languages') }}</span>
                            <span class="font-semibold">{{ count(explode(',', $provider->languages ?? 'English')) }}</span>
                        </div>
                    </div>

                    <a href="{{ route('bookings.create', ['provider' => $provider->id]) }}"
                       class="w-full bg-white text-purple-600 font-bold py-4 px-6 rounded-xl hover:bg-gray-100 transition-colors duration-200 flex items-center justify-center mt-6 group">
                        <svg class="w-5 h-5 mr-2 group-hover:animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        {{ __('dashboard.book_now') }}
                    </a>
                </div>

                <!-- Quick Stats -->
                <div class="bg-white/10 backdrop-blur-lg border border-white/20 rounded-2xl p-6">
                    <h3 class="text-lg font-bold text-white mb-4">{{ __('dashboard.quick_stats') }}</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-white/80">{{ __('dashboard.total_projects') }}</span>
                            <span class="text-white font-semibold">{{ $provider->total_projects ?? $provider->bookings->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-white/80">{{ __('dashboard.success_rate') }}</span>
                            <span class="text-white font-semibold">{{ $provider->success_rate ?? '98%' }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-white/80">{{ __('dashboard.avg_response') }}</span>
                            <span class="text-white font-semibold">{{ $provider->avg_response ?? __('dashboard.less_than_1_hour') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-white/80">{{ __('dashboard.member_since') }}</span>
                            <span class="text-white font-semibold">{{ $provider->created_at->format('M Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes spin-slow {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
.animate-spin-slow {
    animation: spin-slow 8s linear infinite;
}
</style>
@endsection
