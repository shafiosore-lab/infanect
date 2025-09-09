@extends('layouts.app')

@section('title', 'Reports')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">System Reports</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <dt class="text-sm font-medium text-gray-500 truncate">Total Users</dt>
                <dd class="text-lg font-medium text-gray-900">{{ $reports['total_users'] }}</dd>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <dt class="text-sm font-medium text-gray-500 truncate">Total Services</dt>
                <dd class="text-lg font-medium text-gray-900">{{ $reports['total_services'] }}</dd>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <dt class="text-sm font-medium text-gray-500 truncate">Total Bookings</dt>
                <dd class="text-lg font-medium text-gray-900">{{ $reports['total_bookings'] }}</dd>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <dt class="text-sm font-medium text-gray-500 truncate">Total Revenue</dt>
                <dd class="text-lg font-medium text-gray-900">${{ number_format($reports['total_revenue'], 2) }}</dd>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <dt class="text-sm font-medium text-gray-500 truncate">Active Users</dt>
                <dd class="text-lg font-medium text-gray-900">{{ $reports['active_users'] }}</dd>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <dt class="text-sm font-medium text-gray-500 truncate">Monthly Bookings</dt>
                <dd class="text-lg font-medium text-gray-900">{{ $reports['monthly_bookings'] }}</dd>
            </div>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-xl font-semibold mb-4">Quick Actions</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <a href="{{ route('admin.analytics.performance') }}" class="block p-4 border rounded-lg hover:bg-gray-50">
                <h3 class="font-medium">Performance Analytics</h3>
                <p class="text-sm text-gray-600">View detailed performance metrics</p>
            </a>
            <a href="{{ route('admin.finance.insights') }}" class="block p-4 border rounded-lg hover:bg-gray-50">
                <h3 class="font-medium">Financial Insights</h3>
                <p class="text-sm text-gray-600">Review financial data and trends</p>
            </a>
        </div>
    </div>
</div>
@endsection
