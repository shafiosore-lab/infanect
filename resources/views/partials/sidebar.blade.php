<div >
    <!-- Brand -->
    <div class="px-6 py-5 border-b ">
        <h2 class="text-2xl font-bold tracking-wide text-indigo-300">Infanect</h2>
        <p class="text-xs text-slate-400">Admin Dashboard</p>
    </div>

    <!-- Sidebar Content -->
    <nav class="flex-1 px-2 py-4 overflow-y-auto space-y-2" x-data="{ openMenu: null }">
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}"
            class="flex items-center px-4 py-3 rounded-lg transition-all duration-200
                  {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-indigo-700 hover:text-white' }}">
            <span class="mr-3"></span> Dashboard Overview
        </a>

        @if(auth()->user()->isSuperAdmin())

        <!-- ACTIVITIES & MODULES -->
        <div>
            <button @click="openMenu === 8 ? openMenu = null : openMenu = 8"
                class="flex justify-between items-center w-full px-4 py-3 text-slate-300 hover:bg-indigo-700 rounded-lg">
                <span>Activities & Providers</span>
                <svg :class="openMenu === 8 ? 'rotate-90' : ''" class="h-4 w-4 transform transition-transform"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            <div x-show="openMenu === 8" class="pl-8 space-y-2 py-2">
                <a href="{{ route('admin.activities.index') }}" class="block py-2 hover:text-indigo-400">
                    All Activities
                </a>
                <a href="{{ route('admin.providers.index') }}" class="block py-2 hover:text-indigo-400">
                    All Providers
                </a>
                <a href="{{ route('admin.approvals.index') }}" class="block py-2 hover:text-indigo-400">
                    Pending Approvals
                </a>
                <a href="{{ route('admin.modules.index') }}" class="block py-2 hover:text-indigo-400">
                    Categories
                </a>
            </div>
        </div>


        <!-- USER & ACCESS -->
        <div>
            <button @click="openMenu === 1 ? openMenu = null : openMenu = 1"
                class="flex justify-between items-center w-full px-4 py-3 text-slate-300 hover:bg-indigo-700 rounded-lg">
                <span>User & Access</span>
                <svg :class="openMenu === 1 ? 'rotate-90' : ''" class="h-4 w-4 transform transition-transform"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            <div x-show="openMenu === 1" class="pl-8 space-y-2 py-2">
                <a href="{{ route('admin.users.index') }}" class="block py-2 hover:text-indigo-400">Users Management</a>
                <a href="{{ route('admin.roles') }}" class="block py-2 hover:text-indigo-400">Roles Management</a>
                <a href="{{ route('admin.settings') }}" class="block py-2 hover:text-indigo-400">Settings</a>
            </div>
        </div>

        <!-- SERVICES -->
        <div>
            <button @click="openMenu === 2 ? openMenu = null : openMenu = 2"
                class="flex justify-between items-center w-full px-4 py-3 text-slate-300 hover:bg-indigo-700 rounded-lg">
                <span>Service Providers</span>
                <svg :class="openMenu === 2 ? 'rotate-90' : ''" class="h-4 w-4 transform transition-transform"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            <div x-show="openMenu === 2" class="pl-8 space-y-2 py-2">
                <a href="{{ route('admin.services.index') }}" class="block py-2 hover:text-indigo-400">Services</a>
                <a href="{{ route('admin.categories.index') }}" class="block py-2 hover:text-indigo-400">Service
                    Categories</a>
                <!-- <a href="{{ route('admin.providers.index') }}" class="block py-2 hover:text-indigo-400">Providers Directory</a> -->
                <a href="{{ route('admin.service.insights') }}" class="block py-2 hover:text-indigo-400">Service
                    Insights</a>
            </div>
        </div>

        <!-- CLIENTS -->
        <div>
            <button @click="openMenu === 3 ? openMenu = null : openMenu = 3"
                class="flex justify-between items-center w-full px-4 py-3 text-slate-300 hover:bg-indigo-700 rounded-lg">
                <span>Clients & Engagement</span>
                <svg :class="openMenu === 3 ? 'rotate-90' : ''" class="h-4 w-4 transform transition-transform"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            <div x-show="openMenu === 3" class="pl-8 space-y-2 py-2">
                <a href="{{ route('admin.clients.index') }}" class="block py-2 hover:text-indigo-400">Clients List</a>
                <a href="{{ route('admin.bookings.index') }}" class="block py-2 hover:text-indigo-400">Bookings</a>
                <a href="{{ route('admin.client.insights') }}" class="block py-2 hover:text-indigo-400">Client
                    Insights</a>
                <a href="{{ route('admin.feedback') }}" class="block py-2 hover:text-indigo-400">Feedback & Reviews</a>
            </div>
        </div>

        <!-- FINANCIALS -->
        <div>
            <button @click="openMenu === 4 ? openMenu = null : openMenu = 4"
                class="flex justify-between items-center w-full px-4 py-3 text-slate-300 hover:bg-indigo-700 rounded-lg">
                <span>Financials</span>
                <svg :class="openMenu === 4 ? 'rotate-90' : ''" class="h-4 w-4 transform transition-transform"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            <div x-show="openMenu === 4" class="pl-8 space-y-2 py-2">
                <a href="{{ route('admin.finance.insights') }}" class="block py-2 hover:text-indigo-400">Financial
                    Insights</a>
                <a href="{{ route('admin.earnings') }}" class="block py-2 hover:text-indigo-400">Earnings & Payouts</a>
                <a href="{{ route('admin.invoices') }}" class="block py-2 hover:text-indigo-400">Invoices & Billing</a>
                <a href="{{ route('admin.subscriptions') }}" class="block py-2 hover:text-indigo-400">Subscription
                    Plans</a>
            </div>
        </div>

        <!-- OPERATIONS -->
        <div>
            <button @click="openMenu === 5 ? openMenu = null : openMenu = 5"
                class="flex justify-between items-center w-full px-4 py-3 text-slate-300 hover:bg-indigo-700 rounded-lg">
                <span>Operations</span>
                <svg :class="openMenu === 5 ? 'rotate-90' : ''" class="h-4 w-4 transform transition-transform"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            <div x-show="openMenu === 5" class="pl-8 space-y-2 py-2">
                <a href="{{ route('admin.tasks') }}" class="block py-2 hover:text-indigo-400">Tasks</a>
                <a href="{{ route('admin.team') }}" class="block py-2 hover:text-indigo-400">Team</a>
                <a href="{{ route('admin.reports') }}" class="block py-2 hover:text-indigo-400">Reports</a>
                <a href="{{ route('admin.notifications') }}" class="block py-2 hover:text-indigo-400">Notifications</a>
                <a href="{{ route('admin.support') }}" class="block py-2 hover:text-indigo-400">Support Tickets</a>
            </div>
        </div>

        <!-- ANALYTICS -->
        <div>
            <button @click="openMenu === 6 ? openMenu = null : openMenu = 6"
                class="flex justify-between items-center w-full px-4 py-3 text-slate-300 hover:bg-indigo-700 rounded-lg">
                <span>Analytics</span>
                <svg :class="openMenu === 6 ? 'rotate-90' : ''" class="h-4 w-4 transform transition-transform"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            <div x-show="openMenu === 6" class="pl-8 space-y-2 py-2">
                <a href="{{ route('admin.analytics.performance') }}"
                    class="block py-2 hover:text-indigo-400">Performance Analytics</a>
                <a href="{{ route('admin.analytics.growth') }}" class="block py-2 hover:text-indigo-400">Growth
                    Trends</a>
                <a href="{{ route('admin.analytics.retention') }}" class="block py-2 hover:text-indigo-400">Retention
                    Insights</a>
                <a href="{{ route('admin.analytics.engagement') }}" class="block py-2 hover:text-indigo-400">Engagement
                    Heatmaps</a>
            </div>
        </div>

        <!-- SYSTEM -->
        <div>
            <button @click="openMenu === 7 ? openMenu = null : openMenu = 7"
                class="flex justify-between items-center w-full px-4 py-3 text-slate-300 hover:bg-indigo-700 rounded-lg">
                <span>System Config</span>
                <svg :class="openMenu === 7 ? 'rotate-90' : ''" class="h-4 w-4 transform transition-transform"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            <div x-show="openMenu === 7" class="pl-8 space-y-2 py-2">
                <a href="#" class="block py-2 hover:text-indigo-400">Platform Settings</a>
                <a href="#" class="block py-2 hover:text-indigo-400">Audit Logs</a>
                <a href="#" class="block py-2 hover:text-indigo-400">API Keys / Integrations</a>
            </div>
        </div>

        @elseif(auth()->user()->isServiceProvider())
        <!-- SERVICE PROVIDER MENU -->
        <a href="{{ route('services.index') }}"
            class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 text-slate-300 hover:bg-indigo-700 hover:text-white">
            <span class="mr-3"></span> My Services
        </a>

        <a href="{{ route('bookings.index') }}"
            class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 text-slate-300 hover:bg-indigo-700 hover:text-white">
            <span class="mr-3"></span> Bookings
        </a>

        <a href="{{ route('profile.edit') }}"
            class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 text-slate-300 hover:bg-indigo-700 hover:text-white">
            <span class="mr-3"></span> Profile
        </a>

        @elseif(auth()->user()->isActivityProvider())
        <!-- ACTIVITY PROVIDER MENU -->
        <a href="{{ route('activities.index') }}"
            class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 text-slate-300 hover:bg-indigo-700 hover:text-white">
            <span class="mr-3"></span> My Activities
        </a>

        <a href="{{ route('bookings.index') }}"
            class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 text-slate-300 hover:bg-indigo-700 hover:text-white">
            <span class="mr-3"></span> Registrations
        </a>

        <a href="{{ route('profile.edit') }}"
            class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 text-slate-300 hover:bg-indigo-700 hover:text-white">
            <span class="mr-3"></span> Profile
        </a>

        @elseif(auth()->user()->isEmployee())
        <!-- EMPLOYEE MENU -->
        <a href="{{ route('employee.tasks') }}"
            class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 text-slate-300 hover:bg-indigo-700 hover:text-white">
            <span class="mr-3"></span> My Tasks
        </a>

        <a href="{{ route('employee.schedule') }}"
            class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 text-slate-300 hover:bg-indigo-700 hover:text-white">
            <span class="mr-3"></span> Schedule
        </a>

        <a href="{{ route('profile.edit') }}"
            class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 text-slate-300 hover:bg-indigo-700 hover:text-white">
            <span class="mr-3"></span> Profile
        </a>

        @elseif(auth()->user()->isUser())
        <!-- USER MENU -->
        <a href="{{ route('services.index') }}"
            class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 text-slate-300 hover:bg-indigo-700 hover:text-white">
            <span class="mr-3"></span> Browse Services
        </a>

        <a href="{{ route('activities.index') }}"
            class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 text-slate-300 hover:bg-indigo-700 hover:text-white">
            <span class="mr-3"></span> Browse Activities
        </a>

        <a href="{{ route('bookings.index') }}"
            class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 text-slate-300 hover:bg-indigo-700 hover:text-white">
            <span class="mr-3"></span> My Bookings
        </a>

        <a href="{{ route('profile.edit') }}"
            class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 text-slate-300 hover:bg-indigo-700 hover:text-white">
            <span class="mr-3"></span> Profile
        </a>
        @endif

    </div>
    </nav>
</div>
