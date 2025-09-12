@extends('layouts.app')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-6">➕ Create Subscription</h1>

    <form action="{{ route('admin.subscriptions.store') }}" method="POST" class="space-y-6">
        @csrf

        {{-- User --}}
        <div>
            <label class="block font-medium">User</label>
            <select name="user_id" class="w-full border rounded p-2">
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                @endforeach
            </select>
        </div>

        {{-- Plan --}}
        <div>
            <label class="block font-medium">Plan</label>
            <input type="text" name="plan" class="w-full border rounded p-2" placeholder="e.g., Premium, Standard" required>
        </div>

        {{-- Amount & Currency --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-medium">Amount</label>
                <input type="number" step="0.01" name="amount" class="w-full border rounded p-2" required>
            </div>
            <div>
                <label class="block font-medium">Currency</label>
                <input type="text" name="currency" class="w-full border rounded p-2" value="USD" maxlength="3" required>
            </div>
        </div>

        {{-- Payment --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-medium">Payment Method</label>
                <input type="text" name="payment_method" class="w-full border rounded p-2" placeholder="Mpesa, Card, Paypal">
            </div>
            <div>
                <label class="block font-medium">Payment Reference</label>
                <input type="text" name="payment_reference" class="w-full border rounded p-2" placeholder="Transaction ID">
            </div>
        </div>

        {{-- Status --}}
        <div>
            <label class="block font-medium">Status</label>
            <select name="status" class="w-full border rounded p-2">
                <option value="active">Active</option>
                <option value="pending">Pending</option>
                <option value="cancelled">Cancelled</option>
                <option value="expired">Expired</option>
            </select>
        </div>

        {{-- Meta --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-medium">Country</label>
                <input type="text" name="country" class="w-full border rounded p-2" placeholder="Kenya, USA, UK">
            </div>
            <div>
                <label class="block font-medium">Platform</label>
                <input type="text" name="platform" class="w-full border rounded p-2" placeholder="Web, Mobile">
            </div>
        </div>

        {{-- Dates --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-medium">Start Date</label>
                <input type="date" name="starts_at" class="w-full border rounded p-2">
            </div>
            <div>
                <label class="block font-medium">Expiry Date</label>
                <input type="date" name="expires_at" class="w-full border rounded p-2">
            </div>
        </div>


        {{-- Submit --}}
        <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded">💾 Save Subscription</button>
    </form>
</div>
@endsection
