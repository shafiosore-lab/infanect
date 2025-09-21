@extends('layouts.app')

@section('title', 'Page not found')

@section('content')
<div class="container text-center py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body py-5">
                    <div class="mb-4">
                        <i class="fas fa-exclamation-triangle text-warning" style="font-size: 4rem;"></i>
                    </div>
                    <h1 class="display-4 mb-3">404</h1>
                    <h2 class="h4 mb-3">Page Not Found</h2>
                    <p class="text-muted mb-4">
                        Sorry, the page you are looking for could not be found.
                    </p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('dashboard') }}" class="btn btn-primary">
                            <i class="fas fa-home me-2"></i>Go to Dashboard
                        </a>
                        <a href="{{ route('activities.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-list me-2"></i>Browse Activities
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
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
