@props(['user', 'role', 'notificationCount' => 0, 'messageCount' => 0, 'totalBookings' => 0,
        'upcomingBookings' => [], 'clients' => [], 'recentActivities' => [],
        'families' => [], 'completedModules' => []])

@php
    // Helper to determine if current route matches
    $isActive = function($route) {
        return request()->routeIs($route) ? 'bg-primary/20 text-white font-semibold' : '';
    };

    // Helper function to check if route exists
    if (!function_exists('route_exists')) {
        function route_exists($name) {
            return app('router')->has($name);
        }
    }
@endphp

<div class="sidebar flex flex-col h-full py-4">
    <!-- Logo / Brand -->
    <div class="px-6 pb-6 pt-2">
        <a href="{{ route_exists('dashboard') ? route('dashboard', [], false) : '#' }}" class="flex items-center">
            <span class="text-2xl font-bold text-primary">InfaNect</span>
        </a>
    </div>

    <!-- Navigation Links -->
    <nav class="flex-1 px-3 space-y-1">
        <!-- Common Links for All Users -->
        <a href="{{ route_exists('dashboard') ? route('dashboard', [], false) : '#' }}"
           class="flex items-center px-3 py-2 rounded-lg hover:bg-primary/20 transition-colors {{ $isActive('dashboard') }}">
            <i class="fas fa-tachometer-alt w-5 h-5 mr-3"></i>
            Dashboard
        </a>

        <!-- Role-Specific Navigation -->
        @if(in_array($role, ['admin', 'super-admin']))
            <!-- Admin Links -->
            <a href="{{ route_exists('users.index') ? route('users.index', [], false) : '#' }}"
               class="flex items-center px-3 py-2 rounded-lg hover:bg-primary/20 transition-colors {{ $isActive('users.*') }}">
                <i class="fas fa-users w-5 h-5 mr-3"></i>
                Users
            </a>

            <a href="{{ route_exists('activities.index') ? route('activities.index', [], false) : '#' }}"
               class="flex items-center px-3 py-2 rounded-lg hover:bg-primary/20 transition-colors {{ $isActive('activities.*') }}">
                <i class="fas fa-calendar-alt w-5 h-5 mr-3"></i>
                Activities
            </a>

            <a href="{{ route_exists('providers.index') ? route('providers.index', [], false) : '#' }}"
               class="flex items-center px-3 py-2 rounded-lg hover:bg-primary/20 transition-colors {{ $isActive('providers.*') }}">
                <i class="fas fa-user-md w-5 h-5 mr-3"></i>
                Providers
            </a>

            <a href="{{ route_exists('financials.dashboard') ? route('financials.dashboard', [], false) : '#' }}"
               class="flex items-center px-3 py-2 rounded-lg hover:bg-primary/20 transition-colors {{ $isActive('financials.*') }}">
                <i class="fas fa-chart-line w-5 h-5 mr-3"></i>
                Financial Dashboard
            </a>

            <a href="{{ route_exists('settings.index') ? route('settings.index', [], false) : '#' }}"
               class="flex items-center px-3 py-2 rounded-lg hover:bg-primary/20 transition-colors {{ $isActive('settings.*') }}">
                <i class="fas fa-cog w-5 h-5 mr-3"></i>
                Settings
            </a>

        @elseif($role === 'provider-professional')
            <!-- Professional Provider Links -->
            <a href="{{ route_exists('clients.index') ? route('clients.index', [], false) : '#' }}"
               class="flex items-center px-3 py-2 rounded-lg hover:bg-primary/20 transition-colors {{ $isActive('clients.*') }}">
                <i class="fas fa-users w-5 h-5 mr-3"></i>
                My Clients
            </a>

            <a href="{{ route_exists('bookings.index') ? route('bookings.index', [], false) : '#' }}"
               class="flex items-center px-3 py-2 rounded-lg hover:bg-primary/20 transition-colors {{ $isActive('bookings.*') }}">
                <i class="fas fa-calendar-check w-5 h-5 mr-3"></i>
                Appointments
            </a>

            <a href="{{ route_exists('services.index') ? route('services.index', [], false) : '#' }}"
               class="flex items-center px-3 py-2 rounded-lg hover:bg-primary/20 transition-colors {{ $isActive('services.*') }}">
                <i class="fas fa-concierge-bell w-5 h-5 mr-3"></i>
                Services
            </a>

            <a href="{{ route_exists('provider.analytics') ? route('provider.analytics', [], false) : '#' }}"
               class="flex items-center px-3 py-2 rounded-lg hover:bg-primary/20 transition-colors {{ $isActive('provider.analytics') }}">
                <i class="fas fa-chart-bar w-5 h-5 mr-3"></i>
                Analytics
            </a>

        @elseif($role === 'provider-bonding')
            <!-- Bonding Provider Links -->
            <a href="{{ route_exists('activities.index') ? route('activities.index', [], false) : '#' }}"
               class="flex items-center px-3 py-2 rounded-lg hover:bg-primary/20 transition-colors {{ $isActive('activities.*') }}">
                <i class="fas fa-calendar-alt w-5 h-5 mr-3"></i>
                My Activities
            </a>

            <a href="{{ route_exists('activities.create') ? route('activities.create', [], false) : '#' }}"
               class="flex items-center px-3 py-2 rounded-lg hover:bg-primary/20 transition-colors {{ $isActive('activities.create') }}">
                <i class="fas fa-plus w-5 h-5 mr-3"></i>
                Create Activity
            </a>

            <a href="{{ route_exists('families.index') ? route('families.index', [], false) : '#' }}"
               class="flex items-center px-3 py-2 rounded-lg hover:bg-primary/20 transition-colors {{ $isActive('families.*') }}">
                <i class="fas fa-users w-5 h-5 mr-3"></i>
                Families
            </a>

            <a href="{{ route_exists('community.analytics') ? route('community.analytics', [], false) : '#' }}"
               class="flex items-center px-3 py-2 rounded-lg hover:bg-primary/20 transition-colors {{ $isActive('community.*') }}">
                <i class="fas fa-chart-bar w-5 h-5 mr-3"></i>
                Community Impact
            </a>

        @else
            <!-- Client/Regular User Links -->
            <a href="{{ route_exists('activities.index') ? route('activities.index', [], false) : '#' }}"
               class="flex items-center px-3 py-2 rounded-lg hover:bg-primary/20 transition-colors {{ $isActive('activities.*') }}">
                <i class="fas fa-calendar-alt w-5 h-5 mr-3"></i>
                Activities
            </a>

            <a href="{{ route_exists('bookings.index') ? route('bookings.index', [], false) : '#' }}"
               class="flex items-center px-3 py-2 rounded-lg hover:bg-primary/20 transition-colors {{ $isActive('bookings.*') }}">
                <i class="fas fa-calendar-check w-5 h-5 mr-3"></i>
                My Bookings
            </a>

            <a href="{{ route_exists('providers.index') ? route('providers.index', [], false) : '#' }}"
               class="flex items-center px-3 py-2 rounded-lg hover:bg-primary/20 transition-colors {{ $isActive('providers.*') }}">
                <i class="fas fa-user-md w-5 h-5 mr-3"></i>
                Find Providers
            </a>

            <a href="{{ route_exists('mood.index') ? route('mood.index', [], false) : '#' }}"
               class="flex items-center px-3 py-2 rounded-lg hover:bg-primary/20 transition-colors {{ $isActive('mood.*') }}">
                <i class="fas fa-smile w-5 h-5 mr-3"></i>
                Mood Tracker
            </a>
        @endif

        <!-- Common link at the bottom -->
        <a href="{{ route_exists('help.index') ? route('help.index', [], false) : '#' }}"
           class="flex items-center px-3 py-2 rounded-lg hover:bg-primary/20 transition-colors {{ $isActive('help.*') }}">
            <i class="fas fa-question-circle w-5 h-5 mr-3"></i>
            Help & Support
        </a>
    </nav>

    <!-- Messages & Notifications Section -->
    <div class="px-4 py-2 mt-4">
        <div class="space-y-1">
            @if($role && $role !== 'client')
                <a href="{{ route_exists('messages.index') ? route('messages.index', [], false) : '#' }}"
                   class="flex items-center justify-between px-3 py-2 rounded-lg hover:bg-primary/20 transition-colors {{ $isActive('messages.*') }}">
                    <div class="flex items-center">
                        <i class="fas fa-envelope w-5 h-5 mr-3"></i>
                        Messages
                    </div>
                    @if($messageCount > 0)
                        <span class="px-2 py-0.5 bg-primary text-white text-xs rounded-full">
                            {{ $messageCount }}
                        </span>
                    @endif
                </a>

                <a href="{{ route_exists('provider.notifications') ? route('provider.notifications', [], false) : '#' }}"
                   class="flex items-center justify-between px-3 py-2 rounded-lg hover:bg-primary/20 transition-colors {{ $isActive('provider.notifications') }}">
                    <div class="flex items-center">
                        <i class="fas fa-bell w-5 h-5 mr-3"></i>
                        Notifications
                    </div>
                    @if($notificationCount > 0)
                        <span class="px-2 py-0.5 bg-primary text-white text-xs rounded-full">
                            {{ $notificationCount }}
                        </span>
                    @endif
                </a>
            @endif
        </div>
    </div>

    <!-- User Profile Summary -->
    <div class="mt-auto px-4 pt-4">
        <div class="border-t border-gray-700 pt-4">
            <div class="flex items-center px-2">
                <div class="w-8 h-8 bg-primary/20 text-primary rounded-full flex items-center justify-center">
                    {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-white">{{ $user->name ?? 'Guest' }}</p>
                    <p class="text-xs text-gray-400">
                        {{ ucfirst(str_replace('-', ' ', $role)) }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
