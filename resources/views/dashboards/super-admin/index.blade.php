@extends('layouts.super-admin')

@section('page-title', 'Super Admin Dashboard')
@section('page-description', 'Complete system overview and management console')

@section('content')
<div class="container-fluid py-4">
    {{-- Dashboard Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold text-primary">
                <i class="fas fa-crown me-2"></i>System Overview
            </h1>
            <p class="text-muted mb-0">{{ now()->format('l, F j, Y') }} • Complete platform analytics</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary btn-sm" onclick="refreshDashboard()">
                <i class="fas fa-sync-alt me-1"></i>Refresh
            </button>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#systemHealthModal">
                <i class="fas fa-heartbeat me-1"></i>System Health
            </button>
        </div>
    </div>

    {{-- Enhanced Stats Cards with Trends --}}
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4 p-3 stats-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="icon bg-primary text-white rounded-circle p-3 me-3">
                            <i class="fas fa-users fa-lg"></i>
                        </div>
                        <div>
                            <h4 class="mb-1">{{ number_format($stats['users'] ?? 0) }}</h4>
                            <small class="text-muted">Total Users</small>
                        </div>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-success-soft text-success">
                            +{{ $stats['users_growth'] ?? 0 }}%
                        </span>
                        <canvas class="trend-chart mt-1" width="50" height="20" data-values="{{ json_encode($stats['users_trend'] ?? []) }}"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4 p-3 stats-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="icon bg-success text-white rounded-circle p-3 me-3">
                            <i class="fas fa-user-md fa-lg"></i>
                        </div>
                        <div>
                            <h4 class="mb-1">{{ number_format($stats['providers'] ?? 0) }}</h4>
                            <small class="text-muted">Active Providers</small>
                        </div>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-info-soft text-info">
                            {{ $stats['verified_providers'] ?? 0 }} verified
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4 p-3 stats-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="icon bg-warning text-white rounded-circle p-3 me-3">
                            <i class="fas fa-file-alt fa-lg"></i>
                        </div>
                        <div>
                            <h4 class="mb-1">{{ number_format($stats['pending_documents'] ?? 0) }}</h4>
                            <small class="text-muted">Pending KYC</small>
                        </div>
                    </div>
                    <div class="text-end">
                        @if(($stats['pending_documents'] ?? 0) > 0)
                            <span class="badge bg-danger text-white">
                                <i class="fas fa-exclamation-triangle me-1"></i>Action Required
                            </span>
                        @else
                            <span class="badge bg-success-soft text-success">
                                <i class="fas fa-check me-1"></i>All Clear
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4 p-3 stats-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="icon bg-info text-white rounded-circle p-3 me-3">
                            <i class="fas fa-dollar-sign fa-lg"></i>
                        </div>
                        <div>
                            <h4 class="mb-1">${{ number_format($stats['revenue'] ?? 0, 2) }}</h4>
                            <small class="text-muted">Platform Revenue</small>
                        </div>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-warning-soft text-warning">
                            +{{ $stats['revenue_growth'] ?? 0 }}%
                        </span>
                        <canvas class="trend-chart mt-1" width="50" height="20" data-values="{{ json_encode($stats['revenue_trend'] ?? []) }}"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Analytics Dashboard Row --}}
    <div class="row g-4 mb-4">
        {{-- Platform Analytics Chart --}}
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header border-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">📈 Platform Analytics</h5>
                    <div class="btn-group btn-group-sm" role="group">
                        <input type="radio" class="btn-check" name="analytics-period" id="analytics-week" autocomplete="off">
                        <label class="btn btn-outline-primary" for="analytics-week">7D</label>

                        <input type="radio" class="btn-check" name="analytics-period" id="analytics-month" autocomplete="off" checked>
                        <label class="btn btn-outline-primary" for="analytics-month">30D</label>

                        <input type="radio" class="btn-check" name="analytics-period" id="analytics-year" autocomplete="off">
                        <label class="btn btn-outline-primary" for="analytics-year">1Y</label>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="platformChart" height="300"></canvas>
                </div>
            </div>
        </div>

        {{-- System Health & Alerts --}}
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header border-0">
                    <h5 class="mb-0">🔧 System Health</h5>
                </div>
                <div class="card-body">
                    {{-- Health Metrics --}}
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <small class="fw-medium">Database Performance</small>
                            <span class="badge bg-success-soft text-success">Excellent</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-success" style="width: {{ $systemHealth['database_performance'] ?? 95 }}%"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <small class="fw-medium">Server Load</small>
                            <span class="badge bg-{{ ($systemHealth['server_load'] ?? 20) > 80 ? 'danger' : 'success' }}-soft text-{{ ($systemHealth['server_load'] ?? 20) > 80 ? 'danger' : 'success' }}">
                                {{ ($systemHealth['server_load'] ?? 20) > 80 ? 'High' : 'Normal' }}
                            </span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-{{ ($systemHealth['server_load'] ?? 20) > 80 ? 'danger' : 'success' }}" style="width: {{ $systemHealth['server_load'] ?? 20 }}%"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <small class="fw-medium">Cache Hit Rate</small>
                            <span class="badge bg-info-soft text-info">{{ $systemHealth['cache_hit_rate'] ?? 87 }}%</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-info" style="width: {{ $systemHealth['cache_hit_rate'] ?? 87 }}%"></div>
                        </div>
                    </div>

                    {{-- Recent Alerts --}}
                    <div class="mt-4">
                        <h6 class="fw-semibold mb-3">Recent Alerts</h6>
                        @forelse($recentAlerts ?? [] as $alert)
                            <div class="alert alert-{{ $alert['type'] }} alert-sm p-2 mb-2">
                                <small><i class="fas fa-{{ $alert['icon'] }} me-1"></i>{{ $alert['message'] }}</small>
                            </div>
                        @empty
                            <div class="text-center text-muted py-2">
                                <i class="fas fa-shield-check fa-lg mb-1 d-block text-success"></i>
                                <small>All systems operational</small>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Management Actions --}}
    <div class="row g-4 mb-4">
        <div class="col-md-2">
            <a href="{{ route('admin.users.index') }}" class="card text-center text-decoration-none shadow-sm border-0 rounded-4 p-4 hover-lift quick-action">
                <i class="fas fa-users-cog fa-2x mb-2 text-primary"></i>
                <h6 class="fw-semibold mb-0">User Management</h6>
                <small class="text-muted">{{ $stats['users'] ?? 0 }} users</small>
            </a>
        </div>
        <div class="col-md-2">
            <a href="{{ route('admin.providers.index') }}" class="card text-center text-decoration-none shadow-sm border-0 rounded-4 p-4 hover-lift quick-action">
                <i class="fas fa-user-check fa-2x mb-2 text-success"></i>
                <h6 class="fw-semibold mb-0">Provider KYC</h6>
                <small class="text-muted">{{ $stats['pending_documents'] ?? 0 }} pending</small>
            </a>
        </div>
        <div class="col-md-2">
            <a href="{{ route('admin.bookings.index') }}" class="card text-center text-decoration-none shadow-sm border-0 rounded-4 p-4 hover-lift quick-action">
                <i class="fas fa-calendar-alt fa-2x mb-2 text-warning"></i>
                <h6 class="fw-semibold mb-0">Bookings</h6>
                <small class="text-muted">{{ $stats['bookings'] ?? 0 }} total</small>
            </a>
        </div>
        <div class="col-md-2">
            <a href="{{ route('admin.analytics.index') }}" class="card text-center text-decoration-none shadow-sm border-0 rounded-4 p-4 hover-lift quick-action">
                <i class="fas fa-chart-line fa-2x mb-2 text-info"></i>
                <h6 class="fw-semibold mb-0">Analytics</h6>
                <small class="text-muted">Deep insights</small>
            </a>
        </div>
        <div class="col-md-2">
            <a href="{{ route('admin.settings.index') }}" class="card text-center text-decoration-none shadow-sm border-0 rounded-4 p-4 hover-lift quick-action">
                <i class="fas fa-cogs fa-2x mb-2 text-secondary"></i>
                <h6 class="fw-semibold mb-0">Settings</h6>
                <small class="text-muted">System config</small>
            </a>
        </div>
        <div class="col-md-2">
            <button onclick="showBulkActions()" class="card text-center text-decoration-none shadow-sm border-0 rounded-4 p-4 hover-lift quick-action w-100 border-0 bg-transparent">
                <i class="fas fa-bolt fa-2x mb-2 text-purple"></i>
                <h6 class="fw-semibold mb-0">Bulk Actions</h6>
                <small class="text-muted">Mass operations</small>
            </button>
        </div>
    </div>

    {{-- Main Content Row --}}
    <div class="row g-4">
        {{-- Recent Activity --}}
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header border-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">🕒 Recent Activity</h5>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-primary" onclick="exportActivity()">
                            <i class="fas fa-download me-1"></i>Export
                        </button>
                        <a href="{{ route('admin.activity.index') }}" class="btn btn-sm btn-outline-secondary">View All</a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0">User</th>
                                    <th class="border-0">Action</th>
                                    <th class="border-0">Resource</th>
                                    <th class="border-0">Time</th>
                                    <th class="border-0">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentActivity ?? [] as $activity)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-primary-soft rounded-circle d-flex align-items-center justify-content-center me-2">
                                                    {{ strtoupper(substr($activity['user_name'] ?? 'U', 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="fw-semibold">{{ $activity['user_name'] ?? 'System' }}</div>
                                                    <small class="text-muted">{{ $activity['user_role'] ?? 'Unknown' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fw-medium">{{ $activity['action'] ?? 'Unknown' }}</span>
                                        </td>
                                        <td>{{ $activity['resource'] ?? 'N/A' }}</td>
                                        <td>
                                            <small class="text-muted">
                                                {{ isset($activity['created_at']) ? \Carbon\Carbon::parse($activity['created_at'])->diffForHumans() : 'Unknown' }}
                                            </small>
                                        </td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'success' => 'success',
                                                    'pending' => 'warning',
                                                    'failed' => 'danger',
                                                    'info' => 'info'
                                                ];
                                                $statusColor = $statusColors[$activity['status'] ?? 'info'] ?? 'secondary';
                                            @endphp
                                            <span class="badge bg-{{ $statusColor }}-soft text-{{ $statusColor }}">
                                                {{ ucfirst($activity['status'] ?? 'Unknown') }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            <i class="fas fa-history fa-2x mb-2 d-block"></i>
                                            No recent activity found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Stats & Notifications --}}
        <div class="col-lg-4">
            {{-- Platform Overview --}}
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-header border-0">
                    <h5 class="mb-0">📊 Platform Overview</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center g-3">
                        <div class="col-6">
                            <div class="border rounded p-2">
                                <h4 class="text-primary mb-1">{{ $stats['active_sessions'] ?? 0 }}</h4>
                                <small class="text-muted">Active Sessions</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-2">
                                <h4 class="text-success mb-1">{{ $stats['uptime'] ?? '99.9' }}%</h4>
                                <small class="text-muted">Uptime</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-2">
                                <h4 class="text-warning mb-1">{{ $stats['avg_response'] ?? 150 }}ms</h4>
                                <small class="text-muted">Response Time</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-2">
                                <h4 class="text-info mb-1">{{ $stats['storage_used'] ?? 45 }}%</h4>
                                <small class="text-muted">Storage Used</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Recent Notifications --}}
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header border-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">🔔 Notifications</h5>
                    <button class="btn btn-sm btn-outline-secondary" onclick="markAllRead()">Mark All Read</button>
                </div>
                <div class="card-body">
                    @forelse($notifications ?? [] as $notification)
                        <div class="d-flex align-items-start mb-3 p-2 bg-light rounded">
                            <div class="me-3">
                                <i class="fas fa-{{ $notification['icon'] ?? 'bell' }} text-{{ $notification['type'] ?? 'info' }}"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-semibold small">{{ $notification['title'] ?? 'Notification' }}</div>
                                <small class="text-muted">{{ $notification['message'] ?? 'No message' }}</small>
                                <div class="text-muted small mt-1">
                                    {{ isset($notification['created_at']) ? \Carbon\Carbon::parse($notification['created_at'])->diffForHumans() : 'Unknown time' }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-3">
                            <i class="fas fa-bell-slash fa-2x mb-2 d-block"></i>
                            No new notifications
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Toast Container for Real-time Notifications --}}
<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1050;">
    <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <i class="fas fa-bell text-primary me-2"></i>
            <strong class="me-auto">System Alert</strong>
            <small class="text-muted">now</small>
            <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body" id="toastBody">
            <!-- Dynamic content -->
        </div>
    </div>
</div>

@push('styles')
<style>
.text-purple { color: #6f42c1; }
.avatar-sm { width: 35px; height: 35px; font-size: 0.875rem; }
.alert-sm { font-size: 0.875rem; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    loadPlatformChart();
    initRealTimeUpdates();
});

// Enhanced Chart.js implementation
function loadPlatformChart() {
    const ctx = document.getElementById('platformChart');
    if (!ctx) return;

    window.platformChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($chartData['labels'] ?? []),
            datasets: [
                {
                    label: 'Users',
                    data: @json($chartData['users'] ?? []),
                    borderColor: '#6366f1',
                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                    tension: 0.4
                },
                {
                    label: 'Providers',
                    data: @json($chartData['providers'] ?? []),
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.4
                },
                {
                    label: 'Revenue',
                    data: @json($chartData['revenue'] ?? []),
                    borderColor: '#f59e0b',
                    backgroundColor: 'rgba(245, 158, 11, 0.1)',
                    tension: 0.4,
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'top' },
                tooltip: { mode: 'index', intersect: false }
            },
            scales: {
                y: { beginAtZero: true, position: 'left' },
                y1: { type: 'linear', display: true, position: 'right' }
            }
        }
    });
}

