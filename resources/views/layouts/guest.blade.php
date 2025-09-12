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
            background: linear-gradient(135deg, #f8f9fa 0%, #e3f2fd 50%, #f3e5f5 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }
        .auth-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 1.5rem;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            padding: 2.5rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .auth-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 50px rgba(0,0,0,0.15);
        }
        .brand-title {
            font-weight: 700;
            color: #0d6efd;
            background: linear-gradient(45deg, #0d6efd, #6610f2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .nav-tabs .nav-link {
            border: none;
            color: #6c757d;
            font-weight: 500;
            padding: 0.75rem 1.5rem;
            transition: all 0.3s ease;
        }
        .nav-tabs .nav-link.active {
            background: linear-gradient(45deg, #0d6efd, #6610f2);
            color: white;
            border-radius: 0.5rem;
        }
        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }
        .btn-primary {
            background: linear-gradient(45deg, #0d6efd, #6610f2);
            border: none;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(13, 110, 253, 0.4);
        }
        footer {
            font-size: 0.8rem;
            color: #6c757d;
        }
        @media (max-width: 768px) {
            .auth-card {
                padding: 1.5rem;
                margin: 1rem;
            }
            .brand-title {
                font-size: 1.8rem;
            }
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
