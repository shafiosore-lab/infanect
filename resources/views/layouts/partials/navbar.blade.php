<nav class="w-100">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="navbar-brand mb-0">{{ config('app.name', 'Infanect') }}</h1>
        </div>

        <div class="d-flex align-items-center gap-3">
            <!-- Notifications -->
            @if(isset($notificationCount) && $notificationCount > 0)
            <div class="position-relative me-3">
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

            <!-- User dropdown -->
            <div class="dropdown">
                <a href="#" class="nav-link dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown">
                    <span class="me-2 d-none d-md-inline">{{ $user->name ?? 'User' }}</span>
                    <div class="avatar-circle bg-white text-success">
                        {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                    <li><a class="dropdown-item" href="#"><i class="fas fa-user-circle me-2"></i> Profile</a></li>
                    <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i> Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="#"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>
