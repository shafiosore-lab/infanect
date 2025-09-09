@extends('layouts.app')

@section('title', $activity->title . ' - Activity Details')

@section('content')
<div class="container mx-auto p-6 space-y-8">

    <!-- Activity Header -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="relative">
            <!-- Activity Image Placeholder -->
            <div class="h-64 bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                <div class="text-center text-white">
                    <svg class="w-16 h-16 mx-auto mb-4 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <h1 class="text-2xl font-bold">{{ $activity->title }}</h1>
                </div>
            </div>

            <!-- Status Badge -->
            <div class="absolute top-4 right-4">
                @if($activity->is_approved)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Approved
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Pending Approval
                    </span>
                @endif
            </div>
        </div>

        <div class="p-6">
            <!-- Activity Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="space-y-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Activity Information</h3>
                        <div class="space-y-2">
                            <div class="flex items-center text-gray-600">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span>{{ $activity->datetime ? $activity->datetime->format('l, F j, Y \a\t g:i A') : 'Date TBD' }}</span>
                            </div>

                            @if($activity->venue)
                            <div class="flex items-center text-gray-600">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span>{{ $activity->venue }}</span>
                            </div>
                            @endif

                            @if($activity->category)
                            <div class="flex items-center text-gray-600">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                                <span>{{ $activity->category }}</span>
                            </div>
                            @endif

                            @if($activity->difficulty_level)
                            <div class="flex items-center text-gray-600">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                <span>{{ ucfirst($activity->difficulty_level) }}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    @if($activity->description)
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Description</h3>
                        <p class="text-gray-600 leading-relaxed">{{ $activity->description }}</p>
                    </div>
                    @endif
                </div>

                <div class="space-y-4">
                    <!-- Provider Information -->
                    @if($activity->provider)
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Provider</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                    <span class="text-lg font-semibold text-blue-600">{{ substr($activity->provider->name ?? 'P', 0, 1) }}</span>
                                </div>
                                <div class="ml-4">
                                    <h4 class="font-medium text-gray-900">{{ $activity->provider->name ?? 'Unknown Provider' }}</h4>
                                    @if($activity->provider->specialization)
                                    <p class="text-sm text-gray-600">{{ $activity->provider->specialization }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Pricing and Capacity -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Details</h3>
                        <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Price per person</span>
                                <span class="font-semibold text-gray-900">
                                    @if($activity->price > 0)
                                        ${{ number_format($activity->price, 2) }}
                                    @else
                                        Free
                                    @endif
                                </span>
                            </div>

                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Available slots</span>
                                <span class="font-semibold text-gray-900">{{ $activity->availableSlots() }} / {{ $activity->slots ?? 'Unlimited' }}</span>
                            </div>

                            @if($activity->target_audience)
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Target audience</span>
                                <span class="font-semibold text-gray-900">{{ $activity->target_audience }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-200">
                @if($hasBooked)
                    <div class="flex-1 bg-green-100 text-green-800 px-4 py-3 rounded-lg text-center">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        You have already booked this activity
                    </div>
                    <a href="{{ route('bookings.show', $booking) }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg text-center hover:bg-blue-700 transition-colors">
                        View Booking
                    </a>
                @else
                    @if($activity->availableSlots() > 0)
                        <a href="{{ route('bookings.create', $activity) }}" class="flex-1 bg-blue-600 text-white px-6 py-3 rounded-lg text-center hover:bg-blue-700 transition-colors">
                            Book Now
                        </a>
                    @else
                        <div class="flex-1 bg-gray-100 text-gray-500 px-6 py-3 rounded-lg text-center cursor-not-allowed">
                            Fully Booked
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>

    <!-- Similar Activities -->
    @if($similarActivities && $similarActivities->count() > 0)
    <div class="bg-white rounded-xl shadow-md p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-6">Similar Activities</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($similarActivities as $similar)
            <div class="bg-gray-50 rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                <div class="h-32 bg-gradient-to-r from-blue-400 to-purple-500 flex items-center justify-center">
                    <svg class="w-12 h-12 text-white opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="p-4">
                    <h3 class="font-medium text-gray-900 mb-2 line-clamp-2">{{ $similar->title }}</h3>
                    <div class="flex items-center text-sm text-gray-600 mb-2">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        {{ $similar->datetime ? $similar->datetime->format('M j, Y') : 'Date TBD' }}
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="font-semibold text-gray-900">
                            @if($similar->price > 0)
                                ${{ number_format($similar->price, 2) }}
                            @else
                                Free
                            @endif
                        </span>
                        <a href="{{ route('activities.show', $similar) }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection
