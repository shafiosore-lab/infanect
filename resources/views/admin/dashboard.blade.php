{{-- resources/views/dashboards/admin.blade.php --}}
@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container mx-auto p-6 space-y-8">

    <!-- Welcome Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Admin Dashboard</h1>
        <p class="mt-2 text-gray-600">Welcome back, {{ auth()->user()->name }}!</p>
    </div>

    <!-- Key Stats Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <x-stat-card title="Total Users" :value="$stats['total_users']" icon="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" color="blue"/>
        <x-stat-card title="Total Roles" :value="$stats['total_roles']" icon="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.031 9-11.622 0-1.042-.133-2.052-.382-3.016z" color="green"/>
        <x-stat-card title="Total Services" :value="$stats['total_services']" icon="M3 10h11M9 21V3m12 7h-4M9 14h11" color="purple"/>
    </div>

    <!-- Toolbar Tabs -->
    <div x-data="{ tab: 'overview' }" class="space-y-6">
        <div class="flex space-x-4 border-b">
            <button @click="tab = 'overview'"
                    :class="tab === 'overview' ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-500'"
                    class="px-4 py-2 focus:outline-none">Overview</button>
            <button @click="tab = 'service'"
                    :class="tab === 'service' ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-500'"
                    class="px-4 py-2 focus:outline-none">Service Insights</button>
            <button @click="tab = 'client'"
                    :class="tab === 'client' ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-500'"
                    class="px-4 py-2 focus:outline-none">Client Insights</button>
        </div>

        <!-- Overview Section -->
        <div x-show="tab === 'overview'" class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <x-overview-card title="Total Bookings" :value="$stats['total_bookings']" />
            <x-overview-card title="Active Clients" :value="$stats['active_clients']" />
            <x-overview-card title="Revenue" :value="number_format($stats['revenue'], 2)" prefix="$" />
        </div>

        <!-- Service Insights -->
        <div x-show="tab === 'service'" class="bg-white shadow rounded-lg p-6">
            <h2 class="text-lg font-semibold mb-4">Service Performance</h2>
            <canvas id="serviceChart"></canvas>
        </div>

        <!-- Client Insights -->
        <div x-show="tab === 'client'" class="bg-white shadow rounded-lg p-6">
            <h2 class="text-lg font-semibold mb-4">Client Retention Overview</h2>
            <canvas id="donutChart"></canvas>
        </div>
    </div>

    <!-- Top Providers Section -->
    <div class="space-y-4">
        <h2 class="text-xl font-semibold text-gray-800">Top Providers</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($topProviders as $provider)
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-semibold">{{ $provider->name }}</h3>
                    <p class="text-gray-500 text-sm">{{ $provider->user->name ?? 'No Owner' }}</p>
                    <div class="mt-3 flex justify-between items-center">
                        <span class="text-sm text-gray-600">Activities: {{ $provider->activities_count }}</span>
                        <span class="text-sm text-gray-600">Bookings: {{ $provider->bookings_count }}</span>
                    </div>
                    <div class="mt-2 text-yellow-500 font-medium">Rating: {{ $provider->rating ?? 'N/A' }}/5</div>
                </div>
            @endforeach
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Service Chart
    const serviceData = @json($charts['service_data']);
    new Chart(document.getElementById('serviceChart'), {
        type: 'bar',
        data: {
            labels: Object.keys(serviceData),
            datasets: [{
                label: 'Bookings',
                data: Object.values(serviceData),
                backgroundColor: ['#2196F3','#4CAF50','#FFC107','#9C27B0'],
            }]
        },
        options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
    });

    // Donut Chart
    const clientData = @json($charts['client_data']);
    new Chart(document.getElementById('donutChart'), {
        type: 'doughnut',
        data: {
            labels: Object.keys(clientData),
            datasets: [{
                data: Object.values(clientData),
                backgroundColor: ['#4CAF50','#2196F3','#F44336'],
                hoverOffset: 8
            }]
        },
        options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
    });
</script>
@endpush
