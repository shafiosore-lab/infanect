@props(['status'])

<span class="px-2 py-1 rounded text-white
    @if($status=='active') bg-green-500
    @elseif($status=='expired') bg-red-500
    @elseif($status=='trial') bg-yellow-500
    @else bg-gray-500 @endif">
    {{ ucfirst($status) }}
</span>
