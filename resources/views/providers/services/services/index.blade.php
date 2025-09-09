@extends('layouts.app')

@section('title', 'Provider Services')

@section('content')
<div class="container mx-auto p-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 space-y-4 md:space-y-0">
        <h1 class="text-3xl font-bold text-gray-900">Provider Services</h1>
        <a href="{{ route('provider.services.create') }}"
           class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
           Add New Service
        </a>
    </div>

    <!-- Search & Filter -->
    <div class="mb-4 flex flex-col md:flex-row justify-between items-start md:items-center space-y-2 md:space-y-0">
        <form action="{{ route('provider.services.index') }}" method="GET" class="flex items-center w-full md:w-1/2">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Search services..."
                   class="w-full px-4 py-2 border rounded-l-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-r-lg hover:bg-blue-700 transition">
                Search
            </button>
        </form>
        {{-- Optional: Add Category Filter Dropdown --}}
        <div>
            <select name="category" onchange="this.form.submit()"
                    class="px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">All Categories</option>
                @foreach($categories ?? [] as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Services Table -->
    <div class="bg-white shadow rounded-lg overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($services ?? [] as $service)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $service->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ Str::limit($service->description, 60) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ $service->category?->name ?? 'Uncategorized' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right space-x-2">
                            <a href="{{ route('provider.services.edit', $service->id) }}"
                               class="text-indigo-600 hover:text-indigo-900 transition">Edit</a>
                            <form action="{{ route('provider.services.destroy', $service->id) }}" method="POST"
                                  class="inline-block" onsubmit="return confirm('Delete this service?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 transition">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">No services found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $services->appends(request()->query())->links() }}
    </div>
</div>
@endsection
