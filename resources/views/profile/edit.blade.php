@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <div class="bg-white shadow rounded p-6 max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold mb-4">Profile Settings</h1>

        @if(session('status'))
            <div class="p-3 bg-green-100 text-green-800 rounded mb-4">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="mt-1 block w-full rounded-md border-gray-300 p-2" required />
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Phone</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="mt-1 block w-full rounded-md border-gray-300 p-2" />
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Bio</label>
                <textarea name="bio" class="mt-1 block w-full rounded-md border-gray-300 p-2">{{ old('bio', $user->bio) }}</textarea>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Avatar</label>
                <input type="file" name="avatar" class="mt-1 block w-full" />
                @if($user->avatar)
                    <img src="{{ asset('storage/'.$user->avatar) }}" alt="avatar" class="w-24 h-24 rounded mt-2" />
                @endif
            </div>

            <div class="flex items-center justify-between">
                <button class="btn btn-primary">Save Profile</button>
                <a href="{{ route('password.change') }}" class="text-sm text-gray-600">Change Password</a>
            </div>
        </form>
    </div>
</div>
@endsection
