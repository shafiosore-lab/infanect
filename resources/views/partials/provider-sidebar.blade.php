{{-- Provider Sidebar Links --}}
@php
    use App\Models\Provider;
    use App\Models\Transaction;
    use Illuminate\Support\Facades\Cache;

    $providerStats = [];
    $provider = null;

    if (auth()->check()) {
        $cacheKey = 'provider_stats_' . auth()->id();
        $providerStats = Cache::remember($cacheKey, 5 * 60, function () {
            $provider = Provider::where('user_id', auth()->id())->first();

            if (!$provider) {
                return [
                    'total_handled' => 0,
                    'active_services' => 0,
                    'pending_bookings' => 0,
                    'provider_type' => null,
                ];
            }

            return [
                'total_handled' => Transaction::where('provider_id', $provider->id)->sum('amount') ?? 0,
                'active_services' => $provider->services()->where('is_active', true)->count() ?? 0,
                'pending_bookings' => $provider->bookings()->where('status', 'pending')->count() ?? 0,
                'provider_type' => $provider->provider_type ?? 'provider',
            ];
        });

        $provider = Provider::where('user_id', auth()->id())->first();
    }

    $isProfessional = str_contains($providerStats['provider_type'] ?? '', 'professional');
    $isBonding = str_contains($providerStats['provider_type'] ?? '', 'bonding');
@endphp

