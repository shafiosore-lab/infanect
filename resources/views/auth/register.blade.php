@extends('layouts.guest')

@section('content')
<div class="card shadow-lg border-0 rounded-4">
    <div class="card-body p-5">
        <div class="text-center mb-4">
            <h2 class="fw-bold text-primary mb-2">Create Your Account</h2>
            <p class="text-muted">Join Infanect and start your wellness journey</p>
        </div>

        <!-- Session Status -->
        @if(session('status'))
            <div class="alert alert-success small mb-4">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="needs-validation" novalidate>
            @csrf

            <!-- Full Name -->
            <div class="mb-3">
                <label for="name" class="form-label fw-semibold">Full Name</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}"
                       class="form-control @error('name') is-invalid @enderror"
                       required autofocus autocomplete="name" placeholder="Enter your full name">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label fw-semibold">Email Address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}"
                       class="form-control @error('email') is-invalid @enderror"
                       required autocomplete="username" placeholder="Enter your email">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Phone -->
            <div class="mb-3">
                <label for="phone" class="form-label fw-semibold">Phone Number</label>
                <input id="phone" type="tel" name="phone" value="{{ old('phone') }}"
                       class="form-control @error('phone') is-invalid @enderror"
                       autocomplete="tel" placeholder="Enter your phone number">
                @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Department -->
            <div class="mb-3">
                <label for="department" class="form-label fw-semibold">Department</label>
                <input id="department" type="text" name="department" value="{{ old('department') }}"
                       class="form-control @error('department') is-invalid @enderror"
                       placeholder="e.g., IT, Sales, HR, Parent">
                @error('department')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Role Selection -->
            <div class="mb-3">
                <label for="role_id" class="form-label fw-semibold">Account Type</label>
                <select id="role_id" name="role_id" class="form-select @error('role_id') is-invalid @enderror" required>
                    <option value="">Choose your account type...</option>
                    @if(isset($roles) && $roles->isNotEmpty())
                        @foreach($roles as $role)
                            @php
                                $roleSlug = $role->slug ?? strtolower(str_replace(' ', '-', $role->name ?? ''));
                                $roleName = $role->name ?? 'Unknown Role';
                                $isSelected = old('role_id') == $roleSlug ? 'selected' : '';

                                $roleConfig = match($roleSlug) {
                                    'client' => ['icon' => '👤', 'title' => 'Client / Parent', 'desc' => 'Book services and activities for your family'],
                                    'employee' => ['icon' => '👥', 'title' => 'Employee', 'desc' => 'Staff member with basic access'],
                                    'provider' => ['icon' => '🏢', 'title' => 'General Provider', 'desc' => 'Offer general family services'],
                                    'provider-professional' => ['icon' => '🩺', 'title' => 'Professional Provider', 'desc' => 'Healthcare, therapy, and medical services'],
                                    'provider-bonding' => ['icon' => '🤝', 'title' => 'Bonding Provider', 'desc' => 'Community events and family bonding activities'],
                                    'manager' => ['icon' => '📊', 'title' => 'Manager', 'desc' => 'Team management with limited admin access'],
                                    'admin' => ['icon' => '⚙️', 'title' => 'Administrator', 'desc' => 'System administration and user management'],
                                    'super-admin' => ['icon' => '👑', 'title' => 'Super Administrator', 'desc' => 'Full system control and configuration'],
                                    default => ['icon' => '🔹', 'title' => $roleName, 'desc' => 'User role']
                                };
                            @endphp

                            <option value="{{ $roleSlug }}" {{ $isSelected }}>
                                {{ $roleConfig['icon'] }} {{ $roleConfig['title'] }}
                            </option>
                        @endforeach
                    @else
                        <option value="client">👤 Client / Parent</option>
                        <option value="provider-professional">🩺 Professional Provider</option>
                        <option value="provider-bonding">🤝 Bonding Provider</option>
                        <option value="admin">⚙️ Administrator</option>
                    @endif
                </select>
                @error('role_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">Choose the account type that best describes your role. This determines your dashboard and permissions.</div>
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="form-label fw-semibold">Password</label>
                <input id="password" type="password" name="password"
                       class="form-control @error('password') is-invalid @enderror"
                       required autocomplete="new-password" placeholder="Create a strong password">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="mb-4">
                <label for="password_confirmation" class="form-label fw-semibold">Confirm Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation"
                       class="form-control @error('password_confirmation') is-invalid @enderror"
                       required autocomplete="new-password" placeholder="Confirm your password">
                @error('password_confirmation')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-user-plus me-2"></i>Create Account
                </button>
            </div>

            <!-- Login Link -->
            <div class="text-center mt-3">
                <span class="text-muted">Already have an account?</span>
                <a href="{{ route('login') }}" class="text-decoration-none ms-1">Sign in here</a>
            </div>
        </form>
    </div>
</div>
@endsection
