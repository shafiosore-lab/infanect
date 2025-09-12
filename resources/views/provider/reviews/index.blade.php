@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <div class="bg-white shadow rounded p-6">
        <h1 class="text-2xl font-bold mb-4">Reviews</h1>

        @if($reviews->isEmpty())
            <p>No reviews yet.</p>
        @else
            <ul>
                @foreach($reviews as $r)
                    <li class="mb-4">
                        <strong>{{ $r->service_name }}</strong>
                        <div>Rating: {{ $r->rating }} / 5</div>
                        <div>{{ $r->review }}</div>
                        <div class="text-sm text-gray-500">{{ $r->created_at }}</div>
                    </li>
                @endforeach
            </ul>

            {{ $reviews->links() }}
        @endif
    </div>
</div>
@endsection