<div class="mt-4">
    <!-- Header -->
    <div class="flex items-center justify-between mb-3">
        <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">Provider Dashboard</span>
        @if($provider && $provider->status === 'approved')
            <span class="px-2 py-0.5 text-xs rounded bg-green-600 text-white">✓ Verified</span>
        @elseif($provider && $provider->status === 'pending')
            <span class="px-2 py-0.5 text-xs rounded bg-yellow-500 text-white">⏳ Pending</span>
        @endif
    </div>

    <!-- Navigation -->
    <ul class="space-y-1">
        <!-- Core -->
        <li>
            <a href="{{ route('dashboard.provider-professional') }}"
               class="flex items-center px-3 py-2 rounded hover:bg-primary/10 text-sm font-medium {{ request()->routeIs('dashboard.provider-professional') ? 'bg-primary/20 text-primary' : 'text-gray-700' }}">
                <i class="fas fa-chart-line w-5"></i>
                <span class="ml-2">Overview</span>
            </a>
        </li>

        <li>
            <a href="{{ route('services.index') }}"
               class="flex items-center px-3 py-2 rounded hover:bg-primary/10 text-sm font-medium {{ request()->routeIs('services.*') ? 'bg-primary/20 text-primary' : 'text-gray-700' }}">
                <i class="fas fa-concierge-bell w-5"></i>
                <span class="ml-2">My Services</span>
                @if($providerStats['active_services'] > 0)
                    <span class="ml-auto bg-primary text-white text-xs px-2 py-0.5 rounded-full">
                        {{ $providerStats['active_services'] }}
                    </span>
                @endif
            </a>
        </li>

        <li>
            <a href="{{ route('bookings.index') }}"
               class="flex items-center px-3 py-2 rounded hover:bg-primary/10 text-sm font-medium {{ request()->routeIs('bookings.*') ? 'bg-primary/20 text-primary' : 'text-gray-700' }}">
                <i class="fas fa-calendar-check w-5"></i>
                <span class="ml-2">Bookings</span>
                @if($providerStats['pending_bookings'] > 0)
                    <span class="ml-auto bg-yellow-500 text-white text-xs px-2 py-0.5 rounded-full">
                        {{ $providerStats['pending_bookings'] }}
                    </span>
                @endif
            </a>
        </li>

        <!-- Professional -->
        @if($isProfessional)
            <li>
                <a href="{{ route('clients.index') }}"
                   class="flex items-center px-3 py-2 rounded hover:bg-primary/10 text-sm font-medium {{ request()->routeIs('clients.*') ? 'bg-primary/20 text-primary' : 'text-gray-700' }}">
                    <i class="fas fa-user-friends w-5"></i>
                    <span class="ml-2">My Clients</span>
                </a>
            </li>
            <li>
                <a href="{{ route('messages.index') }}"
                   class="flex items-center px-3 py-2 rounded hover:bg-primary/10 text-sm font-medium {{ request()->routeIs('messages.*') ? 'bg-primary/20 text-primary' : 'text-gray-700' }}">
                    <i class="fas fa-envelope-open-text w-5"></i>
                    <span class="ml-2">Message Management</span>
                </a>
            </li>
            <li>
                <a href="{{ route('ai.chat') }}"
                   class="flex items-center px-3 py-2 rounded hover:bg-primary/10 text-sm font-medium {{ request()->routeIs('ai.*') ? 'bg-primary/20 text-primary' : 'text-gray-700' }}">
                    <i class="fas fa-robot w-5"></i>
                    <span class="ml-2">AI Assistant</span>
                </a>
            </li>
            <li>
                <a href="{{ route('training.index') }}"
                   class="flex items-center px-3 py-2 rounded hover:bg-primary/10 text-sm font-medium {{ request()->routeIs('training.*') ? 'bg-primary/20 text-primary' : 'text-gray-700' }}">
                    <i class="fas fa-graduation-cap w-5"></i>
                    <span class="ml-2">Professional Development</span>
                </a>
            </li>
        @endif

        <!-- Bonding -->
        @if($isBonding)
            <li>
                <a href="{{ route('dashboard.provider-bonding') }}"
                   class="flex items-center px-3 py-2 rounded hover:bg-primary/10 text-sm font-medium {{ request()->routeIs('dashboard.provider-bonding') ? 'bg-primary/20 text-primary' : 'text-gray-700' }}">
                    <i class="fas fa-users w-5"></i>
                    <span class="ml-2">Bonding Activities</span>
                </a>
            </li>
            <li>
                <a href="{{ route('activities.index') }}"
                   class="flex items-center px-3 py-2 rounded hover:bg-primary/10 text-sm font-medium {{ request()->routeIs('activities.*') ? 'bg-primary/20 text-primary' : 'text-gray-700' }}">
                    <i class="fas fa-child w-5"></i>
                    <span class="ml-2">Community Events</span>
                </a>
            </li>
            <li>
                <a href="{{ route('families.index') }}"
                   class="flex items-center px-3 py-2 rounded hover:bg-primary/10 text-sm font-medium {{ request()->routeIs('families.*') ? 'bg-primary/20 text-primary' : 'text-gray-700' }}">
                    <i class="fas fa-home w-5"></i>
                    <span class="ml-2">Families</span>
                </a>
            </li>
            <li>
                <a href="{{ route('community.analytics') }}"
                   class="flex items-center px-3 py-2 rounded hover:bg-primary/10 text-sm font-medium {{ request()->routeIs('community.*') ? 'bg-primary/20 text-primary' : 'text-gray-700' }}">
                    <i class="fas fa-chart-pie w-5"></i>
                    <span class="ml-2">Community Impact</span>
                </a>
            </li>
            <li>
                <a href="{{ route('messages.index') }}"
                   class="flex items-center px-3 py-2 rounded hover:bg-primary/10 text-sm font-medium {{ request()->routeIs('messages.*') ? 'bg-primary/20 text-primary' : 'text-gray-700' }}">
                    <i class="fas fa-envelope w-5"></i>
                    <span class="ml-2">Message Management</span>
                </a>
            </li>
        @endif

        <!-- Generic -->
        <li>
            <a href="#" class="flex items-center px-3 py-2 rounded hover:bg-primary/10 text-sm font-medium text-gray-700">
                <i class="fas fa-bell w-5"></i>
                <span class="ml-2">Notifications</span>
            </a>
        </li>

        <li>
            <a href="#" class="flex items-center px-3 py-2 rounded hover:bg-primary/10 text-sm font-medium text-gray-700">
                <i class="fas fa-dollar-sign w-5"></i>
                <span class="ml-2">Financials</span>
            </a>
        </li>

        <!-- Settings -->
        <li>
            <a href="{{ route('profile.edit') }}"
               class="flex items-center px-3 py-2 rounded hover:bg-primary/10 text-sm font-medium {{ request()->routeIs('profile.*') ? 'bg-primary/20 text-primary' : 'text-gray-700' }}">
                <i class="fas fa-cog w-5"></i>
                <span class="ml-2">Settings</span>
            </a>
        </li>

        <!-- Registration -->
        @if(!$provider || $provider->status !== 'approved')
            <li>
                <a href="{{ route('provider.register') }}"
                   class="flex items-center px-3 py-2 rounded hover:bg-yellow-100 text-sm font-medium text-yellow-600">
                    <i class="fas fa-user-plus w-5"></i>
                    <span class="ml-2">Complete Registration</span>
                </a>
            </li>
        @endif
    </ul>

    <!-- Stats -->
    <div class="mt-4 p-4 bg-gray-50 rounded">
        <h6 class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2">Performance</h6>
        <div class="space-y-2 text-sm">
            <div class="flex justify-between">
                <span class="text-gray-500">Total Revenue:</span>
                <span class="font-semibold text-green-600">${{ number_format($providerStats['total_handled'], 2) }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Active Services:</span>
                <span class="font-semibold">{{ $providerStats['active_services'] }}</span>
            </div>
            @if($providerStats['pending_bookings'] > 0)
                <div class="flex justify-between">
                    <span class="text-gray-500">Pending Bookings:</span>
                    <span class="font-semibold text-yellow-600">{{ $providerStats['pending_bookings'] }}</span>
                </div>
            @endif
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-4">
        <h6 class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2">Quick Actions</h6>
        <div class="space-y-2">
            <a href="{{ route('services.create') }}" class="w-full flex items-center justify-center px-3 py-2 rounded border text-sm text-primary border-primary hover:bg-primary hover:text-white">
                <i class="fas fa-plus mr-1"></i> Add Service
            </a>
            @if($isBonding)
                <a href="{{ route('activities.create') }}" class="w-full flex items-center justify-center px-3 py-2 rounded border text-sm text-green-600 border-green-600 hover:bg-green-600 hover:text-white">
                    <i class="fas fa-calendar-plus mr-1"></i> Create Activity
                </a>
            @endif
            <a href="{{ route('dashboard.provider-professional') }}" class="w-full flex items-center justify-center px-3 py-2 rounded border text-sm text-blue-600 border-blue-600 hover:bg-blue-600 hover:text-white">
                <i class="fas fa-chart-line mr-1"></i> View Analytics
            </a>
        </div>
    </div>

    <!-- Provider Type -->
    <div class="mt-4 text-center">
        @if($isProfessional)
            <span class="px-3 py-1 text-xs rounded bg-primary text-white">🩺 Professional Provider</span>
        @elseif($isBonding)
            <span class="px-3 py-1 text-xs rounded bg-green-600 text-white">🤝 Bonding Provider</span>
        @else
            <span class="px-3 py-1 text-xs rounded bg-gray-500 text-white">🏢 General Provider</span>
        @endif
    </div>
</div>
{{-- End of Provider Sidebar Links --}}
