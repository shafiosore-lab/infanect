@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <div class="bg-white shadow rounded p-6">
        <h1 class="text-2xl font-bold mb-4">Manage Availability for {{ $service->name }}</h1>

        @if(session('status'))
            <div class="p-3 bg-green-100 text-green-800 rounded mb-4">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('provider.services.availability.update', $service->id) }}">
            @csrf

            @php
                $days = ['mon'=>'Mon','tue'=>'Tue','wed'=>'Wed','thu'=>'Thu','fri'=>'Fri','sat'=>'Sat','sun'=>'Sun'];
                $availability = $service->availability ?? [];
            @endphp

            <div id="days" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($days as $key => $label)
                    <div class="border rounded p-3">
                        <div class="flex items-center justify-between mb-2">
                            <strong>{{ $label }}</strong>
                            <button type="button" data-day="{{ $key }}" class="add-range text-sm text-blue-600">+ Add range</button>
                        </div>

                        <div class="ranges" data-day="{{ $key }}">
                            @php
                                $ranges = $availability[$key] ?? [];
                            @endphp
                            @if(!empty($ranges) && is_array($ranges))
                                @foreach($ranges as $r)
                                    <div class="flex items-center mb-2 range-item">
                                        <input type="text" name="availability[{{ $key }}][]" value="{{ $r }}" class="mr-2 flex-1 rounded border-gray-300 p-2" />
                                        <button type="button" class="remove-range text-red-600 ml-2">Remove</button>
                                    </div>
                                @endforeach
                            @else
                                <div class="flex items-center mb-2 range-item">
                                    <input type="text" name="availability[{{ $key }}][]" value="09:00-17:00" class="mr-2 flex-1 rounded border-gray-300 p-2" />
                                    <button type="button" class="remove-range text-red-600 ml-2">Remove</button>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4">
                <button class="btn btn-primary">Save Availability</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('click', function(e){
        if(e.target && e.target.classList.contains('add-range')){
            const day = e.target.getAttribute('data-day');
            const container = document.querySelector('.ranges[data-day="'+day+'"]');
            const div = document.createElement('div');
            div.className = 'flex items-center mb-2 range-item';
            div.innerHTML = `<input type="text" name="availability[${day}][]" value="09:00-17:00" class="mr-2 flex-1 rounded border-gray-300 p-2" /> <button type="button" class="remove-range text-red-600 ml-2">Remove</button>`;
            container.appendChild(div);
        }

        if(e.target && e.target.classList.contains('remove-range')){
            const item = e.target.closest('.range-item');
            if(item) item.remove();
        }
    });
</script>
@endsection
