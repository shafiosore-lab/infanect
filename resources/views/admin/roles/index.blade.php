@extends('layouts.app')

@section('title', 'Roles Management')

@section('content')
<div class="container mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Roles Management</h1>
        <a href="{{ route('admin.roles.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
            Add New Role
        </a>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Slug</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($roles as $role)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $role->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $role->slug }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $role->description }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="{{ route('admin.roles.edit', $role) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                        <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">No roles found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $roles->links() }}

    <div class="mt-8">
        <h2 class="text-2xl font-semibold mb-4">User Roles</h2>
        <table class="w-full bg-white rounded shadow">
            <thead>
                <tr class="text-left p-2">
                    <th class="p-2">ID</th>
                    <th class="p-2">Name</th>
                    <th class="p-2">Email</th>
                    <th class="p-2">Role</th>
                    <th class="p-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $u)
                <tr>
                    <td class="p-2">{{ $u->id }}</td>
                    <td class="p-2">{{ $u->name }}</td>
                    <td class="p-2">{{ $u->email }}</td>
                    <td class="p-2">{{ $u->role }}</td>
                    <td class="p-2"><a href="{{ route('admin.roles.management.edit', $u) }}" class="text-blue-600">Edit</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">{{ $users->links() }}</div>
    </div>
</div>
@endsection
