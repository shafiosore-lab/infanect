@extends('layouts.super-admin')

@section('content')
<!-- FontAwesome Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="super-admin-dashboard">
    <!-- Header Section -->
    <div class="dashboard-header bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-700 text-white p-6 rounded-3 shadow-lg mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold mb-2">
                    <i class="fas fa-crown mr-3"></i>Super Admin Dashboard
                </h1>
                <p class="text-blue-100">Welcome back, {{ auth()->user()->name }}! Here's your platform overview.</p>
            </div>
            <div class="text-right">
                <div class="text-sm opacity-75">Last updated</div>
                <div class="font-semibold" id="lastUpdated">{{ now()->format('M j, H:i') }}</div>
            </div>
        </div>
    </div>

    <!-- KPI Cards Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Users -->
        <div class="kpi-card bg-gradient-to-br from-blue-500 to-blue-600 text-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total Users</p>
                    <p class="text-3xl font-bold" id="totalUsers">{{ $stats['users'] ?? 0 }}</p>
                    <p class="text-xs text-blue-200 mt-1">
                        <i class="fas fa-arrow-up text-green-300"></i>
                        <span class="text-green-300">+12%</span> from last month
                    </p>
                </div>
                <div class="bg-white bg-opacity-20 p-3 rounded-full">
                    <i class="fas fa-users text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Active Providers -->
        <div class="kpi-card bg-gradient-to-br from-green-500 to-green-600 text-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Active Providers</p>
                    <p class="text-3xl font-bold" id="activeProviders">{{ $stats['providers'] ?? 0 }}</p>
                    <p class="text-xs text-green-200 mt-1">
                        <i class="fas fa-arrow-up text-green-300"></i>
                        <span class="text-green-300">+8%</span> from last month
                    </p>
                </div>
                <div class="bg-white bg-opacity-20 p-3 rounded-full">
                    <i class="fas fa-user-md text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="kpi-card bg-gradient-to-br from-purple-500 to-purple-600 text-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Total Revenue</p>
                    <p class="text-3xl font-bold" id="totalRevenue">${{ number_format($stats['revenue'] ?? 0, 2) }}</p>
                    <p class="text-xs text-purple-200 mt-1">
                        <i class="fas fa-arrow-up text-green-300"></i>
                        <span class="text-green-300">+15%</span> from last month
                    </p>
                </div>
                <div class="bg-white bg-opacity-20 p-3 rounded-full">
                    <i class="fas fa-dollar-sign text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Pending Approvals -->
        <div class="kpi-card bg-gradient-to-br from-orange-500 to-orange-600 text-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm font-medium">Pending Approvals</p>
                    <p class="text-3xl font-bold" id="pendingApprovals">{{ $stats['pending_documents'] ?? 0 }}</p>
                    <p class="text-xs text-orange-200 mt-1">
                        <i class="fas fa-clock text-yellow-300"></i>
                        <span class="text-yellow-300">Requires attention</span>
                    </p>
                </div>
                <div class="bg-white bg-opacity-20 p-3 rounded-full">
                    <i class="fas fa-clock text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Platform Growth Chart -->
        <div class="chart-card bg-white p-6 rounded-2xl shadow-lg">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-800">
                    <i class="fas fa-chart-line text-blue-500 mr-2"></i>Platform Growth
                </h3>
                <select class="form-select form-select-sm w-auto">
                    <option>Last 7 days</option>
                    <option>Last 30 days</option>
                    <option>Last 90 days</option>
                </select>
            </div>
            <canvas id="growthChart" height="200"></canvas>
        </div>

        <!-- Revenue Breakdown -->
        <div class="chart-card bg-white p-6 rounded-2xl shadow-lg">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-800">
                    <i class="fas fa-pie-chart text-purple-500 mr-2"></i>Revenue Breakdown
                </h3>
                <div class="text-sm text-gray-500">This month</div>
            </div>
            <canvas id="revenueChart" height="200"></canvas>
        </div>
    </div>

    <!-- Analytics Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- User Demographics -->
        <div class="analytics-card bg-white p-6 rounded-2xl shadow-lg">
            <h4 class="text-lg font-bold text-gray-800 mb-4">
                <i class="fas fa-users text-green-500 mr-2"></i>User Demographics
            </h4>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Clients</span>
                    <span class="font-semibold">{{ $stats['clients'] ?? 45 }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Service Providers</span>
                    <span class="font-semibold">{{ $stats['service_providers'] ?? 12 }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Activity Providers</span>
                    <span class="font-semibold">{{ $stats['activity_providers'] ?? 8 }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Admins</span>
                    <span class="font-semibold">{{ $stats['admins'] ?? 3 }}</span>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="analytics-card bg-white p-6 rounded-2xl shadow-lg">
            <h4 class="text-lg font-bold text-gray-800 mb-4">
                <i class="fas fa-history text-blue-500 mr-2"></i>Recent Activity
            </h4>
            <div class="space-y-3">
                <div class="flex items-start space-x-3">
                    <div class="bg-blue-100 p-2 rounded-full">
                        <i class="fas fa-user-plus text-blue-600 text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium">New user registered</p>
                        <p class="text-xs text-gray-500">2 minutes ago</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <div class="bg-green-100 p-2 rounded-full">
                        <i class="fas fa-check-circle text-green-600 text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium">Provider approved</p>
                        <p class="text-xs text-gray-500">15 minutes ago</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <div class="bg-purple-100 p-2 rounded-full">
                        <i class="fas fa-shopping-cart text-purple-600 text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium">New booking</p>
                        <p class="text-xs text-gray-500">1 hour ago</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Health -->
        <div class="analytics-card bg-white p-6 rounded-2xl shadow-lg">
            <h4 class="text-lg font-bold text-gray-800 mb-4">
                <i class="fas fa-server text-orange-500 mr-2"></i>System Health
            </h4>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span>Server Load</span>
                        <span>23%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full" style="width: 23%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span>Database</span>
                        <span>98%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full" style="width: 98%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span>API Response</span>
                        <span>156ms</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-yellow-500 h-2 rounded-full" style="width: 78%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white p-6 rounded-2xl shadow-lg">
        <h3 class="text-xl font-bold text-gray-800 mb-4">
            <i class="fas fa-bolt text-yellow-500 mr-2"></i>Quick Actions
        </h3>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            <a href="{{ route('admin.users.index') }}" class="quick-action-btn bg-blue-50 hover:bg-blue-100 p-4 rounded-xl text-center transition-all duration-300">
                <i class="fas fa-users text-blue-600 text-2xl mb-2"></i>
                <div class="text-sm font-medium text-gray-700">Manage Users</div>
            </a>
            <a href="{{ route('admin.providers.index') }}" class="quick-action-btn bg-green-50 hover:bg-green-100 p-4 rounded-xl text-center transition-all duration-300">
                <i class="fas fa-user-md text-green-600 text-2xl mb-2"></i>
                <div class="text-sm font-medium text-gray-700">Providers</div>
            </a>
            <a href="{{ route('admin.bookings.index') }}" class="quick-action-btn bg-purple-50 hover:bg-purple-100 p-4 rounded-xl text-center transition-all duration-300">
                <i class="fas fa-calendar-check text-purple-600 text-2xl mb-2"></i>
                <div class="text-sm font-medium text-gray-700">Bookings</div>
            </a>
            <a href="{{ route('admin.finance.insights') }}" class="quick-action-btn bg-yellow-50 hover:bg-yellow-100 p-4 rounded-xl text-center transition-all duration-300">
                <i class="fas fa-chart-line text-yellow-600 text-2xl mb-2"></i>
                <div class="text-sm font-medium text-gray-700">Analytics</div>
            </a>
            <a href="{{ route('admin.approvals.index') }}" class="quick-action-btn bg-red-50 hover:bg-red-100 p-4 rounded-xl text-center transition-all duration-300">
                <i class="fas fa-check-circle text-red-600 text-2xl mb-2"></i>
                <div class="text-sm font-medium text-gray-700">Approvals</div>
            </a>
            <a href="{{ route('admin.settings') }}" class="quick-action-btn bg-indigo-50 hover:bg-indigo-100 p-4 rounded-xl text-center transition-all duration-300">
                <i class="fas fa-cogs text-indigo-600 text-2xl mb-2"></i>
                <div class="text-sm font-medium text-gray-700">Settings</div>
            </a>
        </div>
    </div>
</div>

<style>
.super-admin-dashboard {
    font-family: 'Inter', sans-serif;
}

.kpi-card {
    position: relative;
    overflow: hidden;
}

.kpi-card::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    animation: shimmer 3s infinite;
}

@keyframes shimmer {
    0% { transform: rotate(0deg) translate(-50%, -50%); }
    50% { transform: rotate(180deg) translate(-50%, -50%); }
    100% { transform: rotate(360deg) translate(-50%, -50%); }
}

.chart-card, .analytics-card {
    border: 1px solid #e5e7eb;
    transition: all 0.3s ease;
}

.chart-card:hover, .analytics-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

.quick-action-btn {
    transition: all 0.3s ease;
    border: 1px solid transparent;
}

.quick-action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.dashboard-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    position: relative;
    overflow: hidden;
}

.dashboard-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='4'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize charts
    initializeCharts();

    // Update timestamp
    function updateTimestamp() {
        document.getElementById('lastUpdated').textContent = new Date().toLocaleString('en-US', {
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }
    setInterval(updateTimestamp, 60000); // Update every minute

    // Auto refresh stats every 30 seconds
    setInterval(loadStats, 30000);
});

async function loadStats() {
    try {
        const resp = await fetch('{{ route("dashboard.stats.super") }}');
        const data = await resp.json();

        // Update KPI cards
        document.getElementById('totalUsers').textContent = data.users || 0;
        document.getElementById('activeProviders').textContent = data.providers || 0;
        document.getElementById('totalRevenue').textContent = '$' + (data.revenue || 0).toLocaleString();
        document.getElementById('pendingApprovals').textContent = data.pending_documents || 0;

        // Update charts if they exist
        if (window.growthChart) {
            updateCharts(data);
        }
    } catch (error) {
        console.error('Failed to load stats:', error);
    }
}

function initializeCharts() {
    // Growth Chart
    const growthCtx = document.getElementById('growthChart');
    if (growthCtx) {
        window.growthChart = new Chart(growthCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Users',
                    data: [65, 78, 90, 105, 120, 135],
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                }, {
                    label: 'Revenue',
                    data: [1200, 1500, 1800, 2100, 2400, 2700],
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart');
    if (revenueCtx) {
        window.revenueChart = new Chart(revenueCtx, {
            type: 'doughnut',
            data: {
                labels: ['Service Bookings', 'Activity Bookings', 'Subscriptions', 'Other'],
                datasets: [{
                    data: [45, 30, 15, 10],
                    backgroundColor: [
                        '#3b82f6',
                        '#10b981',
                        '#f59e0b',
                        '#ef4444'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });
    }
}

function updateCharts(data) {
    // Update chart data based on real stats
    if (window.growthChart) {
        // Update growth chart with real data
    }
    if (window.revenueChart) {
        // Update revenue chart with real data
    }
}

// Realtime listener for document uploads
@if(env('PUSHER_APP_KEY'))
try {
    window.Echo.channel('documents').listen('DocumentUploaded', (e) => {
        loadStats();
        // Show toast notification instead of alert
        showToast('New document uploaded: ' + e.name, 'success');
    });
} catch(e) {
    console.warn('Echo listen failed', e);
}
@endif

function showToast(message, type = 'info') {
    // Simple toast implementation
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type} border-0`;
    toast.setAttribute('role', 'alert');
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">${message}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    document.body.appendChild(toast);

    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();

    setTimeout(() => toast.remove(), 5000);
}
</script>
@endsection
