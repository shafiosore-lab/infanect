@php
$lessonTitle = 'Lesson';
$lessonExternal = '';
$audio = $audioUrl ?? null;
$video = $videoUrl ?? null;
if (isset($lesson)) {
    if (is_array($lesson)) {
        $lessonTitle = $lesson['title'] ?? $lessonTitle;
        $lessonExternal = $lesson['external_id'] ?? ($lesson['externalId'] ?? $lessonExternal);
        $audio = $audioUrl ?? ($lesson['audioUrl'] ?? $audio);
        $video = $videoUrl ?? ($lesson['videoUrl'] ?? $video);
    } elseif (is_object($lesson)) {
        $lessonTitle = $lesson->title ?? $lessonTitle;
        $lessonExternal = $lesson->external_id ?? ($lesson->externalId ?? $lessonExternal);
        $audio = $audioUrl ?? ($lesson->audioUrl ?? $audio);
        $video = $videoUrl ?? ($lesson->videoUrl ?? $video);
    } else {
        $lessonTitle = (string) $lesson ?: $lessonTitle;
    }
}
$lessonPayload = [
    'title' => $lessonTitle,
    'external' => $lessonExternal,
    'audio' => $audio,
    'video' => $video,
    'savedSaveRoute' => route('saved-lessons.save'),
    'gamificationAwardRoute' => route('gamification.award'),
    'csrf' => csrf_token(),
];
@endphp

@extends('layouts.app')

@section('title', (is_array($lesson) && isset($lesson['title'])) ? $lesson['title'] : 'Lesson')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">
        {{ (is_array($lesson) && isset($lesson['title'])) ? $lesson['title'] : 'Sample Lesson' }}
    </h1>

    <div class="grid grid-cols-1 gap-4">
        <div class="p-4 bg-white rounded shadow">
            <audio id="lesson-audio" controls src="{{ $audioUrl ?? '' }}"></audio>
            <div class="mt-3 flex items-center gap-3">
                <button id="btn-play-audio" class="px-4 py-2 bg-black text-white rounded">Play Audio</button>
                <button id="btn-watch-video" class="px-4 py-2 bg-indigo-600 text-white rounded" {{ empty($videoUrl) ? 'disabled' : '' }}>Watch Video</button>

                <button id="btn-save" class="px-4 py-2 bg-emerald-500 text-white rounded">Save</button>
                <span id="save-status" class="ml-3 text-sm text-gray-600"></span>
            </div>
        </div>

        <div class="p-4 bg-white rounded shadow">
            <h3 class="font-semibold">Reflection</h3>
            <form method="POST" action="#" id="reflection-form">
                @csrf
                <textarea name="reflection" class="w-full p-2 border rounded mt-2" rows="4" placeholder="Write one sentence about how the session went..."></textarea>
                <div class="mt-2 flex gap-2">
                    <button class="px-3 py-2 bg-emerald-600 text-white rounded" id="submit-reflection">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- video modal -->
<div id="video-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60">
    <div class="relative w-full max-w-4xl mx-4">
        <div class="absolute top-2 right-2 z-20 flex gap-2">
            <button id="video-modal-fullscreen" class="bg-white rounded p-2">⤢</button>
            <button id="video-modal-close" class="bg-white rounded p-2">✕</button>
        </div>
        <video id="lesson-video" class="w-full rounded" controls playsinline preload="metadata">
            @if($videoUrl)
                <source src="{{ $videoUrl }}" type="video/mp4">
            @endif
        </video>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const audio = document.getElementById('lesson-audio');
    const btn = document.getElementById('btn-play-audio');
    const btnWatch = document.getElementById('btn-watch-video');
    const modal = document.getElementById('video-modal');
    const video = document.getElementById('lesson-video');
    const closeBtn = document.getElementById('video-modal-close');
    const fsBtn = document.getElementById('video-modal-fullscreen');
    const saveBtn = document.getElementById('btn-save');
    const saveStatus = document.getElementById('save-status');

    if (btn && audio) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            if (audio.paused) { audio.play().catch(()=>{}); btn.textContent = 'Pause Audio'; } else { audio.pause(); btn.textContent = 'Play Audio'; }
        });
        audio.addEventListener('play', function () { btn.textContent = 'Pause Audio'; });
        audio.addEventListener('pause', function () { btn.textContent = 'Play Audio'; });
    }

    if (btnWatch && modal && video) {
        btnWatch.addEventListener('click', function () {
            modal.classList.remove('hidden'); modal.classList.add('flex'); video.currentTime = 0; video.play().catch(()=>{});
        });
        closeBtn.addEventListener('click', function () { video.pause(); modal.classList.remove('flex'); modal.classList.add('hidden'); });
        modal.addEventListener('click', function (e) { if (e.target === modal) { video.pause(); modal.classList.remove('flex'); modal.classList.add('hidden'); }});
        if (fsBtn) fsBtn.addEventListener('click', function () {
            const el = video;
            if (document.fullscreenElement) document.exitFullscreen().catch(()=>{}); else (el.requestFullscreen||el.webkitRequestFullscreen||el.mozRequestFullScreen||el.msRequestFullscreen).call(el).catch(()=>{});
        });
        video.addEventListener('dblclick', function () { if (document.fullscreenElement) document.exitFullscreen().catch(()=>{}); else (video.requestFullscreen||video.webkitRequestFullscreen||video.mozRequestFullScreen||video.msRequestFullscreen).call(video).catch(()=>{}); });
    }

    // Save button: posts to saved-lessons.save for audio and/or video
    saveBtn.addEventListener('click', function () {
        saveStatus.textContent = 'Saving...';
        const csrf = '{{ csrf_token() }}';
        const external = '{{ $lesson['external_id'] ?? '' }}';
        const title = '{{ addslashes($lesson['title'] ?? '') }}';
        const audioUrl = '{{ $audioUrl ?? '' }}';
        const videoUrl = '{{ $videoUrl ?? '' }}';
        const tasks = [];
        if (audioUrl) { const fd = new FormData(); fd.append('type','audio'); fd.append('external_id',external); fd.append('title',title); fd.append('url',audioUrl); tasks.push(fetch('{{ route('saved-lessons.save') }}',{method:'POST',credentials:'same-origin',headers:{'X-CSRF-TOKEN':csrf},body:fd})); }
        if (videoUrl) { const fd2 = new FormData(); fd2.append('type','video'); fd2.append('external_id',external); fd2.append('title',title); fd2.append('url',videoUrl); tasks.push(fetch('{{ route('saved-lessons.save') }}',{method:'POST',credentials:'same-origin',headers:{'X-CSRF-TOKEN':csrf},body:fd2})); }
        if (!tasks.length) { saveStatus.textContent='Nothing to save'; return; }
        Promise.all(tasks).then(()=>{ saveStatus.textContent='Saved'; setTimeout(()=>location.reload(),800); }).catch(()=>{ saveStatus.textContent='Save failed'; });
    });

    // award points when audio ends
    audio.addEventListener('ended', function () {
        fetch('{{ route('gamification.award') }}', { method:'POST', credentials:'same-origin', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Content-Type':'application/json'}, body: JSON.stringify({action:'complete_lesson'}) }).catch(()=>{});
    });

    // reflection submit awards points
    document.getElementById('reflection-form').addEventListener('submit', function (e) {
        e.preventDefault();
        fetch('{{ route('gamification.award') }}', { method:'POST', credentials:'same-origin', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Content-Type':'application/json'}, body: JSON.stringify({action:'submit_reflection'}) }).catch(()=>{});
        alert('Reflection submitted — thank you');
    });
});
</script>
@endsection
