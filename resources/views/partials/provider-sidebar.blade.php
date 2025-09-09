<div class="h-screen w-64 bg-gradient-to-b from-purple-600 via-indigo-600 to-blue-700 text-white shadow-xl">
    <!-- Brand -->
    <div class="px-6 py-5 border-b border-indigo-500">
        <h2 class="text-2xl font-bold tracking-wide text-white">Infanect</h2>
        <p class="text-xs text-purple-200">Provider Portal</p>
    </div>

    <!-- Sidebar Content -->
    <nav class="flex-1 px-2 py-4 overflow-y-auto space-y-2" x-data="{ openMenu: null }">
        <!-- Dashboard -->
        <a href="{{ route('provider.dashboard') }}"
            class="flex items-center px-4 py-3 rounded-lg transition-all duration-200
                  {{ request()->routeIs('provider.dashboard') ? 'bg-white bg-opacity-20 text-white' : 'text-purple-100 hover:bg-white hover:bg-opacity-10 hover:text-white' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            Provider Dashboard
        </a>

        <!-- Activities Management -->
        <a href="{{ route('provider.activities.index') }}"
            class="flex items-center px-4 py-3 rounded-lg transition-all duration-200
                  {{ request()->routeIs('provider.activities.*') ? 'bg-white bg-opacity-20 text-white' : 'text-purple-100 hover:bg-white hover:bg-opacity-10 hover:text-white' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            My Activities
        </a>

        <!-- Employee Management -->
        <a href="{{ route('provider.employees.index') }}"
            class="flex items-center px-4 py-3 rounded-lg transition-all duration-200
                  {{ request()->routeIs('provider.employees.*') ? 'bg-white bg-opacity-20 text-white' : 'text-purple-100 hover:bg-white hover:bg-opacity-10 hover:text-white' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
            </svg>
            Employees
        </a>

        <!-- Bookings -->
        <a href="{{ route('provider.bookings.index') }}"
            class="flex items-center px-4 py-3 rounded-lg transition-all duration-200
                  {{ request()->routeIs('provider.bookings.*') ? 'bg-white bg-opacity-20 text-white' : 'text-purple-100 hover:bg-white hover:bg-opacity-10 hover:text-white' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            Bookings
        </a>

        <!-- Clients -->
        <a href="{{ route('provider.clients.index') }}"
            class="flex items-center px-4 py-3 rounded-lg transition-all duration-200
                  {{ request()->routeIs('provider.clients.*') ? 'bg-white bg-opacity-20 text-white' : 'text-purple-100 hover:bg-white hover:bg-opacity-10 hover:text-white' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            Clients
        </a>

        <!-- Payments -->
        <a href="{{ route('provider.payments') }}"
            class="flex items-center px-4 py-3 rounded-lg transition-all duration-200
                  {{ request()->routeIs('provider.payments') ? 'bg-white bg-opacity-20 text-white' : 'text-purple-100 hover:bg-white hover:bg-opacity-10 hover:text-white' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
            </svg>
            Payments
        </a>

        <!-- Quick Actions -->
        <div class="px-4 py-2">
            <div class="text-xs text-purple-200 uppercase tracking-wide mb-2">Quick Actions</div>
            <a href="{{ route('provider.activities.create') }}"
               class="w-full flex items-center justify-center px-3 py-2 bg-purple-500 hover:bg-purple-600 rounded-md text-sm text-white transition-colors mb-2">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add Activity
            </a>
            <a href="{{ route('provider.employees.create') }}"
               class="w-full flex items-center justify-center px-3 py-2 bg-indigo-500 hover:bg-indigo-600 rounded-md text-sm text-white transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                </svg>
                Add Employee
            </a>
        </div>

        <!-- Account Section -->
        <div class="border-t border-purple-500 mt-6 pt-4">
            <div class="px-4 py-2">
                <p class="text-xs text-purple-200 uppercase tracking-wide">Account</p>
            </div>

            <a href="{{ route('profile.edit') }}"
                class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 text-purple-100 hover:bg-white hover:bg-opacity-10 hover:text-white">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Profile Settings
            </a>

            <form method="POST" action="{{ route('logout') }}" class="px-4 py-2">
                @csrf
                <button type="submit" class="flex items-center w-full px-2 py-2 text-purple-100 hover:bg-white hover:bg-opacity-10 hover:text-white rounded-lg transition-all duration-200">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    Logout
                </button>
            </form>
        </div>
    </nav>
</div>
