{{-- resources/views/provider/activities/index.blade.php --}}
@extends('layouts.app')

@section('title', 'My Activities')

@section('content')
<div class="container mx-auto p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">My Activities</h1>
        <a href="{{ route('provider.activities.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            Add New Activity
        </a>
    </div>

    <!-- Activities List -->
    @if($activities->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($activities as $activity)
                <div class="bg-white shadow rounded-lg p-6">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $activity->title }}</h3>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($activity->is_approved) bg-green-100 text-green-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                            {{ $activity->is_approved ? 'Approved' : 'Pending' }}
                        </span>
                    </div>

                    <p class="text-gray-600 mb-4">{{ Str::limit($activity->description, 100) }}</p>

                    <div class="space-y-2 text-sm text-gray-500">
                        <p><strong>Category:</strong> {{ $activity->category }}</p>
                        <p><strong>Date:</strong> {{ $activity->datetime->format('M j, Y g:i A') }}</p>
                        <p><strong>Venue:</strong> {{ $activity->venue }}</p>
                        <p><strong>Price:</strong> ${{ $activity->price }}</p>
                        <p><strong>Slots:</strong> {{ $activity->slots }}</p>
                    </div>

                    <div class="mt-4 flex space-x-2">
                        <a href="{{ route('provider.activities.edit', $activity) }}" class="text-blue-600 hover:text-blue-800 text-sm">Edit</a>
                        <form method="POST" action="{{ route('provider.activities.destroy', $activity) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $activities->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No activities yet</h3>
            <p class="mt-1 text-sm text-gray-500">Start by creating your first activity.</p>
            <div class="mt-6">
                <a href="{{ route('provider.activities.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                    Create Activity
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