// Real-time updates with enhanced error handling
function refreshDashboard() {
    fetch('{{ route('dashboard.stats.super') }}')
        .then(response => response.json())
        .then(data => updateDashboardStats(data))
        .catch(error => {
            console.error('Dashboard refresh failed:', error);
            showToast('Failed to refresh dashboard', 'danger');
        });
}

function updateDashboardStats(data) {
    // Update stat cards
    Object.keys(data).forEach(key => {
        const element = document.querySelector(`[data-stat="${key}"]`);
        if (element) element.textContent = data[key];
    });

    // Update chart if needed
    if (window.platformChart && data.chartData) {
        window.platformChart.data.datasets.forEach((dataset, i) => {
            if (data.chartData[dataset.label.toLowerCase()]) {
                dataset.data = data.chartData[dataset.label.toLowerCase()];
            }
        });
        window.platformChart.update('none');
    }
}

// Enhanced toast notifications
function showToast(message, type = 'info') {
    const toastBody = document.getElementById('toastBody');
    const toast = document.getElementById('liveToast');

    toastBody.innerHTML = `<i class="fas fa-${type === 'success' ? 'check' : 'info'}-circle me-2"></i>${message}`;

    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
}

// Real-time Echo listener with better UX
@if(env('PUSHER_APP_KEY'))
function initRealTimeUpdates() {
    try {
        if (window.Echo) {
            window.Echo.channel('admin-notifications')
                .listen('SystemAlert', (e) => {
                    showToast(e.message, e.type);
                    refreshDashboard();
                })
                .listen('DocumentUploaded', (e) => {
                    showToast(`New document uploaded: ${e.name}`, 'info');
                    // Update pending documents count
                    refreshDashboard();
                });
        }
    } catch(e) {
        console.warn('Real-time updates not available:', e);
    }
}
@endif

// Bulk actions and other functions
function showBulkActions() {
    // Implementation for bulk actions modal
    console.log('Showing bulk actions');
}

function exportActivity() {
    // Implementation for exporting activity
    window.open('{{ route('admin.activity.export') }}', '_blank');
}

function markAllRead() {
    // Implementation for marking notifications as read
    fetch('{{ route('admin.notifications.mark-read') }}', { method: 'POST' })
        .then(() => showToast('All notifications marked as read', 'success'))
        .catch(() => showToast('Failed to mark notifications', 'danger'));
}

// Auto-refresh every 30 seconds
setInterval(refreshDashboard, 30000);
</script>
@endpush
@endsection
