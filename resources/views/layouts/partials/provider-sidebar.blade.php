{{-- Provider Sidebar Links --}}
@php
    use App\Models\Provider;
    use App\Models\Transaction;
    use Illuminate\Support\Facades\Cache;

    $providerStats = [];
    $provider = null;

    if (auth()->check()) {
        // Cache provider stats for 5 minutes to improve performance
        $cacheKey = 'provider_stats_' . auth()->id();
        $providerStats = Cache::remember($cacheKey, 5 * 60, function () {
            $provider = Provider::where('user_id', auth()->id())->first();

            if (!$provider) {
                return [
                    'total_handled' => 0,
                    'active_services' => 0,
                    'pending_bookings' => 0,
                    'provider_type' => null
                ];
            }

            return [
                'total_handled' => Transaction::where('provider_id', $provider->id)->sum('amount') ?? 0,
                'active_services' => $provider->services()->where('is_active', true)->count() ?? 0,
                'pending_bookings' => $provider->bookings()->where('status', 'pending')->count() ?? 0,
                'provider_type' => $provider->provider_type ?? 'provider'
            ];
        });

        $provider = Provider::where('user_id', auth()->id())->first();
    }

    $isProfessional = str_contains($providerStats['provider_type'] ?? '', 'professional');
    $isBonding = str_contains($providerStats['provider_type'] ?? '', 'bonding');
@endphp

