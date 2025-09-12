@extends('layouts.app')

@section('content')
@php
    use App\Models\Provider;
    use App\Models\Booking;
    use Illuminate\Support\Facades\Cache;

    // Get provider data with caching for performance
    $cacheKey = 'provider_dashboard_' . auth()->id();
    $providerData = Cache::remember($cacheKey, 5 * 60, function () {
        $provider = Provider::where('user_id', auth()->id())->first();

        if (!$provider) {
            return [
                'provider' => null,
                'stats' => ['total_services' => 0, 'pending_bookings' => 0, 'today_sessions' => 0, 'total_revenue' => 0],
                'kyc_status' => 'not_registered',
                'provider_type' => null
            ];
        }

        return [
            'provider' => $provider,
            'stats' => [
                'total_services' => $provider->services()->where('is_active', true)->count() ?? 0,
                'pending_bookings' => Booking::where('provider_id', $provider->id)->where('status', 'pending')->count() ?? 0,
                'today_sessions' => Booking::where('provider_id', $provider->id)->whereDate('booking_date', today())->count() ?? 0,
                'total_revenue' => Booking::where('provider_id', $provider->id)->where('status', 'completed')->sum('amount') ?? 0,
            ],
            'kyc_status' => $provider->kyc_status ?? 'pending',
            'provider_type' => $provider->provider_type ?? 'provider-professional'
        ];
    });

    $isProfessional = str_contains($providerData['provider_type'] ?? '', 'professional');
    $isBonding = str_contains($providerData['provider_type'] ?? '', 'bonding');
@endphp

