@extends('layouts.app')

@section('title', 'Page not found')

@section('content')
<style>
    .confetti-small::after {
        content: '';
        position: absolute;
        left: -10%;
        top: -40px;
        width: 140%;
        height: 200px;
        background-image: radial-gradient(circle at 10% 20%, rgba(255,255,255,0.06) 0 2px, transparent 3px), radial-gradient(circle at 80% 40%, rgba(255,255,255,0.04) 0 2px, transparent 3px);
        pointer-events: none;
        transform: rotate(-6deg);
    }
    @keyframes bounce { 0% { transform: translateY(0);} 50% { transform: translateY(-8px);} 100% { transform: translateY(0);} }
    .bounce { animation: bounce 2s infinite; }
</style>

<div class="min-h-screen flex items-center justify-center p-6">
    <div class="max-w-3xl w-full">
        @if(session('status'))
            <div class="mb-6 p-4 rounded-lg bg-gradient-to-r from-emerald-500 to-teal-500 text-white shadow confetti-small">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center text-2xl">🎉</div>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-lg font-semibold">{{ __('parenting.thanks_title') }}</h2>
                        <p class="mt-2 text-sm text-white/90">{{ session('status') }} {{ __('parenting.thanks_body') }}</p>
                        <div class="mt-4 flex justify-center gap-4">
                            <a href="{{ route('parenting-modules.index') }}" class="px-6 py-2 bg-white text-emerald-700 rounded-md font-medium">{{ __('parenting.back_to_modules') }}</a>
                            <a href="{{ route('start-learning.index') }}" class="px-6 py-2 bg-white/10 text-white rounded-md border border-white/20">{{ __('parenting.play_another') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-white/5 p-8 rounded-2xl border border-white/10 text-center">
            <div class="mb-6">
                <div class="text-6xl font-extrabold">🙏</div>
                <div class="mt-4 text-xl font-semibold">Thank you</div>
                <p class="mt-2 text-sm text-gray-300">Your feedback and data help us improve our services and personalise future lessons.</p>
            </div>

            <div class="mb-6 flex items-center justify-center gap-4">
                <div class="w-24 h-24 bg-emerald-600 rounded-lg flex items-center justify-center text-4xl bounce">🌟</div>
                <div class="w-24 h-24 bg-violet-600 rounded-lg flex items-center justify-center text-4xl">🧡</div>
                <div class="w-24 h-24 bg-yellow-500 rounded-lg flex items-center justify-center text-4xl">🎧</div>
            </div>

            <div class="space-y-3">
                <div class="mt-3 flex flex-wrap justify-center gap-3">
                    <a href="{{ route('parenting-modules.index') }}" class="px-6 py-2 bg-emerald-500 text-white rounded-lg">Back to Parenting Modules</a>
                    <a href="{{ route('start-learning.index') }}" class="px-6 py-2 bg-indigo-600 text-white rounded-lg">Start Learning</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
