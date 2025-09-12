@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto p-6">
    <h1 class="text-2xl font-semibold mb-4">Provider Registration</h1>
    <form method="POST" action="{{ route('provider.register.post') }}">
        @csrf
        <div class="mb-3">
            <label class="block">Name</label>
            <input name="name" class="w-full border p-2" required>
        </div>
        <div class="mb-3">
            <label class="block">Email</label>
            <input name="email" type="email" class="w-full border p-2" required>
        </div>
        <div class="mb-3">
            <label class="block">Phone</label>
            <input name="phone" class="w-full border p-2">
        </div>
        <div class="mb-3">
            <label class="block">Password</label>
            <input name="password" type="password" class="w-full border p-2" required>
        </div>
        <div class="mb-3">
            <label class="block">Confirm Password</label>
            <input name="password_confirmation" type="password" class="w-full border p-2" required>
        </div>
        <button class="px-4 py-2 bg-indigo-600 text-white rounded">Apply as Provider</button>
    </form>
</div>
@endsection
