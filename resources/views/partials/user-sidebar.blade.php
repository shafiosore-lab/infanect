<div class="flex-1 bg-gray-800 text-gray-200 sticky top-0 h-screen">


    <!-- Brand (compact) -->
    <div class="px-4 py-3 border-b bg-gray-900 flex items-center justify-between gap-2">
        <h2 class="text-lg font-semibold text-gray-100">Infanect</h2>
        <!-- Mobile toggle -->
        <button class="md:hidden p-2 text-gray-300 hover:text-gray-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>

    <!-- Sidebar Content -->
    <nav class="hide-scrollbar flex-1 px-2 py-2 overflow-y-auto space-y-1 md:block" x-cloak>
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}"
            class="flex items-center px-3 py-2 rounded-md transition-all duration-150 {{ request()->routeIs('dashboard') ? 'bg-gray-700' : '' }} text-gray-200 hover:bg-gray-700" title="My Dashboard">
            <svg class="h-5 w-5 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 12h18M12 3v18"/>
            </svg>
            <span class="text-sm" x-show="!$root.sidebarCollapsed">My Dashboard</span>
        </a>

        <!-- Browse Activities -->
        <a href="{{ route('activities.index') }}"
            class="flex items-center px-3 py-2 rounded-md transition-all duration-150 {{ request()->routeIs('activities.*') ? 'bg-gray-700' : '' }} text-gray-200 hover:bg-gray-700" title="Browse Activities">
            <svg class="h-5 w-5 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            <span class="text-sm" x-show="!$root.sidebarCollapsed">Browse Activities</span>
        </a>

        <!-- Services -->
        <a href="{{ route('services.index') }}"
            class="flex items-center px-3 py-2 rounded-md transition-all duration-150 {{ request()->routeIs('services.*') ? 'bg-gray-700' : '' }} text-gray-200 hover:bg-gray-700" title="Services">
            <svg class="h-5 w-5 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 12h18M12 3v18"/>
            </svg>
            <span class="text-sm" x-show="!$root.sidebarCollapsed">Services</span>
        </a>

        <!-- My Bookings -->
        <a href="{{ route('bookings.index') }}"
            class="flex items-center px-3 py-2 rounded-md transition-all duration-150 {{ request()->routeIs('bookings.*') ? 'bg-gray-700' : '' }} text-gray-200 hover:bg-gray-700" title="My Bookings">
            <svg class="h-5 w-5 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                </path>
            </svg>
            <span class="text-sm" x-show="!$root.sidebarCollapsed">My Bookings</span>
        </a>

        <!-- Training Modules -->
        <a href="{{ route('training.index') }}"
            class="flex items-center px-3 py-2 rounded-md transition-all duration-150 {{ request()->routeIs('training.*') ? 'bg-gray-700' : '' }} text-gray-200 hover:bg-gray-700" title="Training Modules">
            <svg class="h-5 w-5 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                </path>
            </svg>
            <span class="text-sm" x-show="!$root.sidebarCollapsed">Training Modules</span>
        </a>

        <!-- AI Chat Assistant -->
        <a href="{{ route('ai.chat') }}"
            class="flex items-center px-3 py-2 rounded-md transition-all duration-150 {{ request()->routeIs('ai.*') ? 'bg-gray-700' : '' }} text-gray-200 hover:bg-gray-700" title="AI Assistant">
            <svg class="h-5 w-5 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                </path>
            </svg>
            <span class="text-sm" x-show="!$root.sidebarCollapsed">AI Assistant</span>
        </a>

        @auth
        <!-- User info (moved from layout) -->
        <div class="px-4 py-3 border-t border-gray-700">
            <div class="flex items-center w-full">
                <div class="flex-shrink-0">
                    <div class="h-8 w-8 rounded-full bg-gray-600 flex items-center justify-center">
                        <span class="text-white text-sm font-medium">{{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 2)) }}</span>
                    </div>
                </div>
                <div class="ml-3 flex-1 min-w-0">
                    <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name ?? 'User' }}</p>
                    <p class="text-xs text-gray-400 truncate">{{ Auth::user()->email ?? '' }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="ml-3">
                    @csrf
                    <button type="submit" class="text-gray-400 hover:text-white">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
        @endauth

        <!-- Account Section -->
        <div class="border-t border-gray-700 mt-6 pt-4">
            <div class="px-4 py-2">
                <p class="text-xs text-gray-300 uppercase tracking-wide">Account</p>
            </div>

            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-gray-700 text-gray-200">
                <svg class="h-5 w-5 mr-3" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Profile Settings
            </a>
        </div>
    </nav>
</div>
            </div>

            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-gray-700 text-gray-200">
                <svg class="h-5 w-5 mr-3" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Profile Settings
            </a>
        </div>
    </nav>
</div>
