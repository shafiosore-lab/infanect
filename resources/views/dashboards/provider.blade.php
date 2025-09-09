{{-- resources/views/dashboards/provider.blade.php --}}
@extends('layouts.app')

@section('title', 'Provider Dashboard')

@section('content')
<div class="container mx-auto p-6">
    <!-- Welcome Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Welcome back, {{ auth()->user()->name }}!</h1>
        <p class="mt-2 text-gray-600">
            @if($provider)
                {{ $provider->name }} Dashboard
            @else
                Infanect Provider Portal
            @endif
        </p>
    </div>

    <!-- AI Assistant Quick Access -->
    <div class="mb-6">
        <div class="bg-gradient-to-r from-purple-50 to-indigo-50 border border-purple-200 rounded-lg p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-indigo-600 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-semibold text-gray-900">AI Business Assistant</h3>
                        <p class="text-xs text-gray-600">Get personalized guidance for your service provider business</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="text-right">
                        <div class="text-xs text-green-600 font-medium">● Online</div>
                    </div>
                    <a href="{{ route('ai-chat.index') }}"
                       class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-gradient-to-r from-purple-500 to-indigo-600 hover:from-purple-600 hover:to-indigo-700">
                        Start Chat
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Provider Registration Notice -->
    @if(!$provider)
        <div class="mb-8 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Complete Your Provider Registration</h3>
                        <p class="text-sm text-gray-600">Register your service provider profile to start managing activities and connecting with clients.</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('provider.register') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        Register Now
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- Key Stats Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5 flex items-center">
                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <div class="ml-5">
                    <dt class="text-sm font-medium text-gray-500 truncate">Total Activities</dt>
                    <dd class="text-lg font-medium text-gray-900">{{ $stats['total_activities'] }}</dd>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5 flex items-center">
                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="ml-5">
                    <dt class="text-sm font-medium text-gray-500 truncate">Approved Activities</dt>
                    <dd class="text-lg font-medium text-gray-900">{{ $stats['approved_activities'] }}</dd>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5 flex items-center">
                <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="ml-5">
                    <dt class="text-sm font-medium text-gray-500 truncate">Pending Approvals</dt>
                    <dd class="text-lg font-medium text-gray-900">{{ $stats['pending_approvals'] }}</dd>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5 flex items-center">
                <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <div class="ml-5">
                    <dt class="text-sm font-medium text-gray-500 truncate">Total Bookings</dt>
                    <dd class="text-lg font-medium text-gray-900">{{ $stats['total_bookings'] }}</dd>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5 flex items-center">
                <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                </svg>
                <div class="ml-5">
                    <dt class="text-sm font-medium text-gray-500 truncate">Total Revenue</dt>
                    <dd class="text-lg font-medium text-gray-900">${{ number_format($stats['total_revenue'], 2) }}</dd>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5 flex items-center">
                <svg class="h-6 w-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <div class="ml-5">
                    <dt class="text-sm font-medium text-gray-500 truncate">Employees</dt>
                    <dd class="text-lg font-medium text-gray-900">{{ $stats['total_employees'] }}</dd>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column - Recent Activities & Pending Approvals -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Recent Activities -->
            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold">Recent Activities</h2>
                    <a href="{{ route('provider.activities.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">View All</a>
                </div>
                @if($recentActivities->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentActivities as $activity)
                            <div class="flex items-center p-4 border rounded-lg">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4 flex-1">
                                    <h3 class="text-sm font-medium text-gray-900">{{ $activity->title }}</h3>
                                    <p class="text-sm text-gray-500">
                                        {{ $activity->category }} • {{ $activity->datetime->format('M j, Y g:i A') }}
                                    </p>
                                    <p class="text-xs text-gray-400 mt-1">{{ $activity->venue }}, {{ $activity->city }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($activity->is_approved) bg-green-100 text-green-800
                                        @else bg-yellow-100 text-yellow-800 @endif">
                                        {{ $activity->is_approved ? 'Approved' : 'Pending' }}
                                    </span>
                                    <p class="text-sm font-medium text-gray-900 mt-1">${{ $activity->price }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No activities yet</h3>
                        <p class="mt-1 text-sm text-gray-500">Start by creating your first activity.</p>
                        <div class="mt-6">
                            <a href="{{ route('provider.activities.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                Create Activity
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Pending Approvals -->
            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold">Pending Approvals</h2>
                    <a href="{{ route('admin.approvals.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">View All</a>
                </div>
                @if($pendingApprovals->count() > 0)
                    <div class="space-y-4">
                        @foreach($pendingApprovals as $approval)
                            <div class="flex items-center p-4 border rounded-lg">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4 flex-1">
                                    <h3 class="text-sm font-medium text-gray-900">
                                        {{ ucfirst($approval->type) }} {{ ucfirst($approval->action) }}
                                    </h3>
                                    <p class="text-sm text-gray-500">
                                        {{ $approval->entity ? $approval->entity->title ?? $approval->entity->name ?? 'Entity' : 'Unknown' }}
                                    </p>
                                    <p class="text-xs text-gray-400 mt-1">{{ $approval->created_at->diffForHumans() }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Pending Review
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No pending approvals</h3>
                        <p class="mt-1 text-sm text-gray-500">All your requests have been processed.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Right Column - Quick Actions & Stats -->
        <div class="space-y-8">
            <!-- Quick Actions -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold mb-4">Quick Actions</h2>
                @if($provider)
                    <div class="space-y-3">
                        <a href="{{ route('provider.activities.create') }}" class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Add Activity
                        </a>
                        <a href="{{ route('provider.employees.create') }}" class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                            Add Employee
                        </a>
                        <a href="{{ route('provider.activities.index') }}" class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            Manage Activities
                        </a>
                    </div>
                @else
                    <div class="text-center py-6">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Quick Actions Unavailable</h3>
                        <p class="mt-1 text-sm text-gray-500">Register your provider profile to access these features.</p>
                        <div class="mt-4">
                            <a href="{{ route('provider.register') }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                Register Provider
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Provider Info -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold mb-4">Provider Info</h2>
                @if($provider)
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-600">Provider Name</p>
                            <p class="font-medium">{{ $provider->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Specialization</p>
                            <p class="font-medium">{{ $provider->specialization }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Location</p>
                            <p class="font-medium">{{ $provider->country }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Status</p>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($provider->is_approved) bg-green-100 text-green-800
                                @else bg-yellow-100 text-yellow-800 @endif">
                                {{ $provider->is_approved ? 'Approved' : 'Pending Approval' }}
                            </span>
                        </div>
                    </div>
                @else
                    <div class="text-center py-6">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No Provider Profile</h3>
                        <p class="mt-1 text-sm text-gray-500">Register your provider profile to view details here.</p>
                        <div class="mt-4">
                            <a href="{{ route('provider.register') }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                Register Provider
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Activity Performance -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold mb-4">Activity Performance</h2>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Active Activities</span>
                        <span class="text-sm font-medium">{{ $stats['active_activities'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Total Bookings</span>
                        <span class="text-sm font-medium">{{ $stats['total_bookings'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Revenue Generated</span>
                        <span class="text-sm font-medium">${{ number_format($stats['total_revenue'], 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
