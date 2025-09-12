<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Infanect Admin Panel')</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- AlpineJS for sidebar dropdowns -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 min-h-screen flex">

    <!-- Sidebar -->
    <aside class="min-h-screen w-64 text-white shadow-xl overflow-y-auto bg-gray-900  text-white shadow-xl">
        @include('partials.sidebar') <!-- your sidebar blade -->
    </aside>

    <!-- Main Content -->
    <div class="flex-1 p-6 min-h-screen overflow-y-auto">
        <!-- Breadcrumb / Page Title -->
        <div class="mb-4">
            <h1 class="text-2xl font-bold text-gray-900">@yield('page_title', 'Dashboard')</h1>
        </div>

        <!-- Page Content -->
        @yield('content')
    </div>

</body>
</html>
