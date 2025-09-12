<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Infanect') }}</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Custom Styles -->
    <style>
        :root {
            --primary: #ea1c4d;
            --accent: #65c16e;
            --warning: #fbc761;
            --darkgray: #333333;
        }
        body {
            display: flex;
            min-height: 100vh;
        }
        .app-container {
            display: flex;
            flex: 1;
            width: 100%;
        }
        .content-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .main-content {
            flex: 1;
            padding: 1.5rem;
        }
        @media (max-width: 768px) {
            body { flex-direction: column; }
            .app-container { flex-direction: column; }
        }
    </style>

    @stack('styles')
</head>
<body class="bg-light">

    @php
        // Helper function to check if route exists
        if (!function_exists('route_exists')) {
            function route_exists($name) {
                return app('router')->has($name);
            }
        }
    @endphp

    <div class="app-container">
        <!-- Sidebar -->
        @if(isset($user))
            @php
                // Determine user role if not explicitly set
                $userRole = $role ?? '';

                if (!$userRole && $user) {
                    // Check for method-based roles first
                    if (method_exists($user, 'hasRole')) {
                        foreach (['super-admin', 'admin', 'provider-professional', 'provider-bonding'] as $r) {
                            if ($user->hasRole($r)) {
                                $userRole = $r;
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
                            3 => 'provider',
                        ];
                        $userRole = $roleMap[$user->role_id ?? 0] ?? ($user->role ?? 'client');
                    }
                }
            @endphp

            <x-layouts.partials.sidebar
                :user="$user"
                :role="$userRole"
                :totalBookings="$totalBookings ?? 0"
                :upcomingBookings="$upcomingBookings ?? collect([])"
                :clients="$clients ?? collect([])"
                :recentActivities="$recentActivities ?? collect([])"
                :families="$families ?? collect([])"
                :completedModules="$completedModules ?? collect([])"
                :notificationCount="$notificationCount ?? 0"
                :messageCount="$messageCount ?? 0"
            />
        @endif

        <div class="content-wrapper">
            <!-- Header / Navbar -->
            <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
                <div class="container-fluid">
                    <a class="navbar-brand fw-bold text-primary" href="{{ route_exists('dashboard') ? route('dashboard') : '#' }}">
                        {{ config('app.name', 'Infanect') }}
                    </a>

                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                            @guest
                                @if(route_exists('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">Login</a>
                                </li>
                                @endif
                                @if(route_exists('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">Register</a>
                                </li>
                                @endif
                            @else
                                <!-- Notifications Dropdown -->
                                @if(isset($notificationCount) && $notificationCount > 0)
                                    <li class="nav-item dropdown">
                                        <a class="nav-link position-relative" href="#" role="button" data-bs-toggle="dropdown">
                                            <i class="fas fa-bell"></i>
                                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                                {{ $notificationCount }}
                                            </span>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            @if(route_exists('notifications.index'))
                                                <li><a class="dropdown-item" href="{{ route('notifications.index') }}">View All</a></li>
                                            @endif
                                        </ul>
                                    </li>
                                @endif

                                <!-- User Dropdown -->
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                        {{ $user->name }}
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        @if(route_exists('profile.edit'))
                                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}">
                                                <i class="fas fa-user-circle me-2"></i>Profile
                                            </a></li>
                                        @endif

                                        {{-- Changed from settings.index to profile.settings if available, otherwise removed --}}
                                        @if(route_exists('profile.settings'))
                                            <li><a class="dropdown-item" href="{{ route('profile.settings') }}">
                                                <i class="fas fa-cog me-2"></i>Settings
                                            </a></li>
                                        @endif

                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            @if(route_exists('logout'))
                                                <form method="POST" action="{{ route('logout') }}">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item">
                                                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                                                    </button>
                                                </form>
                                            @else
                                                <a href="#" class="dropdown-item">
                                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                                </a>
                                            @endif
                                        </li>
                                    </ul>
                                </li>
                            @endguest
                        </ul>
                    </div>
                </div>
            </nav>

            <!-- Main Content -->
            <div class="main-content">
                @yield('content')
            </div>

            <!-- Footer -->
            <footer class="bg-white text-center py-3 shadow-sm">
                &copy; {{ date('Y') }} {{ config('app.name', 'Infanect') }}. All rights reserved.
            </footer>
        </div>
    </div>

    <!-- Bootstrap 5 JS & Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Optional: Custom JS -->
    <script src="{{ asset('js/app.js') }}"></script>

    @stack('scripts')
</body>
</html>
