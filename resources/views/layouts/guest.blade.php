<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Infanect') }} - Empowering Families with Digital Wellness</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @stack('styles')

    <style>
        :root {
            --primary-color: #667eea;
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        body {
            font-family: 'Figtree', sans-serif;
            background: var(--primary-gradient);
            min-height: 100vh;
        }

        .btn-primary {
            background: var(--primary-gradient);
            border: none;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }

        .text-primary {
            color: var(--primary-color) !important;
        }

        .card {
            backdrop-filter: blur(10px);
            background-color: rgba(255, 255, 255, 0.95);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-transparent position-absolute w-100" style="z-index: 1000;">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('login') }}">
                <i class="fas fa-heart me-2 text-white"></i>
                <span class="text-white">Infanect</span>
            </a>

            <div class="navbar-nav ms-auto">
                @if(!request()->routeIs('login'))
                    <a class="nav-link text-white" href="{{ route('login') }}">
                        <i class="fas fa-sign-in-alt me-1"></i>Login
                    </a>
                @endif

                @if(!request()->routeIs('register'))
                    <a class="nav-link text-white" href="{{ route('register') }}">
                        <i class="fas fa-user-plus me-1"></i>Register
                    </a>
                @endif

                @if(Route::has('provider.register') && !request()->routeIs('provider.register'))
                    <a class="nav-link text-white" href="{{ route('provider.register') }}">
                        <i class="fas fa-rocket me-1"></i>Provider Registration
                    </a>
                @endif
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="min-vh-100 d-flex align-items-center py-5">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    @yield('content')
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="position-fixed bottom-0 w-100 text-center text-white py-3" style="z-index: -1;">
        <div class="container">
            <p class="mb-0 small opacity-75">
                &copy; {{ date('Y') }} Infanect - Empowering Families with Digital Wellness
            </p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>
