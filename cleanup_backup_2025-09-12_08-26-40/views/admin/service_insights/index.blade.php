@extends('layouts.app')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">📊 Service Insights</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-indigo-600 text-white rounded-lg shadow p-6">
            <h2 class="text-sm font-medium uppercase">Total Services</h2>
            <p class="mt-2 text-2xl font-bold">{{ $totalServices }}</p>
        </div>

        <div class="bg-green-600 text-white rounded-lg shadow p-6">
            <h2 class="text-sm font-medium uppercase">Active Services</h2>
            <p class="mt-2 text-2xl font-bold">{{ $activeServices }}</p>
        </div>

        <div class="bg-yellow-500 text-white rounded-lg shadow p-6">
            <h2 class="text-sm font-medium uppercase">Services with Bookings</h2>
            <p class="mt-2 text-2xl font-bold">{{ $bookedServices }}</p>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Provider</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bookings Count</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Earnings</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($serviceInsights as $insight)
                <tr>
                    <td class="px-6 py-4">{{ $insight->service->name ?? 'N/A' }}</td>
                    <td class="px-6 py-4">{{ $insight->service->provider->name ?? 'N/A' }}</td>
                    <td class="px-6 py-4">{{ $insight->bookings_count }}</td>
                    <td class="px-6 py-4">{{ number_format($insight->total_earnings, 2) }} USD</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-gray-400">No insights available.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
