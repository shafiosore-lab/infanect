@extends('layouts.app')

@section('content')
@php
    use App\Models\Provider;
    use App\Models\Activity;
    use App\Models\Booking;
    use Illuminate\Support\Facades\Cache;

    // Get bonding provider data with caching for performance
    $cacheKey = 'bonding_provider_dashboard_' . auth()->id();
    $providerData = Cache::remember($cacheKey, 5 * 60, function () {
        $provider = Provider::where('user_id', auth()->id())->first();

        if (!$provider) {
            return [
                'provider' => null,
                'stats' => ['total_activities' => 0, 'pending_bookings' => 0, 'today_sessions' => 0, 'total_participants' => 0],
                'kyc_status' => 'not_registered',
                'provider_type' => 'provider-bonding'
            ];
        }

        return [
            'provider' => $provider,
            'stats' => [
                'total_activities' => Activity::where('provider_profile_id', $provider->id)->count() ?? 0,
                'pending_bookings' => Booking::where('provider_id', $provider->id)->where('status', 'pending')->count() ?? 0,
                'today_sessions' => Activity::where('provider_profile_id', $provider->id)->whereDate('start_date', today())->count() ?? 0,
                'total_participants' => Booking::whereHas('activity', function($q) use ($provider) {
                    $q->where('provider_profile_id', $provider->id);
                })->count() ?? 0,
            ],
            'kyc_status' => $provider->kyc_status ?? 'pending',
            'provider_type' => $provider->provider_type ?? 'provider-bonding'
        ];
    });

    $isBonding = str_contains($providerData['provider_type'] ?? '', 'bonding');
@endphp

