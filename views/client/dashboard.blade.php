@extends('layouts.client')

@section('content')
<div class="p-6">
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-semibold">Client Dashboard</h1>
        <div>
            <a href="{{ route('services.index') }}"><x-button.primary>Book a Service</x-button.primary></a>
        </div>
    </div>

    <x-card>
        <h2 class="font-semibold">Upcoming Bookings</h2>
        <p class="text-sm text-gray-500">Check your next appointments and details.</p>
        <div class="mt-3"><a href="{{ route('user.bookings.index') }}" class="text-indigo-600">View bookings</a></div>
    </x-card>

    <div class="mt-4">
        <x-card>
            <h3 class="font-semibold">Personalized Recommendations</h3>
            <p class="text-sm text-gray-500">Based on your preferences</p>
            <div class="mt-3"><a href="{{ route('recommendations') }}"><x-button.primary>See recommendations</x-button.primary></a></div>
        </x-card>
    </div>
</div>
@endsection
