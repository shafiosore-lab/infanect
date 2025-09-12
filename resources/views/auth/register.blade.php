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
        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-900 text-center">Create New Account</h2>
                <p class="mt-2 text-sm text-gray-600 text-center">Join our platform today</p>
            </div>

            <!-- Name -->
            <div class="mb-4">
                <x-input-label for="name" :value="__('Full Name')" />
                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', '')"
                    required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Email Address -->
            <div class="mb-4">
                <x-input-label for="email" :value="__('Email Address')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', '')"
                    required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Phone -->
            <div class="mb-4">
                <x-input-label for="phone" :value="__('Phone Number')" />
                <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone', '')"
                    autocomplete="tel" />
                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
            </div>

            <!-- Department -->
            <div class="mb-4">
                <x-input-label for="department" :value="__('Department')" />
                <x-text-input id="department" class="block mt-1 w-full" type="text" name="department"
                    :value="old('department', '')" placeholder="e.g., IT, Sales, HR" />
                <x-input-error :messages="$errors->get('department')" class="mt-2" />
            </div>

            <!-- Role Selection -->
            <div class="mb-4">
                <x-input-label for="role_id" :value="__('Account Type')" />
                <select id="role_id" name="role_id" aria-label="Select your account type"
                    class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="">{{ __('Select your account type') }}</option>
                    @foreach($roles as $role)
                    @php
                    // Safely get slug/name from a Role object or array
                    $slug = $role->slug ?? strtolower(str_replace(' ', '-', $role->name ?? ($role->title ?? '')));
                    // Build a friendly label
                    if (in_array($slug, ['employee','client'])) {
                    $label = '👤 Parent / Client — Book & pay for activities';
                    } elseif ($slug === 'provider') {
                    $label = '🏢 Provider Admin — Manage activities & services';
                    } elseif ($slug === 'provider-professional') {
                    $label = '🩺 Provider (Professional) — Offer specialised services & manage bookings';
                    } elseif ($slug === 'provider-bonding') {
                    $label = '🤝 Provider (Bonding) — Community & family bonding services';
                    } elseif ($slug === 'admin' || $slug === 'super-admin') {
                    $label = '👑 Super Admin — Full system control';
                    } elseif ($slug === 'manager') {
                    $label = '📊 Manager — Limited admin access';
                    } else {
                    $label = $role->name ?? $role->title ?? ($role['name'] ?? 'Role');
                    }
                    @endphp
                    <option value="{{ $slug }}"
                        {{ old('role_id', '') === $slug ? 'selected' : '' }}
                        aria-label="{{ $label }}">
                        {{ $label }}
                    </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('role_id')" class="mt-2" />
                <p class="mt-1 text-xs text-gray-500">Choose the account type that best describes your role. This
                    determines which dashboard and permissions you receive.</p>
            </div>

            <!-- Password -->
            <div class="mb-4">
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                    autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div class="mb-4">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                    name="password_confirmation" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="flex items-center justify-between mt-6">
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-primary-button class="ml-4">
                    {{ __('Register') }}
                </x-primary-button>
            </div>
        </form>

        @php
        // Ensure $roles is defined and is a collection of simple objects
        if (!isset($roles) || (is_array($roles) && count($roles) === 0) || (is_object($roles) &&
        method_exists($roles,'count') && $roles->count() === 0)) {
        $roles = collect([
        (object)['id' => 1, 'slug' => 'employee', 'name' => 'Regular User'],
        (object)['id' => 2, 'slug' => 'provider', 'name' => 'Provider Admin'],
        (object)['id' => 3, 'slug' => 'admin', 'name' => 'Super Admin'],
        ]);
        } elseif (is_array($roles)) {
        $roles = collect($roles);
        }
        @endphp
    </div>
</div>
@endsection
