@extends('layouts.provider')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Clients</h1>

    {{-- Search & Filter --}}
    <form method="GET" class="flex flex-wrap gap-3 mb-6">
        <input
            type="text"
            name="q"
            value="{{ request('q') }}"
            placeholder="Search clients..."
            class="p-2 border rounded flex-1"
        />
        <select name="country" class="p-2 border rounded">
            <option value="">All Countries</option>
            @foreach($clients->pluck('country')->unique() as $country)
                <option value="{{ $country }}" @selected(request('country')==$country)>{{ $country }}</option>
            @endforeach
        </select>
        <button class="bg-indigo-600 text-white px-3 rounded">Filter</button>
    </form>

    {{-- Clients Table --}}
    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="w-full text-left">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3">Name</th>
                    <th class="p-3">Email</th>
                    <th class="p-3">Phone</th>
                    <th class="p-3">Country</th>
                    <th class="p-3">City</th>
                    <th class="p-3">Status</th>
                    <th class="p-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($clients as $client)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="p-3">{{ $client->first_name }} {{ $client->last_name }}</td>
                        <td class="p-3">{{ $client->email }}</td>
                        <td class="p-3">{{ $client->phone ?? '—' }}</td>
                        <td class="p-3">{{ $client->country ?? '—' }}</td>
                        <td class="p-3">{{ $client->city ?? '—' }}</td>
                        <td class="p-3">{{ $client->deleted_at ? 'Inactive' : 'Active' }}</td>
                        <td class="p-3">
                            <a href="{{ route('provider.clients.show', $client) }}" class="text-indigo-600">View</a>
                            <a href="{{ route('provider.clients.edit', $client) }}" class="text-green-600 ml-2">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="p-4 text-center">No clients found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $clients->links() }}
    </div>
</div>
@endsection
