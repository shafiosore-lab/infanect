<div class="flex-1 bg-gray-800 text-gray-100 sticky top-0 h-screen">
    <style>
    .hide-scrollbar { scrollbar-width: none; -ms-overflow-style: none; }
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    </style>

    <!-- Brand (compact) -->
    <div class="px-4 py-3 border-b bg-gray-900 flex items-center gap-2">
        <h2 class="text-lg font-semibold text-gray-100">Infanect Admin</h2>
    </div>

    <!-- Sidebar Content -->
    <nav class="hide-scrollbar flex-1 px-2 py-3 overflow-y-auto space-y-1">
        {{-- Example link --}}
        <a href="{{ route('admin.dashboard') }}"
            class="flex items-center px-3 py-2 rounded-lg transition-all duration-150 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-700 text-gray-100' : 'text-gray-100 hover:bg-gray-700 hover:text-white' }}">
            <!-- ...existing code... -->
            <span x-show="!$root.sidebarCollapsed">Dashboard</span>
        </a>

        {{-- Other admin links (colors normalized) --}}
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

        <!-- Account section -->
        <div class="border-t border-gray-700 pt-4 mt-4">
            <div class="flex items-center px-3 py-2">
                <div class="flex-shrink-0">
                    <!-- User avatar or icon -->
                    <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.403 1.403A2 2 0 0116 21H8a2 2 0 01-1.597-.697L5 17h5m5-8V4a2 2 0 00-2-2H6a2 2 0 00-2 2v5m11 0H6"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-100">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-400">{{ Auth::user()->email }}</p>
                </div>
            </div>

            <a href="{{ route('profile.edit') }}" class="flex items-center px-3 py-2 rounded-lg transition-all duration-150 text-gray-100 hover:bg-gray-700 hover:text-white">
                <!-- ...existing code... -->
                Profile Settings
            </a>
        </div>
    </nav>
</div>