<div class="flex min-h-screen bg-gray-50">
    <!-- Professional Provider Sidebar -->
    <aside class="w-72 bg-white shadow-lg border-r border-gray-200" id="provider-sidebar">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold text-lg">
                    @if($isProfessional)
                        🩺
                    @elseif($isBonding)
                        🤝
                    @else
                        🏢
                    @endif
                </div>
                <div class="ml-3">
                    <h3 class="font-bold text-gray-900">
                        @if($isProfessional)
                            Professional Provider
                        @elseif($isBonding)
                            Bonding Provider
                        @else
                            Service Provider
                        @endif
                    </h3>
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
                @if(Route::has('dashboard.provider-professional'))
                    <a href="{{ route('dashboard.provider-professional') }}"
                       class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-700 transition-colors {{ request()->routeIs('dashboard.provider-professional') ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-700' : '' }}"
                       aria-current="{{ request()->routeIs('dashboard.provider-professional') ? 'page' : 'false' }}">
                        <i class="fas fa-tachometer-alt w-5 h-5 mr-3" aria-hidden="true"></i>
                        <span class="font-medium">Dashboard</span>
                    </a>
                @endif
            </div>

            <!-- Services Management -->
            <div class="px-6 mb-2">
                <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Services</h4>
            </div>
            <div class="px-6 mb-6 space-y-1">
                @if(Route::has('services.index'))
                    <a href="{{ route('services.index') }}"
                       class="flex items-center px-4 py-2 text-sm text-gray-700 rounded-lg hover:bg-gray-100 transition-colors {{ request()->routeIs('services.*') ? 'bg-gray-100 text-gray-900' : '' }}">
                        <i class="fas fa-cogs w-4 h-4 mr-3" aria-hidden="true"></i>
                        My Services
                        @if($providerData['stats']['total_services'] > 0)
                            <span class="ml-auto bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                {{ $providerData['stats']['total_services'] }}
                            </span>
                        @endif
                    </a>
                @endif

                @if(Route::has('services.create'))
                    <a href="{{ route('services.create') }}"
                       class="flex items-center px-4 py-2 text-sm text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">
                        <i class="fas fa-plus w-4 h-4 mr-3" aria-hidden="true"></i>
                        Add Service
                    </a>
                @endif
            </div>

            <!-- Bookings & Appointments -->
            <div class="px-6 mb-2">
                <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Appointments</h4>
            </div>
            <div class="px-6 mb-6 space-y-1">
                @if(Route::has('bookings.index'))
                    <a href="{{ route('bookings.index') }}"
                       class="flex items-center px-4 py-2 text-sm text-gray-700 rounded-lg hover:bg-gray-100 transition-colors {{ request()->routeIs('bookings.*') ? 'bg-gray-100 text-gray-900' : '' }}">
                        <i class="fas fa-calendar-alt w-4 h-4 mr-3" aria-hidden="true"></i>
                        All Bookings
                    </a>

                    <a href="{{ route('bookings.index') }}?status=pending"
                       class="flex items-center px-4 py-2 text-sm text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">
                        <i class="fas fa-clock w-4 h-4 mr-3" aria-hidden="true"></i>
                        Pending Approvals
                        @if($providerData['stats']['pending_bookings'] > 0)
                            <span class="ml-auto bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                {{ $providerData['stats']['pending_bookings'] }}
                            </span>
                        @endif
                    </a>

                    <a href="{{ route('bookings.index') }}?status=today"
                       class="flex items-center px-4 py-2 text-sm text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">
                        <i class="fas fa-calendar-day w-4 h-4 mr-3" aria-hidden="true"></i>
                        Today's Sessions
                        @if($providerData['stats']['today_sessions'] > 0)
                            <span class="ml-auto bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                {{ $providerData['stats']['today_sessions'] }}
                            </span>
                        @endif
                    </a>
                @endif
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
                            Complete KYC Documents
                            <span class="ml-auto text-orange-600">!</span>
                        </a>
                    @endif
                </div>
            @else
                <!-- Add Update KYC button for approved providers -->
                <div class="px-6 mb-2">
                    <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Verification</h4>
                </div>
                <div class="px-6 mb-6 space-y-1">
                    @if(Route::has('provider.update-kyc'))
                        <a href="{{ route('provider.update-kyc') }}"
                           class="flex items-center px-4 py-2 text-sm text-green-700 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                            <i class="fas fa-shield-check w-4 h-4 mr-3" aria-hidden="true"></i>
                            Update KYC Documents
                        </a>
                    @endif
                    <div class="flex items-center px-4 py-2 text-sm text-green-700">
                        <i class="fas fa-check-circle w-4 h-4 mr-3" aria-hidden="true"></i>
                        <span>KYC Verified</span>
                        <span class="ml-auto text-xs text-green-600">✓</span>
                    </div>
                </div>
            @endif

            <!-- Financial Management -->
            <div class="px-6 mb-2">
                <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Financials</h4>
            </div>
            <div class="px-6 mb-6 space-y-1">
                <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">
                    <i class="fas fa-chart-line w-4 h-4 mr-3" aria-hidden="true"></i>
                    Revenue Analytics
                    <span class="ml-auto text-xs text-gray-500">${{ number_format($providerData['stats']['total_revenue']) }}</span>
                </a>
                <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">
                    <i class="fas fa-file-invoice-dollar w-4 h-4 mr-3" aria-hidden="true"></i>
                    Invoices & Payments
                </a>
                <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">
                    <i class="fas fa-receipt w-4 h-4 mr-3" aria-hidden="true"></i>
                    Expense Tracking
                </a>
            </div>

            <!-- Client Management -->
            <div class="px-6 mb-2">
                <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Clients</h4>
            </div>
            <div class="px-6 mb-6 space-y-1">
                <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">
                    <i class="fas fa-users w-4 h-4 mr-3" aria-hidden="true"></i>
                    Client Directory
                </a>

                <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">
                    <i class="fas fa-star w-4 h-4 mr-3" aria-hidden="true"></i>
                    Reviews & Feedback
                </a>
                <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">
                    <i class="fas fa-brain w-4 h-4 mr-3" aria-hidden="true"></i>
                    Mood Insights
                </a>
            </div>

            <!-- Bonding Activities (if applicable) -->
            @if($isBonding || Route::has('dashboard.provider-bonding'))
                <div class="px-6 mb-2">
                    <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Bonding</h4>
                </div>
                <div class="px-6 mb-6 space-y-1">
                    @if(Route::has('dashboard.provider-bonding'))
                        <a href="{{ route('dashboard.provider-bonding') }}"
                           class="flex items-center px-4 py-2 text-sm text-gray-700 rounded-lg hover:bg-gray-100 transition-colors {{ request()->routeIs('dashboard.provider-bonding') ? 'bg-gray-100 text-gray-900' : '' }}">
                            <i class="fas fa-users w-4 h-4 mr-3" aria-hidden="true"></i>
                            Bonding Activities
                        </a>
                    @endif

                    @if(Route::has('activities.index'))
                        <a href="{{ route('activities.index') }}"
                           class="flex items-center px-4 py-2 text-sm text-gray-700 rounded-lg hover:bg-gray-100 transition-colors {{ request()->routeIs('activities.*') ? 'bg-gray-100 text-gray-900' : '' }}">
                            <i class="fas fa-child w-4 h-4 mr-3" aria-hidden="true"></i>
                            Community Events
                        </a>
                    @endif
                </div>
            @endif

            <!-- Communication -->
            <div class="px-6 mb-2">
                <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Communication</h4>
            </div>
            <div class="px-6 mb-6 space-y-1">
                <button onclick="openBulkSMS()" class="flex items-center w-full px-4 py-2 text-sm text-gray-700 rounded-lg hover:bg-gray-100 transition-colors text-left">
                    <i class="fas fa-sms w-4 h-4 mr-3" aria-hidden="true"></i>
                    Bulk SMS
                </button>
                <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">
                    <i class="fas fa-envelope w-4 h-4 mr-3" aria-hidden="true"></i>
                    Email Campaigns
                </a>
                <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">
                    <i class="fas fa-bell w-4 h-4 mr-3" aria-hidden="true"></i>
                    Notifications
                </a>
            </div>

            <!-- Professional Development -->
            @if($isProfessional)
                <div class="px-6 mb-2">
                    <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Development</h4>
                </div>
                <div class="px-6 mb-6 space-y-1">
                    @if(Route::has('training.index'))
                        <a href="{{ route('training.index') }}"
                           class="flex items-center px-4 py-2 text-sm text-gray-700 rounded-lg hover:bg-gray-100 transition-colors {{ request()->routeIs('training.*') ? 'bg-gray-100 text-gray-900' : '' }}">
                            <i class="fas fa-graduation-cap w-4 h-4 mr-3" aria-hidden="true"></i>
                            Training Modules
                        </a>
                    @endif

                    @if(Route::has('ai.chat'))
                        <a href="{{ route('ai.chat') }}"
                           class="flex items-center px-4 py-2 text-sm text-gray-700 rounded-lg hover:bg-gray-100 transition-colors {{ request()->routeIs('ai.*') ? 'bg-gray-100 text-gray-900' : '' }}">
                            <i class="fas fa-robot w-4 h-4 mr-3" aria-hidden="true"></i>
                            AI Assistant
                        </a>
                    @endif
                </div>
            @endif

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
                    Preferences
                </a>
            </div>
        </nav>

        <!-- User Profile (Fixed at bottom) -->
        <div class="fixed bottom-0 w-72 p-6 border-t border-gray-200 bg-white">
            <div class="flex items-center">
                <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                    <span class="text-sm font-medium text-gray-700">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </span>
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-500">
                        @if($isProfessional)
                            Professional Provider
                        @elseif($isBonding)
                            Bonding Provider
                        @else
                            Service Provider
                        @endif
                    </p>
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
                        @yield('page-title', 'Professional Dashboard')
                    </h1>
                    <p class="text-sm text-gray-600">
                        @yield('page-description', 'Manage your professional services and client relationships')
                    </p>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Quick Actions -->
                    <div class="flex items-center space-x-2">
                        <button onclick="openQuickBooking()"
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            <i class="fas fa-plus mr-2" aria-hidden="true"></i>
                            Quick Booking
                        </button>
                        <button onclick="openBulkSMS()"
                                class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            <i class="fas fa-sms mr-2" aria-hidden="true"></i>
                            Send SMS
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

<!-- Quick Booking Modal -->
<div id="quickBookingModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Quick Booking</h3>
                <form id="quickBookingForm">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Client</label>
                        <select class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option>Search clients...</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Service</label>
                        <select class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option>Select service...</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Date & Time</label>
                        <input type="datetime-local" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                </form>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Create Booking
                </button>
                <button type="button" onclick="closeQuickBooking()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Bulk SMS Modal -->
<div id="bulkSMSModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Send Bulk SMS</h3>
                <form id="bulkSMSForm">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Recipients</label>
                        <div class="mt-2">
                            <label class="inline-flex items-center">
                                <input type="checkbox" class="form-checkbox">
                                <span class="ml-2">All clients</span>
                            </label>
                            <label class="inline-flex items-center ml-6">
                                <input type="checkbox" class="form-checkbox">
                                <span class="ml-2">Pending bookings</span>
                            </label>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Message</label>
                        <textarea rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Type your message..."></textarea>
                        <p class="mt-1 text-sm text-gray-500">0/160 characters</p>
                    </div>
                </form>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Send SMS
                </button>
                <button type="button" onclick="closeBulkSMS()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function openQuickBooking() {
    document.getElementById('quickBookingModal').classList.remove('hidden');
}

function closeQuickBooking() {
    document.getElementById('quickBookingModal').classList.add('hidden');
}

function openBulkSMS() {
    document.getElementById('bulkSMSModal').classList.remove('hidden');
}

function closeBulkSMS() {
    document.getElementById('bulkSMSModal').classList.add('hidden');
}

// Character counter for SMS
document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.querySelector('#bulkSMSForm textarea');
    const counter = document.querySelector('#bulkSMSForm p');

    if (textarea && counter) {
        textarea.addEventListener('input', function() {
            const length = this.value.length;
            counter.textContent = `${length}/160 characters`;
            if (length > 160) {
                counter.classList.add('text-red-500');
            } else {
                counter.classList.remove('text-red-500');
            }
        });
    }
});

// Mobile sidebar toggle
function toggleSidebar() {
    const sidebar = document.getElementById('provider-sidebar');
    sidebar.classList.toggle('hidden');
}

// Close sidebar on mobile when clicking outside
document.addEventListener('click', function(event) {
    const sidebar = document.getElementById('provider-sidebar');
    const toggle = event.target.closest('[onclick="toggleSidebar()"]');

    if (!sidebar.contains(event.target) && !toggle && window.innerWidth < 768) {
        sidebar.classList.add('hidden');
    }
});

// Make sidebar responsive
window.addEventListener('resize', function() {
    const sidebar = document.getElementById('provider-sidebar');
    if (window.innerWidth >= 768) {
        sidebar.classList.remove('hidden');
    }
});
</script>

<style>
@media (max-width: 768px) {
    #provider-sidebar {
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
