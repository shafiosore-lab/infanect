@extends('layouts.provider')

@section('content')
<div class="p-6 max-w-2xl mx-auto bg-white shadow rounded">
    <h1 class="text-2xl font-bold mb-4">Client Details</h1>

    <div class="space-y-2">
        <p><strong>Name:</strong> {{ $client->first_name }} {{ $client->last_name }}</p>
        <p><strong>Email:</strong> {{ $client->email }}</p>
        <p><strong>Phone:</strong> {{ $client->phone ?? '—' }}</p>
        <p><strong>Country:</strong> {{ $client->country ?? '—' }}</p>
        <p><strong>City:</strong> {{ $client->city ?? '—' }}</p>
        <p><strong>Status:</strong> {{ $client->deleted_at ? 'Inactive' : 'Active' }}</p>
        <p><strong>Metadata:</strong> <pre>{{ json_encode($client->metadata ?? [], JSON_PRETTY_PRINT) }}</pre></p>
    </div>

    <div class="mt-4">
        <a href="{{ route('provider.clients.edit', $client) }}" class="text-green-600">Edit Client</a>
        <a href="{{ route('provider.clients.index') }}" class="ml-4 text-indigo-600">Back to Clients</a>
    </div>
</div>
@endsection
