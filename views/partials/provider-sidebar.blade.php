<div class="flex-1 bg-gray-800 text-gray-200 sticky top-0 h-screen">
    <style>
    .hide-scrollbar { scrollbar-width: none; -ms-overflow-style: none; }
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    </style>

    <div class="px-4 py-3 border-b bg-gray-900 flex items-center gap-2">
        <h2 class="text-lg font-semibold text-gray-100">Infanect Provider</h2>
    </div>

    <nav class="hide-scrollbar flex-1 px-2 py-3 overflow-y-auto space-y-1">
        <!-- Dashboard -->
        <a href="{{ route('provider.dashboard') }}"
            class="flex items-center gap-3 px-3 py-2 rounded-md transition-all duration-150
                  {{ request()->routeIs('provider.dashboard') ? 'bg-gray-700' : '' }} text-gray-200 hover:bg-gray-700">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12h18M12 3v18"/>
            </svg>
            <span>Dashboard</span>
        </a>

        <!-- Services Management -->
        <a href="{{ route('provider.services.index') }}"
            class="flex items-center gap-3 px-3 py-2 rounded-md transition-all duration-150
                  {{ request()->routeIs('provider.services*') ? 'bg-gray-700' : '' }} text-gray-200 hover:bg-gray-700">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
            </svg>
            <span>My Services</span>
        </a>

        <!-- Bookings -->
        <a href="{{ route('provider.bookings.index') }}"
            class="flex items-center gap-3 px-3 py-2 rounded-md transition-all duration-150
                  {{ request()->routeIs('provider.bookings*') ? 'bg-gray-700' : '' }} text-gray-200 hover:bg-gray-700">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3M16 7V3M3 11h18M5 21h14a2 2 0 002-2V11a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
            </svg>
            <span>Bookings</span>
        </a>

        <!-- Clients -->
        <a href="{{ route('provider.clients.index') ?? '#' }}"
            class="flex items-center gap-3 px-3 py-2 rounded-md transition-all duration-150 text-gray-200 hover:bg-gray-700">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 15c2.761 0 5.303.814 7.379 2.204M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span>Clients</span>
        </a>

        <!-- Activities Management -->
        <a href="{{ route('provider.activities.index') ?? '#' }}"
            class="flex items-center gap-3 px-3 py-2 rounded-md transition-all duration-150 text-gray-200 hover:bg-gray-700">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6M7 21h10"/>
            </svg>
            <span>Activities</span>
        </a>

        <!-- Metrics -->
        <a href="{{ route('provider.metrics') }}"
            class="flex items-center gap-3 px-3 py-2 rounded-md transition-all duration-150
                  {{ request()->routeIs('provider.metrics') ? 'bg-gray-700' : '' }} text-gray-200 hover:bg-gray-700">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3v18h18"/>
            </svg>
            <span>Metrics</span>
        </a>
    </div>

    <div class="mt-6 pt-4 border-t border-gray-700 px-3 space-y-2">
        <!-- Documents -->
        <a href="{{ route('provider.documents') ?? '#' }}"
            class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-gray-700 text-gray-200">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3"/>
            </svg>
            <span>Documents</span>
        </a>

        <!-- Logout -->
        <form method="POST" action="{{ route('logout') }}" class="inline">
            @csrf
            <button type="submit" class="w-full text-left flex items-center gap-3 px-3 py-2 rounded-md hover:bg-gray-700 text-gray-200">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7"/>
                </svg>
                <span>Logout</span>
            </button>
        </form>
    </div>
</nav>
