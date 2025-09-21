@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-6">
    <h1 class="text-2xl font-semibold">Provider Dashboard (Moved)</h1>
    <p class="mt-2 text-gray-600">The legacy provider dashboard view has been removed. Use one of the consolidated provider dashboards below based on your role or account type.</p>

    <div class="mt-4 space-y-2">
        <a href="{{ route('dashboard.provider-professional') }}" class="inline-block px-4 py-2 bg-indigo-600 text-white rounded">Professional Provider Dashboard</a>
        <a href="{{ route('dashboard.provider-bonding') }}" class="inline-block px-4 py-2 bg-green-600 text-white rounded ml-2">Bonding Provider Dashboard</a>
        <a href="{{ route('dashboard.provider') }}" class="inline-block px-4 py-2 bg-gray-600 text-white rounded ml-2">Provider Home</a>
    </div>

    <p class="mt-4 text-sm text-gray-500">If you are an admin and see this message unexpectedly, please review route/controller mappings for provider dashboards.</p>
</div>
@endsection

@php
    header('Location: ' . route('dashboard.provider-professional'));
    exit;
@endphp
