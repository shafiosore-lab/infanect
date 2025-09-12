@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <h1 class="text-2xl font-semibold mb-4">Your Services</h1>

    <a href="#" class="mb-4 inline-block px-4 py-2 bg-blue-600 text-white rounded">Add Service</a>

    <div class="grid gap-3">
        @foreach($services as $service)
            <div class="p-3 bg-white rounded shadow">
                <h3 class="font-semibold">{{ $service->title }}</h3>
                <p class="text-sm text-gray-600">{{ $service->description }}</p>
            </div>
        @endforeach
    </div>
</div>
@endsection
