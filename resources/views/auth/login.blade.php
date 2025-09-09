@extends('layouts.guest') {{-- Uses your guest layout with tabs --}}

@section('content')
<div class="card shadow-lg border-0 rounded-4">
    <div class="card-body p-5">
        <div class="text-center mb-4">
            <h2 class="fw-bold text-primary">Welcome Back</h2>
            <p class="text-muted">Log in to your account</p>
        </div>

        <!-- Session Status -->
        @if(session('status'))
            <div class="alert alert-success small mb-4">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input id="email" type="email"
                       class="form-control @error('email') is-invalid @enderror"
                       name="email" value="{{ old('email') }}" required autofocus>
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input id="password" type="password"
                       class="form-control @error('password') is-invalid @enderror"
                       name="password" required autocomplete="current-password">
                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <!-- Remember Me -->
            <div class="mb-3 form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
                <label class="form-check-label small text-muted" for="remember_me">
                    Remember me
                </label>
            </div>

            <!-- Actions -->
            <div class="d-flex justify-content-between align-items-center">
                @if (Route::has('password.request'))
                    <a class="small text-decoration-none" href="{{ route('password.request') }}">
                        Forgot your password?
                    </a>
                @endif
                <button type="submit" class="btn btn-primary px-4">
                    Log In
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
