<div class="flex-1 bg-gray-800 text-gray-100 sticky top-0 h-screen">
    <style>
    .hide-scrollbar { scrollbar-width: none; -ms-overflow-style: none; }
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    </style>

    <div class="px-4 py-3 border-b bg-gray-900 flex items-center gap-2">
        <h2 class="text-lg font-semibold text-gray-100">Infanect</h2>
    </div>

    <nav class="hide-scrollbar flex-1 px-2 py-3 overflow-y-auto space-y-1">
        <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2 rounded-lg transition-all duration-150 {{ request()->routeIs('dashboard') ? 'bg-gray-700 text-gray-100' : 'text-gray-100 hover:bg-gray-700 hover:text-white' }}">
            <!-- ...existing code... -->
            <span x-show="!$root.sidebarCollapsed">Dashboard</span>
        </a>

        {{-- ...existing code... --}}

        <!-- User info (moved from layout) -->
        <div class="px-4 py-3 border-t border-gray-700">
            <div class="flex items-center w-full">
                <div class="flex-shrink-0">
                    <div class="h-8 w-8 rounded-full bg-gray-600 flex items-center justify-center">
                        <span class="text-white text-sm font-medium">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</span>
                    </div>
                </div>
                <div class="ml-3 flex-1 min-w-0">
                    <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-400 truncate">{{ Auth::user()->email }}</p>
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

        <!-- Account Section -->
        <div class="border-t border-gray-700 pt-4 mt-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <!-- User avatar and name -->
                </div>
                <div class="ml-3">
                    <!-- User dropdown or other elements -->
                </div>
            </div>

            <a href="{{ route('profile.edit') }}" class="flex items-center px-3 py-2 rounded-lg transition-all duration-150 text-gray-100 hover:bg-gray-700 hover:text-white">
                <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <span x-show="!$root.sidebarCollapsed">Profile Settings</span>
            </a>

            <!-- Logout Button -->
            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <button type="submit" class="w-full flex items-center px-3 py-2 rounded-lg transition-all duration-150 text-gray-100 hover:bg-red-600 hover:text-white">
                    <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    <span x-show="!$root.sidebarCollapsed">Logout</span>
                </button>
            </form>
        </div>
    </nav>
</div>
