@extends('layouts.admin')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Bonding Activities</h1>

    {{-- Filter/Search Form --}}
    <form method="GET" class="flex flex-wrap gap-3 mb-4 items-center">
        <input
            type="text"
            name="q"
            value="{{ request('q') }}"
            placeholder="Search by title or provider..."
            class="p-2 border rounded flex-1 min-w-[200px]"
        />

        <select name="category" class="p-2 border rounded">
            <option value="">All Categories</option>
            @foreach($activities->pluck('category')->unique() as $cat)
                <option value="{{ $cat }}" @selected(request('category')==$cat)>{{ $cat }}</option>
            @endforeach
        </select>

        <select name="country" class="p-2 border rounded">
            <option value="">All Countries</option>
            @foreach($activities->pluck('country')->unique() as $country)
                <option value="{{ $country }}" @selected(request('country')==$country)>{{ $country }}</option>
            @endforeach
        </select>

        <button class="bg-indigo-600 text-white px-4 py-2 rounded">Filter</button>
    </form>

    {{-- Activities Table --}}
    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="w-full text-left">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3">Title</th>
                    <th class="p-3">Category</th>
                    <th class="p-3">Provider</th>
                    <th class="p-3">Country</th>
                    <th class="p-3">Date/Time</th>
                    <th class="p-3">Slots</th>
                    <th class="p-3">Status</th>
                    <th class="p-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($activities as $activity)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="p-3">{{ $activity->title }}</td>
                        <td class="p-3">{{ $activity->category }}</td>
                        <td class="p-3">{{ optional($activity->provider)->name ?? '—' }}</td>
                        <td class="p-3">{{ $activity->country ?? '—' }}</td>
                        <td class="p-3">{{ $activity->datetime?->format('M d, Y H:i') ?? '—' }}</td>
                        <td class="p-3">{{ $activity->availableSlots() ?? 0 }} / {{ $activity->slots }}</td>
                        <td class="p-3">
                            @if($activity->deleted_at)
                                <span class="text-red-600 font-semibold">Inactive</span>
                            @else
                                <span class="text-green-600 font-semibold">Active</span>
                            @endif
                        </td>
                        <td class="p-3 flex gap-2">
                            <a href="{{ route('admin.activities.show', $activity) }}" class="text-indigo-600 hover:underline">View</a>
                            <a href="{{ route('admin.activities.edit', $activity) }}" class="text-yellow-600 hover:underline">Edit</a>
                            <form action="{{ route('admin.activities.destroy', $activity) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this activity?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="p-4 text-center text-gray-500">No activities found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $activities->withQueryString()->links() }}
    </div>
</div>
@endsection
