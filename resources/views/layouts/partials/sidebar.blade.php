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

    <!-- User Profile Summary -->
    <div class="sidebar-header">
        <div class="d-flex align-items-center">
            <div class="avatar-circle me-2 bg-primary">
                {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
            </div>
            <div>
                <h6 class="mb-0 text-truncate text-white" style="max-width: 180px;">{{ $user->name ?? 'User' }}</h6>
                <span class="text-muted small">{{ ucwords(str_replace('-', ' ', $userRole ?? 'Guest')) }}</span>
            </div>
        </div>
    </div>

    <div class="sidebar-nav-container overflow-hidden">
        <!-- Navigation Links -->
        <ul class="nav nav-sidebar flex-column">
            <!-- Common Links for All Users -->
            <div class="sidebar-section">
                <div class="sidebar-section-title">Dashboard</div>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ function_exists('route_exists') && route_exists('dashboard') ? route('dashboard') : '#' }}">
                        <i class="fas fa-tachometer-alt"></i> Overview
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('calendar') ? 'active' : '' }}" href="{{ function_exists('route_exists') && route_exists('calendar') ? route('calendar') : '#' }}">
                        <i class="fas fa-calendar"></i> Calendar
                    </a>
                </li>
            </div>

            <!-- Role-Specific Navigation -->
            @if(in_array($userRole, ['admin', 'super-admin']))
                <!-- Admin Links -->
                <div class="sidebar-section">
                    <div class="sidebar-section-title">Admin</div>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ function_exists('route_exists') && route_exists('users.index') ? route('users.index') : '#' }}">
                            <i class="fas fa-users"></i> Users
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('activities.*') ? 'active' : '' }}" href="{{ function_exists('route_exists') && route_exists('activities.index') ? route('activities.index') : '#' }}">
                            <i class="fas fa-calendar-alt"></i> Activities
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('providers.*') ? 'active' : '' }}" href="{{ function_exists('route_exists') && route_exists('providers.index') ? route('providers.index') : '#' }}">
                            <i class="fas fa-user-md"></i> Providers
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('financials.*') ? 'active' : '' }}" href="{{ function_exists('route_exists') && route_exists('financials.dashboard') ? route('financials.dashboard') : '#' }}">
                            <i class="fas fa-chart-line"></i> Financial Dashboard
                        </a>
                    </li>

                    {{-- Changed from settings.index to profile.edit since settings.index doesn't exist --}}
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}" href="{{ function_exists('route_exists') && route_exists('profile.edit') ? route('profile.edit') : '#' }}">
                            <i class="fas fa-cog"></i> Settings
                        </a>
                    </li>
                </div>

            @elseif($userRole === 'provider-professional')
                <!-- Professional Provider Links -->
                <div class="sidebar-section">
                    <div class="sidebar-section-title">My Business</div>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('clients.*') ? 'active' : '' }}" href="{{ function_exists('route_exists') && route_exists('clients.index') ? route('clients.index') : '#' }}">
                            <i class="fas fa-users"></i> My Clients
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('bookings.*') ? 'active' : '' }}" href="{{ function_exists('route_exists') && route_exists('bookings.index') ? route('bookings.index') : '#' }}">
                            <i class="fas fa-calendar-check"></i> Appointments
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('services.*') ? 'active' : '' }}" href="{{ function_exists('route_exists') && route_exists('services.index') ? route('services.index') : '#' }}">
                            <i class="fas fa-concierge-bell"></i> Services
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('provider.analytics') ? 'active' : '' }}" href="{{ function_exists('route_exists') && route_exists('provider.analytics') ? route('provider.analytics') : '#' }}">
                            <i class="fas fa-chart-bar"></i> Analytics
                        </a>
                    </li>
                </div>

            @elseif($userRole === 'provider-bonding')
                <!-- Bonding Provider Links -->
                <div class="sidebar-section">
                    <div class="sidebar-section-title">My Bonding</div>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('activities.*') ? 'active' : '' }}" href="{{ function_exists('route_exists') && route_exists('activities.index') ? route('activities.index') : '#' }}">
                            <i class="fas fa-calendar-alt"></i> My Activities
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('activities.create') ? 'active' : '' }}" href="{{ function_exists('route_exists') && route_exists('activities.create') ? route('activities.create') : '#' }}">
                            <i class="fas fa-plus"></i> Create Activity
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('families.*') ? 'active' : '' }}" href="{{ function_exists('route_exists') && route_exists('families.index') ? route('families.index') : '#' }}">
                            <i class="fas fa-users"></i> Families
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('community.*') ? 'active' : '' }}" href="{{ function_exists('route_exists') && route_exists('community.analytics') ? route('community.analytics') : '#' }}">
                            <i class="fas fa-chart-bar"></i> Community Impact
                        </a>
                    </li>
                </div>

            @else
                <!-- Client/Regular User Links -->
                <div class="sidebar-section">
                    <div class="sidebar-section-title">My Account</div>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('activities.*') ? 'active' : '' }}" href="{{ function_exists('route_exists') && route_exists('activities.index') ? route('activities.index') : '#' }}">
                            <i class="fas fa-calendar-alt"></i> Activities
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('bookings.*') ? 'active' : '' }}" href="{{ function_exists('route_exists') && route_exists('bookings.index') ? route('bookings.index') : '#' }}">
                            <i class="fas fa-calendar-check"></i> My Bookings
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('providers.*') ? 'active' : '' }}" href="{{ function_exists('route_exists') && route_exists('providers.index') ? route('providers.index') : '#' }}">
                            <i class="fas fa-user-md"></i> Find Providers
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('mood.*') ? 'active' : '' }}" href="{{ function_exists('route_exists') && route_exists('mood.index') ? route('mood.index') : '#' }}">
                            <i class="fas fa-smile"></i> Mood Tracker
                        </a>
                    </li>
                </div>
            @endif

            <!-- Account Management -->
            <div class="sidebar-section mt-auto">
                <div class="sidebar-section-title">Account</div>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}" href="{{ function_exists('route_exists') && route_exists('profile.edit') ? route('profile.edit') : '#' }}">
                        <i class="fas fa-user-circle"></i> Profile
                    </a>
                </li>
                <li class="nav-item">
                    @if(function_exists('route_exists') && route_exists('logout'))
                        <form method="POST" action="{{ route('logout') }}" class="m-0">
                            @csrf
                            <button type="submit" class="nav-link w-100 text-start border-0 bg-transparent" style="color: #ff6b6b;">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </button>
                        </form>
                    @else
                        <a class="nav-link" style="color: #ff6b6b;" href="#">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    @endif
                </li>
            </div>
        </ul>
    </div>

    <!-- Common link at the bottom -->
    @if(!isset($isCompact) || !$isCompact)
    <div class="mt-auto p-3 text-center">
        <small class="text-muted">{{ config('app.name', 'Infanect') }} v{{ config('app.version', '1.0') }}</small>
    </div>
    @endif
</div>
    </div>
</div>
</div>
    </div>
</div>
