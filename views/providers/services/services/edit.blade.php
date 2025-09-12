@extends('layouts.app')

@section('title', 'Edit Service')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6">Edit Service</h1>

    <form action="{{ route('provider.services.update', $service->id) }}" method="POST" class="bg-white shadow rounded-lg p-6">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="name" class="block text-gray-700 font-medium mb-2">Service Name</label>
            <input type="text" name="name" id="name" value="{{ $service->name }}" required
                   class="w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="mb-4">
            <label for="description" class="block text-gray-700 font-medium mb-2">Description</label>
            <textarea name="description" id="description"
                      class="w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ $service->description }}</textarea>
        </div>

        <button type="submit"
                class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Update Service</button>
    </form>
</div>
@endsection
