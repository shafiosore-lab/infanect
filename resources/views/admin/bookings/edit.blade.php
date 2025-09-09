@extends('layouts.app')

@section('title', __('Edit Booking'))

@section('content')
<div class="container mx-auto p-6">

    <h2 class="text-2xl font-bold text-gray-800 mb-6">{{ __('Edit Booking') }}</h2>

    <form action="{{ route('bookings.update', $booking->id) }}" method="POST" class="bg-white shadow rounded-lg p-6 grid grid-cols-1 gap-4">
        @csrf
        @method('PUT')

        {{-- Client --}}
        <div>
            <label class="block text-sm font-medium">{{ __('Client') }}</label>
            <select name="client_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                @foreach($clients as $client)
                    <option value="{{ $client->id }}" {{ $client->id == $booking->client_id ? 'selected' : '' }}>{{ $client->name }}</option>
                @endforeach
            </select>
            @error('client_id')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Service --}}
        <div>
            <label class="block text-sm font-medium">{{ __('Service') }}</label>
            <select name="service_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                @foreach($services as $service)
                    <option value="{{ $service->id }}" {{ $service->id == $booking->service_id ? 'selected' : '' }}>{{ $service->name }}</option>
                @endforeach
            </select>
            @error('service_id')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Datetime --}}
        <div>
            <label class="block text-sm font-medium">{{ __('Date & Time') }}</label>
            <input type="datetime-local" name="datetime" value="{{ $booking->datetime->format('Y-m-d\TH:i') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            @error('datetime')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Status --}}
        <div>
            <label class="block text-sm font-medium">{{ __('Status') }}</label>
            <select name="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                <option value="completed" {{ $booking->status == 'completed' ? 'selected' : '' }}>{{ __('Completed') }}</option>
                <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>{{ __('Cancelled') }}</option>
            </select>
            @error('status')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Notes --}}
        <div>
            <label class="block text-sm font-medium">{{ __('Notes') }}</label>
            <textarea name="notes" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ $booking->notes }}</textarea>
        </div>

        {{-- Submit --}}
        <div class="flex justify-end">
            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">{{ __('Update Booking') }}</button>
        </div>
    </form>
</div>
@endsection
