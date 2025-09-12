@extends('layouts.app')

@section('title', 'Provider Profile - ' . $provider->name)

@section('content')
<div class="min-h-screen bg-gradient-to-b from-slate-50 to-white py-6">
    <!-- Header -->
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('providers.index') }}" class="inline-flex items-center px-3 py-2 text-blue-600 hover:text-blue-800 transition-colors text-sm">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Providers
            </a>
        </div>

        <!-- Dashboard Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Left Column: Provider Info -->
            <div class="lg:col-span-1 space-y-6">

                <!-- Provider Card -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="text-center">
                        <!-- Photo -->
                        <div class="w-20 h-20 relative mx-auto mb-4 overflow-hidden rounded-full">
                            <img
                                src="https://images.unsplash.com/photo-{{
                                    collect([
                                        '1559839734-2b71ea197ec2?ixlib=rb-4.0.3&auto=format&fit=crop&w=80&h=80&q=85',
                                        '1594824388511-923247e6e01e?ixlib=rb-4.0.3&auto=format&fit=crop&w=80&h=80&q=85',
                                        '1612349317150-e3d4ac1d0e35?ixlib=rb-4.0.3&auto=format&fit=crop&w=80&h=80&q=85',
                                        '1582750433449-648ed127bb54?ixlib=rb-4.0.3&auto=format&fit=crop&w=80&h=80&q=85'
                                    ])[($provider->id - 1) % 4]
                                }}"
                                alt="{{ $provider->name }}"
                                class="w-full h-full object-cover"
                            >
                            <!-- Rating Badge -->
                            <div class="absolute -top-1 -right-1">
                                <div class="bg-yellow-500 text-white text-xs font-bold px-2 py-1 rounded-full flex items-center">
                                    <i class="fas fa-star mr-1"></i>
                                    {{ number_format($provider->rating, 1) }}
                                </div>
                            </div>
                        </div>

                        <!-- Name & Title -->
                        <h1 class="text-xl font-bold text-gray-900 mb-1">{{ $provider->name }}</h1>
                        <p class="text-blue-600 font-medium text-sm mb-4">{{ $provider->title }}</p>

                        <!-- Price -->
                        <div class="bg-blue-50 rounded-lg p-3 mb-4">
                            <div class="text-2xl font-bold text-blue-600">${{ $provider->price }}</div>
                            <div class="text-xs text-gray-600">per session</div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-2">
                            <a href="{{ route('providers.book', $provider->id) }}" class="w-full px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all duration-300 font-medium text-sm">
                                <i class="fas fa-calendar mr-2"></i>Book Now
                            </a>
                            <button class="w-full px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium text-sm">
                                <i class="fas fa-heart mr-2"></i>Save
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Quick Info Card -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="font-semibold text-gray-900 mb-4 text-sm">Quick Info</h3>
                    <div class="space-y-3 text-xs">
                        <div class="flex items-center">
                            <i class="fas fa-map-marker-alt text-blue-500 w-4 mr-3"></i>
                            <span class="text-gray-700">{{ $provider->location }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-language text-green-500 w-4 mr-3"></i>
                            <span class="text-gray-700">{{ implode(', ', $provider->languages) }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-clock text-purple-500 w-4 mr-3"></i>
                            <span class="text-green-600">{{ $provider->availability }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-reply text-orange-500 w-4 mr-3"></i>
                            <span class="text-gray-700">Within 2 hours</span>
                        </div>
                    </div>
                </div>

                <!-- Trust Badges -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="font-semibold text-gray-900 mb-4 text-sm">Trust & Safety</h3>
                    <div class="space-y-2">
                        <div class="flex items-center text-xs text-gray-600">
                            <i class="fas fa-shield-check text-green-500 mr-2"></i>
                            <span>Verified & Licensed</span>
                        </div>
                        <div class="flex items-center text-xs text-gray-600">
                            <i class="fas fa-certificate text-blue-500 mr-2"></i>
                            <span>Board Certified</span>
                        </div>
                        <div class="flex items-center text-xs text-gray-600">
                            <i class="fas fa-lock text-purple-500 mr-2"></i>
                            <span>Secure Platform</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Details -->
            <div class="lg:col-span-2 space-y-6">

                <!-- About Card -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-3">About</h2>
                    <p class="text-gray-700 text-sm leading-relaxed">{{ $provider->bio }}</p>
                </div>

                <!-- Specializations Card -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-3">Specializations</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($provider->services as $service)
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">{{ $service }}</span>
                        @endforeach
                    </div>
                </div>

                <!-- Statistics Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

                    <!-- Experience Card -->
                    <div class="bg-white rounded-xl shadow-md p-4 text-center">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-graduation-cap text-blue-600"></i>
                        </div>
                        <div class="text-2xl font-bold text-gray-900">8+</div>
                        <div class="text-xs text-gray-600">Years Experience</div>
                    </div>

                    <!-- Clients Card -->
                    <div class="bg-white rounded-xl shadow-md p-4 text-center">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-users text-green-600"></i>
                        </div>
                        <div class="text-2xl font-bold text-gray-900">500+</div>
                        <div class="text-xs text-gray-600">Clients Helped</div>
                    </div>

                    <!-- Sessions Card -->
                    <div class="bg-white rounded-xl shadow-md p-4 text-center">
                        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-calendar-check text-purple-600"></i>
                        </div>
                        <div class="text-2xl font-bold text-gray-900">1.2K+</div>
                        <div class="text-xs text-gray-600">Sessions Completed</div>
                    </div>
                </div>

                <!-- Availability Card -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-3">Availability</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        @foreach(['Mon', 'Tue', 'Wed', 'Thu'] as $day)
                            <div class="text-center p-3 bg-green-50 rounded-lg border border-green-200">
                                <div class="text-xs font-medium text-gray-700">{{ $day }}</div>
                                <div class="text-xs text-green-600 mt-1">9AM-5PM</div>
                            </div>
                        @endforeach
                    </div>
                    <p class="text-xs text-gray-600 mt-3">
                        <i class="fas fa-info-circle mr-1"></i>
                        Weekend appointments available by request
                    </p>
                </div>

                <!-- Reviews Preview -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-3">Recent Reviews</h3>
                    <div class="space-y-3">
                        @for($i = 0; $i < 2; $i++)
                            <div class="border-l-4 border-blue-500 pl-4 py-2">
                                <div class="flex items-center mb-2">
                                    <div class="flex text-yellow-400 text-xs mr-2">
                                        @for($s = 0; $s < 5; $s++)
                                            <i class="fas fa-star"></i>
                                        @endfor
                                    </div>
                                    <span class="text-xs text-gray-600">{{ $i == 0 ? '2 days ago' : '1 week ago' }}</span>
                                </div>
                                <p class="text-sm text-gray-700">
                                    {{ $i == 0
                                        ? 'Amazing session! Really helped our family communicate better.'
                                        : 'Professional and caring approach. Highly recommended for families.' }}
                                </p>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