<div class="flex min-h-screen bg-gray-50">
    <!-- Bonding Provider Sidebar -->
    <aside class="w-72 bg-white shadow-lg border-r border-gray-200" id="bonding-sidebar">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center text-white font-bold text-lg">
                    🤝
                </div>
                <div class="ml-3">
                    <h3 class="font-bold text-gray-900">Bonding Provider</h3>
                    <p class="text-sm text-gray-600">{{ Auth::user()->name }}</p>

                    <!-- KYC Status Badge -->
                    @if($providerData['kyc_status'] === 'approved')
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 mt-1">
                            ✓ Verified
                        </span>
                    @elseif($providerData['kyc_status'] === 'pending')
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800 mt-1">
                            ⏳ Pending Review
                        </span>
                    @else
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 mt-1">
                            ❌ Not Verified
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <nav class="mt-6 overflow-y-auto max-h-screen pb-20">
            <!-- Dashboard -->
            <div class="px-6 mb-6">
                @if(Route::has('dashboard.provider-bonding'))
                    <a href="{{ route('dashboard.provider-bonding') }}"
                       class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors {{ request()->routeIs('dashboard.provider-bonding') ? 'bg-green-50 text-green-700 border-r-2 border-green-700' : '' }}"
                       aria-current="{{ request()->routeIs('dashboard.provider-bonding') ? 'page' : 'false' }}">
                        <i class="fas fa-tachometer-alt w-5 h-5 mr-3" aria-hidden="true"></i>
                        <span class="font-medium">Bonding Dashboard</span>
                    </a>
                @endif
            </div>

            <!-- Activities Management -->
            <div class="px-6 mb-2">
                <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Community Activities</h4>
            </div>
            <div class="px-6 mb-6 space-y-1">
                @if(Route::has('activities.index'))
                    <a href="{{ route('activities.index') }}"
                       class="flex items-center px-4 py-2 text-sm text-gray-700 rounded-lg hover:bg-gray-100 transition-colors {{ request()->routeIs('activities.*') ? 'bg-gray-100 text-gray-900' : '' }}">
                        <i class="fas fa-calendar-alt w-4 h-4 mr-3" aria-hidden="true"></i>
                        My Activities
                        @if($providerData['stats']['total_activities'] > 0)
                            <span class="ml-auto bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                {{ $providerData['stats']['total_activities'] }}
                            </span>
                        @endif
                    </a>
                @endif

                @if(Route::has('activities.create'))
                    <a href="{{ route('activities.create') }}"
                       class="flex items-center px-4 py-2 text-sm text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">
                        <i class="fas fa-plus w-4 h-4 mr-3" aria-hidden="true"></i>
                        Create Activity
                    </a>
                @endif

                <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">
                    <i class="fas fa-users w-4 h-4 mr-3" aria-hidden="true"></i>
                    Community Events
                </a>

                <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">
                    <i class="fas fa-child w-4 h-4 mr-3" aria-hidden="true"></i>
                    Family Programs
                </a>
            </div>

            <!-- Participant Management -->
            <div class="px-6 mb-2">
                <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Participants</h4>
            </div>
            <div class="px-6 mb-6 space-y-1">
                @if(Route::has('bookings.index'))
                    <a href="{{ route('bookings.index') }}"
                       class="flex items-center px-4 py-2 text-sm text-gray-700 rounded-lg hover:bg-gray-100 transition-colors {{ request()->routeIs('bookings.*') ? 'bg-gray-100 text-gray-900' : '' }}">
                        <i class="fas fa-users w-4 h-4 mr-3" aria-hidden="true"></i>
                        All Participants
                    </a>

                    <a href="{{ route('bookings.index') }}?status=pending"
                       class="flex items-center px-4 py-2 text-sm text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">
                        <i class="fas fa-clock w-4 h-4 mr-3" aria-hidden="true"></i>
                        Pending Registrations
                        @if($providerData['stats']['pending_bookings'] > 0)
                            <span class="ml-auto bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                {{ $providerData['stats']['pending_bookings'] }}
                            </span>
                        @endif
                    </a>

                    <a href="{{ route('bookings.index') }}?status=today"
                       class="flex items-center px-4 py-2 text-sm text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">
                        <i class="fas fa-calendar-day w-4 h-4 mr-3" aria-hidden="true"></i>
                        Today's Activities
                        @if($providerData['stats']['today_sessions'] > 0)
                            <span class="ml-auto bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                {{ $providerData['stats']['today_sessions'] }}
                            </span>
                        @endif
                    </a>
                @endif

                <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">
                    <i class="fas fa-heart w-4 h-4 mr-3" aria-hidden="true"></i>
                    Family Feedback
                </a>
            </div>

            <!-- KYC & Verification -->
            @if($providerData['kyc_status'] !== 'approved')
                <div class="px-6 mb-2">
                    <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Verification</h4>
                </div>
                <div class="px-6 mb-6 space-y-1">
                    @if(Route::has('provider.register'))
                        <a href="{{ route('provider.register') }}"
                           class="flex items-center px-4 py-2 text-sm text-orange-700 bg-orange-50 rounded-lg hover:bg-orange-100 transition-colors">
                            <i class="fas fa-id-card w-4 h-4 mr-3" aria-hidden="true"></i>
                            Complete Background Check
                            <span class="ml-auto text-orange-600">!</span>
                        </a>
                    @endif
                </div>
            @endif

            <!-- Community Analytics -->
            <div class="px-6 mb-2">
                <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Community Impact</h4>
            </div>
            <div class="px-6 mb-6 space-y-1">
                <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">
                    <i class="fas fa-chart-line w-4 h-4 mr-3" aria-hidden="true"></i>
                    Engagement Analytics
                    <span class="ml-auto text-xs text-gray-500">{{ $providerData['stats']['total_participants'] }} families</span>
                </a>
                <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">
                    <i class="fas fa-trophy w-4 h-4 mr-3" aria-hidden="true"></i>
                    Impact Reports
                </a>
                <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">
                    <i class="fas fa-handshake w-4 h-4 mr-3" aria-hidden="true"></i>
                    Bonding Metrics
                </a>
            </div>

            <!-- Communication & Outreach -->
            <div class="px-6 mb-2">
                <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Community Outreach</h4>
            </div>
            <div class="px-6 mb-6 space-y-1">
                <button onclick="openBulkSMS()" class="flex items-center w-full px-4 py-2 text-sm text-gray-700 rounded-lg hover:bg-gray-100 transition-colors text-left">
                    <i class="fas fa-sms w-4 h-4 mr-3" aria-hidden="true"></i>
                    Family Notifications
                </button>
                <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">
                    <i class="fas fa-bullhorn w-4 h-4 mr-3" aria-hidden="true"></i>
                    Event Promotion
                </a>
                <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">
                    <i class="fas fa-bell w-4 h-4 mr-3" aria-hidden="true"></i>
                    Community Updates
                </a>
            </div>

            <!-- Resource Center -->
            <div class="px-6 mb-2">
                <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Resources</h4>
            </div>
            <div class="px-6 mb-6 space-y-1">
                @if(Route::has('training.index'))
                    <a href="{{ route('training.index') }}"
                       class="flex items-center px-4 py-2 text-sm text-gray-700 rounded-lg hover:bg-gray-100 transition-colors {{ request()->routeIs('training.*') ? 'bg-gray-100 text-gray-900' : '' }}">
                        <i class="fas fa-book w-4 h-4 mr-3" aria-hidden="true"></i>
                        Activity Guidelines
                    </a>
                @endif

                <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">
                    <i class="fas fa-shield-alt w-4 h-4 mr-3" aria-hidden="true"></i>
                    Safety Protocols
                </a>

                <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">
                    <i class="fas fa-question-circle w-4 h-4 mr-3" aria-hidden="true"></i>
                    Support Center
                </a>
            </div>

            <!-- Settings -->
            <div class="px-6 mb-2">
                <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Settings</h4>
            </div>
            <div class="px-6 space-y-1 mb-6">
                @if(Route::has('profile.edit'))
                    <a href="{{ route('profile.edit') }}"
                       class="flex items-center px-4 py-2 text-sm text-gray-700 rounded-lg hover:bg-gray-100 transition-colors {{ request()->routeIs('profile.*') ? 'bg-gray-100 text-gray-900' : '' }}">
                        <i class="fas fa-user-cog w-4 h-4 mr-3" aria-hidden="true"></i>
                        Profile Settings
                    </a>
                @endif
                <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">
                    <i class="fas fa-cog w-4 h-4 mr-3" aria-hidden="true"></i>
                    Activity Preferences
                </a>
            </div>
        </nav>

        <!-- User Profile (Fixed at bottom) -->
        <div class="fixed bottom-0 w-72 p-6 border-t border-gray-200 bg-white">
            <div class="flex items-center">
                <div class="w-8 h-8 bg-green-300 rounded-full flex items-center justify-center">
                    <span class="text-sm font-medium text-green-700">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </span>
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-500">Bonding Provider</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-gray-400 hover:text-gray-600 transition-colors" title="Logout">
                        <i class="fas fa-sign-out-alt w-4 h-4" aria-hidden="true"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="flex-1 overflow-y-auto">
        <!-- Top Header -->
        <header class="bg-white shadow-sm border-b border-gray-200 px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        @yield('page-title', 'Bonding Dashboard')
                    </h1>
                    <p class="text-sm text-gray-600">
                        @yield('page-description', 'Create meaningful connections through community activities')
                    </p>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Quick Actions -->
                    <div class="flex items-center space-x-2">
                        @if(Route::has('activities.create'))
                            <a href="{{ route('activities.create') }}"
                               class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                                <i class="fas fa-plus mr-2" aria-hidden="true"></i>
                                New Activity
                            </a>
                        @endif
                        <button onclick="openBulkSMS()"
                                class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                            <i class="fas fa-bullhorn mr-2" aria-hidden="true"></i>
                            Notify Families
                        </button>
                    </div>

                    <!-- Mobile sidebar toggle -->
                    <button onclick="toggleSidebar()" class="md:hidden p-2 text-gray-400 hover:text-gray-600">
                        <i class="fas fa-bars w-5 h-5" aria-hidden="true"></i>
                    </button>

                    <!-- Notifications -->
                    <div class="relative">
                        <button class="p-2 text-gray-400 hover:text-gray-600 transition-colors" aria-label="Notifications">
                            <i class="fas fa-bell w-5 h-5" aria-hidden="true"></i>
                            @if($providerData['stats']['pending_bookings'] > 0)
                                <span class="absolute -top-1 -right-1 block h-3 w-3 rounded-full bg-red-400"></span>
                            @endif
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <div class="p-6">
            @yield('content')
        </div>
    </main>
