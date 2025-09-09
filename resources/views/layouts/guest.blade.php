<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Infanect') }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom Styles -->
    <style>
        body {
            background: linear-gradient(135deg, #f8f9fa, #e9f5ff);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .auth-card {
            background: #fff;
            border-radius: 1rem;
            box-shadow: 0 6px 20px rgba(0,0,0,0.08);
            padding: 2rem;
        }
        .brand-title {
            font-weight: 700;
            color: #0d6efd;
        }
        footer {
            font-size: 0.8rem;
            color: #6c757d;
        }
    </style>
</head>
<body>

    <div class="container min-vh-100 d-flex flex-column justify-content-center align-items-center">

        <!-- Brand -->
        <div class="text-center mb-4">
            <h1 class="brand-title">{{ config('app.name', 'Infanect') }}</h1>
            <p class="text-muted">Empowering Families with Digital Wellness</p>
        </div>

        <!-- Auth Card -->
        <div class="col-12 col-md-8 col-lg-5 auth-card">

            <!-- Nav Tabs -->
            <ul class="nav nav-tabs mb-4" id="authTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}"
                       href="{{ route('login') }}" role="tab">
                        🔑 Login
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link {{ request()->routeIs('register') ? 'active' : '' }}"
                       href="{{ route('register') }}" role="tab">
                        📝 Register
                    </a>
                </li>
            </ul>

            <!-- Yield content from child views -->
            @yield('content')
        </div>

        <!-- Footer -->
        <footer class="mt-5 text-center">
            &copy; {{ date('Y') }} {{ config('app.name', 'Infanect') }}. All rights reserved.
        </footer>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
