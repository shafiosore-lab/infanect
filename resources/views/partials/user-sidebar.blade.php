<div class="flex-1 bg-gradient-to-b from-blue-600 via-indigo-600 to-purple-700 text-white">
    <!-- Brand -->
    <div class="px-6 py-5 border-b border-indigo-500">
        <h2 class="text-2xl font-bold tracking-wide text-white">Infanect</h2>
        <p class="text-xs text-blue-200">Client Portal</p>
    </div>

    <!-- Sidebar Content -->
    <nav class="flex-1 px-2 py-4 overflow-y-auto space-y-2" x-data="{ openMenu: null }">
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}"
            class="flex items-center px-4 py-3 rounded-lg transition-all duration-200
                  {{ request()->routeIs('dashboard') ? 'bg-white bg-opacity-20 text-white' : 'text-blue-100 hover:bg-white hover:bg-opacity-10 hover:text-white' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
            </svg>
            My Dashboard
        </a>

        <!-- Browse Activities -->
        <a href="{{ route('activities.index') }}"
            class="flex items-center px-4 py-3 rounded-lg transition-all duration-200
                  {{ request()->routeIs('activities.*') ? 'bg-white bg-opacity-20 text-white' : 'text-blue-100 hover:bg-white hover:bg-opacity-10 hover:text-white' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            Browse Activities
        </a>

        <!-- View Providers -->
        <a href="{{ route('providers.index') }}"
            class="flex items-center px-4 py-3 rounded-lg transition-all duration-200
                  {{ request()->routeIs('providers.*') ? 'bg-white bg-opacity-20 text-white' : 'text-blue-100 hover:bg-white hover:bg-opacity-10 hover:text-white' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                </path>
            </svg>
            Service Providers
        </a>

        <!-- My Bookings -->
        <a href="{{ route('bookings.index') }}"
            class="flex items-center px-4 py-3 rounded-lg transition-all duration-200
                  {{ request()->routeIs('bookings.*') ? 'bg-white bg-opacity-20 text-white' : 'text-blue-100 hover:bg-white hover:bg-opacity-10 hover:text-white' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                </path>
            </svg>
            My Bookings
        </a>

        <!-- Parenting Modules -->
        <a href="{{ route('parenting-modules.index') }}"
            class="flex items-center px-4 py-3 rounded-lg transition-all duration-200
                  {{ request()->routeIs('parenting-modules.*') ? 'bg-white bg-opacity-20 text-white' : 'text-blue-100 hover:bg-white hover:bg-opacity-10 hover:text-white' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                </path>
            </svg>
            Parenting Modules
        </a>

        <!-- Training Modules -->
        <a href="{{ route('training-modules.index') }}"
            class="flex items-center px-4 py-3 rounded-lg transition-all duration-200
                  {{ request()->routeIs('training-modules.*') ? 'bg-white bg-opacity-20 text-white' : 'text-blue-100 hover:bg-white hover:bg-opacity-10 hover:text-white' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                </path>
            </svg>
            Training Modules
        </a>

        <!-- AI Chat Assistant -->
        <a href="{{ route('ai-chat.index') }}"
            class="flex items-center px-4 py-3 rounded-lg transition-all duration-200
                  {{ request()->routeIs('ai-chat.*') ? 'bg-white bg-opacity-20 text-white' : 'text-blue-100 hover:bg-white hover:bg-opacity-10 hover:text-white' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                </path>
            </svg>
            AI Assistant
        </a>

        <!-- Quick Search -->
        <div class="px-4 py-2">
            <form action="{{ route('activities.search') }}" method="GET" class="flex">
                <input type="text" name="q" placeholder="Search activities..."
                    class="flex-1 px-3 py-2 text-sm bg-white bg-opacity-10 border border-blue-400 rounded-l-lg text-white placeholder-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-300"
                    value="{{ request('q') }}">
                <button type="submit" class="px-3 py-2 bg-blue-500 hover:bg-blue-600 rounded-r-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>
            </form>
        </div>

        <!-- Account Section -->
        <div class="border-t border-blue-500 mt-6 pt-4">
            <div class="px-4 py-2">
                <p class="text-xs text-blue-200 uppercase tracking-wide">Account</p>
            </div>

            <a href="{{ route('profile.edit') }}"
                class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 text-blue-100 hover:bg-white hover:bg-opacity-10 hover:text-white">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Profile Settings
            </a>

            <form method="POST" action="{{ route('logout') }}" class="px-4 py-2">
                @csrf
                <button type="submit"
                    class="flex items-center w-full px-2 py-2 text-blue-100 hover:bg-white hover:bg-opacity-10 hover:text-white rounded-lg transition-all duration-200">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                        </path>
                    </svg>
                    Logout
                </button>
            </form>
        </div>
    </nav>
</div>
