<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Infanect - Connecting Families with Professional Care</title>
    <meta name="description" content="Infanect connects parents with verified professional service providers including doctors, therapists, and childcare experts. Book appointments, access parenting resources, and join our community.">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap" rel="stylesheet" />
    <style>
        body { font-family: 'Instrument Sans', sans-serif; }
        .hero-bg { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .feature-icon { width: 64px; height: 64px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; }
        .btn-custom { padding: 0.75rem 2rem; font-size: 1.1rem; font-weight: 600; border-radius: 0.5rem; transition: all 0.3s; }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold fs-3 text-primary" href="#">Infanect</a>
            <div class="d-flex">
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn btn-outline-primary me-2">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-link text-decoration-none me-3">Sign In</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">Get Started</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-bg text-white py-5">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h1 class="display-4 fw-bold mb-4">
                        Connecting Families with
                        <span class="text-warning">Professional Care</span>
                    </h1>
                    <p class="lead mb-4">
                        Find verified doctors, therapists, and childcare providers. Access parenting resources,
                        book appointments easily, and join a supportive community of families.
                    </p>
                    <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                        <a href="{{ route('register') }}" class="btn btn-light btn-custom fw-semibold">
                            Start Your Journey
                        </a>
                        <a href="#features" class="btn btn-outline-light btn-custom fw-semibold">
                            Learn More
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-5 bg-light">
        <div class="container py-5">
            <div class="row justify-content-center mb-5">
                <div class="col-lg-8 text-center">
                    <h2 class="display-5 fw-bold mb-3">Why Choose Infanect?</h2>
                    <p class="lead text-muted">
                        We make it easy for families to find trusted professionals and access the resources they need.
                    </p>
                </div>
            </div>

            <div class="row g-4">
                <!-- Feature 1 -->
                <div class="col-lg-3 col-md-6 text-center">
                    <div class="feature-icon bg-primary bg-opacity-10 text-primary mx-auto">
                        <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="h5 fw-semibold mb-3">Verified Providers</h3>
                    <p class="text-muted">All our service providers undergo thorough background checks and verification processes.</p>
                </div>

                <!-- Feature 2 -->
                <div class="col-lg-3 col-md-6 text-center">
                    <div class="feature-icon bg-success bg-opacity-10 text-success mx-auto">
                        <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <h3 class="h5 fw-semibold mb-3">Parenting Resources</h3>
                    <p class="text-muted">Access expert-written articles, videos, and guides on child development and parenting.</p>
                </div>

                <!-- Feature 3 -->
                <div class="col-lg-3 col-md-6 text-center">
                    <div class="feature-icon bg-info bg-opacity-10 text-info mx-auto">
                        <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="h5 fw-semibold mb-3">Community Support</h3>
                    <p class="text-muted">Connect with other parents, share experiences, and get support from our community.</p>
                </div>

                <!-- Feature 4 -->
                <div class="col-lg-3 col-md-6 text-center">
                    <div class="feature-icon bg-warning bg-opacity-10 text-warning mx-auto">
                        <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="h5 fw-semibold mb-3">Easy Booking</h3>
                    <p class="text-muted">Book appointments with just a few clicks and manage your schedule effortlessly.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="py-5 bg-secondary bg-opacity-10">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h2 class="display-5 fw-bold mb-4">About Infanect</h2>
                    <p class="lead text-muted mb-4">
                        Infanect was founded with a simple mission: to make professional childcare and family services
                        accessible to every family. We believe that every child deserves the best care, and every parent
                        deserves peace of mind.
                    </p>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-custom">
                        Join Our Community
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-light py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <h5 class="fw-bold mb-3">Infanect</h5>
                    <p class="text-muted">
                        Connecting families with trusted professional care providers.
                    </p>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h6 class="fw-semibold mb-3">Services</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-muted text-decoration-none">Find Providers</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Book Appointments</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Parenting Resources</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h6 class="fw-semibold mb-3">Support</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-muted text-decoration-none">Help Center</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Contact Us</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Privacy Policy</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h6 class="fw-semibold mb-3">Community</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-muted text-decoration-none">Blog</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Forum</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Events</a></li>
                    </ul>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center text-muted">
                <p class="mb-0">&copy; 2024 Infanect. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
