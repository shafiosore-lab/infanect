@extends('layouts.provider-bonding')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Bonding Provider Dashboard</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="p-4 bg-white rounded shadow">
            <div class="text-sm text-gray-500">Activities</div>
            <div class="text-2xl font-bold">{{ $stats['activities'] ?? 0 }}</div>
        </div>
        <div class="p-4 bg-white rounded shadow">
            <div class="text-sm text-gray-500">Bookings</div>
            <div class="text-2xl font-bold">{{ $stats['bookings'] ?? 0 }}</div>
        </div>
        <div class="p-4 bg-white rounded shadow">
            <div class="text-sm text-gray-500">Earnings</div>
            <div class="text-2xl font-bold">{{ $stats['earnings'] ?? 0 }}</div>
        </div>
    </div>

    <div class="mt-6">
        <a href="{{ route('activities.index') }}" class="btn btn-primary">Manage Activities</a>
    </div>
</div>
@endsection
