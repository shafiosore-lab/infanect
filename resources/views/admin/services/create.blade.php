{{-- resources/views/admin/services/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="p-6 max-w-3xl mx-auto bg-white rounded-xl shadow-lg space-y-6">
    <!-- Header -->
    <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
        🚀 {{ __('Add New Service') }}
    </h2>

    <!-- Form -->
    <form action="{{ route('admin.services.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf

        <!-- Service Name -->
        <div>
            <label class="block text-gray-700 font-medium">{{ __('Service Name') }}</label>
            <input type="text" name="name" value="{{ old('name') }}"
                   class="w-full mt-1 px-3 py-2 border rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
        </div>

        <!-- Category -->
        <div>
            <label class="block text-gray-700 font-medium">{{ __('Category') }}</label>
            <select name="category_id"
                    class="w-full mt-1 px-3 py-2 border rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">{{ __('Select Category') }}</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Provider -->
        <div>
            <label class="block text-gray-700 font-medium">{{ __('Provider') }}</label>
            <select name="provider_id"
                    class="w-full mt-1 px-3 py-2 border rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">{{ __('Select Provider') }}</option>
                @foreach($providers as $provider)
                    <option value="{{ $provider->id }}" @selected(old('provider_id') == $provider->id)>{{ $provider->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Price + Currency -->
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-gray-700 font-medium">{{ __('Price') }}</label>
                <input type="number" name="price" step="0.01" value="{{ old('price') }}"
                       class="w-full mt-1 px-3 py-2 border rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div>
                <label class="block text-gray-700 font-medium">{{ __('Currency') }}</label>
                <select name="currency"
                        class="w-full mt-1 px-3 py-2 border rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="USD" @selected(old('currency') == "USD")>USD</option>
                    <option value="EUR" @selected(old('currency') == "EUR")>EUR</option>
                    <option value="KES" @selected(old('currency') == "KES")>KES</option>
                    <option value="GBP" @selected(old('currency') == "GBP")>GBP</option>
                    <!-- Add more as needed -->
                </select>
            </div>
        </div>

        <!-- Duration -->
        <div>
            <label class="block text-gray-700 font-medium">{{ __('Duration (minutes)') }}</label>
            <input type="number" name="duration" value="{{ old('duration') }}"
                   class="w-full mt-1 px-3 py-2 border rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
        </div>

        <!-- Country -->
        <div>
            <label class="block text-gray-700 font-medium">{{ __('Country') }}</label>
            <select name="country"
                    class="w-full mt-1 px-3 py-2 border rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">{{ __('Select Country') }}</option>
                @foreach($countries as $country)
                    <option value="{{ $country }}" @selected(old('country') == $country)>{{ $country }}</option>
                @endforeach
            </select>
        </div>

        <!-- Description -->
        <div>
            <label class="block text-gray-700 font-medium">{{ __('Description') }}</label>
            <textarea name="description" rows="4"
                      class="w-full mt-1 px-3 py-2 border rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('description') }}</textarea>
        </div>

        <!-- Image -->
        <div>
            <label class="block text-gray-700 font-medium">{{ __('Service Image (optional)') }}</label>
            <input type="file" name="image" accept="image/*"
                   class="w-full mt-1 px-3 py-2 border rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
        </div>

        <!-- Status -->
        <div class="flex items-center">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active'))
                   class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
            <label class="ml-2 text-gray-700">{{ __('Active') }}</label>
        </div>

        <!-- Submit -->
        <div class="flex justify-end">
            <button type="submit"
                    class="px-6 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg shadow hover:from-indigo-700 hover:to-purple-700 transition-all">
                {{ __('Save Service') }}
            </button>
        </div>
    </form>
</div>
@endsection
