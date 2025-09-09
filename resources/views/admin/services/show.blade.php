{{-- resources/views/admin/services/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="p-6 max-w-4xl mx-auto">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-2">
            🛠 {{ __('Service Details') }}
        </h1>
        <a href="{{ route('admin.services.index') }}"
           class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition">
            ← {{ __('Back to Services') }}
        </a>
    </div>

    <!-- Card -->
    <div class="bg-white shadow-lg rounded-xl overflow-hidden">
        <div class="grid grid-cols-1 md:grid-cols-3">
            <!-- Image -->
            <div class="md:col-span-1">
                @if($service->image)
                    <img src="{{ asset('storage/' . $service->image) }}"
                         alt="{{ $service->name }}"
                         class="w-full h-64 object-cover">
                @else
                    <div class="w-full h-64 flex items-center justify-center bg-gray-100 text-gray-400">
                        {{ __('No Image') }}
                    </div>
                @endif
            </div>

            <!-- Info -->
            <div class="md:col-span-2 p-6 space-y-4">
                <h2 class="text-2xl font-semibold text-gray-900">{{ $service->name }}</h2>

                <p class="text-gray-700">{{ $service->description ?? __('No description available.') }}</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                    <div>
                        <span class="font-semibold">{{ __('Provider:') }}</span>
                        {{ $service->provider->name ?? __('N/A') }}
                    </div>
                    <div>
                        <span class="font-semibold">{{ __('Category:') }}</span>
                        {{ $service->category->name ?? __('N/A') }}
                    </div>
                    <div>
                        <span class="font-semibold">{{ __('Price:') }}</span>
                        {{ number_format($service->price, 2) }} {{ $service->currency ?? 'USD' }}
                    </div>
                    <div>
                        <span class="font-semibold">{{ __('Duration:') }}</span>
                        {{ $service->duration ? $service->duration . ' ' . __('minutes') : __('N/A') }}
                    </div>
                    <div>
                        <span class="font-semibold">{{ __('Country:') }}</span>
                        {{ $service->country ?? __('Global') }}
                    </div>
                    <div>
                        <span class="font-semibold">{{ __('Status:') }}</span>
                        <span class="px-2 py-1 rounded text-xs font-semibold
                                    {{ $service->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $service->is_active ? __('Active') : __('Inactive') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Metadata + Actions -->
        <div class="p-6 border-t border-gray-200 space-y-4">
            @if(!empty($service->metadata))
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ __('Additional Information') }}</h3>
                    <pre class="bg-gray-50 p-3 rounded-lg text-sm text-gray-700">
{{ json_encode($service->metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}
                    </pre>
                </div>
            @endif

            <!-- Actions -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.services.edit', $service) }}"
                   class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                    ✏️ {{ __('Edit') }}
                </a>
                <form action="{{ route('admin.services.destroy', $service) }}" method="POST"
                      onsubmit="return confirm('{{ __('Are you sure you want to delete this service?') }}');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        🗑 {{ __('Delete') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
