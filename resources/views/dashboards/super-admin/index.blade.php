@extends('layouts.super-admin')

@section('content')
<div class="min-h-screen bg-gray-50 p-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Super Admin Dashboard</h1>
        <p class="text-gray-600 mt-2">System overview and management console</p>
    </div>

    <!-- Overview Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Users -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Users</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['users']['total']) }}</p>
                    <p class="text-sm text-green-600 mt-1">
                        <i class="fas fa-arrow-up mr-1"></i>
                        +{{ $stats['users']['new_this_month'] }} this month
                    </p>
                </div>
                <div class="p-3 bg-blue-100 rounded-full">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4 flex justify-between text-sm">
                <span class="text-gray-500">Active: {{ $stats['users']['active'] }}</span>
                <span class="text-gray-500">Providers: {{ $stats['users']['providers'] }}</span>
            </div>
        </div>

        <!-- Total Providers -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Providers</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['providers']['total']) }}</p>
                    <p class="text-sm text-yellow-600 mt-1">
                        <i class="fas fa-clock mr-1"></i>
                        {{ $stats['providers']['pending'] }} pending verification
                    </p>
                </div>
                <div class="p-3 bg-green-100 rounded-full">
                    <i class="fas fa-user-md text-green-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4 flex justify-between text-sm">
                <span class="text-gray-500">Verified: {{ $stats['providers']['verified'] }}</span>
                <span class="text-gray-500">Bonding: {{ $stats['providers']['bonding'] }}</span>
            </div>
        </div>

        <!-- Total Bookings -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Bookings</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['bookings']['total']) }}</p>
                    <p class="text-sm text-blue-600 mt-1">
                        <i class="fas fa-calendar mr-1"></i>
                        {{ $stats['bookings']['this_month'] }} this month
                    </p>
                </div>
                <div class="p-3 bg-purple-100 rounded-full">
                    <i class="fas fa-calendar-check text-purple-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4 flex justify-between text-sm">
                <span class="text-gray-500">Completed: {{ $stats['bookings']['completed'] }}</span>
                <span class="text-gray-500">Pending: {{ $stats['bookings']['pending'] }}</span>
            </div>
        </div>

        <!-- Revenue -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                    <p class="text-3xl font-bold text-gray-900">KSh {{ number_format($stats['bookings']['revenue']) }}</p>
                    <p class="text-sm text-green-600 mt-1">
                        <i class="fas fa-chart-line mr-1"></i>
                        KSh {{ number_format($stats['transactions']['this_month_volume']) }} this month
                    </p>
                </div>
                <div class="p-3 bg-yellow-100 rounded-full">
                    <i class="fas fa-coins text-yellow-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4 flex justify-between text-sm">
                <span class="text-gray-500">Transactions: {{ $stats['transactions']['successful'] }}</span>
                <span class="text-gray-500">Rating: {{ $stats['reviews']['average_rating'] }}★</span>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- User Growth Chart -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">User Growth</h3>
                <div class="flex space-x-2">
                    <button class="px-3 py-1 text-sm bg-blue-100 text-blue-700 rounded-full">12M</button>
                    <button class="px-3 py-1 text-sm text-gray-500 hover:bg-gray-100 rounded-full">6M</button>
                </div>
            </div>
            <div class="h-64">
                <canvas id="userGrowthChart"></canvas>
            </div>
        </div>

        <!-- Revenue Growth Chart -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Revenue Growth</h3>
                <div class="flex space-x-2">
                    <button class="px-3 py-1 text-sm bg-green-100 text-green-700 rounded-full">12M</button>
                    <button class="px-3 py-1 text-sm text-gray-500 hover:bg-gray-100 rounded-full">6M</button>
                </div>
            </div>
            <div class="h-64">
                <canvas id="revenueGrowthChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Detailed Stats Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Provider Status -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Provider Status</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Verified Providers</span>
                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                        {{ $stats['providers']['verified'] }}
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Pending Verification</span>
                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium">
                        {{ $stats['providers']['pending'] }}
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Bonding Providers</span>
                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                        {{ $stats['providers']['bonding'] }}
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Professional Providers</span>
                    <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded-full text-sm font-medium">
                        {{ $stats['providers']['professional'] }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Transaction Status -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Transaction Status</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Successful</span>
                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                        {{ $stats['transactions']['successful'] }}
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Failed</span>
                    <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-sm font-medium">
                        {{ $stats['transactions']['failed'] }}
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Pending</span>
                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium">
                        {{ $stats['transactions']['pending'] }}
                    </span>
                </div>
                <div class="pt-2 border-t">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 font-medium">Total Volume</span>
                        <span class="text-lg font-bold text-gray-900">
                            KSh {{ number_format($stats['transactions']['volume']) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Health -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">System Health</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Database Size</span>
                    <span class="text-sm font-medium text-gray-900">{{ $stats['system']['database_size'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Cache Hit Rate</span>
                    <span class="text-sm font-medium text-green-600">{{ $stats['system']['cache_hit_rate'] }}%</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Uptime</span>
                    <span class="text-sm font-medium text-green-600">{{ $stats['system']['uptime'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Server Load</span>
                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                        {{ $stats['system']['server_load'] }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Users -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Users</h3>
            <div class="space-y-4">
                @forelse($recentActivity['users'] as $user)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <span class="text-sm font-medium text-blue-600">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $user->email }}</p>
                            </div>
                        </div>
                        <span class="text-xs text-gray-500">{{ $user->created_at->diffForHumans() }}</span>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">No recent users</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Bookings -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Bookings</h3>
            <div class="space-y-4">
                @forelse($recentActivity['bookings'] as $booking)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-calendar text-purple-600 text-xs"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $booking->user->name ?? 'Unknown User' }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    KSh {{ number_format($booking->amount) }} • {{ ucfirst($booking->status) }}
                                </p>
                            </div>
                        </div>
                        <span class="text-xs text-gray-500">{{ $booking->created_at->diffForHumans() }}</span>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">No recent bookings</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const chartData = @json($chartData);

        // User Growth Chart
        const userCtx = document.getElementById('userGrowthChart').getContext('2d');
        new Chart(userCtx, {
            type: 'line',
            data: {
                labels: chartData.months,
                datasets: [{
                    label: 'New Users',
                    data: chartData.userGrowth,
                    borderColor: '#3B82F6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Revenue Growth Chart
        const revenueCtx = document.getElementById('revenueGrowthChart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'bar',
            data: {
                labels: chartData.months,
                datasets: [{
                    label: 'Revenue (KSh)',
                    data: chartData.revenueGrowth,
                    backgroundColor: '#10B981',
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
    </script>
@endsection
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
