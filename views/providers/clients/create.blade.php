@extends('layouts.provider')

@section('content')
<div class="p-6 max-w-3xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">{{ isset($client) ? 'Edit Client' : 'Add New Client' }}</h1>

    <form method="POST" action="{{ isset($client) ? route('provider.clients.update', $client) : route('provider.clients.store') }}">
        @csrf
        @if(isset($client))
            @method('PUT')
        @endif

        <div class="mb-4">
            <label class="block font-medium mb-1">First Name</label>
            <input type="text" name="first_name" value="{{ old('first_name', $client->first_name ?? '') }}" class="w-full p-2 border rounded" required>
        </div>

        <div class="mb-4">
            <label class="block font-medium mb-1">Last Name</label>
            <input type="text" name="last_name" value="{{ old('last_name', $client->last_name ?? '') }}" class="w-full p-2 border rounded">
        </div>

        <div class="mb-4">
            <label class="block font-medium mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email', $client->email ?? '') }}" class="w-full p-2 border rounded" required>
        </div>

        <div class="mb-4">
            <label class="block font-medium mb-1">Phone</label>
            <input type="text" name="phone" value="{{ old('phone', $client->phone ?? '') }}" class="w-full p-2 border rounded">
        </div>

        <div class="mb-4">
            <label class="block font-medium mb-1">Country</label>
            <input type="text" name="country" value="{{ old('country', $client->country ?? '') }}" class="w-full p-2 border rounded">
        </div>

        <div class="mb-4">
            <label class="block font-medium mb-1">City</label>
            <input type="text" name="city" value="{{ old('city', $client->city ?? '') }}" class="w-full p-2 border rounded">
        </div>

        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded">
            {{ isset($client) ? 'Update Client' : 'Add Client' }}
        </button>
    </form>
</div>
@endsection
