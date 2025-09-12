@props(['user', 'role', 'notificationCount' => 0, 'messageCount' => 0])

<div class="d-flex flex-column flex-shrink-0 p-3 bg-dark text-white vh-100" style="width: 250px;">
    <a href="{{ route('home', [], false) }}" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
        <span class="fs-4 fw-bold text-primary">{{ config('app.name', 'Infanect') }}</span>
    </a>
    <hr>

    <!-- User Info -->
    <div class="mb-4 text-center">
        <div class="rounded-circle bg-primary bg-opacity-25 d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
            <span class="fs-5 text-primary">{{ strtoupper(substr($user->name ?? 'U', 0, 2)) }}</span>
        </div>
        <div class="mt-2">
            <strong>{{ $user->name ?? 'Guest' }}</strong><br>
            <small class="text-muted">{{ ucfirst(str_replace('-', ' ', $role ?? 'client')) }}</small>
        </div>
    </div>
    <hr>

    <!-- Sidebar Menu -->
    <ul class="nav nav-pills flex-column mb-auto">
        <!-- Common Dashboard Link -->
        <li class="nav-item">
            <a href="{{ route('dashboard', [], false) }}"
               class="nav-link {{ request()->routeIs('dashboard*') ? 'active' : 'text-white' }}">
                <i class="fas fa-home me-2"></i> Dashboard
            </a>
        </li>

        <!-- Client / Parent -->
        @if($role === 'client')
            <li>
                <a href="{{ route('bookings.index', [], false) }}"
                   class="nav-link {{ request()->routeIs('bookings*') ? 'active' : 'text-white' }}">
                    <i class="fas fa-calendar-check me-2"></i> My Bookings
                </a>
            </li>
            <li>
                <a href="{{ route('activities.index', [], false) }}"
                   class="nav-link {{ request()->routeIs('activities*') ? 'active' : 'text-white' }}">
                    <i class="fas fa-child me-2"></i> Activities
                </a>
            </li>
            <li>
                <a href="{{ route('providers.index', [], false) }}"
                   class="nav-link {{ request()->routeIs('providers*') ? 'active' : 'text-white' }}">
                    <i class="fas fa-user-md me-2"></i> Find Providers
                </a>
            </li>
            <li>
                <a href="{{ route('mood.index', [], false) }}"
                   class="nav-link {{ request()->routeIs('mood*') ? 'active' : 'text-white' }}">
                    <i class="fas fa-smile me-2"></i> Mood Tracker
                </a>
            </li>

        <!-- Professional Provider -->
        @elseif($role === 'provider-professional')
            <li>
                <a href="{{ route('clients.index', [], false) }}"
                   class="nav-link {{ request()->routeIs('clients*') ? 'active' : 'text-white' }}">
                    <i class="fas fa-users me-2"></i> My Clients
                </a>
            </li>
            <li>
                <a href="{{ route('bookings.index', [], false) }}"
                   class="nav-link {{ request()->routeIs('bookings*') ? 'active' : 'text-white' }}">
                    <i class="fas fa-calendar-alt me-2"></i> Appointments
                    @if($upcomingBookings ?? 0 > 0)
                        <span class="badge rounded-pill bg-primary ms-2">{{ $upcomingBookings }}</span>
                    @endif
                </a>
            </li>
            <li>
                <a href="{{ route('services.index', [], false) }}"
                   class="nav-link {{ request()->routeIs('services*') ? 'active' : 'text-white' }}">
                    <i class="fas fa-concierge-bell me-2"></i> Services
                </a>
            </li>
            <li>
                <a href="{{ route('provider.analytics', [], false) }}"
                   class="nav-link {{ request()->routeIs('provider.analytics*') ? 'active' : 'text-white' }}">
                    <i class="fas fa-chart-bar me-2"></i> Analytics
                </a>
            </li>

        <!-- Bonding Provider -->
        @elseif($role === 'provider-bonding')
            <li>
                <a href="{{ route('activities.index', [], false) }}"
                   class="nav-link {{ request()->routeIs('activities*') ? 'active' : 'text-white' }}">
                    <i class="fas fa-calendar-alt me-2"></i> My Activities
                </a>
            </li>
            <li>
                <a href="{{ route('activities.create', [], false) }}"
                   class="nav-link {{ request()->routeIs('activities.create') ? 'active' : 'text-white' }}">
                    <i class="fas fa-plus me-2"></i> Create Activity
                </a>
            </li>
            <li>
                <a href="{{ route('families.index', [], false) }}"
                   class="nav-link {{ request()->routeIs('families*') ? 'active' : 'text-white' }}">
                    <i class="fas fa-users me-2"></i> Families
                </a>
            </li>
            <li>
                <a href="{{ route('community.analytics', [], false) }}"
                   class="nav-link {{ request()->routeIs('community.*') ? 'active' : 'text-white' }}">
                    <i class="fas fa-chart-bar me-2"></i> Community Impact
                </a>
            </li>

        <!-- Admin / Manager -->
        @elseif(in_array($role, ['admin', 'super-admin']))
            <li>
                <a href="{{ route('users.index', [], false) }}"
                   class="nav-link {{ request()->routeIs('users*') ? 'active' : 'text-white' }}">
                    <i class="fas fa-users me-2"></i> Users
                </a>
            </li>
            <li>
                <a href="{{ route('activities.index', [], false) }}"
                   class="nav-link {{ request()->routeIs('activities*') ? 'active' : 'text-white' }}">
                    <i class="fas fa-calendar-alt me-2"></i> Activities
                </a>
            </li>
            <li>
                <a href="{{ route('providers.index', [], false) }}"
                   class="nav-link {{ request()->routeIs('providers*') ? 'active' : 'text-white' }}">
                    <i class="fas fa-user-md me-2"></i> Providers
                </a>
            </li>
            <li>
                <a href="{{ route('financials.dashboard', [], false) }}"
                   class="nav-link {{ request()->routeIs('financials*') ? 'active' : 'text-white' }}">
                    <i class="fas fa-chart-line me-2"></i> Financial Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('settings.index', [], false) }}"
                   class="nav-link {{ request()->routeIs('settings*') ? 'active' : 'text-white' }}">
                    <i class="fas fa-cog me-2"></i> Settings
                </a>
            </li>
        @endif
    </ul>

    <!-- Messages & Notifications -->
    <hr>
    <div class="mb-3">
        <div class="d-flex justify-content-around">
            <a href="{{ route('messages.index', [], false) }}" class="btn btn-outline-light position-relative">
                <i class="fas fa-envelope"></i>
                @if($messageCount > 0)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        {{ $messageCount }}
                    </span>
                @endif
            </a>

            <a href="{{ route('notifications.index', [], false) }}" class="btn btn-outline-light position-relative">
                <i class="fas fa-bell"></i>
                @if($notificationCount > 0)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        {{ $notificationCount }}
                    </span>
                @endif
            </a>

            <a href="{{ route('profile.edit', [], false) }}" class="btn btn-outline-light">
                <i class="fas fa-user-cog"></i>
            </a>
        </div>
    </div>

    <!-- Logout -->
    <form method="POST" action="{{ route('logout', [], false) }}">
        @csrf
        <button type="submit" class="btn btn-outline-light w-100 text-start">
            <i class="fas fa-sign-out-alt me-2"></i> Logout
        </button>
    </form>
</div>
