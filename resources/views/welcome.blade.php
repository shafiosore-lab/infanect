<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <!-- Styles & Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <script src="{{ asset('js/app.js') }}" defer></script>
    @endif
</head>
<body class="min-h-screen bg-white text-gray-900 font-sans">

    <!-- Main Wrapper -->
    <div id="app" class="flex flex-col min-h-screen">

        <!-- Navigation -->
        @include('partials.navbar')

        <!-- Page Content -->
        <main class="flex-1 p-6 lg:p-12">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-gray-100 dark:bg-gray-900 text-gray-700 dark:text-gray-300 py-6 text-center">
            &copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.
        </footer>

    </div>

    <!-- Optional Scripts -->
    @stack('scripts')
</body>
</html>
