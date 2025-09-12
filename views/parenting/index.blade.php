@extends('layouts.app')

@section('title', 'Parenting')

@section('content')
<div class="max-w-5xl mx-auto p-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold">Parenting</h1>
            <p class="text-sm text-gray-500">Explore parenting modules and micro-lessons.</p>
        </div>
        <div>
            <a href="{{ route('start-learning.index') }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded">Start Learning</a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="p-4 border rounded bg-white/5">
            <h3 class="font-semibold">Parenting Modules</h3>
            <p class="text-xs text-gray-400">Browse quick lessons by topic.</p>
            <div class="mt-3">
                <a href="{{ route('parenting-modules.index') }}" class="text-sm text-green-400 hover:underline">Open modules</a>
            </div>
        </div>

        <div class="p-4 border rounded bg-white/5">
            <h3 class="font-semibold">Saved Lessons</h3>
            <p class="text-xs text-gray-400">Your saved micro-lessons and reflections.</p>
            <div class="mt-3">
                <a href="{{ route('user.bookings.index') }}" class="text-sm text-green-400 hover:underline">Saved items</a>
            </div>
        </div>
    </div>
</div>
@endsection
