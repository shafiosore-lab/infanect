@extends('layouts.guest') {{-- Uses your guest layout with tabs --}}

@section('content')
<div class="card shadow-lg border-0 rounded-4">
    <div class="card-body p-5">
        <div class="text-center mb-4">
            <h2 class="fw-bold text-primary">Create New Account</h2>
            <p class="text-muted">Join our platform today</p>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Full Name -->
            <div class="mb-3">
                <label for="name" class="form-label">Full Name</label>
                <input id="name" type="text"
                       class="form-control @error('name') is-invalid @enderror"
                       name="name" value="{{ old('name') }}" required autofocus>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input id="email" type="email"
                       class="form-control @error('email') is-invalid @enderror"
                       name="email" value="{{ old('email') }}" required>
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <!-- Phone -->
            <div class="mb-3">
                <label for="phone" class="form-label">Phone Number</label>
                <input id="phone" type="text"
                       class="form-control @error('phone') is-invalid @enderror"
                       name="phone" value="{{ old('phone') }}">
                @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <!-- Department -->
            <div class="mb-3">
                <label for="department" class="form-label">Department</label>
                <input id="department" type="text"
                       class="form-control @error('department') is-invalid @enderror"
                       name="department" value="{{ old('department') }}" placeholder="e.g., IT, Sales, HR">
                @error('department') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <!-- Role Selection -->
            <div class="mb-3">
                <label for="role_id" class="form-label">Account Type</label>
                <select id="role_id" name="role_id"
                        class="form-select @error('role_id') is-invalid @enderror">
                    <option value="">Select your account type</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                            @if($role->slug === 'employee')
                                👤 Regular User - Book & pay for activities
                            @elseif($role->slug === 'provider')
                                🏢 Provider Admin - Manage activities & services
                            @elseif($role->slug === 'admin')
                                👑 Super Admin - Full system control
                            @elseif($role->slug === 'manager')
                                📊 Manager - Limited admin access
                            @else
                                {{ $role->name }}
                            @endif
                        </option>
                    @endforeach
                </select>
                @error('role_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                <small class="text-muted">Choose the account type that best describes your role</small>
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input id="password" type="password"
                       class="form-control @error('password') is-invalid @enderror"
                       name="password" required>
                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <!-- Confirm Password -->
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input id="password_confirmation" type="password"
                       class="form-control @error('password_confirmation') is-invalid @enderror"
                       name="password_confirmation" required>
                @error('password_confirmation') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <!-- Actions -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <a class="small text-decoration-none" href="{{ route('login') }}">
                    Already registered?
                </a>
                <button type="submit" class="btn btn-primary px-4">
                    Register
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
