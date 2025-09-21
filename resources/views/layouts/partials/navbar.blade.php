<!-- Fixed Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top w-100 border-bottom">
    <div class="container-fluid d-flex justify-content-between align-items-center">

        <!-- Brand -->
        <a href="{{ route('dashboard') }}" class="navbar-brand fw-bold text-primary mb-0 h1">
            {{ config('app.name', 'Infanect') }}
        </a>

        <!-- Right Side -->
        <div class="d-flex align-items-center gap-3">

            <!-- Notifications -->
            @if(isset($notificationCount) && $notificationCount > 0)
                <div class="dropdown me-3">
                    <a href="#" class="nav-link position-relative" data-bs-toggle="dropdown">
                        <i class="fas fa-bell nav-icon"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            {{ $notificationCount }}
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end shadow-sm">
                        <h6 class="dropdown-header">Notifications</h6>
                        <a class="dropdown-item" href="#">View all notifications</a>
                    </div>
                </div>
            @endif

            <!-- User Dropdown -->
            <div class="dropdown">
                <a href="#" class="nav-link dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown">
                    <span class="me-2 d-none d-md-inline fw-medium">{{ $user->name ?? 'User' }}</span>
                    <div class="rounded-circle bg-primary text-white fw-bold d-flex align-items-center justify-content-center"
                         style="width: 35px; height: 35px;">
                        {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                    <li><a class="dropdown-item" href="#"><i class="fas fa-user-circle me-2"></i> Profile</a></li>
                    <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i> Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="fas fa-sign-out-alt me-2"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>

        </div>
    </div>
</nav>

<!-- Spacer for fixed navbar -->
<div style="margin-top: 70px;"></div>
{{-- End of Navbar --}}
