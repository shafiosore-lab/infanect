@extends('layouts.app')

@section('title', 'Edit Role')

@section('content')
<div class="container mx-auto p-6">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Edit Role</h1>

        <div class="bg-white shadow rounded-lg p-6">
            <form method="POST" action="{{ route('admin.roles.update', $role) }}">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Role Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $role->name) }}"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="slug" class="block text-sm font-medium text-gray-700">Role Slug</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug', $role->slug) }}"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @error('slug')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" rows="3"
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('description', $role->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end space-x-3">
                    <a href="{{ route('admin.roles.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg">
                        Cancel
                    </a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                        Update Role
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="max-w-3xl mx-auto mt-8">
        <h1 class="text-2xl font-semibold mb-4">Edit Role for {{ $user->name }}</h1>

        <form method="POST" action="{{ route('admin.roles.management.update', $user) }}">@csrf @method('PUT')
            <label class="block mb-2">Role</label>
            <select name="role" class="w-full p-2 border rounded">
                @foreach($roles as $r)
                    <option value="{{ $r }}" {{ $user->role === $r ? 'selected' : '' }}>{{ $r }}</option>
                @endforeach
            </select>
            <div class="mt-4">
                <button class="px-4 py-2 bg-indigo-600 text-white rounded">Save</button>
                <a href="{{ route('admin.roles.management.index') }}" class="ml-2 text-gray-600">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
