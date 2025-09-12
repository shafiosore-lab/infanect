@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <h1 class="text-2xl font-semibold mb-4">Your Activities</h1>

    <a href="#" class="mb-4 inline-block px-4 py-2 bg-green-600 text-white rounded">Add Activity</a>

    <div class="grid gap-3">
        @foreach($activities as $activity)
            <div class="p-3 bg-white rounded shadow">
                <h3 class="font-semibold">{{ $activity->title }}</h3>
                <p class="text-sm text-gray-600">{{ $activity->category }}</p>
            </div>
        @endforeach
    </div>
</div>
@endsection
