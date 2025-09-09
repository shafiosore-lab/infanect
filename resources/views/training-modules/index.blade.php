{{-- resources/views/training-modules/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Training Modules')

@section('content')
<div class="container mx-auto p-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Training Modules</h1>
        <p class="mt-2 text-gray-600">Professional training resources with AI-powered assistance</p>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white shadow rounded-lg p-6 mb-8">
        <form method="GET" action="{{ route('training-modules.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div class="md:col-span-2">
                    <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                    <input type="text" name="search" id="search" value="{{ $request->search }}"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Search modules...">
                </div>

                <!-- Category Filter -->
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                    <select name="category" id="category"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ $request->category == $cat ? 'selected' : '' }}>
                                {{ ucfirst($cat) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Difficulty Filter -->
                <div>
                    <label for="difficulty" class="block text-sm font-medium text-gray-700">Difficulty</label>
                    <select name="difficulty" id="difficulty"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Levels</option>
                        @foreach($difficulties as $diff)
                            <option value="{{ $diff }}" {{ $request->difficulty == $diff ? 'selected' : '' }}>
                                {{ ucfirst($diff) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Sort Options -->
            <div class="flex flex-wrap gap-4 items-end">
                <div>
                    <label for="sort" class="block text-sm font-medium text-gray-700">Sort by</label>
                    <select name="sort" id="sort"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="created_at" {{ $request->sort == 'created_at' ? 'selected' : '' }}>Newest</option>
                        <option value="title" {{ $request->sort == 'title' ? 'selected' : '' }}>Title</option>
                        <option value="rating" {{ $request->sort == 'rating' ? 'selected' : '' }}>Rating</option>
                        <option value="duration" {{ $request->sort == 'duration' ? 'selected' : '' }}>Duration</option>
                        <option value="popularity" {{ $request->sort == 'popularity' ? 'selected' : '' }}>Popularity</option>
                    </select>
                </div>

                <div>
                    <select name="order" id="order"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="desc" {{ $request->order == 'desc' ? 'selected' : '' }}>Descending</option>
                        <option value="asc" {{ $request->order == 'asc' ? 'selected' : '' }}>Ascending</option>
                    </select>
                </div>

                <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Search
                </button>

                @if($request->hasAny(['search', 'category', 'difficulty', 'sort']))
                    <a href="{{ route('training-modules.index') }}"
                       class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Clear Filters
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Modules Grid -->
    @if($modules->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            @foreach($modules as $module)
                <div class="bg-white shadow rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
                    <div class="p-6">
                        <!-- Module Header -->
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                    <a href="{{ route('training-modules.show', $module) }}"
                                       class="hover:text-blue-600">{{ $module->title }}</a>
                                </h3>
                                <div class="flex items-center space-x-2 text-sm text-gray-500">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ ucfirst($module->category) }}
                                    </span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ ucfirst($module->difficulty_level) }}
                                    </span>
                                    @if($module->enable_ai_chat)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                            AI Chat
                                        </span>
                                    @endif
                                </div>
                            </div>

                        </div>

                        <!-- Description -->
                        <p class="text-gray-600 text-sm mb-4 line-clamp-3">{{ $module->description }}</p>

                        <!-- Stats -->
                        <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $module->estimated_duration }} min
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    {{ number_format($module->view_count) }}
                                </div>
                            </div>
                            @if($module->rating > 0)
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                    {{ number_format($module->rating, 1) }}
                                </div>
                            @endif
                        </div>

                        <!-- Progress Bar (if user has progress) -->
                        @php
                            $userProgress = $userProgress[$module->id] ?? null;
                        @endphp
                        @if($userProgress)
                            <div class="mb-4">
                                <div class="flex items-center justify-between text-sm text-gray-600 mb-1">
                                    <span>Progress</span>
                                    <span>{{ $userProgress->progress_percentage }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $userProgress->progress_percentage }}%"></div>
                                </div>
                                <div class="text-xs text-gray-500 mt-1">
                                    Status: {{ ucfirst(str_replace('_', ' ', $userProgress->status)) }}
                                </div>
                            </div>
                        @endif

                        <!-- Action Button -->
                        <div class="flex items-center justify-between">
                            <a href="{{ route('training-modules.show', $module) }}"
                               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                @if($userProgress && $userProgress->status === 'completed')
                                    Review
                                @elseif($userProgress && $userProgress->status === 'in_progress')
                                    Continue
                                @else
                                    Start Learning
                                @endif
                            </a>

                            @if($userProgress)
                                <button onclick="toggleFavorite({{ $module->id }})"
                                        class="text-gray-400 hover:text-red-500 p-1"
                                        id="favorite-btn-{{ $module->id }}">
                                    <svg class="w-5 h-5 {{ $userProgress->is_favorited ? 'text-red-500 fill-current' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="flex justify-center">
            {{ $modules->appends(request()->query())->links() }}
        </div>
    @else
        <!-- No modules found -->
        <div class="bg-white shadow rounded-lg p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No training modules found</h3>
            <p class="mt-1 text-sm text-gray-500">
                @if($request->hasAny(['search', 'category', 'difficulty']))
                    Try adjusting your search criteria or clearing the filters.
                @else
                    Check back later for new training modules.
                @endif
            </p>
            @if($request->hasAny(['search', 'category', 'difficulty']))
                <div class="mt-6">
                    <a href="{{ route('training-modules.index') }}"
                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        Clear Filters
                    </a>
                </div>
            @endif
        </div>
    @endif
</div>

<script>
function toggleFavorite(moduleId) {
    fetch(`/training-modules/${moduleId}/favorite`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({})
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const btn = document.getElementById(`favorite-btn-${moduleId}`);
            const icon = btn.querySelector('svg');
            if (data.is_favorited) {
                icon.classList.add('text-red-500', 'fill-current');
            } else {
                icon.classList.remove('text-red-500', 'fill-current');
            }
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>
@endsection
