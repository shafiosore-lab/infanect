{{-- resources/views/dashboards/admin.blade.php --}}
@extends('layouts.admin')

@section('title', __('Admin Dashboard'))

@section('content')
<div class="container mx-auto p-6">
    <!-- Welcome -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">{{ __('Admin Dashboard') }}</h1>
        <p class="mt-2 text-gray-600">{{ __('Welcome back,') }} {{ auth()->user()->name }}!</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        @foreach ($stats as $key => $value)
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5 flex items-center">
                    <div class="p-3 rounded-full bg-gradient-to-r from-indigo-500 to-blue-500 text-white">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <div class="ml-5">
                        <dt class="text-sm font-medium text-gray-500 truncate">{{ __(ucwords(str_replace('_',' ', $key))) }}</dt>
                        <dd class="text-lg font-medium text-gray-900">
                            @if(is_numeric($value))
                                {{ is_float($value) ? number_format($value,2) : number_format($value) }}
                            @else
                                {{ $value }}
                            @endif
                        </dd>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Tabbed Insights -->
    <div x-data="{ tab: 'overview' }">
        <div class="flex space-x-4 border-b mb-6 overflow-x-auto">
            @foreach (['overview'=>'Overview','service'=>'Service Insights','client'=>'Client Insights','providers'=>'Providers','bookings'=>'Bookings'] as $t => $label)
                <button @click="tab = '{{ $t }}'"
                    :class="tab === '{{ $t }}' ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-500'"
                    class="px-4 py-2 focus:outline-none whitespace-nowrap">{{ __($label) }}</button>
            @endforeach
        </div>

        <!-- Overview -->
        <div x-show="tab === 'overview'" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-lg font-semibold">{{ __('Total Bookings') }}</h2>
                    <p class="text-3xl font-bold mt-2">{{ $stats['total_bookings'] ?? 0 }}</p>
                </div>
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-lg font-semibold">{{ __('Active Clients') }}</h2>
                    <p class="text-3xl font-bold mt-2">{{ $stats['active_clients'] ?? 0 }}</p>
                </div>
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-lg font-semibold">{{ __('Revenue') }}</h2>
                    <p class="text-3xl font-bold mt-2">${{ number_format($stats['revenue'] ?? 0, 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Service Insights -->
        <div x-show="tab === 'service'" class="bg-white shadow rounded-lg p-6">
            <h2 class="text-lg font-semibold mb-4">{{ __('Service Performance') }}</h2>
            <canvas id="serviceChart"></canvas>
        </div>

        <!-- Client Insights -->
        <div x-show="tab === 'client'" class="bg-white shadow rounded-lg p-6">
            <h2 class="text-lg font-semibold mb-4">{{ __('Client Retention') }}</h2>
            <canvas id="donutChart"></canvas>
        </div>

        <!-- Providers -->
        <div x-show="tab === 'providers'" class="bg-white shadow rounded-lg p-6">
            <h2 class="text-lg font-semibold mb-4">{{ __('Provider Distribution') }}</h2>
            <canvas id="providerChart"></canvas>
        </div>

        <!-- Bookings -->
        <div x-show="tab === 'bookings'" class="bg-white shadow rounded-lg p-6">
            <h2 class="text-lg font-semibold mb-4">{{ __('Booking Status') }}</h2>
            <canvas id="bookingChart"></canvas>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Data from controller
    const serviceData   = @json($charts['service_data'] ?? []);
    const clientData    = @json($charts['client_data'] ?? []);
    const providerData  = @json($charts['provider_data'] ?? []);
    const bookingData   = @json($charts['booking_status_data'] ?? []);

    // Service Chart
    if (document.getElementById('serviceChart')) {
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
    }

    // Donut Chart
    if (document.getElementById('donutChart')) {
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
    }

    // Provider Chart
    if (document.getElementById('providerChart')) {
        new Chart(document.getElementById('providerChart'), {
            type: 'pie',
            data: {
                labels: Object.keys(providerData),
                datasets: [{
                    data: Object.values(providerData),
                    backgroundColor: ['#FF6384','#36A2EB','#FFCE56','#4BC0C0','#9966FF'],
                    hoverOffset: 8
                }]
            },
            options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
        });
    }

    // Booking Chart
    if (document.getElementById('bookingChart')) {
        new Chart(document.getElementById('bookingChart'), {
            type: 'bar',
            data: {
                labels: Object.keys(bookingData),
                datasets: [{
                    label: 'Bookings',
                    data: Object.values(bookingData),
                    backgroundColor: ['#FF6384','#36A2EB','#FFCE56','#4BC0C0','#9966FF'],
                }]
            },
            options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
        });
    }
</script>
@endpush
