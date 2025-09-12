@extends('layouts.app')

@section('title', 'Provider Dashboard')

@section('content')
<div class="container mx-auto p-6">
    <!-- Welcome Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Welcome back, {{ auth()->user()->name ?? 'User' }}!</h1>
        <p class="mt-2 text-gray-600">
            @if($provider)
                {{ $provider->name ?? 'Provider Dashboard' }}
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
        @php
            $safeStats = [
                'total_activities' => $stats['total_activities'] ?? 0,
                'approved_activities' => $stats['approved_activities'] ?? 0,
                'pending_approvals' => $stats['pending_approvals'] ?? 0,
                'total_bookings' => $stats['total_bookings'] ?? 0,
                'total_revenue' => $stats['total_revenue'] ?? 0,
                'total_employees' => $stats['total_employees'] ?? 0,
                'active_activities' => $stats['active_activities'] ?? 0,
            ];
        @endphp

        @foreach($safeStats as $key => $value)
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5 flex items-center">
                    <div class="ml-5">
                        <dt class="text-sm font-medium text-gray-500 truncate">{{ ucfirst(str_replace('_', ' ', $key)) }}</dt>
                        <dd class="text-lg font-medium text-gray-900">
                            @php
                                $displayValue = is_array($value) ? count($value) : (is_numeric($value) ? $value : 0);
                            @endphp
                            {{ $displayValue }}
                        </dd>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- The rest of your content (Recent Activities, Pending Approvals, Quick Actions, Provider Info) -->
    {{-- Make sure to use safe null checks like $provider->name ?? '' and $activity->title ?? '' to prevent array/object errors --}}
</div>
@endsection
