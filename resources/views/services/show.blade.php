@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <div class="bg-white shadow rounded p-6">
        <h1 class="text-2xl font-bold mb-2">{{ $service->name }}</h1>
        <p class="text-gray-700 mb-4">{{ $service->description }}</p>

        <div class="flex items-center justify-between mb-4">
            <div>
                <strong>Provider:</strong> {{ $service->provider->name ?? 'N/A' }}
            </div>
            <div>
                <strong>Price:</strong> {{ $service->currency ?? 'KES' }} {{ number_format($service->price, 2) }}
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Choose date</label>
            <input id="date" type="text" value="{{ $date }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" />
        </div>

        <div id="slots-wrapper">
            <p>Loading slots...</p>
        </div>

        <form id="booking-form" method="POST" action="{{ route('services.book', $service->id) }}" class="mt-4 hidden">
            @csrf
            <input type="hidden" name="start_at" id="form_start_at" />
            <input type="hidden" name="end_at" id="form_end_at" />
            <input type="hidden" name="timezone" id="form_timezone" value="" />
            <button type="submit" class="btn btn-primary">Book Selected Slot</button>
        </form>
    </div>
</div>

<script>
    async function fetchSlots(date){
        const tz = window.APP.timezone || 'UTC';
        const resp = await fetch("{{ route('services.slots', $service->id) }}?date="+date+"&timezone="+encodeURIComponent(tz));
        const data = await resp.json();
        return data.slots || [];
    }

    function renderSlots(slots){
        const wrapper = document.getElementById('slots-wrapper');
        wrapper.innerHTML = '';
        if(!slots || slots.length === 0){
            wrapper.innerHTML = '<p>No slots available for this date.</p>';
            document.getElementById('booking-form').classList.add('hidden');
            return;
        }

        const list = document.createElement('div');
        list.className = 'grid grid-cols-1 md:grid-cols-2 gap-2';

        slots.forEach(slot => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'p-3 border rounded text-left hover:bg-gray-100';
            btn.textContent = slot.start + ' - ' + slot.end;
            btn.addEventListener('click', ()=>{
                document.getElementById('form_start_at').value = slot.start;
                document.getElementById('form_end_at').value = slot.end;
                document.getElementById('booking-form').classList.remove('hidden');
                // highlight
                document.querySelectorAll('#slots-wrapper button').forEach(b=>b.classList.remove('bg-blue-100'));
                btn.classList.add('bg-blue-100');
            });
            list.appendChild(btn);
        });

        wrapper.appendChild(list);
    }

    flatpickr('#date', {
        defaultDate: '{{ $date }}',
        onChange: async function(selectedDates, dateStr, instance){
            const dateIso = instance.formatDate(selectedDates[0], 'Y-m-d');
            const slots = await fetchSlots(dateIso);
            renderSlots(slots);
        }
    });

    document.addEventListener('DOMContentLoaded', async function(){
        // set timezone
        document.getElementById('form_timezone').value = window.APP.timezone || 'UTC';

        const dateInput = document.getElementById('date');
        const slots = await fetchSlots(dateInput.value);
        renderSlots(slots);

        dateInput.addEventListener('change', async function(){
            const s = await fetchSlots(this.value);
            renderSlots(s);
        });
    });
</script>
@endsection
