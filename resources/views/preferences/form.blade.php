@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">
    <h1 class="text-2xl font-semibold mb-4">Activity Preferences</h1>

    <form method="POST" action="{{ route('activity.preferences.store') }}">@csrf
        <label>Category</label>
        <input name="preferences[category]" class="block w-full p-2 border rounded" />
        <label class="mt-3">Age group</label>
        <input name="preferences[age_group]" class="block w-full p-2 border rounded" />
        <div class="mt-3">
            <button class="px-3 py-2 bg-blue-600 text-white rounded">Save Preferences</button>
        </div>
    </form>
</div>
@endsection
