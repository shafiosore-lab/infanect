@extends('layouts.app')

@section('content')
<div class="p-6 space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800 flex items-center">
            🛠 {{ __('Services') }}
        </h1>
        <a href="{{ route('admin.services.create') }}"
           class="px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg shadow hover:from-indigo-700 hover:to-purple-700 transition-all">
            + {{ __('Add New Service') }}
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white p-4 shadow rounded-lg flex flex-wrap items-center gap-4">
        <form method="GET" action="{{ route('admin.services.index') }}" class="flex flex-wrap items-center gap-3 w-full">
            <input type="text" name="q" value="{{ request('q') }}"
                   placeholder="{{ __('Search services...') }}"
                   class="w-64 border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">

            <select name="status" class="border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">{{ __('All Statuses') }}</option>
                <option value="1" @selected(request('status') === "1")>{{ __('Active') }}</option>
                <option value="0" @selected(request('status') === "0")>{{ __('Inactive') }}</option>
            </select>

            <select name="country" class="border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">{{ __('All Countries') }}</option>
                @foreach($countries ?? [] as $country)
                    <option value="{{ $country }}" @selected(request('country') === $country)>{{ $country }}</option>
                @endforeach
            </select>

            <button type="submit"
                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700 transition">
                {{ __('Filter') }}
            </button>
        </form>
    </div>

    <!-- Services Table -->
    <div class="bg-white shadow rounded-lg overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">#</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">{{ __('Name') }}</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">{{ __('Provider') }}</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">{{ __('Price') }}</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">{{ __('Category') }}</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">{{ __('Country') }}</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">{{ __('Status') }}</th>
                    <th class="px-6 py-3 text-right font-medium text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($services as $service)
                <tr>
                    <td class="px-6 py-4">{{ $service->id }}</td>
                    <td class="px-6 py-4 font-semibold">{{ $service->name }}</td>
                    <td class="px-6 py-4">{{ $service->provider->name ?? '—' }}</td>
                    <td class="px-6 py-4">{{ $service->formatted_price ?? $service->price }} {{ $service->currency ?? 'USD' }}</td>
                    <td class="px-6 py-4">{{ $service->category->name ?? '—' }}</td>
                    <td class="px-6 py-4">{{ $service->country ?? '🌍' }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $service->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $service->status_label }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right space-x-3">
                        <a href="{{ route('admin.services.edit', $service) }}"
                           class="text-indigo-600 hover:text-indigo-900">{{ __('Edit') }}</a>
                        <form action="{{ route('admin.services.destroy', $service) }}" method="POST" class="inline-block"
                              onsubmit="return confirm('{{ __('Are you sure?') }}');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">{{ __('Delete') }}</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-4 text-center text-gray-400">
                        {{ __('No services available.') }}
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">{{ $services->links() }}</div>
</div>
@endsection
