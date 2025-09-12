@extends('layouts.app')

@section('title', __('services.services') . ' - ' . __('services.browse_all'))

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="text-center">
                <h1 class="text-4xl md:text-6xl font-bold mb-4">
                    {{ __('services.discover_services') }}
                </h1>
                <p class="text-xl text-white/90 mb-8 max-w-3xl mx-auto">
                    {{ __('services.find_perfect_service') }}
                </p>

                <!-- Search Bar -->
                <div class="max-w-2xl mx-auto">
                    <form action="{{ route('services.index') }}" method="GET" class="flex gap-2">
                        <div class="flex-1">
                            <input type="text"
                                   name="search"
                                   value="{{ request('search') }}"
                                   placeholder="{{ __('services.search_services') }}"
                                   class="w-full px-6 py-4 rounded-xl border-0 focus:ring-2 focus:ring-white/50 text-gray-900 placeholder-gray-500">
                        </div>
                        <button type="submit"
                                class="px-8 py-4 bg-white text-purple-600 font-semibold rounded-xl hover:bg-gray-100 transition-colors duration-200 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            {{ __('common.search') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">

        <!-- Filters and Sorting -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
            <div class="flex flex-col md:flex-row gap-4 items-center justify-between">

                <!-- Category Filter -->
                <div class="flex items-center space-x-4">
                    <span class="text-gray-700 font-medium">{{ __('services.category') }}:</span>
                    <select name="category"
                            onchange="this.form.submit()"
                            form="filterForm"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        <option value="">{{ __('services.all_categories') }}</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Sort Options -->
                <div class="flex items-center space-x-4">
                    <span class="text-gray-700 font-medium">{{ __('common.sort_by') }}:</span>
                    <select name="sort"
                            onchange="this.form.submit()"
                            form="filterForm"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        <option value="name" {{ request('sort', 'name') == 'name' ? 'selected' : '' }}>
                            {{ __('services.name') }}
                        </option>
                        <option value="price" {{ request('sort') == 'price' ? 'selected' : '' }}>
                            {{ __('services.price') }}
                        </option>
                        <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>
                            {{ __('services.newest') }}
                        </option>
                    </select>

                    <select name="direction"
                            onchange="this.form.submit()"
                            form="filterForm"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        <option value="asc" {{ request('direction', 'asc') == 'asc' ? 'selected' : '' }}>
                            {{ __('common.ascending') }}
                        </option>
                        <option value="desc" {{ request('direction') == 'desc' ? 'selected' : '' }}>
                            {{ __('common.descending') }}
                        </option>
                    </select>
                </div>
            </div>

            <!-- Hidden Form for Filters -->
            <form id="filterForm" action="{{ route('services.index') }}" method="GET" class="hidden">
                <input type="hidden" name="search" value="{{ request('search') }}">
                <input type="hidden" name="category" value="{{ request('category') }}">
                <input type="hidden" name="sort" value="{{ request('sort', 'name') }}">
                <input type="hidden" name="direction" value="{{ request('direction', 'asc') }}">
            </form>
        </div>

        <!-- Services Grid -->
        @if($services->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8 mb-12">

                @foreach($services as $service)
                    <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-shadow duration-300 overflow-hidden group">
                        <!-- Service Image/Icon -->
                        <div class="h-48 bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 flex items-center justify-center">
                            <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>

                        <!-- Service Content -->
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-3">
                                <h3 class="text-xl font-bold text-gray-900 group-hover:text-purple-600 transition-colors duration-200">
                                    {{ $service->name }}
                                </h3>
                                @if($service->category)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        {{ $service->category->name }}
                                    </span>
                                @endif
                            </div>

                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                {{ Str::limit($service->description, 100) }}
                            </p>

                            <!-- Provider Info -->
                            <div class="flex items-center space-x-3 mb-4">
                                <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center">
                                    <span class="text-white font-semibold text-xs">
                                        {{ $service->user ? substr($service->user->name ?? 'P', 0, 1) : 'P' }}
                                    </span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $service->user ? ($service->user->name ?? __('services.provider')) : __('services.provider') }}</p>
                                    <p class="text-xs text-gray-500">{{ $service->user ? ($service->user->city ?? '') : '' }}</p>
                                </div>
                            </div>

                            <!-- Price and Action -->
                            <div class="flex items-center justify-between">
                                <div class="text-2xl font-bold text-purple-600">
                                    ${{ number_format($service->price, 2) }}
                                </div>
                                <a href="{{ route('bookings.create.service', $service->id) }}"
                                   class="px-6 py-2 bg-purple-600 text-white font-semibold rounded-lg hover:bg-purple-700 transition-colors duration-200 flex items-center text-sm">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    {{ __('services.book_now') }}
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>

            <!-- Pagination -->
            <div class="flex justify-center">
                {{ $services->appends(request()->query())->links() }}
            </div>

        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <svg class="w-24 h-24 text-gray-400 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.29-.966-5.5-2.5m13.5-9a2.18 2.18 0 00.281-1.673C18.132 2.73 16.735 2 15 2s-3.132.73-3.781 1.827c-.1.194-.187.403-.281.673M12 7.367c.312.143.649.267 1 .367m-1-3.267c-.312.143-.649.267-1 .367m0 0c-.312-.143-.649-.267-1-.367m0 0c.312.143.649.267 1 .367"></path>
                </svg>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ __('services.no_services_found') }}</h3>
                <p class="text-gray-600 mb-6">{{ __('services.try_different_search') }}</p>
                <a href="{{ route('services.index') }}"
                   class="inline-flex items-center px-6 py-3 bg-purple-600 text-white font-semibold rounded-lg hover:bg-purple-700 transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    {{ __('services.view_all_services') }}
                </a>
            </div>
        @endif

    </div>
</div>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection
