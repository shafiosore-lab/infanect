@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Search Activities</h1>

    <form method="GET" action="{{ route('activities.search') }}" class="mb-4">
        <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Search..." class="border p-2 w-full" />
    </form>

    @if($results->isEmpty())
        <p>No results.</p>
    @else
        <ul>
            @foreach($results as $r)
                <li class="mb-3">
                    <a href="{{ route('activities.show', $r->id) }}" class="font-semibold">{{ $r->title }}</a>
                    <div class="text-sm text-gray-600">{{ Str::limit($r->description, 150) }}</div>
                </li>
            @endforeach
        </ul>

        {{ $results->links() }}
    @endif
</div>
@endsection
