@extends('layouts.app')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-6">✏ Edit Subscription</h1>

    <form action="{{ route('admin.subscriptions.update', $subscription->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- User --}}
        <div>
            <label class="block font-medium">User</label>
            <select name="user_id" class="w-full border rounded p-2">
                @foreach($users as $user)
                    <option value="{{ $user->id }}" @if($subscription->user_id == $user->id) selected @endif>
                        {{ $user->name }} ({{ $user->email }})
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Plan --}}
        <div>
            <label class="block font-medium">Plan</label>
            <input type="text" name="plan" value="{{ $subscription->plan }}" class="w-full border rounded p-2" required>
        </div>

        {{-- Amount & Currency --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-medium">Amount</label>
                <input type="number" step="0.01" name="amount" value="{{ $subscription->amount }}" class="w-full border rounded p-2" required>
            </div>
            <div>
                <label class="block font-medium">Currency</label>
                <input type="text" name="currency" value="{{ $subscription->currency }}" maxlength="3" class="w-full border rounded p-2" required>
            </div>
        </div>

        {{-- Payment --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-medium">Payment Method</label>
                <input type="text" name="payment_method" value="{{ $subscription->payment_method }}" class="w-full border rounded p-2">
            </div>
            <div>
                <label class="block font-medium">Payment Reference</label>
                <input type="text" name="payment_reference" value="{{ $subscription->payment_reference }}" class="w-full border rounded p-2">
            </div>
        </div>

        {{-- Status --}}
        <div>
            <label class="block font-medium">Status</label>
            <select name="status" class="w-full border rounded p-2">
                <option value="active" @if($subscription->status=='active') selected @endif>Active</option>
                <option value="pending" @if($subscription->status=='pending') selected @endif>Pending</option>
                <option value="cancelled" @if($subscription->status=='cancelled') selected @endif>Cancelled</option>
                <option value="expired" @if($subscription->status=='expired') selected @endif>Expired</option>
            </select>
        </div>

        {{-- Meta --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-medium">Country</label>
                <input type="text" name="country" value="{{ $subscription->country }}" class="w-full border rounded p-2">
            </div>
            <div>
                <label class="block font-medium">Platform</label>
                <input type="text" name="platform" value="{{ $subscription->platform }}" class="w-full border rounded p-2">
            </div>
        </div>

        {{-- Dates --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-medium">Start Date</label>
                <input type="date" name="starts_at" value="{{ optional($subscription->starts_at)->format('Y-m-d') }}" class="w-full border rounded p-2">
            </div>
            <div>
                <label class="block font-medium">Expiry Date</label>
                <input type="date" name="expires_at" value="{{ optional($subscription->expires_at)->format('Y-m-d') }}" class="w-full border rounded p-2">
            </div>
        </div>

        {{-- Submit --}}
        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded">💾 Update Subscription</button>
    </form>
</div>
@endsection