</div>

<!-- Bulk SMS Modal (Bonding-specific) -->
<div id="bulkSMSModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50" onclick="closeBulkSMS()">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
        <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6" onclick="event.stopPropagation()">
            <form id="bulkSMSForm">
                @csrf
                <div class="mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Send Family Notification</h3>
                    <p class="text-sm text-gray-500">Notify families about upcoming activities or important updates</p>
                </div>

                <div class="mb-4">
                    <label for="sms_recipients" class="block text-sm font-medium text-gray-700 mb-2">Recipients</label>
                    <select id="sms_recipients" name="recipients" class="w-full border border-gray-300 rounded-md px-3 py-2">
                        <option value="all_families">All Registered Families</option>
                        <option value="active_participants">Active Participants</option>
                        <option value="upcoming_activities">Families with Upcoming Activities</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="sms_message" class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                    <textarea id="sms_message" name="message" rows="4"
                              class="w-full border border-gray-300 rounded-md px-3 py-2 resize-none"
                              placeholder="Hi families! We have an exciting new bonding activity coming up..."></textarea>
                    <div class="text-right text-xs text-gray-500 mt-1">
                        <span id="sms_char_count">0</span>/160 characters
                    </div>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeBulkSMS()"
                            class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 border border-transparent rounded-md text-sm font-medium text-white bg-green-600 hover:bg-green-700">
                        <i class="fas fa-paper-plane mr-2"></i>Send Notifications
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openBulkSMS() {
    document.getElementById('bulkSMSModal').classList.remove('hidden');
}

function closeBulkSMS() {
    document.getElementById('bulkSMSModal').classList.add('hidden');
    document.getElementById('bulkSMSForm').reset();
    document.getElementById('sms_char_count').textContent = '0';
}

// Character counter for SMS
document.getElementById('sms_message').addEventListener('input', function() {
    const count = this.value.length;
    document.getElementById('sms_char_count').textContent = count;

    // Change color when approaching limit
    const counter = document.getElementById('sms_char_count');
    if (count > 140) {
        counter.classList.add('text-red-500');
    } else {
        counter.classList.remove('text-red-500');
    }
});

// Mobile sidebar toggle
function toggleSidebar() {
    const sidebar = document.getElementById('bonding-sidebar');
    sidebar.classList.toggle('hidden');
}

// Form submission
document.getElementById('bulkSMSForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    // Here you would typically send the data to your backend
    console.log('Sending SMS notification...', Object.fromEntries(formData));

    // Show success message
    alert('Family notifications sent successfully!');
    closeBulkSMS();
});
</script>

<style>
@media (max-width: 768px) {
    #bonding-sidebar {
        position: fixed;
        top: 0;
        left: 0;
        z-index: 40;
        height: 100vh;
    }
}

.transition-colors {
    transition: background-color 0.15s ease-in-out, color 0.15s ease-in-out;
}
</style>
@endsection
