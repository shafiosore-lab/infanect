<div class="h-screen flex flex-col bg-gradient-to-b from-slate-900 via-indigo-900 to-slate-800 text-white shadow-lg">

    <!-- Brand -->
    <div class="px-4 py-3 border-b bg-gray-900 flex items-center gap-2">
        <h2 class="text-lg font-semibold text-gray-100">Infanect</h2>
    </div>

    <!-- Sidebar Content -->
    <nav class="h-full px-2 py-4 text-sm text-gray-200 bg-gray-800" x-data="{ openMenu: null }>

        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-md transition-all duration-200
                  {{ request()->routeIs('dashboard') ? 'bg-gray-700' : 'text-gray-200 hover:bg-gray-700' }}">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12h18M12 3v18"/></svg>
            <span>Dashboard Overview</span>
        </a>

        @if(auth()->user()->isSuperAdmin())

        <!-- ACTIVITIES & MODULES -->
        <div>
            <button @click="openMenu === 1 ? openMenu = null : openMenu = 1"
                    class="flex justify-between items-center w-full px-4 py-3 text-gray-200 hover:bg-gray-700 rounded-md">
                <span>Activities & Providers</span>
                <svg :class="openMenu === 1 ? 'rotate-90' : ''" class="h-4 w-4 transform transition-transform"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            <div x-show="openMenu === 1" class="pl-8 space-y-2 py-2">
                <a href="{{ route('admin.activities.index') }}" class="block py-2 text-gray-200 hover:text-indigo-400">All Activities</a>
                <a href="{{ route('admin.providers.index') }}" class="block py-2 text-gray-200 hover:text-indigo-400">All Providers</a>
                <a href="{{ route('admin.approvals.index') }}" class="block py-2 text-gray-200 hover:text-indigo-400">Pending Approvals</a>
                <a href="{{ route('admin.modules.index') }}" class="block py-2 text-gray-200 hover:text-indigo-400">Modules & Categories</a>
                <a href="{{ route('admin.ai.recommendations') }}" class="block py-2 text-gray-200 hover:text-indigo-400">AI Recommendations</a>
            </div>
        </div>

        <!-- USER & ACCESS -->
        <div>
            <button @click="openMenu === 2 ? openMenu = null : openMenu = 2"
                    class="flex justify-between items-center w-full px-4 py-3 text-gray-200 hover:bg-gray-700 rounded-md">
                <span>User & Access</span>
                <svg :class="openMenu === 2 ? 'rotate-90' : ''" class="h-4 w-4 transform transition-transform"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            <div x-show="openMenu === 2" class="pl-8 space-y-2 py-2">
                <a href="{{ route('admin.users.index') }}" class="block py-2 text-gray-200 hover:text-indigo-400">Users Management</a>
                <a href="{{ route('admin.roles') }}" class="block py-2 text-gray-200 hover:text-indigo-400">Roles & Permissions</a>
                <a href="{{ route('admin.settings') }}" class="block py-2 text-gray-200 hover:text-indigo-400">Platform Settings</a>
            </div>
        </div>

        <!-- SERVICES -->
        <div>
            <button @click="openMenu === 3 ? openMenu = null : openMenu = 3"
                    class="flex justify-between items-center w-full px-4 py-3 text-gray-200 hover:bg-gray-700 rounded-md">
                <span>Service Providers</span>
                <svg :class="openMenu === 3 ? 'rotate-90' : ''" class="h-4 w-4 transform transition-transform"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            <div x-show="openMenu === 3" class="pl-8 space-y-2 py-2">
                <a href="{{ route('admin.services.index') }}" class="block py-2 text-gray-200 hover:text-indigo-400">Services</a>
                <a href="{{ route('admin.categories.index') }}" class="block py-2 text-gray-200 hover:text-indigo-400">Service Categories</a>
                <a href="{{ route('admin.service.insights') }}" class="block py-2 text-gray-200 hover:text-indigo-400">Service Insights</a>
            </div>
        </div>

        <!-- CLIENTS / USERS -->
        <div>
            <button @click="openMenu === 4 ? openMenu = null : openMenu = 4"
                    class="flex justify-between items-center w-full px-4 py-3 text-gray-200 hover:bg-gray-700 rounded-md">
                <span>Clients / Users</span>
                <svg :class="openMenu === 4 ? 'rotate-90' : ''" class="h-4 w-4 transform transition-transform"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            <div x-show="openMenu === 4" class="pl-8 space-y-2 py-2">
                <a href="{{ route('admin.clients.index') }}" class="block py-2 text-gray-200 hover:text-indigo-400">Clients List</a>
                <a href="{{ route('admin.bookings.index') }}" class="block py-2 text-gray-200 hover:text-indigo-400">Bookings</a>
                <a href="{{ route('admin.modules.index') }}" class="block py-2 text-gray-200 hover:text-indigo-400">Bonding / Parenting Modules</a>
                <a href="{{ route('admin.client.insights') }}" class="block py-2 text-gray-200 hover:text-indigo-400">Client Insights</a>
                <a href="{{ route('admin.feedback') }}" class="block py-2 text-gray-200 hover:text-indigo-400">Feedback & Reviews</a>
                <a href="{{ route('admin.ai.chat') }}" class="block py-2 text-gray-200 hover:text-indigo-400">AI Chat Support</a>
            </div>
        </div>

        <!-- FINANCIALS -->
        <div>
            <button @click="openMenu === 5 ? openMenu = null : openMenu = 5"
                    class="flex justify-between items-center w-full px-4 py-3 text-gray-200 hover:bg-gray-700 rounded-md">
                <span>Financials</span>
                <svg :class="openMenu === 5 ? 'rotate-90' : ''" class="h-4 w-4 transform transition-transform"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="ml-2 bg-red-500 px-2 rounded-full text-xs">{{ $pendingTransactions ?? 0 }}</span>
            </button>
            <div x-show="openMenu === 5" class="pl-8 space-y-2 py-2">
                <a href="{{ route('admin.finance.insights') }}" class="block py-2 text-gray-200 hover:text-indigo-400">Financial Insights</a>
                <a href="{{ route('admin.earnings') }}" class="block py-2 text-gray-200 hover:text-indigo-400">Earnings & Payouts</a>
                <a href="{{ route('admin.invoices') }}" class="block py-2 text-gray-200 hover:text-indigo-400">Invoices & Billing</a>
                <a href="{{ route('admin.subscriptions') }}" class="block py-2 text-gray-200 hover:text-indigo-400">Subscription Plans</a>
            </div>
        </div>

        <!-- OPERATIONS -->
        <div>
            <button @click="openMenu === 6 ? openMenu = null : openMenu = 6"
                    class="flex justify-between items-center w-full px-4 py-3 text-gray-200 hover:bg-gray-700 rounded-md">
                <span>Operations</span>
                <svg :class="openMenu === 6 ? 'rotate-90' : ''" class="h-4 w-4 transform transition-transform"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            <div x-show="openMenu === 6" class="pl-8 space-y-2 py-2">
                <a href="{{ route('admin.tasks') }}" class="block py-2 text-gray-200 hover:text-indigo-400">Tasks</a>
                <a href="{{ route('admin.team') }}" class="block py-2 text-gray-200 hover:text-indigo-400">Team</a>
                <a href="{{ route('admin.reports') }}" class="block py-2 text-gray-200 hover:text-indigo-400">Reports</a>
                <a href="{{ route('admin.notifications') }}" class="block py-2 text-gray-200 hover:text-indigo-400">Notifications</a>
                <a href="{{ route('admin.support') }}" class="block py-2 text-gray-200 hover:text-indigo-400">Support Tickets</a>
            </div>
        </div>

        @elseif(auth()->user()->isServiceProvider())
            <!-- Service Provider Menu -->
            <a href="{{ route('services.index') }}" class="flex items-center px-4 py-3 rounded-lg text-slate-300 hover:bg-indigo-700 hover:text-white">
                🛠️ My Services
            </a>
            <a href="{{ route('bookings.index') }}" class="flex items-center px-4 py-3 rounded-lg text-slate-300 hover:bg-indigo-700 hover:text-white">
                📋 Bookings
            </a>
            <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-3 rounded-lg text-slate-300 hover:bg-indigo-700 hover:text-white">
                👤 Profile
            </a>

        @elseif(auth()->user()->isActivityProvider())
            <!-- Activity Provider Menu -->
            <a href="{{ route('activities.index') }}" class="flex items-center px-4 py-3 rounded-lg text-slate-300 hover:bg-indigo-700 hover:text-white">
                🎯 My Activities
            </a>
            <a href="{{ route('bookings.index') }}" class="flex items-center px-4 py-3 rounded-lg text-slate-300 hover:bg-indigo-700 hover:text-white">
                📋 Registrations
            </a>
            <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-3 rounded-lg text-slate-300 hover:bg-indigo-700 hover:text-white">
                👤 Profile
            </a>

        @elseif(auth()->user()->isClient() || auth()->user()->isUser())
            <!-- Client / User Menu -->
            <a href="{{ route('user.modules.index') }}" class="flex items-center px-4 py-3 rounded-lg text-slate-300 hover:bg-indigo-700 hover:text-white">
                📚 Modules & Activities
            </a>
            <a href="{{ route('user.bookings.index') }}" class="flex items-center px-4 py-3 rounded-lg text-slate-300 hover:bg-indigo-700 hover:text-white">
                📋 My Bookings
            </a>
            <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-3 rounded-lg text-slate-300 hover:bg-indigo-700 hover:text-white">
                👤 Profile
            </a>
        @endif

    </nav>
</div>

<style>
/* Force sidebar appearance to black with white text/links */
aside, .sidebar, .side-nav, .main-sidebar, .partial-sidebar {
    background-color: #000 !important;
    color: #fff !important;
}

aside a, .sidebar a, .side-nav a, .main-sidebar a, .partial-sidebar a,
.sidebar .nav-link, .side-nav .nav-link {
    color: #fff !important;
}

/* Active/hover states */
.sidebar .active, .side-nav .active, .main-sidebar .active {
    background-color: #111 !important;
}
.sidebar a:hover, .side-nav a:hover, .main-sidebar a:hover {
    color: #fff !important;
    background-color: rgba(255,255,255,0.05) !important;
}

/* Hide scrollbars for sidebar nav */
.hide-scrollbar { scrollbar-width: none; -ms-overflow-style: none; }
.hide-scrollbar::-webkit-scrollbar { display: none; }
</style>
