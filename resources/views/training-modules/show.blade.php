{{-- resources/views/training-modules/show.blade.php --}}
@extends('layouts.app')

@section('title', $module->title . ' - Training Module')

@section('content')
<div class="container mx-auto p-6">
    <!-- Header -->
    <div class="bg-white shadow rounded-lg p-6 mb-8">
        <div class="flex items-start justify-between mb-6">
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $module->title }}</h1>
                <p class="text-gray-600 mb-4">{{ $module->description }}</p>

                <div class="flex items-center space-x-4 text-sm text-gray-500">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        {{ ucfirst($module->category) }}
                    </span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        {{ ucfirst($module->difficulty_level) }}
                    </span>
                    @if($module->enable_ai_chat)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            AI Chat Enabled
                        </span>
                    @endif
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ $module->estimated_duration }} minutes
                    </div>
                </div>
            </div>

            <div class="flex items-center space-x-3">
                @if($module->enable_ai_chat && $module->canUseAiChat())
                    <a href="{{ route('training-modules.chat', $module) }}"
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        AI Chat Assistant
                    </a>
                @endif

                <button onclick="toggleFavorite({{ $module->id }})"
                        class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                        id="favorite-btn">
                    <svg class="w-4 h-4 mr-2 {{ $userProgress->is_favorited ? 'text-red-500 fill-current' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                    {{ $userProgress->is_favorited ? 'Favorited' : 'Add to Favorites' }}
                </button>
            </div>
        </div>

        <!-- Progress Section -->
        @if($userProgress)
            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-lg font-medium text-gray-900">Your Progress</h3>
                    <span class="text-sm text-gray-500">{{ $userProgress->progress_percentage }}% Complete</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-blue-600 h-3 rounded-full transition-all duration-300" style="width: {{ $userProgress->progress_percentage }}%"></div>
                </div>
                <div class="flex items-center justify-between mt-2 text-sm text-gray-600">
                    <span>Status: {{ ucfirst(str_replace('_', ' ', $userProgress->status)) }}</span>
                    <span>Last accessed: {{ $userProgress->last_accessed_at ? $userProgress->last_accessed_at->diffForHumans() : 'Never' }}</span>
                </div>
            </div>
        @endif

        <!-- Module Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-blue-50 p-4 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-8 h-8 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    <div>
                        <p class="text-sm text-gray-600">Views</p>
                        <p class="text-xl font-semibold text-gray-900">{{ number_format($module->view_count) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-green-50 p-4 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-8 h-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="text-sm text-gray-600">Completions</p>
                        <p class="text-xl font-semibold text-gray-900">{{ number_format($module->completion_count) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-yellow-50 p-4 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-8 h-8 text-yellow-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                    </svg>
                    <div>
                        <p class="text-sm text-gray-600">Rating</p>
                        <p class="text-xl font-semibold text-gray-900">{{ $module->rating > 0 ? number_format($module->rating, 1) : 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-purple-50 p-4 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-8 h-8 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 4v10a2 2 0 002 2h4a2 2 0 002-2V11m-8 0H4a2 2 0 00-2 2v4a2 2 0 002 2h16a2 2 0 002-2v-4a2 2 0 00-2-2h-4"></path>
                    </svg>
                    <div>
                        <p class="text-sm text-gray-600">Created</p>
                        <p class="text-xl font-semibold text-gray-900">{{ $module->created_at->format('M Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Document Content (if available) -->
    @if($module->hasDocument() || !empty($module->document_content))
        <div class="bg-white shadow rounded-lg p-6 mb-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Module Content</h2>

            @if($module->hasDocument())
                <div class="mb-4">
                    <a href="{{ $module->document_url }}" target="_blank"
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Download Document
                    </a>
                </div>
            @endif

            @if(!empty($module->document_content))
                <div class="prose max-w-none">
                    @if(isset($module->document_content['sections']))
                        @foreach($module->document_content['sections'] as $section)
                            <div class="mb-6">
                                @if(isset($section['title']))
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $section['title'] }}</h3>
                                @endif
                                @if(isset($section['content']))
                                    <div class="text-gray-700 leading-relaxed">{{ $section['content'] }}</div>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <div class="text-gray-700 leading-relaxed">
                            {{ $module->document_content['full_text'] ?? 'Content extracted from document.' }}
                        </div>
                    @endif
                </div>
            @endif
        </div>
    @endif

    <!-- Action Buttons -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <button onclick="updateProgress({{ $module->id }}, 100)"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Mark as Complete
                </button>

                @if($module->enable_ai_chat && $module->canUseAiChat())
                    <a href="{{ route('training-modules.chat', $module) }}"
                       class="inline-flex items-center px-4 py-2 border border-purple-300 text-sm font-medium rounded-md text-purple-700 bg-purple-50 hover:bg-purple-100">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        Ask AI Assistant
                    </a>
                @endif
            </div>

            <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-500">Rate this module:</span>
                <div class="flex items-center space-x-1">
                    @for($i = 1; $i <= 5; $i++)
                        <button onclick="rateModule({{ $module->id }}, {{ $i }})"
                                class="w-6 h-6 {{ $userProgress && $userProgress->rating >= $i ? 'text-yellow-400' : 'text-gray-300' }} hover:text-yellow-400">
                            <svg fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                        </button>
                    @endfor
                </div>
            </div>
        </div>
    </div>
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
            const btn = document.getElementById('favorite-btn');
            const icon = btn.querySelector('svg');
            const text = btn.querySelector('span') || btn;
            if (data.is_favorited) {
                icon.classList.add('text-red-500', 'fill-current');
                text.textContent = 'Favorited';
            } else {
                icon.classList.remove('text-red-500', 'fill-current');
                text.textContent = 'Add to Favorites';
            }
        }
    })
    .catch(error => console.error('Error:', error));
}

function updateProgress(moduleId, percentage) {
    fetch(`/training-modules/${moduleId}/progress`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            progress_percentage: percentage
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
}

function rateModule(moduleId, rating) {
    fetch(`/training-modules/${moduleId}/rate`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            rating: rating
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>
@endsection