<div class="mt-4">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">Provider Dashboard</span>
        @if($provider && $provider->status === 'approved')
            <span class="badge bg-success badge-sm">✓ Verified</span>
        @elseif($provider && $provider->status === 'pending')
            <span class="badge bg-warning badge-sm">⏳ Pending</span>
        @endif
    </div>

    <ul class="list-unstyled ml-2 space-y-1">
        <!-- Core Provider Links -->
        <li class="mb-2">
            <a href="{{ route('dashboard.provider-professional') }}"
               class="d-flex align-items-center text-primary hover:underline {{ request()->routeIs('dashboard.provider-professional') ? 'fw-bold' : '' }}">
                <i class="fas fa-chart-line me-2"></i>Overview
            </a>
        </li>

        <li class="mb-2">
            <a href="{{ route('services.index') }}"
               class="d-flex align-items-center text-primary hover:underline {{ request()->routeIs('services.*') ? 'fw-bold' : '' }}">
                <i class="fas fa-concierge-bell me-2"></i>My Services
                @if($providerStats['active_services'] > 0)
                    <span class="badge bg-primary badge-sm ms-auto">{{ $providerStats['active_services'] }}</span>
                @endif
            </a>
        </li>

        <li class="mb-2">
            <a href="{{ route('bookings.index') }}"
               class="d-flex align-items-center text-primary hover:underline {{ request()->routeIs('bookings.*') ? 'fw-bold' : '' }}">
                <i class="fas fa-calendar-check me-2"></i>Bookings
                @if($providerStats['pending_bookings'] > 0)
                    <span class="badge bg-warning badge-sm ms-auto">{{ $providerStats['pending_bookings'] }}</span>
                @endif
            </a>
        </li>

        <!-- Professional Provider Specific Links -->
        @if($isProfessional)
            <li class="mb-2">
                <a href="{{ route('ai.chat') }}"
                   class="d-flex align-items-center text-primary hover:underline {{ request()->routeIs('ai.*') ? 'fw-bold' : '' }}">
                    <i class="fas fa-robot me-2"></i>AI Assistant
                </a>
            </li>

            <li class="mb-2">
                <a href="{{ route('training.index') }}"
                   class="d-flex align-items-center text-primary hover:underline {{ request()->routeIs('training.*') ? 'fw-bold' : '' }}">
                    <i class="fas fa-graduation-cap me-2"></i>Professional Development
                </a>
            </li>
        @endif

        <!-- Bonding Provider Specific Links -->
        @if($isBonding)
            <li class="mb-2">
                <a href="{{ route('dashboard.provider-bonding') }}"
                   class="d-flex align-items-center text-primary hover:underline {{ request()->routeIs('dashboard.provider-bonding') ? 'fw-bold' : '' }}">
                    <i class="fas fa-users me-2"></i>Bonding Activities
                </a>
            </li>

            <li class="mb-2">
                <a href="{{ route('activities.index') }}"
                   class="d-flex align-items-center text-primary hover:underline {{ request()->routeIs('activities.*') ? 'fw-bold' : '' }}">
                    <i class="fas fa-child me-2"></i>Community Events
                </a>
            </li>
        @endif

        <!-- Communication & Support -->
        <li class="mb-2">
            <a href="#" class="d-flex align-items-center text-primary hover:underline">
                <i class="fas fa-envelope me-2"></i>Messages
                <span class="badge bg-secondary badge-sm ms-auto">0</span>
            </a>
        </li>

        <li class="mb-2">
            <a href="#" class="d-flex align-items-center text-primary hover:underline">
                <i class="fas fa-bell me-2"></i>Notifications
            </a>
        </li>

        <!-- Financial Management -->
        <li class="mb-2">
            <a href="#" class="d-flex align-items-center text-primary hover:underline">
                <i class="fas fa-chart-bar me-2"></i>Analytics
            </a>
        </li>

        <li class="mb-2">
            <a href="#" class="d-flex align-items-center text-primary hover:underline">
                <i class="fas fa-dollar-sign me-2"></i>Financials
            </a>
        </li>

        <!-- Settings -->
        <li class="mb-2">
            <a href="{{ route('profile.edit') }}"
               class="d-flex align-items-center text-primary hover:underline {{ request()->routeIs('profile.*') ? 'fw-bold' : '' }}">
                <i class="fas fa-cog me-2"></i>Settings
            </a>
        </li>

        <!-- Provider Registration (if not approved yet) -->
        @if(!$provider || $provider->status !== 'approved')
            <li class="mb-2">
                <a href="{{ route('provider.register') }}"
                   class="d-flex align-items-center text-warning hover:underline">
                    <i class="fas fa-user-plus me-2"></i>Complete Registration
                </a>
            </li>
        @endif
    </ul>

    <!-- Provider Stats Summary -->
    <div class="mt-4 p-3 bg-light rounded">
        <h6 class="text-xs font-semibold uppercase tracking-wider text-gray-600 mb-2">Performance</h6>
        <div class="row g-2 text-sm">
            <div class="col-12">
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Total Revenue:</span>
                    <span class="fw-bold text-success">${{ number_format($providerStats['total_handled'], 2) }}</span>
                </div>
            </div>
            <div class="col-12">
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Active Services:</span>
                    <span class="fw-bold">{{ $providerStats['active_services'] }}</span>
                </div>
            </div>
            @if($providerStats['pending_bookings'] > 0)
                <div class="col-12">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Pending Bookings:</span>
                        <span class="fw-bold text-warning">{{ $providerStats['pending_bookings'] }}</span>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-3">
        <h6 class="text-xs font-semibold uppercase tracking-wider text-gray-600 mb-2">Quick Actions</h6>
        <div class="d-grid gap-1">
            <a href="{{ route('services.create') }}" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-plus me-1"></i>Add Service
            </a>
            @if($isBonding)
                <a href="{{ route('activities.create') }}" class="btn btn-sm btn-outline-success">
                    <i class="fas fa-calendar-plus me-1"></i>Create Activity
                </a>
            @endif
            <a href="{{ route('dashboard.provider-professional') }}" class="btn btn-sm btn-outline-info">
                <i class="fas fa-chart-line me-1"></i>View Analytics
            </a>
        </div>
    </div>

    <!-- Provider Type Badge -->
    <div class="mt-3 text-center">
        @if($isProfessional)
            <span class="badge bg-primary">🩺 Professional Provider</span>
        @elseif($isBonding)
            <span class="badge bg-success">🤝 Bonding Provider</span>
        @else
            <span class="badge bg-secondary">🏢 General Provider</span>
        @endif
    </div>
</div>

<style>
.hover\:underline:hover {
    text-decoration: underline !important;
}

.space-y-1 > * + * {
    margin-top: 0.25rem;
}

.tracking-wider {
    letter-spacing: 0.05em;
}

.badge-sm {
    font-size: 0.7rem;
    padding: 0.2rem 0.4rem;
}
</style>
