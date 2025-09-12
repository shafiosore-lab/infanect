@extends('layouts.guest')

@section('content')
<div class="card shadow-lg border-0 rounded-4">
    <div class="card-body p-5">
        <div class="text-center mb-4">
            <h2 class="fw-bold text-primary mb-2">Welcome Back</h2>
            <p class="text-muted">Sign in to continue your wellness journey</p>
        </div>

        <!-- Session Status -->
        @if(session('status'))
            <div class="alert alert-success small mb-4">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="needs-validation" novalidate>
            @csrf

            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label fw-semibold">Email Address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}"
                       class="form-control @error('email') is-invalid @enderror"
                       required autofocus autocomplete="email" placeholder="Enter your email">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="form-label fw-semibold">Password</label>
                <input id="password" type="password" name="password"
                       class="form-control @error('password') is-invalid @enderror"
                       required autocomplete="current-password" placeholder="Enter your password">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="mb-4 d-flex justify-content-between align-items-center">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
                    <label class="form-check-label text-muted" for="remember_me">
                        Remember me
                    </label>
                </div>
                @if (Route::has('password.request'))
                    <a class="text-decoration-none small" href="{{ route('password.request') }}">
                        Forgot password?
                    </a>
                @endif
            </div>

            <!-- Submit Button -->
            <div class="d-grid mb-3">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-sign-in-alt me-2"></i>Sign In
                </button>
            </div>
        </form>

        <!-- Registration Links -->
        <div class="text-center">
            <p class="text-muted mb-2">Don't have an account?</p>
            <div class="d-flex flex-column gap-2">
                <a href="{{ route('register') }}" class="btn btn-outline-primary">
                    <i class="fas fa-user-plus me-2"></i>Register as Parent/Client
                </a>
                <a href="{{ route('provider.register') }}" class="btn btn-outline-success">
                    <i class="fas fa-building me-2"></i>Apply as Service Provider
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
