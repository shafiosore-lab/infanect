@extends('layouts.app')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Clients</h1>

    <table class="min-w-full bg-white border">
        <thead>
            <tr class="bg-gray-200">
                <th class="px-4 py-2">Name</th>
                <th class="px-4 py-2">Email</th>
                <th class="px-4 py-2">Status</th>
                <th class="px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clients as $client)
            <tr>
                <td class="border px-4 py-2">{{ $client->name }}</td>
                <td class="border px-4 py-2">{{ $client->email }}</td>
                <td class="border px-4 py-2">
                    {{ $client->is_active ? 'Active' : 'Inactive' }}
                </td>
                <td class="border px-4 py-2">
                    <a href="{{ route('admin.clients.show', $client->id) }}" class="text-blue-500">View</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $clients->links() }}
    </div>
</div>
@endsection
