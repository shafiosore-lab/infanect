@extends('layouts.app')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-6">Earnings & Payouts Dashboard</h1>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="p-4 bg-white shadow rounded-lg">
            <h3 class="font-semibold">Total Earnings</h3>
            <p class="text-xl text-green-600">
                {{ $recentBookings->first()?->currency ?? 'USD' }} {{ number_format($earnings['total_earnings']) }}
            </p>
        </div>
        <div class="p-4 bg-white shadow rounded-lg">
            <h3 class="font-semibold">Monthly Earnings</h3>
            <p class="text-xl text-blue-600">
                {{ $recentBookings->first()?->currency ?? 'USD' }} {{ number_format($earnings['monthly_earnings']) }}
            </p>
        </div>
        <div class="p-4 bg-white shadow rounded-lg">
            <h3 class="font-semibold">Pending Payouts</h3>
            <p class="text-xl text-red-500">
                {{ $recentBookings->first()?->currency ?? 'USD' }} {{ number_format($earnings['pending_payouts']) }}
            </p>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Monthly Earnings Trend -->
        <div class="p-6 bg-white shadow rounded-lg">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Monthly Earnings Trend</h3>
            <canvas id="monthlyEarningsChart" height="200"></canvas>
        </div>

        <!-- Booking Status Breakdown -->
        <div class="p-6 bg-white shadow rounded-lg">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Booking Status Breakdown</h3>
            <canvas id="bookingStatusChart" height="200"></canvas>
        </div>
    </div>

    <!-- Recent Bookings -->
    <div class="p-6 bg-white shadow rounded-lg">
        <h3 class="text-lg font-semibold mb-4">Recent Bookings</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount Paid</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Scheduled At</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($recentBookings as $booking)
                        <tr>
                            <td class="px-6 py-4">{{ $booking->id }}</td>
                            <td class="px-6 py-4">{{ $booking->user->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4">{{ $booking->service->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4">{{ $booking->currency ?? 'USD' }} {{ number_format($booking->amount_paid) }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded-full text-white
                                    {{ $booking->status == 'pending' ? 'bg-yellow-500' : '' }}
                                    {{ $booking->status == 'confirmed' ? 'bg-green-500' : '' }}
                                    {{ $booking->status == 'cancelled' ? 'bg-red-500' : '' }}
                                    {{ $booking->status == 'refunded' ? 'bg-gray-500' : '' }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">{{ $booking->scheduled_at?->format('d M Y H:i') ?? 'N/A' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Monthly Earnings Line Chart
    const monthlyCtx = document.getElementById('monthlyEarningsChart').getContext('2d');
    new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($earnings['monthly_labels']) !!},
            datasets: [{
                label: 'Earnings',
                data: {!! json_encode($earnings['monthly_values']) !!},
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.2)',
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: true } },
            interaction: { mode: 'nearest', axis: 'x', intersect: false }
        }
    });

    // Booking Status Doughnut Chart
    const statusCtx = document.getElementById('bookingStatusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode(array_keys($earnings['status_breakdown'])) !!},
            datasets: [{
                data: {!! json_encode(array_values($earnings['status_breakdown'])) !!},
                backgroundColor: ['#16a34a','#facc15','#ef4444','#6b7280'],
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom' } }
        }
    });
</script>
@endsection
