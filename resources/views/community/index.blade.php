@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <h1 class="text-2xl font-semibold mb-4">Community</h1>

    <form method="POST" action="{{ route('community.posts.store') }}">@csrf
        <textarea name="content" class="w-full p-2 border rounded" placeholder="Share something..."></textarea>
        <button class="mt-2 px-3 py-2 bg-blue-600 text-white rounded">Post</button>
    </form>

    <div class="mt-6">
        @foreach($posts as $post)
            <div class="p-3 bg-white rounded mb-2">
                <p>{{ $post->content }}</p>
                <small class="text-gray-500">{{ $post->created_at }}</small>
            </div>
        @endforeach
    </div>
</div>
@endsection
