@extends('layouts.app')

@section('title', __('Create Booking'))

@section('content')
<div class="container mx-auto p-6">

    <h2 class="text-2xl font-bold text-gray-800 mb-6">{{ __('Create New Booking') }}</h2>

    <form action="{{ route('bookings.store') }}" method="POST" class="bg-white shadow rounded-lg p-6 grid grid-cols-1 gap-4">
        @csrf

        {{-- Client --}}
        <div>
            <label class="block text-sm font-medium">{{ __('Client') }}</label>
            <select name="client_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                @foreach($clients as $client)
                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                @endforeach
            </select>
            @error('client_id')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Service --}}
        <div>
            <label class="block text-sm font-medium">{{ __('Service') }}</label>
            <select name="service_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                @foreach($services as $service)
                    <option value="{{ $service->id }}">{{ $service->name }}</option>
                @endforeach
            </select>
            @error('service_id')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Datetime --}}
        <div>
            <label class="block text-sm font-medium">{{ __('Date & Time') }}</label>
            <input type="datetime-local" name="datetime" value="{{ old('datetime') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            @error('datetime')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Status --}}
        <div>
            <label class="block text-sm font-medium">{{ __('Status') }}</label>
            <select name="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                <option value="pending">{{ __('Pending') }}</option>
                <option value="completed">{{ __('Completed') }}</option>
                <option value="cancelled">{{ __('Cancelled') }}</option>
            </select>
            @error('status')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Notes --}}
        <div>
            <label class="block text-sm font-medium">{{ __('Notes') }}</label>
            <textarea name="notes" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('notes') }}</textarea>
        </div>

        {{-- Submit --}}
        <div class="flex justify-end">
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">{{ __('Create Booking') }}</button>
        </div>
    </form>
</div>
@endsection
