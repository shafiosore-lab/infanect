@php
/**
 * Engagement Dashboard Sidebar Component
 * Supports dynamic roles & dashboard metrics:
 * - Admin, Super Admin
 * - Provider Professional
 * - Provider Bonding
 * - Client
 */

$user = auth()->user();
$userRole = '';
$dashboardType = request()->get('dashboard_type');

// Determine user role
if ($user) {
    if (method_exists($user, 'hasRole')) {
        $userRole = $user->hasRole('super-admin') ? 'super-admin' :
                    ($user->hasRole('admin') ? 'admin' :
                    ($user->hasRole('provider-professional') ? 'provider-professional' :
                    ($user->hasRole('provider-bonding') ? 'provider-bonding' : 'client')));
    } else {
        $roleMap = [
            7 => 'admin',
            8 => 'super-admin',
            4 => 'provider-professional',
            5 => 'provider-bonding',
        ];
        $userRole = $roleMap[$user->role_id ?? 0] ?? 'client';
    }
}

// Helper for safe route checking
$routeExists = fn($name) => app('router')->has($name);

// Determine provider type for metrics
$providerData = json_decode($user->provider_data ?? '{}', true);
$providerType = $providerData['provider_type'] ?? null;
$currentDashboard = $dashboardType ?? $providerType ?? $userRole;

// Example metrics based on current dashboard
$metrics = match($currentDashboard) {
    'provider-professional' => [
        ['icon' => 'fa-calendar-check', 'title' => 'Total Bookings', 'value' => $totalBookings ?? 0, 'color' => 'primary'],
        ['icon' => 'fa-clock', 'title' => 'Upcoming Sessions', 'value' => ($upcomingBookings ?? collect([]))->count(), 'color' => 'success'],
        ['icon' => 'fa-users', 'title' => 'Clients Served', 'value' => ($clients ?? collect([]))->count(), 'color' => 'warning'],
    ],
    'provider-bonding' => [
        ['icon' => 'fa-calendar-check', 'title' => 'Total Bookings', 'value' => $totalBookings ?? 0, 'color' => 'primary'],
        ['icon' => 'fa-star', 'title' => 'Activities Joined', 'value' => ($recentActivities ?? collect([]))->count(), 'color' => 'warning'],
        ['icon' => 'fa-users', 'title' => 'Families Supported', 'value' => ($families ?? collect([]))->count(), 'color' => 'success'],
    ],
    default => [
        ['icon' => 'fa-calendar-check', 'title' => 'Bookings Made', 'value' => $totalBookings ?? 0, 'color' => 'primary'],
        ['icon' => 'fa-star', 'title' => 'Activities Joined', 'value' => ($recentActivities ?? collect([]))->count(), 'color' => 'warning'],
        ['icon' => 'fa-brain', 'title' => 'Modules Completed', 'value' => ($completedModules ?? collect([]))->count(), 'color' => 'success'],
    ],
};

// Active link check
$isActiveLink = function($route) {
    if (!app('router')->has($route)) return false;
    return request()->routeIs($route) || request()->routeIs("$route.*");
};
@endphp

