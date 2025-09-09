{{-- resources/views/provider/register.blade.php --}}
@extends('layouts.app')

@section('title', 'Provider Registration')

@section('content')
<div class="max-w-3xl mx-auto bg-white shadow-lg rounded-2xl p-8 mt-10">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Register as a Service Provider</h2>

    <form action="{{ route('provider.register.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Provider Name --}}
        <div class="mb-4">
            <label for="name" class="block text-gray-700 font-semibold mb-2">Provider / Business Name</label>
            <input type="text" name="name" id="name" class="w-full border-gray-300 rounded-lg shadow-sm" required>
        </div>

        {{-- Email --}}
        <div class="mb-4">
            <label for="email" class="block text-gray-700 font-semibold mb-2">Email</label>
            <input type="email" name="email" id="email" class="w-full border-gray-300 rounded-lg shadow-sm" required>
        </div>

        {{-- Phone --}}
        <div class="mb-4">
            <label for="phone" class="block text-gray-700 font-semibold mb-2">Phone</label>
            <input type="text" name="phone" id="phone" class="w-full border-gray-300 rounded-lg shadow-sm">
        </div>

        {{-- Specialization --}}
        <div class="mb-4">
            <label for="specialization" class="block text-gray-700 font-semibold mb-2">Specialization</label>
            <select name="specialization" id="specialization" class="w-full border-gray-300 rounded-lg shadow-sm">
                <option value="bonding_activity">Bonding Activity</option>
                <option value="counseling">Counseling</option>
                <option value="training">Training</option>
                <option value="event">Event</option>
            </select>
        </div>

        {{-- Country --}}
        <div class="mb-4">
            <label for="country" class="block text-gray-700 font-semibold mb-2">Country</label>
            <input type="text" name="country" id="country" placeholder="Kenya, USA, UK" class="w-full border-gray-300 rounded-lg shadow-sm" required>
        </div>

        {{-- City --}}
        <div class="mb-4">
            <label for="city" class="block text-gray-700 font-semibold mb-2">City</label>
            <input type="text" name="city" id="city" class="w-full border-gray-300 rounded-lg shadow-sm">
        </div>

        {{-- Logo --}}
        <div class="mb-4">
            <label for="logo" class="block text-gray-700 font-semibold mb-2">Logo (Optional)</label>
            <input type="file" name="logo" id="logo" class="w-full">
        </div>

        {{-- Submit --}}
        <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg shadow hover:bg-indigo-700">
            Register Provider
        </button>
    </form>
</div>
@endsection
