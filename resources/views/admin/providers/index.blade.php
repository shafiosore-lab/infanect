@extends('layouts.admin')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Service Providers</h1>

    {{-- Search & Filter --}}
    <form method="GET" class="flex gap-3 mb-4">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by name or email" class="p-2 border rounded w-full max-w-xs">
        <button type="submit" class="bg-indigo-600 text-white px-3 rounded">Search</button>
    </form>

    {{-- Flash message --}}
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    {{-- Providers Table --}}
    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="w-full text-left">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3">Name</th>
                    <th class="p-3">Email</th>
                    <th class="p-3">Phone</th>
                    <th class="p-3">Specialization</th>
                    <th class="p-3">Rating</th>
                    <th class="p-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($providers as $provider)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="p-3">{{ $provider->name }}</td>
                        <td class="p-3">{{ $provider->email }}</td>
                        <td class="p-3">{{ $provider->phone ?? '—' }}</td>
                        <td class="p-3">{{ $provider->specialization ?? '—' }}</td>
                        <td class="p-3">{{ $provider->rating ?? '—' }}</td>
                        <td class="p-3 flex gap-2">
                            <a href="{{ route('admin.providers.show', $provider) }}" class="text-indigo-600">View</a>
                            <a href="{{ route('admin.providers.edit', $provider) }}" class="text-yellow-600">Edit</a>
                            <form action="{{ route('admin.providers.destroy', $provider) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-4 text-center">No providers found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $providers->links() }}
    </div>
</div>
@endsection
