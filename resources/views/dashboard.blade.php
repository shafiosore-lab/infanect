@extends('layouts.app')

@section('content')
<div class="p-6 max-w-4xl mx-auto">
    <h1 class="text-2xl font-semibold mb-4">Dashboard</h1>

    @auth
        @php $user = auth()->user(); $role = $user->role ?? 'client'; @endphp

        {{-- Account Type chooser remains --}}
        <div class="bg-white p-4 rounded shadow mb-4">
            <div class="flex items-start justify-between">
                <div>
                    <h3 class="font-semibold">Account Type</h3>
                    <p class="text-sm text-gray-600">Select your account type — choose the option that best describes your role. This will take you to the relevant dashboard view. (This chooser does not change your saved account role.)</p>
                </div>
                <div>
                    <select id="accountTypeSelect" class="border rounded p-2">
                        <option value="client" {{ $user->isClient() ? 'selected' : '' }}>Parent / Client</option>
                        <option value="provider" {{ $user->isProvider() ? 'selected' : '' }}>Provider</option>
                        <option value="super-admin" {{ $user->isSuperAdmin() ? 'selected' : '' }}>Super Admin</option>
                    </select>
                </div>
            </div>
            <div class="mt-3 text-sm">
                <strong>Current saved role:</strong> {{ $role ?? 'not set' }}
                <span class="mx-2">|</span>
                <a href="{{ route('profile.edit') }}" class="text-indigo-600">Edit profile</a>
                <span class="mx-2">|</span>
                <a href="{{ route('provider.register') }}" class="text-indigo-600">Apply as Provider</a>
            </div>
        </div>

        {{-- Role-specific panels with permission checks --}}
        @if($user->isSuperAdmin())
            <div class="bg-white p-4 rounded shadow">
                <h2 class="font-semibold">Admin Overview</h2>
                <p class="text-sm text-gray-600">Access system-wide tools and insights.</p>
                <div class="mt-3 space-x-2">
                    <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 bg-indigo-600 text-white rounded">Admin Dashboard</a>
                    <a href="{{ route('messages.logs') }}" class="px-3 py-2 bg-gray-100 rounded">Messages Logs</a>
                    <a href="{{ route('admin.engagement.insights') }}" class="px-3 py-2 bg-gray-100 rounded">Engagement Insights</a>
                </div>
            </div>
        @endif

        @if($user->isProvider())
            <div class="bg-white p-4 rounded shadow mt-4">
                <h2 class="font-semibold">Provider Tools</h2>
                <p class="text-sm text-gray-600">Manage your services, bookings and view suggested families.</p>
                <div class="mt-3 space-x-2">
                    @if($user->hasPermission('manage services'))
                        <a href="{{ route('provider.services.index') }}" class="px-3 py-2 bg-gray-100 rounded">Manage Services</a>
                    @endif
                    @if($user->hasPermission('view bookings'))
                        <a href="{{ route('provider.bookings.index') }}" class="px-3 py-2 bg-gray-100 rounded">View Bookings</a>
                    @endif
                    @if($user->hasPermission('view notifications'))
                        <a href="{{ route('provider.notifications') }}" class="px-3 py-2 bg-gray-100 rounded">My Notifications</a>
                    @endif
                    @if(! $user->providerApproved())
                        <div class="mt-2 text-sm text-yellow-600">Your provider account is not approved yet. Some actions are restricted.</div>
                    @endif
                </div>
            </div>
        @endif

        @if($user->isClient())
            <div class="bg-white p-4 rounded shadow mt-4">
                <h2 class="font-semibold">Parent Dashboard</h2>
                <p class="text-sm text-gray-600">Discover activities, submit your mood and get tailored weekend plans.</p>
                <div class="mt-3 space-x-2">
                    @if($user->hasPermission('view recommendations'))
                        <a href="{{ route('dashboard.client') }}" class="px-3 py-2 bg-indigo-600 text-white rounded">My Dashboard</a>
                    @endif
                    @if($user->hasPermission('submit mood'))
                        <a href="{{ route('mood.submit') ?? url('/mood') }}" class="px-3 py-2 bg-gray-100 rounded">Submit Mood</a>
                    @endif
                    <a href="{{ route('activities.index') }}" class="px-3 py-2 bg-gray-100 rounded">Browse Activities</a>
                </div>
            </div>
        @endif

    @else
        <div class="bg-white p-4 rounded shadow">
            <p>Please <a href="{{ route('login') }}" class="text-indigo-600">log in</a> to view your dashboard.</p>
        </div>
    @endauth

</div>

<script>
document.getElementById('accountTypeSelect')?.addEventListener('change', function(e){
    const val = e.target.value;
    if(val === 'super-admin'){
        window.location.href = '{{ route('dashboard.super-admin') }}';
    } else if(val === 'provider'){
        window.location.href = '{{ route('dashboard.provider') }}';
    } else {
        window.location.href = '{{ route('dashboard.client') }}';
    }
});
</script>
@endsection
