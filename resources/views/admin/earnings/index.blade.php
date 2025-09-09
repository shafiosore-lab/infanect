@extends('layouts.app')

@section('content')
<div class="p-6 space-y-6">

    <!-- Page Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800">Earnings & Payouts Dashboard</h1>
        <div>
            <!-- Optional filters for future international rollout -->
            <form method="GET" action="{{ route('earnings.index') }}" class="flex space-x-2">
                <input type="text" name="country" value="{{ request('country') }}" placeholder="Country"
                       class="px-3 py-2 border rounded-lg">
                <select name="platform" class="px-3 py-2 border rounded-lg">
                    <option value="">All Platforms</option>
                    <option value="web" {{ request('platform')=='web' ? 'selected' : '' }}>Web</option>
                    <option value="mobile" {{ request('platform')=='mobile' ? 'selected' : '' }}>Mobile</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Filter</button>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-6">
        <div class="p-4 bg-white shadow rounded">
            <h3 class="font-semibold text-gray-600">Total Earnings</h3>
            <p class="text-2xl font-bold text-green-600">Ksh {{ number_format($earnings['total_earnings']) }}</p>
        </div>

        <div class="p-4 bg-white shadow rounded">
            <h3 class="font-semibold text-gray-600">Monthly Earnings</h3>
            <p class="text-2xl font-bold text-blue-600">Ksh {{ number_format($earnings['monthly_earnings']) }}</p>
        </div>

        <div class="p-4 bg-white shadow rounded">
            <h3 class="font-semibold text-gray-600">Pending Payouts</h3>
            <p class="text-2xl font-bold text-red-500">Ksh {{ number_format($earnings['pending_payouts']) }}</p>
        </div>

        <div class="p-4 bg-white shadow rounded">
            <h3 class="font-semibold text-gray-600">Returning Clients</h3>
            <p class="text-2xl font-bold text-indigo-600">{{ $earnings['returning_clients'] ?? 0 }}</p>
        </div>

        <div class="p-4 bg-white shadow rounded">
            <h3 class="font-semibold text-gray-600">Total Bookings</h3>
            <p class="text-2xl font-bold text-purple-600">{{ $earnings['total_bookings'] ?? 0 }}</p>
        </div>
    </div>

    <!-- Optional: Detailed Bookings Table -->
    <div class="mt-6 bg-white shadow rounded overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paid</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Scheduled</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($bookings as $booking)
                    <tr>
                        <td class="px-6 py-4">{{ $booking->id }}</td>
                        <td class="px-6 py-4">{{ $booking->user->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4">{{ $booking->service->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4">Ksh {{ number_format($booking->amount) }}</td>
                        <td class="px-6 py-4">Ksh {{ number_format($booking->amount_paid) }}</td>
                        <td class="px-6 py-4 capitalize">{{ $booking->status }}</td>
                        <td class="px-6 py-4">{{ $booking->scheduled_at?->format('d M Y H:i') ?? 'Not set' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
@endsection
