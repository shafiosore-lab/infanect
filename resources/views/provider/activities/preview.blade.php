{{-- resources/views/provider/activities/preview.blade.php --}}
@extends('layouts.app')

@section('title', 'Preview Activity')

@section('content')
<div class="container mx-auto p-6 max-w-4xl">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Activity Preview</h1>
        <p class="mt-2 text-gray-600">Review your activity details before submitting for approval.</p>
    </div>

    <!-- Preview Card -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <!-- Activity Header -->
        <div class="bg-gradient-to-r from-blue-500 to-purple-600 px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-white">{{ $request->title }}</h2>
                        <p class="text-blue-100">{{ ucfirst($request->category) }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-bold text-white">${{ $request->price }}</div>
                    <div class="text-blue-100 text-sm">{{ $request->slots }} slots available</div>
                </div>
            </div>
        </div>

        <!-- Activity Details -->
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-4">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Date & Time</h3>
                        <p class="mt-1 text-lg text-gray-900">{{ \Carbon\Carbon::parse($request->datetime)->format('F j, Y \a\t g:i A') }}</p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Venue</h3>
                        <p class="mt-1 text-lg text-gray-900">{{ $request->venue }}</p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Location</h3>
                        <p class="mt-1 text-lg text-gray-900">{{ $request->country }}</p>
                    </div>

                    @if($request->duration)
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Duration</h3>
                        <p class="mt-1 text-lg text-gray-900">{{ $request->duration }}</p>
                    </div>
                    @endif
                </div>

                <!-- Right Column -->
                <div class="space-y-4">
                    @if($request->difficulty_level)
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Difficulty Level</h3>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($request->difficulty_level === 'beginner') bg-green-100 text-green-800
                            @elseif($request->difficulty_level === 'intermediate') bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800 @endif">
                            {{ ucfirst($request->difficulty_level) }}
                        </span>
                    </div>
                    @endif

                    @if($request->target_audience)
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Target Audience</h3>
                        <p class="mt-1 text-lg text-gray-900">{{ $request->target_audience }}</p>
                    </div>
                    @endif

                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Provider</h3>
                        <p class="mt-1 text-lg text-gray-900">{{ $provider ? $provider->name : 'Not specified' }}</p>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="mt-6">
                <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Description</h3>
                <div class="mt-2 prose prose-gray max-w-none">
                    <p class="text-gray-900 whitespace-pre-line">{{ $request->description }}</p>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="bg-gray-50 px-6 py-4">
            <div class="flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0 sm:space-x-4">
                <!-- Left side - Edit -->
                <div class="flex space-x-3">
                    <a href="{{ route('provider.activities.create') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Details
                    </a>
                </div>

                <!-- Right side - Submit -->
                <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
                    <a href="{{ route('provider.activities.index') }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Cancel
                    </a>

                    <!-- Primary Submit Button -->
                    <form method="POST" action="{{ route('provider.activities.store') }}" class="inline">
                        @csrf
                        @foreach($request->all() as $key => $value)
                            @if(is_array($value))
                                @foreach($value as $arrayKey => $arrayValue)
                                    <input type="hidden" name="{{ $key }}[{{ $arrayKey }}]" value="{{ $arrayValue }}">
                                @endforeach
                            @else
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endif
                        @endforeach
                        <input type="hidden" name="action" value="submit">
                        <button type="submit" class="inline-flex items-center justify-center px-8 py-3 border border-transparent rounded-lg shadow-sm text-base font-semibold text-white bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transform hover:scale-105 transition-all duration-200">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                            Submit Activity for Approval
                        </button>
                    </form>

                    <!-- Alternative Submit Option -->
                    <form method="POST" action="{{ route('provider.activities.store') }}" class="inline">
                        @csrf
                        @foreach($request->all() as $key => $value)
                            @if(is_array($value))
                                @foreach($value as $arrayKey => $arrayValue)
                                    <input type="hidden" name="{{ $key }}[{{ $arrayKey }}]" value="{{ $arrayValue }}">
                                @endforeach
                            @else
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endif
                        @endforeach
                        <input type="hidden" name="action" value="submit">
                        <input type="hidden" name="priority" value="high">
                        <button type="submit" class="inline-flex items-center justify-center px-6 py-2 border-2 border-green-300 rounded-md shadow-sm text-sm font-medium text-green-700 bg-green-50 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            Submit as Priority
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Preview Notice -->
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">Preview Mode</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <p>This is how your activity will appear to users once approved. Review all details carefully before submitting for approval.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
