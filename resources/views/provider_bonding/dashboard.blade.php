@extends('layouts.provider-bonding')

@section('page-title', 'Bonding Provider Dashboard')
@section('page-description', 'Manage bonding sessions and compliance effortlessly.')

@section('content')
<div class="p-6 space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Bonding Provider Dashboard</h1>
        <p class="text-sm text-gray-500 mt-1">Manage bonding sessions and compliance effortlessly.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Scheduled Bonding Sessions -->
        <div class="bg-white rounded-2xl shadow-md p-6 hover:shadow-lg transition">
            <p class="text-sm text-gray-500">Scheduled Bonding Sessions</p>
            <p class="mt-2 text-3xl font-extrabold text-indigo-600">
                {{ \App\Models\Booking::where('provider_id', auth()->id())->where('type','bonding')->count() ?? 0 }}
            </p>
        </div>

        <!-- Compliance Docs -->
        <div class="bg-white rounded-2xl shadow-md p-6 hover:shadow-lg transition">
            <p class="text-sm text-gray-500">Compliance Docs</p>
            <p class="mt-2 text-3xl font-extrabold text-green-600">
                {{ \App\Models\ProviderDocument::where('provider_id', auth()->id())->count() ?? 0 }}
            </p>
        </div>

        <!-- Pending Approvals -->
        <div class="bg-white rounded-2xl shadow-md p-6 hover:shadow-lg transition">
            <p class="text-sm text-gray-500">Pending Approvals</p>
            <p class="mt-2 text-3xl font-extrabold text-red-600">
                {{ \App\Models\Approval::where('provider_id', auth()->id())->where('status','pending')->count() ?? 0 }}
            </p>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="bg-white rounded-2xl shadow-md p-6">
        <h2 class="font-semibold text-gray-800 mb-4">Bonding Activity (7 days)</h2>
        <canvas id="bondingChart" height="120"></canvas>
    </div>

    <!-- Actions -->
    <div class="flex justify-end">
        <a href="{{ route('provider.activities.index') }}">
            <x-button.primary class="px-6 py-2 text-sm font-semibold rounded-xl shadow-md hover:shadow-lg">
                Manage Activities
            </x-button.primary>
        </a>
    </div>
</div>

<script>
// Chart placeholder logic (optional extension)
</script>
@endsection
