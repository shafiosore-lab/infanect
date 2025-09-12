@extends('layouts.app')

@section('title', 'Start Learning')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Start Learning</h1>
    <div class="grid grid-cols-1 gap-4">
        @if(isset($lessons) && is_array($lessons) && count($lessons) > 0)
            @foreach($lessons as $l)
                <div class="p-4 bg-white rounded shadow flex items-center justify-between">
                    <div>
                        <div class="font-semibold">{{ $l['title'] ?? 'Untitled Lesson' }}</div>
                        <div class="text-sm text-gray-600">{{ $l['external_id'] ?? 'No ID' }}</div>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('start-learning.play.direct', ['externalId' => $l['external_id'] ?? 'lesson_bedtime_01']) }}" class="px-3 py-2 bg-black text-white rounded">Open</a>
                    </div>
                </div>
            @endforeach
        @else
            <div class="p-4 bg-white rounded shadow text-center">
                <p class="text-gray-600 mb-4">No lessons available at the moment.</p>
                <a href="{{ route('start-learning.play.direct', ['externalId' => 'lesson_bedtime_01']) }}" class="px-4 py-2 bg-blue-600 text-white rounded">Try Sample Lesson</a>
            </div>
        @endif
    </div>
</div>
@endsection
