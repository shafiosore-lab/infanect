@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto">
    <h1 class="text-2xl font-semibold mb-4">Provider Dashboard</h1>

    <div class="grid grid-cols-3 gap-4">
        <div class="p-4 bg-white rounded shadow">
            <h3 class="text-sm text-gray-500">Services</h3>
            <p class="text-2xl font-bold">{{ $stats['services'] ?? 0 }}</p>
        </div>
        <div class="p-4 bg-white rounded shadow">
            <h3 class="text-sm text-gray-500">Activities</h3>
            <p class="text-2xl font-bold">{{ $stats['activities'] ?? 0 }}</p>
        </div>
        <div class="p-4 bg-white rounded shadow">
            <h3 class="text-sm text-gray-500">Bookings</h3>
            <p class="text-2xl font-bold">{{ $stats['bookings'] ?? 0 }}</p>
        </div>
    </div>

    <div class="mt-6">
        <a href="{{ route('provider.services') }}" class="px-4 py-2 bg-blue-600 text-white rounded">Manage Services</a>
        <a href="{{ route('provider.activities') }}" class="px-4 py-2 bg-green-600 text-white rounded ml-2">Manage Activities</a>
    </div>
</div>
@endsection
