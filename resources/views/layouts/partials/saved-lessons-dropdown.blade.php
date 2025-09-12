<style>
.saved-dropdown { position: relative; display: inline-block; }
.saved-dropdown .panel { display: none; position: absolute; right: 0; top: calc(100% + 8px); z-index: 50; }
.saved-dropdown:hover .panel { display: block; }
</style>
<div class="saved-dropdown">
    <button class="px-3 py-2 bg-white/10 text-white rounded-lg">Saved</button>
    <div class="panel w-80 bg-white rounded-lg shadow-lg text-black p-3 z-50">
        <div class="text-sm font-semibold mb-2">Saved Audio</div>
        @php $saved = session('saved_lessons', ['audio'=>[], 'video'=>[]]); @endphp
        @if(empty($saved['audio']))
            <div class="text-xs text-gray-500 mb-2">No audio saved</div>
        @else
            @foreach($saved['audio'] as $audio)
                <div class="flex items-center justify-between mb-2">
                    <div>
                        <div class="text-sm font-medium">{{ $audio['title'] ?? $audio['external_id'] }}</div>
                        <div class="text-xs text-gray-500">Saved: {{ $audio['saved_at'] ?? '' }}</div>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('start-learning.play.direct', ['externalId' => $audio['external_id']]) }}" class="px-2 py-1 bg-black text-white rounded text-xs">Play</a>
                        <form method="POST" action="{{ route('saved-lessons.delete') }}">@csrf
                            <input type="hidden" name="type" value="audio">
                            <input type="hidden" name="external_id" value="{{ $audio['external_id'] }}">
                            <button class="px-2 py-1 bg-red-500 text-white rounded text-xs">Delete</button>
                        </form>
                    </div>
                </div>
            @endforeach
        @endif

        <div class="border-t my-2"></div>
        <div class="text-sm font-semibold mb-2">Saved Video</div>
        @if(empty($saved['video']))
            <div class="text-xs text-gray-500">No video saved</div>
        @else
            @foreach($saved['video'] as $video)
                <div class="flex items-center justify-between mb-2">
                    <div>
                        <div class="text-sm font-medium">{{ $video['title'] ?? $video['external_id'] }}</div>
                        <div class="text-xs text-gray-500">Saved: {{ $video['saved_at'] ?? '' }}</div>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('start-learning.play.direct', ['externalId' => $video['external_id']]) }}" class="px-2 py-1 bg-black text-white rounded text-xs">Play</a>
                        <form method="POST" action="{{ route('saved-lessons.delete') }}">@csrf
                            <input type="hidden" name="type" value="video">
                            <input type="hidden" name="external_id" value="{{ $video['external_id'] }}">
                            <button class="px-2 py-1 bg-red-500 text-white rounded text-xs">Delete</button>
                        </form>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>

<div id="saved-lessons" class="relative inline-block text-left">
    <button id="saved-lessons-btn" class="px-3 py-2 bg-white/10 text-white rounded">Saved</button>
    <div id="saved-lessons-menu" class="hidden absolute right-0 mt-2 w-64 bg-white rounded shadow-lg p-3 text-black">
        <div class="text-sm font-semibold mb-2">Saved Lessons</div>
        <div id="saved-list-content" class="space-y-2 text-xs text-gray-700">Loading...</div>
    </div>
</div>

<script>
(function(){
    const btn = document.getElementById('saved-lessons-btn');
    const menu = document.getElementById('saved-lessons-menu');
    const content = document.getElementById('saved-list-content');
    btn.addEventListener('click', function(e){ e.preventDefault(); menu.classList.toggle('hidden'); fetchList(); });

    function fetchList(){ fetch('{{ route('saved-lessons.list') }}', { credentials:'same-origin' }).then(r=>r.json()).then(data=>{
        content.innerHTML = '';
        if ((data.audio||[]).length === 0 && (data.video||[]).length === 0) { content.innerHTML = '<div class="text-xs text-gray-500">No saved lessons</div>'; return; }
        if ((data.audio||[]).length) {
            const h = document.createElement('div'); h.className='font-semibold text-sm'; h.textContent='Audio'; content.appendChild(h);
            data.audio.forEach(a=>{ const row = document.createElement('div'); row.className='flex items-center justify-between'; row.innerHTML = '<div>'+a.title+'</div><div class="flex gap-1"><a href="/start-learning/play/'+a.external_id+'" class="text-emerald-600">Play</a><button data-external="'+a.external_id+'" data-type="audio" class="text-red-600 ml-2 btn-delete">Delete</button></div>'; content.appendChild(row); });
        }
        if ((data.video||[]).length) {
            const h = document.createElement('div'); h.className='font-semibold text-sm mt-2'; h.textContent='Video'; content.appendChild(h);
            data.video.forEach(a=>{ const row = document.createElement('div'); row.className='flex items-center justify-between'; row.innerHTML = '<div>'+a.title+'</div><div class="flex gap-1"><a href="/start-learning/play/'+a.external_id+'" class="text-emerald-600">Play</a><button data-external="'+a.external_id+'" data-type="video" class="text-red-600 ml-2 btn-delete">Delete</button></div>'; content.appendChild(row); });
        }

        content.querySelectorAll('.btn-delete').forEach(b=>b.addEventListener('click', function(){ fetch('{{ route('saved-lessons.delete') }}', { method:'POST', credentials:'same-origin', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Content-Type':'application/json'}, body: JSON.stringify({ type: b.getAttribute('data-type'), external_id: b.getAttribute('data-external') }) }).then(()=>fetchList()).catch(()=>{}); }));
    }).catch(()=>{ content.innerHTML = '<div class="text-xs text-gray-500">Unable to load</div>'; }); }
})();
</script>
