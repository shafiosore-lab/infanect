<!-- FontAwesome Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="sidebar-modern vh-100 d-flex flex-column text-white position-fixed start-0 top-0 shadow-lg"
    style="width: 280px; background: linear-gradient(135deg, #1e293b 0%, #334155 50%, #0f172a 100%); z-index: 1030;">

    <!-- Brand -->
    <div class="brand-section px-4 py-4 border-bottom border-secondary d-flex align-items-center gap-3">
        <div class="brand-icon bg-primary rounded-circle d-flex align-items-center justify-content-center"
            style="width: 40px; height: 40px;">
            <i class="fas fa-heart text-white"></i>
        </div>
        <h2 class="h5 mb-0 fw-bold text-white">Infanect</h2>
    </div>

    <!-- Sidebar Content -->
    <div class="accordion" id="sidebarAccordion">
        <nav class="sidebar-nav flex-grow-1 px-3 py-4 overflow-auto">

            @auth
            <!-- Universal Dashboard -->
            <a href="{{ route('dashboard') }}"
                class="nav-link d-flex align-items-center gap-3 px-3 py-3 rounded-3 mb-2 sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>

            <!-- Universal Messages -->
            <a href="{{ route('messages.index') }}"
                class="nav-link d-flex align-items-center gap-3 px-3 py-3 rounded-3 mb-2 sidebar-link {{ request()->routeIs('messages.*') ? 'active' : '' }}">
                <i class="fas fa-envelope"></i>
                <span>Messages</span>
                @if(isset($unreadMessages) && $unreadMessages > 0)
                <span class="badge bg-danger rounded-pill ms-auto">{{ $unreadMessages }}</span>
                @endif
            </a>

            <!-- Universal Notifications -->
            <a href="{{ route('notifications.index') }}"
                class="nav-link d-flex align-items-center gap-3 px-3 py-3 rounded-3 mb-2 sidebar-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                <i class="fas fa-bell"></i>
                <span>Notifications</span>
                @if(isset($unreadNotifications) && $unreadNotifications > 0)
                <span class="badge bg-warning rounded-pill ms-auto">{{ $unreadNotifications }}</span>
                @endif
            </a>

            <!-- ADMIN MENU -->
            @if(auth()->user()->hasRole('super-admin') || auth()->user()->hasRole('admin'))
            @include('partials.sidebar.admin')
            @endif

            <!-- SERVICE PROVIDER MENU -->
            @if(auth()->user()->hasRole('provider-professional') || auth()->user()->hasRole('provider-bonding'))
            @include('partials.sidebar.provider')
            @endif

            <!-- CLIENT MENU -->
            @if(auth()->user()->hasRole('client') || auth()->user()->hasRole('user'))
            @include('partials.sidebar.client')
            @endif

            @endauth

        </nav>
    </div>

    <!-- Profile/Footer -->
    @auth
    <div class="user-profile px-3 py-3 border-top border-secondary mt-auto">
        <div class="d-flex align-items-center gap-3">
            <div class="user-avatar bg-primary rounded-circle d-flex align-items-center justify-content-center"
                style="width: 40px; height: 40px;">
                <i class="fas fa-user text-white"></i>
            </div>
            <div class="flex-grow-1">
                <div class="fw-semibold text-white small">{{ auth()->user()->name }}</div>
                <div class="text-muted small">{{ ucfirst(auth()->user()->getRoleNames()->first() ?? 'User') }}</div>
            </div>
            <a href="{{ route('logout') }}" class="btn btn-sm btn-outline-light"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt"></i>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
        </div>
    </div>
    @else
    <div class="user-profile px-3 py-3 border-top border-secondary mt-auto text-center">
        <a href="{{ route('login') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-sign-in-alt me-2"></i>Login
        </a>
    </div>
    @endauth
</div>
