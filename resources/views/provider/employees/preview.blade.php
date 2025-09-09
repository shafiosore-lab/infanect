{{-- resources/views/provider/employees/preview.blade.php --}}
@extends('layouts.app')

@section('title', 'Preview Employee')

@section('content')
<div class="container mx-auto p-6 max-w-2xl">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Employee Preview</h1>
        <p class="mt-2 text-gray-600">Review employee details before submitting for approval.</p>
    </div>

    <!-- Preview Card -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <!-- Employee Header -->
        <div class="bg-gradient-to-r from-green-500 to-teal-600 px-6 py-4">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-white">{{ $request->name }}</h2>
                    <p class="text-green-100">Employee • {{ $provider ? $provider->name : 'Provider' }}</p>
                </div>
            </div>
        </div>

        <!-- Employee Details -->
        <div class="p-6">
            <div class="space-y-4">
                <div>
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Full Name</h3>
                    <p class="mt-1 text-lg text-gray-900">{{ $request->name }}</p>
                </div>

                <div>
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Email Address</h3>
                    <p class="mt-1 text-lg text-gray-900">{{ $request->email }}</p>
                </div>

                @if($request->phone)
                <div>
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Phone Number</h3>
                    <p class="mt-1 text-lg text-gray-900">{{ $request->phone }}</p>
                </div>
                @endif

                <div>
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Provider</h3>
                    <p class="mt-1 text-lg text-gray-900">{{ $provider ? $provider->name : 'Not specified' }}</p>
                </div>

                <div>
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Department</h3>
                    <p class="mt-1 text-lg text-gray-900">{{ $provider ? $provider->name . ' Employee' : 'Employee' }}</p>
                </div>

                <div>
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Status</h3>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        Pending Approval
                    </span>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="bg-gray-50 px-6 py-4">
            <div class="flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0 sm:space-x-4">
                <!-- Left side - Edit -->
                <div class="flex space-x-3">
                    <a href="{{ route('provider.employees.create') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Details
                    </a>
                </div>

                <!-- Right side - Submit -->
                <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
                    <a href="{{ route('provider.employees.index') }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Cancel
                    </a>

                    <!-- Primary Submit Button -->
                    <form method="POST" action="{{ route('provider.employees.store') }}" class="inline">
                        @csrf
                        @foreach($request->all() as $key => $value)
                            @if(is_array($value))
                                @foreach($value as $arrayKey => $arrayValue)
                                    <input type="hidden" name="{{ $key }}[{{ $arrayKey }}]" value="{{ $arrayValue }}">
                                @endforeach
                            @else
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endif
                        @endforeach
                        <input type="hidden" name="action" value="submit">
                        <button type="submit" class="inline-flex items-center justify-center px-8 py-3 border border-transparent rounded-lg shadow-sm text-base font-semibold text-white bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transform hover:scale-105 transition-all duration-200">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                            Add Employee to Team
                        </button>
                    </form>

                    <!-- Alternative Submit Option -->
                    <form method="POST" action="{{ route('provider.employees.store') }}" class="inline">
                        @csrf
                        @foreach($request->all() as $key => $value)
                            @if(is_array($value))
                                @foreach($value as $arrayKey => $arrayValue)
                                    <input type="hidden" name="{{ $key }}[{{ $arrayKey }}]" value="{{ $arrayValue }}">
                                @endforeach
                            @else
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endif
                        @endforeach
                        <input type="hidden" name="action" value="submit">
                        <input type="hidden" name="send_credentials" value="immediate">
                        <button type="submit" class="inline-flex items-center justify-center px-6 py-2 border-2 border-green-300 rounded-md shadow-sm text-sm font-medium text-green-700 bg-green-50 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            Add & Send Credentials
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Preview Notice -->
    <div class="mt-6 bg-green-50 border border-green-200 rounded-lg p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-green-800">Preview Mode</h3>
                <div class="mt-2 text-sm text-green-700">
                    <p>This is how the employee profile will appear. The employee will receive login credentials once approved by an administrator.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Employee Setup Info -->
    <div class="mt-4 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-yellow-800">Employee Setup</h3>
                <div class="mt-2 text-sm text-yellow-700">
                    <p>After approval, the employee will receive an email with login credentials (default password: password123) and will be prompted to change their password on first login.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
