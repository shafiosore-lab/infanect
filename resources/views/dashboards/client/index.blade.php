@extends('layouts.client')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Client Dashboard</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="p-4 bg-white rounded shadow">
            <div class="text-sm text-gray-500">Bookings</div>
            <div class="text-2xl font-bold">{{ $stats['bookings'] ?? 0 }}</div>
        </div>
        <div class="p-4 bg-white rounded shadow">
            <div class="text-sm text-gray-500">Payments</div>
            <div class="text-2xl font-bold">{{ $stats['payments'] ?? 0 }}</div>
        </div>
        <div class="p-4 bg-white rounded shadow">
            <div class="text-sm text-gray-500">AI Library</div>
            <div class="text-2xl font-bold">{{ $stats['ai_library'] ?? 0 }}</div>
        </div>
    </div>

    <div class="mt-6">
        <a href="{{ route('user.bookings.index') }}" class="btn btn-primary">My Bookings</a>
        <a href="{{ route('ai.chat') }}" class="btn btn-secondary">AI Library</a>
    </div>
</div>
@endsection
