{{-- resources/views/parenting-modules/index.blade.php --}}
@extends('layouts.app')

@section('title', __('parenting.modules_title'))

@section('content')
<style>
    @keyframes floaty { 0% { transform: translateY(0px); } 50% { transform: translateY(-6px); } 100% { transform: translateY(0px); } }
    .floaty { animation: floaty 4s ease-in-out infinite; }
    .pulse-slow { animation: pulse 2.5s cubic-bezier(.4,0,.6,1) infinite; }
</style>

{{-- Make outer wrapper respect locale direction and fill dashboard height --}}
<div dir="{{ in_array(app()->getLocale(), ['ar','he','fa']) ? 'rtl' : 'ltr' }}" class="w-full min-h-screen flex flex-col">
    <main lang="{{ app()->getLocale() }}" class="flex-1 w-full px-4 md:px-6 py-6">
        <div class="mx-auto max-w-7xl">
            <!-- Hero (top row) -->
            <div class="rounded-2xl p-6 md:p-8 bg-gradient-to-r from-emerald-600 to-teal-500 text-white mb-6 shadow-lg relative overflow-hidden">
                <div class="absolute -right-24 -top-24 opacity-20 transform rotate-45 w-72 h-72 bg-white rounded-full"></div>
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1 pr-4">
                        <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">{{ __('parenting.modules_title') }}</h1>
                        <p class="mt-2 text-base md:text-lg text-white/90 max-w-2xl">{{ __('parenting.modules_subtitle') }}</p>

                        <div class="mt-4 flex flex-wrap items-center gap-3">
                            <a href="{{ route('start-learning.index') }}" class="inline-flex items-center px-4 py-2 bg-white text-emerald-700 font-semibold rounded-lg shadow-sm hover:scale-105 transform transition">{{ __('parenting.start_button') }}</a>
                            <a href="{{ route('parenting-modules.index') }}" class="inline-flex items-center px-3 py-2 bg-white/10 text-white rounded-lg border border-white/20">{{ __('parenting.explore') }}</a>
                        </div>
                    </div>

                    <div class="flex-shrink-0 ml-2">
                        {{-- Locale switcher for international users --}}
                        @includeIf('layouts.partials.locale-switcher')
                        @includeIf('layouts.partials.saved-lessons-dropdown')
                    </div>
                </div>

                {{-- Inline Start Learning form visible on wide screens (keeps hero tidy) --}}
                <div class="mt-6 hidden lg:block">
                    <div class="bg-white/6 p-3 md:p-4 rounded-lg border border-white/10 inline-block">
                        <form method="POST" action="{{ route('start-learning.play') }}" class="flex items-center gap-3">
                            @csrf
                            <label class="text-sm text-white/90 mr-2">{{ __('parenting.mood_prompt_title') }}</label>
                            <select name="mood" class="rounded px-2 py-1 bg-white text-black">
                                <option value="happy">{{ __('parenting.moods.happy') }}</option>
                                <option value="stressed">{{ __('parenting.moods.stressed') }}</option>
                                <option value="curious">{{ __('parenting.moods.curious') }}</option>
                                <option value="tired">{{ __('parenting.moods.tired') }}</option>
                            </select>
                            <input name="interests" placeholder="{{ __('parenting.interests_placeholder') }}" class="px-2 py-1 rounded bg-white/5 text-white border border-white/10" />
                            <button class="px-3 py-1 bg-emerald-500 text-white rounded">{{ __('parenting.start_button') }}</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Calm Space: breathing exercise, coping strategies, crisis support -->
            <div class="mb-6">
                <div class="p-4 rounded-lg bg-white/95 text-black shadow-md flex items-center justify-between" role="region" aria-label="Calm Space">
                    <div>
                        <h2 class="text-lg font-bold">Calm Space</h2>
                        <p class="text-sm text-gray-700">Short guided breathing and quick coping strategies to help you feel grounded before diving into a lesson.</p>
                        <div class="mt-2 flex flex-wrap gap-2">
                            <button id="open-breath" class="px-3 py-2 bg-emerald-600 text-white rounded-md">Take a 60s breathing exercise</button>
                            <button id="toggle-coping" class="px-3 py-2 bg-white border text-black rounded-md">Quick coping strategies</button>
                            <a href="{{ Route::has('resources.mental-health') ? route('resources.mental-health') : '/resources/mental-health' }}" class="px-3 py-2 bg-white/10 text-white rounded-md">More mental health resources</a>
                        </div>
                    </div>

                    <div class="text-right text-sm">
                        <div class="font-semibold">Need immediate help?</div>
                        <div class="text-gray-700">If you're in crisis call your local emergency number or your country's crisis line.</div>
                    </div>
                </div>

                <div id="coping-panel" class="mt-3 p-3 rounded-lg bg-white/90 text-black shadow-sm hidden" aria-hidden="true">
                    <ul class="list-disc pl-5 text-sm">
                        <li>5-4-3-2-1 grounding: name 5 things you see, 4 you can touch, 3 you hear, 2 you smell, 1 you taste.</li>
                        <li>Take a walk or step outside for fresh air for 5 minutes.</li>
                        <li>Use a calm, slow breath: inhale 4s — hold 4s — exhale 6s, repeat.</li>
                        <li>Write one sentence about how you feel — no judgment.</li>
                    </ul>
                </div>
            </div>

            <!-- Recommended badge CSS -->
            <style>
            .recommended { box-shadow: 0 6px 20px rgba(16, 185, 129, 0.18); border-color: #10b981; transform: translateY(-2px); }
            .breath-circle { width:120px;height:120px;border-radius:9999px;background:linear-gradient(135deg,#34d399,#10b981);opacity:0.95;display:flex;align-items:center;justify-content:center;color:white;font-weight:600 }
            </style>

            <!-- Cards grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="p-4 rounded-lg bg-white border border-gray-200 shadow-sm h-full flex flex-col justify-between">
                    <div>
                        <div class="flex items-start gap-3">
                            <div class="w-12 h-12 bg-white rounded-lg flex items-center justify-center text-2xl">🌙</div>
                            <div>
                                <h3 class="text-lg font-bold text-black">Bedtime Routines</h3>
                                <p class="mt-1 text-sm text-black/70">A simple 3-step routine to help toddlers wind down tonight.</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center justify-between">
                        <div class="flex gap-2">
                            <a href="{{ route('start-learning.play.direct', ['externalId' => 'lesson_bedtime_01']) }}" class="inline-flex items-center px-3 py-2 bg-black text-white rounded-md">{{ __('parenting.bedtime.play') }}</a>
                            <a href="#" class="inline-flex items-center px-3 py-2 bg-white text-black rounded-md">{{ __('parenting.bedtime.preview') }}</a>
                        </div>
                        <div class="text-xs text-black/60">{{ __('parenting.bedtime.duration') }}</div>
                    </div>
                </div>

                <div class="p-4 rounded-lg bg-white border border-gray-200 shadow-sm h-full flex flex-col justify-between">
                    <div>
                        <div class="flex items-start gap-3">
                            <div class="w-12 h-12 bg-white rounded-lg flex items-center justify-center text-2xl">😡</div>
                            <div>
                                <h3 class="text-lg font-bold text-black">{{ __('parenting.tantrums.title') }}</h3>
                                <p class="mt-1 text-sm text-black/70">{{ __('parenting.tantrums.desc') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center justify-between">
                        <div class="flex gap-2">
                            <a href="{{ route('start-learning.play.direct', ['externalId' => 'lesson_tantrums_01']) }}" class="inline-flex items-center px-3 py-2 bg-black text-white rounded-md">{{ __('parenting.tantrums.play') ?? __('parenting.bedtime.play') }}</a>
                            <a href="#" class="inline-flex items-center px-3 py-2 bg-white text-black rounded-md">{{ __('parenting.tantrums.preview') }}</a>
                        </div>
                        <div class="text-xs text-black/60">{{ __('parenting.tantrums.duration') }}</div>
                    </div>
                </div>

                <div class="p-4 rounded-lg bg-white border border-gray-200 shadow-sm h-full flex flex-col justify-between">
                    <div>
                        <div class="flex items-start gap-3">
                            <div class="w-12 h-12 bg-white rounded-lg flex items-center justify-center text-2xl">📱</div>
                            <div>
                                <h3 class="text-lg font-bold text-black">{{ __('parenting.screentime.title') }}</h3>
                                <p class="mt-1 text-sm text-black/70">{{ __('parenting.screentime.desc') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center justify-between">
                        <div class="flex gap-2">
                            <a href="{{ route('start-learning.play.direct', ['externalId' => 'lesson_screentime_01']) }}" class="inline-flex items-center px-3 py-2 bg-black text-white rounded-md">{{ __('parenting.screentime.play') ?? __('parenting.bedtime.play') }}</a>
                            <a href="#" class="inline-flex items-center px-3 py-2 bg-white text-black rounded-md">{{ __('parenting.screentime.preview') }}</a>
                        </div>
                        <div class="text-xs text-black/60">{{ __('parenting.screentime.duration') }}</div>
                    </div>
                </div>
            </div>

            <!-- Breathing modal -->
            <div id="breath-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60" aria-hidden="true" role="dialog" aria-label="Breathing exercise">
                <div class="bg-white rounded-xl p-6 max-w-md w-full text-center">
                    <button id="breath-close" class="absolute top-4 right-4 bg-gray-100 p-2 rounded">✕</button>
                    <h3 class="text-xl font-bold mb-2">60s Guided Breathing</h3>
                    <p class="text-sm text-gray-700 mb-4">Follow the circle and the prompts. Pause anytime.</p>
                    <div id="breath-visual" class="mx-auto mb-4">
                        <div id="breath-circle" class="breath-circle" aria-hidden="true">Breathe</div>
                    </div>
                    <div id="breath-cue" class="text-lg font-medium mb-2">Get ready...</div>
                    <div class="flex justify-center gap-3 mt-2">
                        <button id="breath-start" class="px-4 py-2 bg-emerald-600 text-white rounded-md">Start</button>
                        <button id="breath-stop" class="px-4 py-2 bg-gray-200 text-black rounded-md">Stop</button>
                    </div>
                </div>
            </div>

            <!-- Preview modal for card videos -->
            <div id="card-preview-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60">
                <div class="relative w-full max-w-4xl mx-4">
                    <div class="absolute top-2 right-2 z-20 flex gap-2">
                        <button id="card-preview-fullscreen" class="bg-white rounded p-2">⤢</button>
                        <button id="card-preview-close" class="bg-white rounded p-2">✕</button>
                    </div>
                    <video id="card-preview-video" class="w-full rounded" controls playsinline preload="metadata"></video>
                </div>
            </div>

            <script>
            (function(){
                // Defensive DOM helpers
                function byId(id){ return document.getElementById(id); }
                function safeFetch(url, opts){ try { return fetch(url, opts).catch(()=>{}); } catch(e) { return Promise.resolve(); } }

                // Toggle coping strategies
                const toggleCopingBtn = byId('toggle-coping');
                const copingPanel = byId('coping-panel');
                if (toggleCopingBtn && copingPanel) {
                    toggleCopingBtn.addEventListener('click', function(){
                        const hidden = copingPanel.classList.contains('hidden');
                        if (hidden) { copingPanel.classList.remove('hidden'); copingPanel.setAttribute('aria-hidden','false');
+                            safeFetch('{{ Route::has('calmspace.track') ? route('calmspace.track') : '#' }}', { method:'POST', credentials:'same-origin', headers: {'X-CSRF-TOKEN':'{{ csrf_token() }}','Content-Type':'application/json'}, body: JSON.stringify({type:'coping'}) });
                         } else { copingPanel.classList.add('hidden'); copingPanel.setAttribute('aria-hidden','true'); }
                     });
                 }

                // Breathing modal logic (defensive)
                const modal = byId('breath-modal');
                const startBtn = byId('breath-start');
                const stopBtn = byId('breath-stop');
                const closeBtn = byId('breath-close');
                const cue = byId('breath-cue');
                const circle = byId('breath-circle');
                if (circle) circle.style.transition = 'transform 0.9s ease-in-out';
                if (circle) circle.setAttribute('aria-hidden','true');

                // only add one live region
                if (!document.getElementById('calmspace-live')){
                    const live = document.createElement('div'); live.id='calmspace-live'; live.setAttribute('aria-live','polite'); live.className='sr-only'; document.body.appendChild(live);
                }

                let intervalId, secondsLeft=60;

                function openBreath(){
                    if (!modal) return; modal.classList.remove('hidden'); modal.classList.add('flex');
                    secondsLeft = 60; if (cue) cue.textContent = 'Get ready...'; if (circle) circle.style.transform='scale(1)';
                    safeFetch('{{ Route::has('calmspace.track') ? route('calmspace.track') : '#' }}', { method:'POST', credentials:'same-origin', headers: {'X-CSRF-TOKEN':'{{ csrf_token() }}','Content-Type':'application/json'}, body: JSON.stringify({type:'breathing_open'}) });
                }
                function closeBreath(){ if (!modal) return; modal.classList.remove('flex'); modal.classList.add('hidden'); clearInterval(intervalId); if (cue) cue.textContent='Get ready...'; if (circle) circle.style.transform='scale(1)'; }

                const openBtn = byId('open-breath'); if (openBtn) openBtn.addEventListener('click', openBreath);
                if (closeBtn) closeBtn.addEventListener('click', closeBreath);
                if (stopBtn) stopBtn.addEventListener('click', closeBreath);

                if (startBtn) startBtn.addEventListener('click', function(){
                    safeFetch('{{ Route::has('calmspace.track') ? route('calmspace.track') : '#' }}', { method:'POST', credentials:'same-origin', headers: {'X-CSRF-TOKEN':'{{ csrf_token() }}','Content-Type':'application/json'}, body: JSON.stringify({type:'breathing_start'}) });

                    const cycle = [ {phase:'Inhale',dur:4,scale:1.3},{phase:'Hold',dur:4,scale:1.3},{phase:'Exhale',dur:6,scale:0.8} ];
                    let elapsed=0; let cycleIndex=0; let phaseRemaining=cycle[0].dur;
                    if (cue) cue.textContent = cycle[0].phase + ' — ' + phaseRemaining + 's'; if (circle) circle.style.transform = 'scale(' + cycle[0].scale + ')';
                    const liveEl = document.getElementById('calmspace-live'); if (liveEl) liveEl.textContent = cycle[0].phase + ' ' + phaseRemaining + ' seconds';

                    intervalId = setInterval(function(){
                        elapsed++; secondsLeft--; phaseRemaining--;
                        if (phaseRemaining <= 0) { cycleIndex = (cycleIndex+1) % cycle.length; phaseRemaining = cycle[cycleIndex].dur; if (circle) circle.style.transform = 'scale(' + cycle[cycleIndex].scale + ')'; }
                        if (cue) cue.textContent = cycle[cycleIndex].phase + ' — ' + phaseRemaining + 's'; if (liveEl) liveEl.textContent = cycle[cycleIndex].phase + ' ' + phaseRemaining + ' seconds';
                        if (secondsLeft <= 0) { clearInterval(intervalId); if (cue) cue.textContent='Done — well done'; if (liveEl) liveEl.textContent = 'Done — well done'; setTimeout(closeBreath,1200);
                            safeFetch('{{ Route::has('calmspace.track') ? route('calmspace.track') : '#' }}', { method:'POST', credentials:'same-origin', headers: {'X-CSRF-TOKEN':'{{ csrf_token() }}','Content-Type':'application/json'}, body: JSON.stringify({type:'breathing_complete'}) });
                        }
                    }, 1000);
                });

                // Mood-based recommendation highlight from query param ?mood=stressed|happy|curious|tired
                function getQueryParam(name){ const params = new URLSearchParams(location.search); return params.get(name); }
                const map = { 'stressed':'tantrums', 'happy':'bedtime', 'tired':'bedtime', 'curious':'screentime' };
                const mood = (getQueryParam('mood') || '').toLowerCase();
                if (map[mood]){
                    const slug = map[mood];
                    document.querySelectorAll('.p-4.rounded-lg').forEach(card => {
                        const txt = (card.textContent||'').toLowerCase();
                        if (txt.includes(slug) || txt.includes(map[mood])) {
                            card.classList.add('recommended');
                            const firstDiv = card.querySelector('div');
                            if (firstDiv) {
                                const badge = document.createElement('div'); badge.className='text-xs text-emerald-700 font-semibold'; badge.textContent='Recommended'; firstDiv.appendChild(badge);
                            }
                        }
                    });
                }

            })();
            </script>
        </div>
    </main>
</div>

{{-- keep existing prefetch script unchanged --}}
@includeIf('parenting-modules.partials.prefetch')

<!-- Remove any Privacy / Terms links or text on this page (best-effort) -->
<script>
(function(){
    try {
        const texts = ['privacy', 'terms'];
        // hide anchors with privacy/terms in href or text
        document.querySelectorAll('a').forEach(a => {
            const href = (a.getAttribute('href') || '').toLowerCase();
            const t = (a.textContent || '').toLowerCase();
            if (texts.some(x => href.includes(x) || t.includes(x))) {
                a.style.display = 'none';
            }
        });
        // hide any elements whose text content contains Privacy or Terms
        document.querySelectorAll('body *').forEach(el => {
            if (el.children.length === 0) {
                const txt = (el.textContent || '').toLowerCase().trim();
                if (texts.some(x => txt === x || txt.includes(x))) el.style.display = 'none';
            }
        });
    } catch(e) { /* ignore */ }
})();
</script>
@endsection
