{{-- resources/views/admin/services/edit.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="p-6 max-w-2xl mx-auto bg-white rounded shadow">
    <h2 class="text-xl font-bold mb-4">✏️ {{ __('Edit Service') }}</h2>

    <form action="{{ route('admin.services.update', $service) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Service Name -->
        <div class="mb-4">
            <label class="block text-gray-700 font-medium">{{ __('Service Name') }}</label>
            <input type="text" name="name"
                   class="w-full px-3 py-2 border rounded"
                   value="{{ old('name', $service->name) }}" required>
        </div>

        <!-- Category -->
        <div class="mb-4">
            <label class="block text-gray-700 font-medium">{{ __('Category') }}</label>
            <select name="category_id" class="w-full px-3 py-2 border rounded">
                <option value="">{{ __('Select Category') }}</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}"
                            {{ $service->category_id == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Provider -->
        <div class="mb-4">
            <label class="block text-gray-700 font-medium">{{ __('Provider') }}</label>
            <select name="provider_id" class="w-full px-3 py-2 border rounded">
                <option value="">{{ __('Select Provider') }}</option>
                @foreach($providers as $provider)
                    <option value="{{ $provider->id }}"
                            {{ $service->provider_id == $provider->id ? 'selected' : '' }}>
                        {{ $provider->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Price -->
        <div class="mb-4">
            <label class="block text-gray-700 font-medium">{{ __('Price') }}</label>
            <input type="number" step="0.01" name="price"
                   class="w-full px-3 py-2 border rounded"
                   value="{{ old('price', $service->price) }}">
        </div>

        <!-- Duration -->
        <div class="mb-4">
            <label class="block text-gray-700 font-medium">{{ __('Duration (minutes)') }}</label>
            <input type="number" name="duration"
                   class="w-full px-3 py-2 border rounded"
                   value="{{ old('duration', $service->duration) }}">
        </div>

        <!-- Country -->
        <div class="mb-4">
            <label class="block text-gray-700 font-medium">{{ __('Country') }}</label>
            <input type="text" name="country"
                   class="w-full px-3 py-2 border rounded"
                   value="{{ old('country', $service->country) }}">
        </div>

        <!-- Description -->
        <div class="mb-4">
            <label class="block text-gray-700 font-medium">{{ __('Description') }}</label>
            <textarea name="description" rows="4"
                      class="w-full px-3 py-2 border rounded">{{ old('description', $service->description) }}</textarea>
        </div>

        <!-- Status -->
        <div class="mb-4">
            <label class="inline-flex items-center">
                <input type="checkbox" name="is_active" value="1"
                       class="mr-2" {{ old('is_active', $service->is_active) ? 'checked' : '' }}>
                {{ __('Active') }}
            </label>
        </div>

        <!-- Image Upload -->
        <div class="mb-6">
            <label class="block text-gray-700 font-medium">{{ __('Service Image') }}</label>
            <input type="file" name="image" class="w-full px-3 py-2 border rounded">
            @if($service->image)
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $service->image) }}"
                         alt="{{ $service->name }}"
                         class="w-32 h-32 object-cover rounded border">
                </div>
            @endif
        </div>

        <!-- Actions -->
        <div class="flex justify-between">
            <a href="{{ route('admin.services.index') }}"
               class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 transition">
                ← {{ __('Cancel') }}
            </a>
            <button type="submit"
                    class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                💾 {{ __('Update Service') }}
            </button>
        </div>
    </form>
</div>
@endsection
