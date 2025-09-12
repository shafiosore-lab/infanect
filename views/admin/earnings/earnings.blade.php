@extends('layouts.app')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">💰 Earnings & Financial Overview</h1>

    <!-- Earnings Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-indigo-600 text-white rounded-lg shadow p-6">
            <h2 class="text-sm font-medium uppercase">Total Earnings</h2>
            <p class="mt-2 text-2xl font-bold">{{ number_format($totalEarnings, 2) }} USD</p>
        </div>

        <div class="bg-green-600 text-white rounded-lg shadow p-6">
            <h2 class="text-sm font-medium uppercase">This Month</h2>
            <p class="mt-2 text-2xl font-bold">{{ number_format($monthlyEarnings, 2) }} USD</p>
        </div>

        <div class="bg-yellow-500 text-white rounded-lg shadow p-6">
            <h2 class="text-sm font-medium uppercase">Pending Payouts</h2>
            <p class="mt-2 text-2xl font-bold">{{ number_format($pendingPayouts, 2) }} USD</p>
        </div>
    </div>

    <!-- Recent Bookings Table -->
    <div class="bg-white shadow rounded-lg overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount Paid</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Scheduled</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($recentBookings as $booking)
                <tr>
                    <td class="px-6 py-4">{{ $booking->id }}</td>
                    <td class="px-6 py-4">{{ $booking->user->name ?? 'N/A' }}</td>
                    <td class="px-6 py-4">{{ $booking->service->name ?? 'N/A' }}</td>
                    <td class="px-6 py-4">{{ number_format($booking->amount_paid, 2) }} {{ $booking->currency ?? 'USD' }}</td>
                    <td class="px-6 py-4 capitalize">
                        <span class="px-2 py-1 rounded text-sm font-semibold
                            {{ $booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $booking->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                            {{ $booking->status }}
                        </span>
                    </td>
                    <td class="px-6 py-4">{{ $booking->scheduled_at?->format('d M Y, H:i') ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-400">No recent bookings yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
