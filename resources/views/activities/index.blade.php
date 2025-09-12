{{-- resources/views/activities/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Browse Activities - Infanect')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 via-blue-50 to-purple-50">
    <!-- Hero Header -->
    <section class="relative bg-gradient-to-r from-green-600 via-teal-600 to-blue-700 text-white py-12 overflow-hidden">
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent"></div>

        <!-- Floating Elements -->
        <div class="absolute top-8 left-8 w-16 h-16 bg-white/10 rounded-full blur-lg animate-pulse"></div>
        <div class="absolute bottom-8 right-8 w-20 h-20 bg-green-200/10 rounded-full blur-xl animate-bounce"></div>

        <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="flex flex-col items-center">
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mb-4 backdrop-blur-sm border border-white/30">
                    <i class="fas fa-puzzle-piece text-2xl text-white"></i>
                </div>
                <h1 class="text-3xl md:text-4xl font-bold mb-4 bg-gradient-to-r from-white to-green-100 bg-clip-text text-transparent">
                    Family Activities
                </h1>
                <p class="text-lg text-green-100 max-w-3xl mb-8">
                    Discover amazing activities and experiences designed to bring your family closer together
                </p>

                <!-- Quick Stats -->
                <div class="flex flex-wrap gap-6 justify-center">
                    <div class="bg-white/20 backdrop-blur-sm rounded-full px-6 py-3 border border-white/30">
                        <span class="font-bold text-lg">{{ $activities->count() }}+</span>
                        <span class="text-green-100 ml-1">Activities</span>
                    </div>
                    <div class="bg-white/20 backdrop-blur-sm rounded-full px-6 py-3 border border-white/30">
                        <span class="font-bold text-lg">{{ !empty($locations) && is_countable($locations) ? count($locations) : 8 }}+</span>
                        <span class="text-green-100 ml-1">Locations</span>
                    </div>
                    <div class="bg-white/20 backdrop-blur-sm rounded-full px-6 py-3 border border-white/30">
                        <span class="font-bold text-lg">4.7</span>
                        <span class="text-green-100 ml-1">Avg Rating</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Search & Filters -->
    <section class="py-8 bg-white border-b border-gray-200">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <form method="GET" action="{{ route('activities.index') }}" class="space-y-4">

                <!-- Search Bar -->
                <div class="relative max-w-2xl mx-auto">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Search activities, locations, or keywords..."
                           class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-green-500 focus:border-green-500 text-base">
                </div>

                <!-- Filter Pills -->
                <div class="flex flex-wrap gap-4 justify-center">
                    <!-- Category Filter -->
                    <div class="relative">
                        <select name="category" class="appearance-none bg-white border border-gray-200 rounded-full px-6 py-3 pr-10 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <option value="">All Categories</option>
                            @if(isset($categories) && is_array($categories))
                                @foreach($categories as $key => $label)
                                    <option value="{{ $key }}" {{ request('category') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            @endif
                        </select>
                        <i class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                    </div>

                    <!-- Age Group Filter -->
                    <div class="relative">
                        <select name="age_group" class="appearance-none bg-white border border-gray-200 rounded-full px-6 py-3 pr-10 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <option value="">All Ages</option>
                            @if(isset($ageGroups) && is_array($ageGroups))
                                @foreach($ageGroups as $key => $label)
                                    <option value="{{ $key }}" {{ request('age_group') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            @endif
                        </select>
                        <i class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                    </div>

                    <!-- Location Filter -->
                    <div class="relative">
                        <select name="location" class="appearance-none bg-white border border-gray-200 rounded-full px-6 py-3 pr-10 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <option value="">All Locations</option>
                            @if(isset($locations) && is_array($locations))
                                @foreach($locations as $location)
                                    <option value="{{ $location }}" {{ request('location') == $location ? 'selected' : '' }}>{{ $location }}</option>
                                @endforeach
                            @endif
                        </select>
                        <i class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                    </div>

                    <!-- Search Button -->
                    <button type="submit" class="px-8 py-3 bg-gradient-to-r from-green-600 to-teal-600 text-white rounded-full hover:from-green-700 hover:to-teal-700 transition-all duration-300 font-medium shadow-lg hover:shadow-xl transform hover:scale-105">
                        <i class="fas fa-search mr-2"></i>
                        Search
                    </button>
                </div>
            </form>
        </div>
    </section>

    <!-- Activities Grid -->
    <section class="py-12">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            @if($activities->count() > 0)
                <!-- Results Header -->
                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-2xl font-bold text-gray-900">
                        {{ $activities->count() }} Activities Found
                        @if(request()->hasAny(['search', 'category', 'age_group', 'location']))
                            <span class="text-base font-normal text-gray-600">for your search</span>
                        @endif
                    </h2>

                    @if(request()->hasAny(['search', 'category', 'age_group', 'location']))
                        <a href="{{ route('activities.index') }}" class="text-green-600 hover:text-green-700 font-medium">
                            <i class="fas fa-times mr-1"></i>
                            Clear Filters
                        </a>
                    @endif
                </div>

                <!-- Activities Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-2">
                    @foreach($activities as $activity)
                        <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-all duration-300 border border-gray-100 overflow-hidden group h-fit">
                            <!-- Activity Color Design -->
                            <div class="relative h-6 overflow-hidden
                                @switch($activity->category ?? 'general')
                                    @case('outdoor')
                                        bg-gradient-to-br from-green-400 to-emerald-600
                                        @break
                                    @case('creative')
                                        bg-gradient-to-br from-purple-400 to-pink-600
                                        @break
                                    @case('sports')
                                        bg-gradient-to-br from-blue-400 to-cyan-600
                                        @break
                                    @case('educational')
                                        bg-gradient-to-br from-orange-400 to-red-600
                                        @break
                                    @default
                                        bg-gradient-to-br from-gray-400 to-slate-600
                                @endswitch
                            ">
                                <!-- Category Icon -->
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="text-white/80 text-sm">
                                        @switch($activity->category ?? 'general')
                                            @case('outdoor')
                                                <i class="fas fa-tree"></i>
                                                @break
                                            @case('creative')
                                                <i class="fas fa-palette"></i>
                                                @break
                                            @case('sports')
                                                <i class="fas fa-futbol"></i>
                                                @break
                                            @case('educational')
                                                <i class="fas fa-graduation-cap"></i>
                                                @break
                                            @default
                                                <i class="fas fa-puzzle-piece"></i>
                                        @endswitch
                                    </div>
                                </div>

                                <!-- Price Badge (moved to content area) -->
                                <div class="absolute top-0.5 right-1">
                                    <span class="px-1 py-0.5 bg-white/95 backdrop-blur-sm text-gray-900 rounded text-xs font-bold shadow-sm">
                                        ${{ $activity->price ?? '0' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Activity Content -->
                            <div class="p-2">
                                <!-- Title, Category & Location -->
                                <div class="mb-1">
                                    <div class="flex items-center justify-between mb-0.5">
                                        <h3 class="font-semibold text-gray-900 text-xs leading-tight line-clamp-1 group-hover:text-green-600 transition-colors flex-1 mr-2">
                                            {{ $activity->title ?? 'Untitled Activity' }}
                                        </h3>
                                        <span class="px-1.5 py-0.5 bg-gray-100 text-gray-600 rounded text-xs font-medium whitespace-nowrap">
                                            {{ (isset($categories) && is_array($categories) && isset($categories[$activity->category ?? ''])) ? $categories[$activity->category] : ucfirst($activity->category ?? 'general') }}
                                        </span>
                                    </div>
                                    <div class="flex items-center text-xs text-gray-500 mb-0.5">
                                        <i class="fas fa-map-marker-alt mr-1 text-red-400" style="font-size: 10px;"></i>
                                        <span class="truncate text-xs">{{ $activity->location ?? 'Location TBD' }}</span>
                                    </div>
                                </div>

                                <!-- Rating & Reviews -->
                                <div class="flex items-center mb-1">
                                    <div class="flex items-center">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= floor($activity->rating ?? 0))
                                                <i class="fas fa-star text-yellow-400" style="font-size: 10px;"></i>
                                            @else
                                                <i class="far fa-star text-gray-300" style="font-size: 10px;"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <span class="text-xs text-gray-600 ml-1">{{ $activity->rating ?? '0.0' }}</span>
                                    <span class="text-xs text-gray-400 mx-1">·</span>
                                    <span class="text-xs text-gray-600">{{ $activity->reviews ?? '0' }}</span>
                                </div>

                                <!-- Duration -->
                                <div class="flex items-center text-xs text-gray-600 mb-1">
                                    <i class="fas fa-clock text-blue-500 mr-1" style="font-size: 10px;"></i>
                                    <span class="text-xs">{{ $activity->duration ?? 'Duration TBD' }}</span>
                                </div>

                                <!-- Age Groups -->
                                @if(isset($activity->age_groups) && is_array($activity->age_groups) && count($activity->age_groups) > 0)
                                    <div class="flex flex-wrap gap-1 mb-2">
                                        @foreach(array_slice($activity->age_groups, 0, 2) as $ageGroup)
                                            <span class="px-1.5 py-0.5 bg-blue-50 text-blue-600 rounded text-xs">
                                                {{ ucfirst($ageGroup) }}
                                            </span>
                                        @endforeach
                                        @if(count($activity->age_groups) > 2)
                                            <span class="px-1.5 py-0.5 bg-gray-50 text-gray-600 rounded text-xs">
                                                +{{ count($activity->age_groups) - 2 }}
                                            </span>
                                        @endif
                                    </div>
                                @endif

                                <!-- Action Buttons -->
                                <div class="flex gap-1">
                                    <a href="{{ route('activities.show', $activity->id) }}"
                                       class="flex-1 px-2 py-1.5 bg-gray-100 text-gray-700 rounded hover:bg-gray-200 transition-colors duration-200 font-medium text-center text-xs">
                                        Details
                                    </a>
                                    @auth
                                        <a href="{{ route('activities.book', $activity->id) }}"
                                           class="flex-1 px-2 py-1.5 bg-gradient-to-r from-green-600 to-teal-600 text-white rounded hover:from-green-700 hover:to-teal-700 transition-colors duration-200 font-medium text-center text-xs">
                                            Book Now
                                        </a>
                                    @else
                                        <a href="{{ route('login') }}"
                                           class="flex-1 px-2 py-1.5 bg-gradient-to-r from-green-600 to-teal-600 text-white rounded hover:from-green-700 hover:to-teal-700 transition-colors duration-200 font-medium text-center text-xs">
                                            Book Now
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if(isset($paginationData) && $paginationData->has_pages)
                    <div class="mt-12 flex flex-col items-center space-y-4">
                        <!-- Results Info -->
                        <div class="text-sm text-gray-600">
                            Showing {{ $paginationData->from }} to {{ $paginationData->to }} of {{ $paginationData->total }} activities
                        </div>

                        <!-- Pagination Links -->
                        <nav class="flex items-center space-x-1">
                            <!-- Previous Page -->
                            @if($paginationData->current_page > 1)
                                <a href="{{ request()->fullUrlWithQuery(['page' => $paginationData->current_page - 1]) }}"
                                   class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 hover:text-gray-700 transition-colors">
                                    <i class="fas fa-chevron-left mr-1"></i>
                                    Previous
                                </a>
                            @else
                                <span class="px-3 py-2 text-sm font-medium text-gray-400 bg-gray-100 border border-gray-300 rounded-md cursor-not-allowed">
                                    <i class="fas fa-chevron-left mr-1"></i>
                                    Previous
                                </span>
                            @endif

                            <!-- Page Numbers -->
                            @php
                                $start = max(1, $paginationData->current_page - 2);
                                $end = min($paginationData->last_page, $paginationData->current_page + 2);
                            @endphp

                            @if($start > 1)
                                <a href="{{ request()->fullUrlWithQuery(['page' => 1]) }}"
                                   class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 hover:text-gray-700 transition-colors">
                                    1
                                </a>
                                @if($start > 2)
                                    <span class="px-2 py-2 text-sm text-gray-400">...</span>
                                @endif
                            @endif

                            @for($i = $start; $i <= $end; $i++)
                                @if($i == $paginationData->current_page)
                                    <span class="px-3 py-2 text-sm font-medium text-white bg-green-600 border border-green-600 rounded-md">
                                        {{ $i }}
                                    </span>
                                @else
                                    <a href="{{ request()->fullUrlWithQuery(['page' => $i]) }}"
                                       class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 hover:text-gray-700 transition-colors">
                                        {{ $i }}
                                    </a>
                                @endif
                            @endfor

                            @if($end < $paginationData->last_page)
                                @if($end < $paginationData->last_page - 1)
                                    <span class="px-2 py-2 text-sm text-gray-400">...</span>
                                @endif
                                <a href="{{ request()->fullUrlWithQuery(['page' => $paginationData->last_page]) }}"
                                   class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 hover:text-gray-700 transition-colors">
                                    {{ $paginationData->last_page }}
                                </a>
                            @endif

                            <!-- Next Page -->
                            @if($paginationData->current_page < $paginationData->last_page)
                                <a href="{{ request()->fullUrlWithQuery(['page' => $paginationData->current_page + 1]) }}"
                                   class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 hover:text-gray-700 transition-colors">
                                    Next
                                    <i class="fas fa-chevron-right ml-1"></i>
                                </a>
                            @else
                                <span class="px-3 py-2 text-sm font-medium text-gray-400 bg-gray-100 border border-gray-300 rounded-md cursor-not-allowed">
                                    Next
                                    <i class="fas fa-chevron-right ml-1"></i>
                                </span>
                            @endif
                        </nav>
                    </div>
                @endif

            @else
                <!-- No Results -->
                <div class="text-center py-16">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-search text-3xl text-gray-400"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">No Activities Found</h3>
                    <p class="text-gray-600 mb-8 max-w-md mx-auto">
                        We couldn't find any activities matching your search criteria. Try adjusting your filters or search terms.
                    </p>
                    <a href="{{ route('activities.index') }}"
                       class="px-8 py-3 bg-gradient-to-r from-green-600 to-teal-600 text-white rounded-full hover:from-green-700 hover:to-teal-700 transition-all duration-300 font-medium shadow-lg">
                        Browse All Activities
                    </a>
                </div>
            @endif
        </div>
    </section>
</div>

<style>
.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.h-fit {
    height: fit-content;
}

.group:hover .group-hover\:scale-105 {
    transform: scale(1.05);
}

.group:hover .group-hover\:text-green-600 {
    color: #059669;
}

/* Card hover effects */
.bg-white:hover {
    box-shadow: 0 10px 25px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

/* Color design hover effects */
.group:hover .bg-gradient-to-br {
    transform: scale(1.02);
    filter: brightness(1.1);
}

/* Decorative elements animation */
@keyframes float {
    0%, 100% { transform: translateY(0px) scale(1); }
    50% { transform: translateY(-2px) scale(1.1); }
}

.group:hover .bg-white\/20,
.group:hover .bg-white\/10,
.group:hover .bg-white\/30 {
    animation: float 2s ease-in-out infinite;
}

/* Smooth transitions */
.transition-all {
    transition: all 0.3s ease;
}

.transition-transform {
    transition: transform 0.3s ease;
}

.transition-colors {
    transition: color 0.3s ease;
}

/* Category icon hover effect */
.group:hover .text-white\/80 {
    color: rgba(255, 255, 255, 1);
    transform: scale(1.1);
    transition: all 0.3s ease;
}
</style>

@endsection
