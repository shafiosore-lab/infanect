<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'InfaNect Admin Panel')</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Configure Tailwind with custom InfaNect colors -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#ea1c4d',
                        accent: '#65c16e',
                        warning: '#fbc761',
                        darkgray: '#333333'
                    },
                    spacing: {
                        '72': '18rem',
                        '84': '21rem',
                        '96': '24rem',
                    }
                }
            }
        }
    </script>

    <!-- AlpineJS for dropdowns & sidebar -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen flex flex-col md:flex-row overflow-hidden">

    <!-- Mobile Nav Toggle -->
    <div x-data="{ sidebarOpen: false }" class="md:hidden">
        <button @click="sidebarOpen = !sidebarOpen"
                class="fixed top-4 left-4 z-40 p-2 bg-primary text-white rounded-md shadow-lg">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Mobile Sidebar Overlay -->
        <div x-show="sidebarOpen"
             @click="sidebarOpen = false"
             class="fixed inset-0 bg-gray-900 bg-opacity-50 z-30"></div>

        <!-- Mobile Sidebar -->
        <aside x-show="sidebarOpen"
               x-transition:enter="transition ease-out duration-300"
               x-transition:enter-start="-translate-x-full"
               x-transition:enter-end="translate-x-0"
               x-transition:leave="transition ease-in duration-300"
               x-transition:leave-start="translate-x-0"
               x-transition:leave-end="-translate-x-full"
               class="fixed inset-y-0 left-0 w-64 bg-gray-900 text-white shadow-xl overflow-y-auto z-30">
            @include('partials.sidebar')
        </aside>
    </div>

    <!-- Desktop Sidebar -->
    <aside class="hidden md:block min-h-screen w-64 bg-gray-900 text-white shadow-xl overflow-y-auto">
        @include('partials.sidebar')
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col min-h-screen overflow-y-auto">
        <!-- Header -->
        <header class="bg-white shadow-sm sticky top-0 z-20">
            <div class="container mx-auto px-4 py-3 flex justify-between items-center">
                <div class="text-xl font-bold text-primary ml-10 md:ml-0">
                    @yield('page_title', 'Dashboard')
                </div>
                <div class="flex items-center gap-3">
                    {{-- Optional search bar --}}
                    <div class="relative hidden md:block">
                        <input type="search" placeholder="Search..."
                               class="border rounded-lg pl-9 pr-3 py-2 w-64 focus:ring focus:ring-primary/30 focus:border-primary">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>

                    {{-- Notifications --}}
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="p-2 text-gray-600 hover:text-primary relative">
                            <i class="fas fa-bell"></i>
                            <span class="absolute -top-1 -right-1 bg-primary text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">
                                3
                            </span>
                        </button>
                        <div x-show="open"
                             @click.outside="open = false"
                             x-transition
                             class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 overflow-hidden">
                            <div class="p-3 border-b border-gray-100">
                                <h3 class="font-semibold text-gray-900">Notifications</h3>
                            </div>
                            <div class="max-h-72 overflow-y-auto">
                                <a href="#" class="block p-3 hover:bg-gray-50 border-b border-gray-100">
                                    <div class="flex">
                                        <div class="flex-shrink-0 bg-primary/10 rounded-lg p-2">
                                            <i class="fas fa-user-plus text-primary"></i>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">New user registered</p>
                                            <p class="text-xs text-gray-500">5 minutes ago</p>
                                        </div>
                                    </div>
                                </a>
                                <a href="#" class="block p-3 hover:bg-gray-50 border-b border-gray-100">
                                    <div class="flex">
                                        <div class="flex-shrink-0 bg-accent/10 rounded-lg p-2">
                                            <i class="fas fa-calendar-check text-accent"></i>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">New booking confirmed</p>
                                            <p class="text-xs text-gray-500">1 hour ago</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <a href="#" class="block p-2 text-center text-sm text-primary font-medium hover:bg-gray-50">
                                View all notifications
                            </a>
                        </div>
                    </div>

                    {{-- User Menu --}}
                    @auth
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center text-gray-900 font-medium py-2 px-3 rounded-lg hover:bg-gray-100">
                            <span class="w-8 h-8 bg-primary/10 rounded-full flex items-center justify-center text-primary mr-2">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </span>
                            <span class="hidden sm:inline">{{ auth()->user()->name }}</span>
                            <i class="fas fa-chevron-down ml-2 text-gray-500 text-xs transition-transform" :class="{'rotate-180': open}"></i>
                        </button>
                        <div x-show="open"
                             @click.outside="open = false"
                             x-transition
                             class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 overflow-hidden z-50">
                            <a href="{{ route_exists('profile.show') ? route('profile.show') : '#' }}"
                               class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-50">
                               <i class="fas fa-user-circle mr-2 text-gray-500"></i>
                               Profile
                            </a>
                            <a href="{{ route_exists('settings.index') ? route('settings.index') : '#' }}"
                               class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-50">
                               <i class="fas fa-cog mr-2 text-gray-500"></i>
                               Settings
                            </a>
                            <div class="border-t border-gray-100 my-1"></div>
                            <form method="POST" action="{{ route_exists('logout') ? route('logout') : '#' }}">
                                @csrf
                                <button type="submit" class="w-full text-left flex items-center px-4 py-2 text-gray-700 hover:bg-gray-50">
                                    <i class="fas fa-sign-out-alt mr-2 text-gray-500"></i>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                    @else
                        <a href="{{ route_exists('login') ? route('login') : '#' }}" class="text-primary px-3 py-2 rounded-md font-medium">Login</a>
                        <a href="{{ route_exists('register') ? route('register') : '#' }}" class="bg-primary text-white px-3 py-2 rounded-md font-medium hover:bg-primary/90">Sign Up</a>
                    @endauth
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1">
            <div class="container mx-auto px-4 py-5">
                @yield('content')
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 py-4">
            <div class="container mx-auto px-4 text-center text-gray-500 text-sm">
                &copy; {{ date('Y') }} InfaNect. All rights reserved.
            </div>
        </footer>
    </div>

    @stack('scripts')
</body>
</html>

{{-- Helper to check if a route exists --}}
@php
if (!function_exists('route_exists')) {
    function route_exists($name) {
        return app('router')->has($name);
    }
}
@endphp
