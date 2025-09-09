@php
    $count = $count ?? 3;
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 animate-pulse">
    @for($i = 0; $i < $count; $i++)
        <div class="bg-gray-200 rounded-lg h-24"></div>
    @endfor
</div>
