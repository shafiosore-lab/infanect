@extends('layouts.provider-professional')

@section('page-title', 'Professional Dashboard')
@section('page-description', 'Monitor your practice performance and manage client relationships')

@section('content')
<div class="container-fluid py-4">
    {{-- Dashboard Header with KYC Alert --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold text-primary">Welcome back, {{ auth()->user()->name }}! 🩺</h1>
            <p class="text-muted mb-0">{{ now()->format('l, F j, Y') }} • Your wellness practice overview</p>
        </div>
        @if($providerData['kyc_status'] !== 'approved')
            <div class="alert alert-warning alert-sm mb-0">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <a href="{{ route('provider.register') }}" class="alert-link">Complete KYC to accept bookings</a>
            </div>
        @endif
    </div>

    {{-- Enhanced Stats Cards with Trends --}}
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4 p-3 stats-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="icon bg-primary text-white rounded-circle p-3 me-3">
                            <i class="fas fa-calendar-check fa-lg"></i>
                        </div>
                        <div>
                            <h4 class="mb-1">{{ $stats['upcoming_appointments'] ?? 0 }}</h4>
                            <small class="text-muted">This Week</small>
                        </div>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-success-soft text-success">
                            +{{ $stats['appointments_growth'] ?? 0 }}%
                        </span>
                        <div class="mt-1">
                            <canvas class="trend-chart" width="50" height="20" data-values="{{ json_encode($stats['appointments_trend'] ?? []) }}"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4 p-3 stats-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="icon bg-success text-white rounded-circle p-3 me-3">
                            <i class="fas fa-users fa-lg"></i>
                        </div>
                        <div>
                            <h4 class="mb-1">{{ $stats['active_clients'] ?? 0 }}</h4>
                            <small class="text-muted">Active Clients</small>
                        </div>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-info-soft text-info">
                            +{{ $stats['clients_growth'] ?? 0 }}
                        </span>
                        <div class="mt-1">
                            <canvas class="trend-chart" width="50" height="20" data-values="{{ json_encode($stats['clients_trend'] ?? []) }}"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4 p-3 stats-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="icon bg-warning text-white rounded-circle p-3 me-3">
                            <i class="fas fa-dollar-sign fa-lg"></i>
                        </div>
                        <div>
                            <h4 class="mb-1">${{ number_format($stats['monthly_earnings'] ?? 0, 2) }}</h4>
                            <small class="text-muted">This Month</small>
                        </div>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-warning-soft text-warning">
                            +{{ $stats['earnings_growth'] ?? 0 }}%
                        </span>
                        <div class="mt-1">
                            <canvas class="trend-chart" width="50" height="20" data-values="{{ json_encode($stats['earnings_trend'] ?? []) }}"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4 p-3 stats-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="icon bg-info text-white rounded-circle p-3 me-3">
                            <i class="fas fa-heart fa-lg"></i>
                        </div>
                        <div>
                            <h4 class="mb-1">{{ number_format($stats['satisfaction_score'] ?? 0, 1) }}</h4>
                            <small class="text-muted">Satisfaction</small>
                        </div>
                    </div>
                    <div class="text-end">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star {{ $i <= ($stats['satisfaction_score'] ?? 0) ? 'text-warning' : 'text-muted' }}"></i>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Analytics Dashboard Row --}}
    <div class="row g-4 mb-4">
        {{-- Revenue Analytics Chart --}}
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header border-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">📊 Revenue Analytics</h5>
                    <div class="btn-group btn-group-sm" role="group">
                        <input type="radio" class="btn-check" name="revenue-period" id="revenue-week" autocomplete="off">
                        <label class="btn btn-outline-primary" for="revenue-week">7D</label>

                        <input type="radio" class="btn-check" name="revenue-period" id="revenue-month" autocomplete="off" checked>
                        <label class="btn btn-outline-primary" for="revenue-month">30D</label>

                        <input type="radio" class="btn-check" name="revenue-period" id="revenue-year" autocomplete="off">
                        <label class="btn btn-outline-primary" for="revenue-year">1Y</label>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" height="300"></canvas>
                </div>
            </div>
        </div>

        {{-- Client Mood Analytics --}}
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header border-0">
                    <h5 class="mb-0">🧠 Client Wellness Insights</h5>
                </div>
                <div class="card-body">
                    <canvas id="moodChart" height="200"></canvas>
                    <div class="mt-3">
                        @if(isset($moodInsights) && $moodInsights->count() > 0)
                            @foreach($moodInsights->take(3) as $insight)
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge bg-{{ $insight['color'] }}-soft text-{{ $insight['color'] }}">
                                        {{ $insight['mood'] }}
                                    </span>
                                    <span class="text-muted small">{{ $insight['percentage'] }}%</span>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted small">No mood data available yet.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions Enhanced --}}
    <div class="row g-4 mb-4">
        <div class="col-md-2">
            <a href="{{ $safeRoute('bookings.create') }}" class="card text-center text-decoration-none shadow-sm border-0 rounded-4 p-4 hover-lift quick-action">
                <i class="fas fa-plus fa-2x mb-2 text-primary"></i>
                <h6 class="fw-semibold mb-0">New Booking</h6>
                <small class="text-muted">Schedule session</small>
            </a>
        </div>
        <div class="col-md-2">
            <a href="{{ $safeRoute('clients.index') }}" class="card text-center text-decoration-none shadow-sm border-0 rounded-4 p-4 hover-lift quick-action">
                <i class="fas fa-users fa-2x mb-2 text-success"></i>
                <h6 class="fw-semibold mb-0">My Clients</h6>
                <small class="text-muted">{{ $stats['active_clients'] ?? 0 }} active</small>
            </a>
        </div>
        <div class="col-md-2">
            <a href="{{ $safeRoute('services.index') }}" class="card text-center text-decoration-none shadow-sm border-0 rounded-4 p-4 hover-lift quick-action">
                <i class="fas fa-cogs fa-2x mb-2 text-warning"></i>
                <h6 class="fw-semibold mb-0">Services</h6>
                <small class="text-muted">{{ $providerData['stats']['total_services'] ?? 0 }} active</small>
            </a>
        </div>
        <div class="col-md-2">
            <button onclick="openBulkSMS()" class="card text-center text-decoration-none shadow-sm border-0 rounded-4 p-4 hover-lift quick-action w-100 border-0 bg-transparent">
                <i class="fas fa-sms fa-2x mb-2 text-info"></i>
                <h6 class="fw-semibold mb-0">Bulk SMS</h6>
                <small class="text-muted">Contact clients</small>
            </button>
        </div>
        <div class="col-md-2">
            <a href="{{ $safeRoute('ai.chat') }}" class="card text-center text-decoration-none shadow-sm border-0 rounded-4 p-4 hover-lift quick-action">
                <i class="fas fa-robot fa-2x mb-2 text-secondary"></i>
                <h6 class="fw-semibold mb-0">AI Assistant</h6>
                <small class="text-muted">Get insights</small>
            </a>
        </div>
        <div class="col-md-2">
            <a href="{{ $safeRoute('training.index') }}" class="card text-center text-decoration-none shadow-sm border-0 rounded-4 p-4 hover-lift quick-action">
                <i class="fas fa-graduation-cap fa-2x mb-2 text-purple"></i>
                <h6 class="fw-semibold mb-0">Training</h6>
                <small class="text-muted">Learn & grow</small>
            </a>
        </div>
    </div>

    {{-- Main Content Row --}}
    <div class="row g-4">
        {{-- Recent Appointments Enhanced --}}
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header border-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">📅 Recent Appointments</h5>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-primary" onclick="openQuickBooking()">
                            <i class="fas fa-plus me-1"></i>Quick Book
                        </button>
                        <a href="{{ $safeRoute('bookings.index') }}" class="btn btn-sm btn-outline-secondary">View All</a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0">Client</th>
                                    <th class="border-0">Service</th>
                                    <th class="border-0">Date & Time</th>
                                    <th class="border-0">Status</th>
                                    <th class="border-0">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentBookings ?? [] as $booking)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-primary-soft rounded-circle d-flex align-items-center justify-content-center me-2">
                                                    {{ strtoupper(substr($booking->user->name ?? 'U', 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="fw-semibold">{{ $booking->user->name ?? 'Unknown' }}</div>
                                                    <small class="text-muted">{{ $booking->user->phone ?? 'No phone' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fw-medium">{{ $booking->service->name ?? 'General Session' }}</span>
                                        </td>
                                        <td>
                                            <div>
                                                <div class="fw-medium">{{ \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y') }}</div>
                                                <small class="text-muted">{{ $booking->booking_time ? \Carbon\Carbon::parse($booking->booking_time)->format('g:i A') : 'Time TBD' }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'pending' => 'warning',
                                                    'confirmed' => 'success',
                                                    'completed' => 'primary',
                                                    'cancelled' => 'danger'
                                                ];
                                                $statusColor = $statusColors[$booking->status] ?? 'secondary';
                                            @endphp
                                            <span class="badge bg-{{ $statusColor }}-soft text-{{ $statusColor }}">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                    Actions
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="{{ $safeRoute('bookings.show', $booking->id) }}">
                                                        <i class="fas fa-eye me-2"></i>View Details
                                                    </a></li>
                                                    @if($booking->status === 'pending')
                                                        <li><a class="dropdown-item text-success" href="#" onclick="confirmBooking({{ $booking->id }})">
                                                            <i class="fas fa-check me-2"></i>Confirm
                                                        </a></li>
                                                    @endif
                                                    <li><a class="dropdown-item" href="#" onclick="rescheduleBooking({{ $booking->id }})">
                                                        <i class="fas fa-calendar me-2"></i>Reschedule
                                                    </a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            <i class="fas fa-calendar-plus fa-2x mb-2 d-block"></i>
                                            No recent appointments. <a href="{{ $safeRoute('bookings.create') }}">Schedule your first session</a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar with Activities and Notifications --}}
        <div class="col-lg-4">
            {{-- Today's Schedule --}}
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-header border-0">
                    <h5 class="mb-0">📋 Today's Schedule</h5>
                </div>
                <div class="card-body">
                    @forelse($todaysBookings ?? [] as $booking)
                        <div class="d-flex align-items-center mb-3 p-2 bg-light rounded">
                            <div class="text-primary fw-bold me-3">
                                {{ $booking->booking_time ? \Carbon\Carbon::parse($booking->booking_time)->format('g:i A') : 'TBD' }}
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-semibold">{{ $booking->user->name ?? 'Client' }}</div>
                                <small class="text-muted">{{ $booking->service->name ?? 'Session' }}</small>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-3">
                            <i class="fas fa-sun fa-2x mb-2 d-block"></i>
                            No appointments today. Take a well-deserved break!
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Quick Stats --}}
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header border-0">
                    <h5 class="mb-0">⚡ Quick Insights</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center g-3">
                        <div class="col-6">
                            <div class="border rounded p-2">
                                <h4 class="text-primary mb-1">{{ $stats['total_sessions'] ?? 0 }}</h4>
                                <small class="text-muted">Total Sessions</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-2">
                                <h4 class="text-success mb-1">{{ $stats['completion_rate'] ?? 0 }}%</h4>
                                <small class="text-muted">Completion Rate</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-2">
                                <h4 class="text-warning mb-1">${{ $stats['avg_session_value'] ?? 0 }}</h4>
                                <small class="text-muted">Avg Session Value</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-2">
                                <h4 class="text-info mb-1">{{ $stats['response_time'] ?? 0 }}h</h4>
                                <small class="text-muted">Response Time</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.stats-card:hover { transform: translateY(-2px); transition: all 0.3s ease; }
.hover-lift:hover { transform: translateY(-3px); transition: all 0.3s ease; }
.quick-action { transition: all 0.2s ease; }
.quick-action:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
.bg-primary-soft { background-color: rgba(13, 110, 253, 0.1); }
.bg-success-soft { background-color: rgba(25, 135, 84, 0.1); }
.bg-warning-soft { background-color: rgba(255, 193, 7, 0.1); }
.bg-info-soft { background-color: rgba(13, 202, 240, 0.1); }
.bg-danger-soft { background-color: rgba(220, 53, 69, 0.1); }
.bg-secondary-soft { background-color: rgba(108, 117, 125, 0.1); }
.text-purple { color: #6f42c1; }
.avatar-sm { width: 35px; height: 35px; font-size: 0.875rem; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart');
    if (revenueCtx) {
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: @json($chartData['revenue']['labels'] ?? []),
                datasets: [{
                    label: 'Revenue',
                    data: @json($chartData['revenue']['data'] ?? []),
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { callback: value => '$' + value } }
                }
            }
        });
    }

    // Mood Chart
    const moodCtx = document.getElementById('moodChart');
    if (moodCtx) {
        new Chart(moodCtx, {
            type: 'doughnut',
            data: {
                labels: @json($chartData['mood']['labels'] ?? []),
                datasets: [{
                    data: @json($chartData['mood']['data'] ?? []),
                    backgroundColor: ['#28a745', '#ffc107', '#dc3545', '#17a2b8', '#6c757d']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } }
            }
        });
    }

    // Mini trend charts
    document.querySelectorAll('.trend-chart').forEach(canvas => {
        const values = JSON.parse(canvas.dataset.values || '[]');
        new Chart(canvas, {
            type: 'line',
            data: {
                labels: Array(values.length).fill(''),
                datasets: [{
                    data: values,
                    borderColor: '#0d6efd',
                    borderWidth: 1,
                    fill: false,
                    pointRadius: 0
                }]
            },
            options: {
                responsive: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { display: false },
                    y: { display: false }
                }
            }
        });
    });
});

// Booking actions
function confirmBooking(bookingId) {
    // Implementation for confirming booking
    console.log('Confirming booking:', bookingId);
}

function rescheduleBooking(bookingId) {
    // Implementation for rescheduling
    console.log('Rescheduling booking:', bookingId);
}
</script>
@endpush
@endsection
