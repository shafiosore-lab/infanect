<!-- resources/views/admin/activities/index.blade.php -->

@extends('layouts.admin') {{-- Make sure layouts/admin.blade.php exists --}}

@section('title', 'Bonding Activities')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold text-gray-900 mb-4">Bonding Activities</h1>
    <p class="text-gray-600 mb-6">Manage all bonding activities for parents and children here.</p>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Activity Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($activities ?? [] as $activity)
                    <tr>
                        <td class="px-6 py-4">{{ $activity->name }}</td>
                        <td class="px-6 py-4">{{ $activity->category ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $activity->description ?? '-' }}</td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('activities.edit', $activity->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">Edit</a>
                            <form action="{{ route('activities.destroy', $activity->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this activity?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">No activities found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
