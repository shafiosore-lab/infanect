@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">
    <h1 class="text-2xl font-semibold mb-4">Provider Onboarding</h1>

    <form method="POST" action="{{ route('provider.onboarding') }}">
        @csrf
        <div class="grid grid-cols-1 gap-4">
            <div>
                <label class="block text-sm font-medium">Business / Organization Name</label>
                <input name="business_name" class="mt-1 block w-full" />
            </div>
            <div>
                <label class="block text-sm font-medium">Category</label>
                <input name="category" class="mt-1 block w-full" />
            </div>
            <div>
                <label class="block text-sm font-medium">Country</label>
                <input name="country" class="mt-1 block w-full" />
            </div>
            <div>
                <label class="block text-sm font-medium">City</label>
                <input name="city" class="mt-1 block w-full" />
            </div>
            <div>
                <label class="block text-sm font-medium">Timezone</label>
                <input name="timezone" class="mt-1 block w-full" />
            </div>
            <div>
                <label class="block text-sm font-medium">Preferred Language</label>
                <input name="language" class="mt-1 block w-full" />
            </div>

            <div class="pt-4">
                <button class="px-4 py-2 bg-blue-600 text-white rounded">Save and continue</button>
            </div>
        </div>
    </form>
</div>
@endsection
