@extends('layouts.app')

@section('content')
@php
    // Helper function to check if route exists
    if (!function_exists('route_exists')) {
        function route_exists($name) {
            return app('router')->has($name);
        }
    }
@endphp

<div class="container-fluid">
    <!-- Welcome Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card text-white" style="background: linear-gradient(135deg, #ea1c4d 0%, #fbc761 100%)">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="mb-1">👋 {{ $welcomeMessage ?? 'Welcome to InfaNect!' }}</h2>
                            <p class="mb-0">Hello {{ $user->name }}, ready to explore?</p>
                        </div>
                        <div class="col-md-4 text-end">
                            @if(isset($trainingRoute) && $trainingRoute)
                                <a href="{{ $trainingRoute }}" class="btn btn-light btn-sm">
                                    <i class="fas fa-graduation-cap me-1"></i>Start Learning
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Determine Logged-in User Role and Dashboard Type -->
    @php
        $userRole = $role ?? ($user->role_id ?? ($user->role->slug ?? 'client'));
        $providerData = json_decode($user->provider_data ?? '{}', true);
        $providerType = $providerData['provider_type'] ?? null;
        $dashboardType = request()->get('dashboard_type');

        $isProvider = in_array($userRole, ['provider-professional', 'provider-bonding']) ||
                      in_array($providerType, ['provider-professional', 'provider-bonding']);

        // Determine dashboard to display
        if($dashboardType) {
            $currentDashboard = $dashboardType;
        } elseif($providerType) {
            $currentDashboard = $providerType;
        } else {
            $currentDashboard = 'client';
        }
    @endphp

    <!-- Provider Dashboard Alert -->
    @if($isProvider)
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info d-flex align-items-center">
                <i class="fas fa-info-circle me-3 fs-4"></i>
                <div>
                    <h5 class="mb-1">{{ ucfirst(str_replace('-', ' ', $currentDashboard)) }} Dashboard Detected</h5>
                    <p class="mb-2">Access your specialized dashboard for tools and insights.</p>
                    @if($currentDashboard === 'provider-professional')
                        <a href="{{ route_exists('dashboard.provider-professional') ? route('dashboard.provider-professional') : '#' }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-stethoscope me-1"></i>Professional Dashboard
                        </a>
                    @elseif($currentDashboard === 'provider-bonding')
                        <a href="{{ route_exists('dashboard.provider-bonding') ? route('dashboard.provider-bonding') : '#' }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-users me-1"></i>Bonding Dashboard
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Dashboard Metrics -->
    <div class="row mb-4">
        @if(isset($metrics) && is_array($metrics))
            @foreach($metrics as $metric)
            <div class="col-md-4 mb-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="rounded-circle bg-{{ $metric['color'] ?? 'primary' }} bg-opacity-10 p-3">
                                    <i class="fas {{ $metric['icon'] ?? 'fa-chart-bar' }} text-{{ $metric['color'] ?? 'primary' }} fs-4"></i>
                                </div>
                            </div>
                            <div class="ms-3">
                                <div class="small text-muted">{{ $metric['title'] ?? 'Metric' }}</div>
                                <div class="h4 mb-0">{{ $metric['value'] ?? '0' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        @endif
    </div>

    <!-- Dashboard Options -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">🔧 Dashboard Options</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">Switch between dashboards or register:</p>
                    <div class="d-flex gap-2 flex-wrap">
                        @if(in_array($userRole, ['provider-professional', 'provider-bonding']))
                            <a href="{{ route('dashboard') }}?dashboard_type=professional" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-stethoscope me-1"></i>Professional Provider
                            </a>
                            <a href="{{ route('dashboard') }}?dashboard_type=bonding" class="btn btn-outline-warning btn-sm">
                                <i class="fas fa-users me-1"></i>Bonding Provider
                            </a>
                        @endif
                        @if(route_exists('provider.register'))
                            <a href="{{ route('provider.register') }}" class="btn btn-success btn-sm">
                                <i class="fas fa-user-plus me-1"></i>Register as Provider
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Any dashboard-specific JavaScript can go here
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Dashboard loaded successfully');
    });
</script>
@endpush
