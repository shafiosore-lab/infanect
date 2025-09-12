@extends('layouts.app')

@section('title', 'Messages - Infanect')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 via-blue-50 to-purple-50">
    <!-- Hero Header -->
    <section class="relative bg-gradient-to-r from-purple-600 via-blue-600 to-indigo-700 text-white py-12 overflow-hidden">
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent"></div>

        <!-- Floating Elements -->
        <div class="absolute top-8 left-8 w-16 h-16 bg-white/10 rounded-full blur-lg animate-pulse"></div>
        <div class="absolute bottom-8 right-8 w-20 h-20 bg-purple-200/10 rounded-full blur-xl animate-bounce"></div>

        <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="flex flex-col items-center">
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mb-4 backdrop-blur-sm border border-white/30">
                    <i class="fas fa-envelope text-2xl text-white"></i>
                </div>
                <h1 class="text-4xl md:text-5xl font-bold mb-4 bg-gradient-to-r from-white to-purple-100 bg-clip-text text-transparent">
                    Messages
                </h1>
                <p class="text-xl text-purple-100 max-w-3xl mb-8">
                    Stay connected with the Infanect community and never miss important updates
                </p>
            </div>
        </div>
    </section>

    <!-- Messages Section -->
    <section class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Message Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-inbox text-blue-600"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">{{ $messages->count() }}</h3>
                            <p class="text-sm text-gray-600">Total Messages</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-envelope text-red-600"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-red-600">{{ $messages->where('is_read', false)->count() }}</h3>
                            <p class="text-sm text-gray-600">Unread Messages</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-check-circle text-green-600"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-green-600">{{ $messages->where('is_read', true)->count() }}</h3>
                            <p class="text-sm text-gray-600">Read Messages</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Messages List -->
            <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-bold text-gray-900">Your Messages</h2>
                        <button class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors text-sm font-medium">
                            <i class="fas fa-plus mr-2"></i>
                            Compose
                        </button>
                    </div>
                </div>

                <div class="divide-y divide-gray-200">
                    @forelse($messages as $message)
                        <div class="p-6 hover:bg-gray-50 transition-colors cursor-pointer {{ !$message->is_read ? 'bg-blue-50 border-l-4 border-l-blue-500' : '' }}"
                             onclick="window.location.href='{{ route('messages.show', $message->id) }}'">
                            <div class="flex items-start justify-between">
                                <div class="flex items-start flex-1">
                                    <!-- Message Type Icon -->
                                    <div class="w-10 h-10 rounded-lg flex items-center justify-center mr-4 flex-shrink-0
                                        @switch($message->type)
                                            @case('system')
                                                bg-blue-100
                                                @break
                                            @case('booking')
                                                bg-green-100
                                                @break
                                            @case('promotion')
                                                bg-purple-100
                                                @break
                                            @default
                                                bg-gray-100
                                        @endswitch
                                    ">
                                        @switch($message->type)
                                            @case('system')
                                                <i class="fas fa-cog text-blue-600"></i>
                                                @break
                                            @case('booking')
                                                <i class="fas fa-calendar-check text-green-600"></i>
                                                @break
                                            @case('promotion')
                                                <i class="fas fa-star text-purple-600"></i>
                                                @break
                                            @default
                                                <i class="fas fa-envelope text-gray-600"></i>
                                        @endswitch
                                    </div>

                                    <!-- Message Content -->
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between mb-1">
                                            <h3 class="font-semibold text-gray-900 {{ !$message->is_read ? 'font-bold' : '' }}">
                                                {{ $message->sender }}
                                            </h3>
                                            <span class="text-sm text-gray-500">
                                                {{ date('M j, Y', strtotime($message->created_at)) }}
                                            </span>
                                        </div>

                                        <h4 class="font-medium text-gray-800 mb-2 {{ !$message->is_read ? 'font-semibold' : '' }}">
                                            {{ $message->subject }}
                                        </h4>

                                        <p class="text-gray-600 text-sm line-clamp-2">
                                            {{ $message->preview }}
                                        </p>

                                        <!-- Message Labels -->
                                        <div class="flex items-center mt-3 space-x-2">
                                            <span class="px-2 py-1 rounded-full text-xs font-medium
                                                @switch($message->type)
                                                    @case('system')
                                                        bg-blue-100 text-blue-800
                                                        @break
                                                    @case('booking')
                                                        bg-green-100 text-green-800
                                                        @break
                                                    @case('promotion')
                                                        bg-purple-100 text-purple-800
                                                        @break
                                                    @default
                                                        bg-gray-100 text-gray-800
                                                @endswitch
                                            ">
                                                {{ ucfirst($message->type) }}
                                            </span>

                                            @if(!$message->is_read)
                                                <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs font-medium">
                                                    New
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Arrow -->
                                <div class="ml-4 flex-shrink-0">
                                    <i class="fas fa-chevron-right text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-12 text-center">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-inbox text-gray-400 text-xl"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">No Messages Yet</h3>
                            <p class="text-gray-600">
                                You don't have any messages at the moment. We'll notify you when you receive new messages.
                            </p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>
</div>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection
