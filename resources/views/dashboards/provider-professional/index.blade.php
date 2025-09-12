@extends('layouts.provider-professional')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Provider (Professional) Dashboard</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="p-4 bg-white rounded shadow">
            <div class="text-sm text-gray-500">Services</div>
            <div class="text-2xl font-bold">{{ $stats['services'] ?? 0 }}</div>
        </div>
        <div class="p-4 bg-white rounded shadow">
            <div class="text-sm text-gray-500">AI Documents</div>
            <div class="text-2xl font-bold">{{ $stats['ai_documents'] ?? 0 }}</div>
        </div>
        <div class="p-4 bg-white rounded shadow">
            <div class="text-sm text-gray-500">Earnings</div>
            <div class="text-2xl font-bold">{{ $stats['earnings'] ?? 0 }}</div>
        </div>
    </div>

    <div class="mt-6">
        <a href="{{ route('provider.services.index') }}" class="btn btn-primary">Manage Services</a>
        <a href="{{ route('ai.chat') }}" class="btn btn-secondary">AI Documents</a>
    </div>
</div>
@endsection
