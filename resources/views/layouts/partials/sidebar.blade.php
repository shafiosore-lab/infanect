@php
    // Get user and determine role
    $user = auth()->user();
    $userRole = '';

    if ($user) {
        // Check for method-based roles first
        if (method_exists($user, 'hasRole')) {
            foreach (['super-admin', 'admin', 'provider-professional', 'provider-bonding'] as $role) {
                if ($user->hasRole($role)) {
                    $userRole = $role;
                    break;
                }
            }
        } else {
            // Otherwise try to get from role_id or role property
            $roleMap = [
                7 => 'admin',
                8 => 'super-admin',
                4 => 'provider-professional',
                5 => 'provider-bonding',
            ];
            $userRole = $roleMap[$user->role_id ?? 0] ?? ($user->role ?? 'client');
        }
    }

    // Helper function to check if route exists
    if (!function_exists('route_exists')) {
        function route_exists($name) {
            return app('router')->has($name);
        }
    }

    // Helper to determine if current route matches
    $isActive = function($route) {
        return request()->routeIs($route) ? 'bg-primary/20 text-white font-semibold' : '';
    };
@endphp

<div class="sidebar flex flex-col h-full py-4">
    <!-- Logo / Brand -->
    <div class="px-6 pb-6 pt-2">
        <a href="{{ route_exists('dashboard') ? route('dashboard') : '#' }}" class="flex items-center">
            <span class="text-2xl font-bold text-primary">InfaNect</span>
        </a>
    </div>

    <!-- Navigation Links -->
    <nav class="flex-1 px-3 space-y-1">
        <!-- Common Links for All Users -->
        <a href="{{ route_exists('dashboard') ? route('dashboard') : '#' }}"
           class="flex items-center px-3 py-2 rounded-lg hover:bg-primary/20 transition-colors {{ $isActive('dashboard') }}">
            <i class="fas fa-tachometer-alt w-5 h-5 mr-3"></i>
            Dashboard
        </a>

        <!-- Role-Specific Navigation -->
        @if(in_array($userRole, ['admin', 'super-admin']))
            <!-- Admin Links -->
            <a href="{{ route_exists('users.index') ? route('users.index') : '#' }}"
               class="flex items-center px-3 py-2 rounded-lg hover:bg-primary/20 transition-colors {{ $isActive('users.*') }}">
                <i class="fas fa-users w-5 h-5 mr-3"></i>
                Users
            </a>

            <a href="{{ route_exists('activities.index') ? route('activities.index') : '#' }}"
               class="flex items-center px-3 py-2 rounded-lg hover:bg-primary/20 transition-colors {{ $isActive('activities.*') }}">
                <i class="fas fa-calendar-alt w-5 h-5 mr-3"></i>
                Activities
            </a>

            <a href="{{ route_exists('providers.index') ? route('providers.index') : '#' }}"
               class="flex items-center px-3 py-2 rounded-lg hover:bg-primary/20 transition-colors {{ $isActive('providers.*') }}">
                <i class="fas fa-user-md w-5 h-5 mr-3"></i>
                Providers
            </a>

            <a href="{{ route_exists('financials.dashboard') ? route('financials.dashboard') : '#' }}"
               class="flex items-center px-3 py-2 rounded-lg hover:bg-primary/20 transition-colors {{ $isActive('financials.*') }}">
                <i class="fas fa-chart-line w-5 h-5 mr-3"></i>
                Financial Dashboard
            </a>

            {{-- Changed from settings.index to profile.edit since settings.index doesn't exist --}}
            <a href="{{ route_exists('profile.edit') ? route('profile.edit') : '#' }}"
               class="flex items-center px-3 py-2 rounded-lg hover:bg-primary/20 transition-colors {{ $isActive('profile.*') }}">
                <i class="fas fa-cog w-5 h-5 mr-3"></i>
                Settings
            </a>

        @elseif($userRole === 'provider-professional')
            <!-- Professional Provider Links -->
            <a href="{{ route_exists('clients.index') ? route('clients.index') : '#' }}"
               class="flex items-center px-3 py-2 rounded-lg hover:bg-primary/20 transition-colors {{ $isActive('clients.*') }}">
                <i class="fas fa-users w-5 h-5 mr-3"></i>
                My Clients
            </a>

            <a href="{{ route_exists('bookings.index') ? route('bookings.index') : '#' }}"
               class="flex items-center px-3 py-2 rounded-lg hover:bg-primary/20 transition-colors {{ $isActive('bookings.*') }}">
                <i class="fas fa-calendar-check w-5 h-5 mr-3"></i>
                Appointments
            </a>

            <a href="{{ route_exists('services.index') ? route('services.index') : '#' }}"
               class="flex items-center px-3 py-2 rounded-lg hover:bg-primary/20 transition-colors {{ $isActive('services.*') }}">
                <i class="fas fa-concierge-bell w-5 h-5 mr-3"></i>
                Services
            </a>

            <a href="{{ route_exists('provider.analytics') ? route('provider.analytics') : '#' }}"
               class="flex items-center px-3 py-2 rounded-lg hover:bg-primary/20 transition-colors {{ $isActive('provider.analytics') }}">
                <i class="fas fa-chart-bar w-5 h-5 mr-3"></i>
                Analytics
            </a>

        @elseif($userRole === 'provider-bonding')
            <!-- Bonding Provider Links -->
            <a href="{{ route_exists('activities.index') ? route('activities.index') : '#' }}"
               class="flex items-center px-3 py-2 rounded-lg hover:bg-primary/20 transition-colors {{ $isActive('activities.*') }}">
                <i class="fas fa-calendar-alt w-5 h-5 mr-3"></i>
                My Activities
            </a>

            <a href="{{ route_exists('activities.create') ? route('activities.create') : '#' }}"
               class="flex items-center px-3 py-2 rounded-lg hover:bg-primary/20 transition-colors {{ $isActive('activities.create') }}">
                <i class="fas fa-plus w-5 h-5 mr-3"></i>
                Create Activity
            </a>

            <a href="{{ route_exists('families.index') ? route('families.index') : '#' }}"
               class="flex items-center px-3 py-2 rounded-lg hover:bg-primary/20 transition-colors {{ $isActive('families.*') }}">
                <i class="fas fa-users w-5 h-5 mr-3"></i>
                Families
            </a>

            <a href="{{ route_exists('community.analytics') ? route('community.analytics') : '#' }}"
               class="flex items-center px-3 py-2 rounded-lg hover:bg-primary/20 transition-colors {{ $isActive('community.*') }}">
                <i class="fas fa-chart-bar w-5 h-5 mr-3"></i>
                Community Impact
            </a>

        @else
            <!-- Client/Regular User Links -->
            <a href="{{ route_exists('activities.index') ? route('activities.index') : '#' }}"
               class="flex items-center px-3 py-2 rounded-lg hover:bg-primary/20 transition-colors {{ $isActive('activities.*') }}">
                <i class="fas fa-calendar-alt w-5 h-5 mr-3"></i>
                Activities
            </a>

            <a href="{{ route_exists('bookings.index') ? route('bookings.index') : '#' }}"
               class="flex items-center px-3 py-2 rounded-lg hover:bg-primary/20 transition-colors {{ $isActive('bookings.*') }}">
                <i class="fas fa-calendar-check w-5 h-5 mr-3"></i>
                My Bookings
            </a>

            <a href="{{ route_exists('providers.index') ? route('providers.index') : '#' }}"
               class="flex items-center px-3 py-2 rounded-lg hover:bg-primary/20 transition-colors {{ $isActive('providers.*') }}">
                <i class="fas fa-user-md w-5 h-5 mr-3"></i>
                Find Providers
            </a>

            <a href="{{ route_exists('mood.index') ? route('mood.index') : '#' }}"
               class="flex items-center px-3 py-2 rounded-lg hover:bg-primary/20 transition-colors {{ $isActive('mood.*') }}">
                <i class="fas fa-smile w-5 h-5 mr-3"></i>
                Mood Tracker
            </a>
        @endif

        <!-- Common link at the bottom -->
        <a href="{{ route_exists('help.index') ? route('help.index') : '#' }}"
           class="flex items-center px-3 py-2 rounded-lg hover:bg-primary/20 transition-colors {{ $isActive('help.*') }}">
            <i class="fas fa-question-circle w-5 h-5 mr-3"></i>
            Help & Support
        </a>
    </nav>

    <!-- Messages & Notifications Section -->
    <div class="px-4 py-2 mt-4">
        <div class="space-y-1">
            @if($userRole && $userRole !== 'client')
                <a href="{{ route_exists('messages.index') ? route('messages.index') : '#' }}"
                   class="flex items-center justify-between px-3 py-2 rounded-lg hover:bg-primary/20 transition-colors {{ $isActive('messages.*') }}">
                    <div class="flex items-center">
                        <i class="fas fa-envelope w-5 h-5 mr-3"></i>
                        Messages
                    </div>
                    @if(($messageCount ?? 0) > 0)
                        <span class="px-2 py-0.5 bg-primary text-white text-xs rounded-full">
                            {{ $messageCount ?? 0 }}
                        </span>
                    @endif
                </a>

                <a href="{{ route_exists('provider.notifications') ? route('provider.notifications') : '#' }}"
                   class="flex items-center justify-between px-3 py-2 rounded-lg hover:bg-primary/20 transition-colors {{ $isActive('provider.notifications') }}">
                    <div class="flex items-center">
                        <i class="fas fa-bell w-5 h-5 mr-3"></i>
                        Notifications
                    </div>
                    @if(($notificationCount ?? 0) > 0)
                        <span class="px-2 py-0.5 bg-primary text-white text-xs rounded-full">
                            {{ $notificationCount ?? 0 }}
                        </span>
                    @endif
                </a>
            @endif
        </div>
    </div>

    <!-- Engagement Dashboard -->
    <div class="mt-4 px-4">
        <x-sidebar.engagement-dashboard
            :totalBookings="$totalBookings ?? 0"
            :upcomingBookings="$upcomingBookings ?? collect([])"
            :clients="$clients ?? collect([])"
            :recentActivities="$recentActivities ?? collect([])"
            :families="$families ?? collect([])"
            :completedModules="$completedModules ?? collect([])"
            :notificationCount="$notificationCount ?? 0"
            :messageCount="$messageCount ?? 0"
        />
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
                        {{ ucfirst(str_replace('-', ' ', $userRole)) }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
