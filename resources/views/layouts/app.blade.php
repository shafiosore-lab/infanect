<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Infanect') }}</title>

    <!-- Compiled CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <!-- Compiled JS -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <!-- Chart.js CDN for dashboards -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js" defer></script>

    <!-- Optionally include Laravel Echo + Pusher for realtime (if environment configured) -->
    @if(env('PUSHER_APP_KEY'))
        <script src="https://js.pusher.com/7.2/pusher.min.js" defer></script>
        <script src="https://cdn.jsdelivr.net/npm/laravel-echo/dist/echo.iife.js" defer></script>
        <script defer>
            document.addEventListener('DOMContentLoaded', function () {
                try {
                    window.Pusher = Pusher;
                    window.Echo = new Echo({
                        broadcaster: 'pusher',
                        key: '{{ env('PUSHER_APP_KEY') }}',
                        cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
                        forceTLS: {{ env('PUSHER_SCHEME', 'https') === 'https' ? 'true' : 'false' }},
                    });
                } catch (e) {
                    console.warn('Echo init failed', e);
                }
            });
        </script>
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    {{-- Flatpickr CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    {{-- Optionally theme --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">

    <!-- Scripts -->
    <!-- @vite(['resources/css/app.css', 'resources/js/app.js']) -->
     <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <!-- Alpine.js for reactive UI (required for sidebar collapse, x-show, x-cloak) -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Chart.js for dashboards -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
</head>
<body class="bg-gray-50 text-gray-900 antialiased">

    @include('components.navbar')

    <div id="app" x-data="{ sidebarCollapsed: false }" class="min-h-screen">
        <!-- Sidebar -->
        <div class="hidden md:flex md:flex-col md:fixed md:inset-y-0" :class="sidebarCollapsed ? 'md:w-20' : 'md:w-64'">
            <div class="flex-1 flex flex-col min-h-0 bg-gray-800">
                <div class="flex-1 flex flex-col pt-5 pb-4 overflow-y-auto">

                    @if(auth()->check() && (method_exists(auth()->user(), 'hasRole') ? auth()->user()->hasRole('admin') : (auth()->user()->role ?? '') === 'admin' || auth()->user()->role === 'manager'))
                        @include('partials.sidebar')
                    @elseif(auth()->check() && (method_exists(auth()->user(), 'hasRole') ? auth()->user()->hasRole('provider') : (auth()->user()->role ?? '') === 'provider'))
                        @include('partials.provider-sidebar')
                    @else
                        @include('partials.user-sidebar')
                    @endif

                </div>

            </div>
        </div>

        <!-- Mobile sidebar -->
        <div x-data="{ sidebarOpen: false }" class="md:hidden">
            <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 flex z-40">
                <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0" x-description="Background overlay, show/hide based on slide-over state." aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-600 opacity-75"></div>
                </div>
                <div x-show="sidebarOpen" x-transition:enter="transition ease-in-out duration-300 transform" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in-out duration-300 transform" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" class="relative flex-1 flex flex-col max-w-xs w-full bg-gray-800">
                    <div class="absolute top-0 right-0 -mr-12 pt-2">
                        <button x-click="sidebarOpen = false" class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                            <span class="sr-only">Close sidebar</span>
                            <svg class="h-6 w-6 text-white" x-description="Heroicon name: x" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="flex-1 h-0 pt-5 pb-4 overflow-y-auto">
                        <div class="flex-shrink-0 flex items-center px-4">
                            <x-application-logo class="h-8 w-auto text-white" />
                            <span class="ml-2 text-white text-lg font-semibold">{{ config('app.name', 'Laravel') }}</span>
                        </div>
                        @include('partials.sidebar')
                    </div>
                    <div class="flex-shrink-0 flex border-t border-gray-700 p-4">
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
                            <!-- logout button moved to sidebar partial -->
                        </div>
                    </div>
                </div>
                <div class="flex-shrink-0 w-14" aria-hidden="true"></div>
            </div>
        </div>

        <!-- Main content -->
        <div class="md:pl-64 flex flex-col flex-1">
            <!-- Top navigation -->
            <div class="sticky top-0 z-10 md:hidden pl-1 pt-1 sm:pl-3 sm:pt-3 bg-gray-100">
                <button x-data="{ sidebarOpen: false }" @click="sidebarOpen = true" class="-ml-0.5 -mt-0.5 h-12 w-12 inline-flex items-center justify-center rounded-md text-gray-500 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="h-6 w-6" x-description="Heroicon name: menu" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>




            <main class="flex-1">
                <!-- Page Heading -->


                <!-- Page Content -->
                <div class="py-6">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        @yield('content')
                    </div>
                </div>
            </main>
        </div>
    </div>

    @include('components.footer')

    <!-- Flatpickr JS and timezone helper -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        window.APP = window.APP || {};
        try {
            window.APP.timezone = Intl.DateTimeFormat().resolvedOptions().timeZone || 'UTC';
        } catch (e) {
            window.APP.timezone = 'UTC';
        }
    </script>
</body>
</html>
