@extends('layouts.app')

@section('title', __('Booking Details'))

@section('content')
<div class="container mx-auto p-6">

    <h2 class="text-2xl font-bold text-gray-800 mb-6">{{ __('Booking Details') }}</h2>

    <div class="bg-white shadow rounded-lg p-6 grid grid-cols-1 md:grid-cols-2 gap-4">

        <div>
            <h3 class="text-sm font-medium text-gray-500">{{ __('Client') }}</h3>
            <p class="text-lg font-semibold">{{ $booking->client->name }}</p>
        </div>

        <div>
            <h3 class="text-sm font-medium text-gray-500">{{ __('Service') }}</h3>
            <p class="text-lg font-semibold">{{ $booking->service->name }}</p>
        </div>

        <div>
            <h3 class="text-sm font-medium text-gray-500">{{ __('Date & Time') }}</h3>
            <p class="text-lg font-semibold">{{ $booking->datetime->format('d M Y H:i') }}</p>
        </div>

        <div>
            <h3 class="text-sm font-medium text-gray-500">{{ __('Status') }}</h3>
            <p class="text-lg font-semibold capitalize">{{ $booking->status }}</p>
        </div>

        @if($booking->notes)
        <div class="md:col-span-2">
            <h3 class="text-sm font-medium text-gray-500">{{ __('Notes') }}</h3>
            <p class="text-lg font-semibold">{{ $booking->notes }}</p>
        </div>
        @endif

        <div class="md:col-span-2 flex justify-end space-x-2 mt-4">
            <a href="{{ route('bookings.edit', $booking->id) }}" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">{{ __('Edit') }}</a>
            <form action="{{ route('bookings.destroy', $booking->id) }}" method="POST" onsubmit="return confirm('{{ __('Delete this booking?') }}');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">{{ __('Delete') }}</button>
            </form>
        </div>

    </div>

</div>
@endsection
