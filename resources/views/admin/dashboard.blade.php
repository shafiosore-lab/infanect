{{-- resources/views/dashboards/admin.blade.php --}}
@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="max-w-7xl mx-auto p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold">Admin Dashboard</h1>
        <p class="text-sm text-gray-500">Key platform metrics</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="p-4 border rounded">
            <div class="text-sm text-gray-500">Parenting Modules</div>
            <div class="text-xl font-semibold">{{ $parenting_modules ?? 'N/A' }}</div>
        </div>

        <div class="p-4 border rounded">
            <div class="text-sm text-gray-500">Bookings Today</div>
            <div class="text-xl font-semibold">{{ $bookings_today ?? 'N/A' }}</div>
        </div>

        <div class="p-4 border rounded">
            <div class="text-sm text-gray-500">Total Revenue</div>
            <div class="text-xl font-semibold">{{ $revenue_total !== null ? number_format($revenue_total, 2) : 'N/A' }}</div>
        </div>

        <div class="p-4 border rounded">
            <div class="text-sm text-gray-500">Reviews</div>
            <div class="text-xl font-semibold">{{ $reviews_count ?? 'N/A' }}</div>
        </div>
    </div>

    <div class="mt-6">
        <a href="{{ route('admin.engagement.insights') }}" class="text-primary hover:underline">View Engagement Insights</a>
    </div>
</div>
@endsection
