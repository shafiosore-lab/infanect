{{-- resources/views/admin/finance/insights.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Finance Insights</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="p-4 bg-white shadow rounded">
            <h2 class="text-gray-500">Total Earnings</h2>
            <p class="text-xl font-bold">${{ number_format($totalEarnings, 2) }}</p>
        </div>

        <div class="p-4 bg-white shadow rounded">
            <h2 class="text-gray-500">Monthly Earnings</h2>
            <p class="text-xl font-bold">${{ number_format($monthlyEarnings, 2) }}</p>
        </div>

        <div class="p-4 bg-white shadow rounded">
            <h2 class="text-gray-500">Pending Payouts</h2>
            <p class="text-xl font-bold">${{ number_format($pendingPayouts, 2) }}</p>
        </div>
    </div>

    <div class="mt-6 p-4 bg-white shadow rounded">
        <h2 class="text-gray-500 mb-2">Active Providers: {{ $activeProviders }}</h2>

        <h3 class="text-gray-500 mt-4 mb-2">Top Services</h3>
        <ul class="list-disc list-inside">
            @foreach($popularServices as $service)
                <li>{{ $service->name }} ({{ $service->bookings_count }} bookings)</li>
            @endforeach
        </ul>
    </div>
</div>
@endsection