<div class="sidebar-section mt-4">
    <span class="text-xs font-semibold uppercase tracking-wider text-gray-400 px-4 py-2 block">Engagement</span>
    <ul class="ml-2 mt-2 space-y-1">

        {{-- Admin / Super Admin Links --}}
        @if(in_array($userRole, ['admin', 'super-admin']))
            <li class="dropdown relative group">
                <button type="button" class="text-primary hover:bg-gray-100 flex items-center justify-between w-full px-4 py-2 rounded-lg" onclick="toggleDropdown(this)">
                    <span><i class="fas fa-envelope mr-2"></i>Messages</span>
                    <i class="fas fa-chevron-down ml-2 transition-transform duration-200"></i>
                </button>
                <ul class="dropdown-menu ml-4 mt-1 hidden bg-white shadow-sm rounded-lg py-1 z-10" aria-label="submenu">
                    @if($routeExists('messages.inbox'))
                        <li><a href="{{ route('messages.inbox') }}" class="block px-4 py-2 hover:bg-gray-100 {{ $isActiveLink('messages.inbox') ? 'bg-gray-100 font-medium' : '' }}">
                            <i class="fas fa-inbox mr-2 text-primary"></i>Inbox
                        </a></li>
                    @endif
                    @if($routeExists('messages.sent'))
                        <li><a href="{{ route('messages.sent') }}" class="block px-4 py-2 hover:bg-gray-100 {{ $isActiveLink('messages.sent') ? 'bg-gray-100 font-medium' : '' }}">
                            <i class="fas fa-paper-plane mr-2 text-primary"></i>Sent
                        </a></li>
                    @endif
                    @if($routeExists('messages.create'))
                        <li><a href="{{ route('messages.create') }}" class="block px-4 py-2 hover:bg-gray-100 {{ $isActiveLink('messages.create') ? 'bg-gray-100 font-medium' : '' }}">
                            <i class="fas fa-pen mr-2 text-primary"></i>Compose
                        </a></li>
                    @endif
                    @if($routeExists('messages.logs'))
                        <li><a href="{{ route('messages.logs') }}" class="block px-4 py-2 hover:bg-gray-100 {{ $isActiveLink('messages.logs') ? 'bg-gray-100 font-medium' : '' }}">
                            <i class="fas fa-file-alt mr-2 text-primary"></i>Logs & Reports
                        </a></li>
                    @endif
                </ul>
            </li>

            <li class="dropdown relative group">
                <button type="button" class="text-primary hover:bg-gray-100 flex items-center justify-between w-full px-4 py-2 rounded-lg" onclick="toggleDropdown(this)">
                    <span><i class="fas fa-dollar-sign mr-2"></i>Financials</span>
                    <i class="fas fa-chevron-down ml-2 transition-transform duration-200"></i>
                </button>
                <ul class="dropdown-menu ml-4 mt-1 hidden bg-white shadow-sm rounded-lg py-1 z-10" aria-label="submenu">
                    @if($routeExists('financials.dashboard'))
                        <li><a href="{{ route('financials.dashboard') }}" class="block px-4 py-2 hover:bg-gray-100 {{ $isActiveLink('financials.dashboard') ? 'bg-gray-100 font-medium' : '' }}">
                            <i class="fas fa-chart-line mr-2 text-primary"></i>Dashboard
                        </a></li>
                    @endif
                    @if($routeExists('transactions.index'))
                        <li><a href="{{ route('transactions.index') }}" class="block px-4 py-2 hover:bg-gray-100 {{ $isActiveLink('transactions.index') ? 'bg-gray-100 font-medium' : '' }}">
                            <i class="fas fa-exchange-alt mr-2 text-primary"></i>Transactions
                        </a></li>
                    @endif
                    @if($routeExists('expenses.index'))
                        <li><a href="{{ route('expenses.index') }}" class="block px-4 py-2 hover:bg-gray-100 {{ $isActiveLink('expenses.index') ? 'bg-gray-100 font-medium' : '' }}">
                            <i class="fas fa-receipt mr-2 text-primary"></i>Expenses
                        </a></li>
                    @endif
                </ul>
            </li>

            @if($routeExists('admin.engagement.insights'))
                <li><a href="{{ route('admin.engagement.insights') }}" class="block px-4 py-2 hover:bg-gray-100 rounded-lg {{ $isActiveLink('admin.engagement.insights') ? 'bg-gray-100 font-medium' : '' }}">
                    <i class="fas fa-chart-pie mr-2 text-primary"></i>Engagement Insights
                </a></li>
            @endif

            @if($routeExists('mentalhealth.index'))
                <li><a href="{{ route('mentalhealth.index') }}" class="block px-4 py-2 hover:bg-gray-100 rounded-lg {{ $isActiveLink('mentalhealth.index') ? 'bg-gray-100 font-medium' : '' }}">
                    <i class="fas fa-brain mr-2 text-primary"></i>Mental Health Modules
                </a></li>
            @endif
        @endif

        {{-- Provider Professional Links --}}
        @if($currentDashboard === 'provider-professional')
            @if($routeExists('provider.notifications'))
                <li><a href="{{ route('provider.notifications') }}" class="block px-4 py-2 hover:bg-gray-100 rounded-lg {{ $isActiveLink('provider.notifications') ? 'bg-gray-100 font-medium' : '' }}">
                    <i class="fas fa-bell mr-2 text-primary"></i>My Notifications
                    @if(isset($notificationCount) && $notificationCount > 0)
                        <span class="bg-primary text-white text-xs rounded-full px-2 py-0.5 ml-1">{{ $notificationCount }}</span>
                    @endif
                </a></li>
            @endif

            @if($routeExists('messages.index'))
                <li><a href="{{ route('messages.index') }}" class="block px-4 py-2 hover:bg-gray-100 rounded-lg {{ $isActiveLink('messages.index') ? 'bg-gray-100 font-medium' : '' }}">
                    <i class="fas fa-envelope mr-2 text-primary"></i>Messages
                </a></li>
            @endif

            @if($routeExists('clients.index'))
                <li><a href="{{ route('clients.index') }}" class="block px-4 py-2 hover:bg-gray-100 rounded-lg {{ $isActiveLink('clients.index') ? 'bg-gray-100 font-medium' : '' }}">
                    <i class="fas fa-users mr-2 text-primary"></i>My Clients
                </a></li>
            @endif

            @if($routeExists('mentalhealth.index'))
                <li><a href="{{ route('mentalhealth.index') }}" class="block px-4 py-2 hover:bg-gray-100 rounded-lg {{ $isActiveLink('mentalhealth.index') ? 'bg-gray-100 font-medium' : '' }}">
                    <i class="fas fa-brain mr-2 text-primary"></i>Mental Health Resources
                </a></li>
            @endif

            <li class="dropdown relative group">
                <button type="button" class="text-primary hover:bg-gray-100 flex items-center justify-between w-full px-4 py-2 rounded-lg" onclick="toggleDropdown(this)">
                    <span><i class="fas fa-dollar-sign mr-2"></i>Financials</span>
                    <i class="fas fa-chevron-down ml-2 transition-transform duration-200"></i>
                </button>
                <ul class="dropdown-menu ml-4 mt-1 hidden bg-white shadow-sm rounded-lg py-1 z-10">
                    @if($routeExists('provider.financials'))
                        <li><a href="{{ route('provider.financials') }}" class="block px-4 py-2 hover:bg-gray-100 {{ $isActiveLink('provider.financials') ? 'bg-gray-100 font-medium' : '' }}">
                            <i class="fas fa-money-bill-wave mr-2 text-primary"></i>Payouts
                        </a></li>
                    @endif
                    @if($routeExists('transactions.index'))
                        <li><a href="{{ route('transactions.index') }}" class="block px-4 py-2 hover:bg-gray-100 {{ $isActiveLink('transactions.index') ? 'bg-gray-100 font-medium' : '' }}">
                            <i class="fas fa-exchange-alt mr-2 text-primary"></i>Transactions
                        </a></li>
                    @endif
                    @if($routeExists('invoices.index'))
                        <li><a href="{{ route('invoices.index') }}" class="block px-4 py-2 hover:bg-gray-100 {{ $isActiveLink('invoices.index') ? 'bg-gray-100 font-medium' : '' }}">
                            <i class="fas fa-file-invoice-dollar mr-2 text-primary"></i>Invoices
                        </a></li>
                    @endif
                </ul>
            </li>
        @endif

        {{-- Provider Bonding Links --}}
        @if($currentDashboard === 'provider-bonding')
            @if($routeExists('provider.notifications'))
                <li><a href="{{ route('provider.notifications') }}" class="block px-4 py-2 hover:bg-gray-100 rounded-lg {{ $isActiveLink('provider.notifications') ? 'bg-gray-100 font-medium' : '' }}">
                    <i class="fas fa-bell mr-2 text-primary"></i>My Notifications
                    @if(isset($notificationCount) && $notificationCount > 0)
                        <span class="bg-primary text-white text-xs rounded-full px-2 py-0.5 ml-1">{{ $notificationCount }}</span>
                    @endif
                </a></li>
            @endif

            @if($routeExists('messages.index'))
                <li><a href="{{ route('messages.index') }}" class="block px-4 py-2 hover:bg-gray-100 rounded-lg {{ $isActiveLink('messages.index') ? 'bg-gray-100 font-medium' : '' }}">
                    <i class="fas fa-envelope mr-2 text-primary"></i>Messages
                </a></li>
            @endif

            @if($routeExists('activities.index'))
                <li><a href="{{ route('activities.index') }}" class="block px-4 py-2 hover:bg-gray-100 rounded-lg {{ $isActiveLink('activities.index') ? 'bg-gray-100 font-medium' : '' }}">
                    <i class="fas fa-calendar-alt mr-2 text-primary"></i>Manage Activities
                </a></li>
            @endif

            @if($routeExists('activities.calendar'))
                <li><a href="{{ route('activities.calendar') }}" class="block px-4 py-2 hover:bg-gray-100 rounded-lg {{ $isActiveLink('activities.calendar') ? 'bg-gray-100 font-medium' : '' }}">
                    <i class="fas fa-calendar-week mr-2 text-primary"></i>Activity Calendar
                </a></li>
            @endif

            @if($routeExists('community.analytics'))
                <li><a href="{{ route('community.analytics') }}" class="block px-4 py-2 hover:bg-gray-100 rounded-lg {{ $isActiveLink('community.analytics') ? 'bg-gray-100 font-medium' : '' }}">
                    <i class="fas fa-chart-bar mr-2 text-primary"></i>Engagement Insights
                </a></li>
            @endif
        @endif
    </ul>

    {{-- Metrics Summary --}}
    <div class="mt-6">
        <span class="text-xs font-semibold uppercase tracking-wider text-gray-400 px-4 py-2 block">Quick Stats</span>
        <div class="mt-2 space-y-3 px-4">
            @foreach($metrics as $metric)
                <div class="p-3 bg-white rounded-lg shadow-sm border border-gray-100">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center">
                            <div class="bg-{{ $metric['color'] }}-50 text-{{ $metric['color'] }} p-2 rounded-lg mr-3">
                                <i class="fas {{ $metric['icon'] }}"></i>
                            </div>
                            <span class="text-sm text-gray-700">{{ $metric['title'] }}</span>
                        </div>
                        <span class="text-sm font-semibold">{{ $metric['value'] }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

@once
@push('scripts')
<script>
function toggleDropdown(button) {
    // Get dropdown menu
    const menu = button.nextElementSibling;

    // Toggle dropdown visibility
    if (menu) menu.classList.toggle('hidden');

    // Toggle icon rotation
    const icon = button.querySelector('.fa-chevron-down');
    if (icon) icon.classList.toggle('rotate-180');

    // Close other dropdowns
    document.querySelectorAll('.dropdown-menu').forEach(otherMenu => {
        if (otherMenu !== menu && !otherMenu.classList.contains('hidden')) {
            otherMenu.classList.add('hidden');

            // Reset other dropdown icons
            const parentButton = otherMenu.previousElementSibling;
            const otherIcon = parentButton?.querySelector('.fa-chevron-down');
            if (otherIcon) otherIcon.classList.remove('rotate-180');
        }
    });
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(event) {
    if (!event.target.closest('.sidebar-section')) {
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            menu.classList.add('hidden');
        });

        document.querySelectorAll('.fa-chevron-down').forEach(icon => {
            icon.classList.remove('rotate-180');
        });
    }
});

// Handle mobile menu behavior
document.addEventListener('DOMContentLoaded', function() {
    // Auto-expand active dropdown if any of its children is active
    document.querySelectorAll('.dropdown-menu').forEach(menu => {
        if (menu.querySelector('.bg-gray-100')) {
            menu.classList.remove('hidden');
            const button = menu.previousElementSibling;
            const icon = button?.querySelector('.fa-chevron-down');
            if (icon) icon.classList.add('rotate-180');
        }
    });
});
</script>
@endpush
@endonce
