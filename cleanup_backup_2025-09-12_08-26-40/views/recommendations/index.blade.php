@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <h1 class="text-2xl font-semibold mb-4">Recommended Activities</h1>
    <div id="recommendations">Loading...</div>
</div>

<script>
async function load() {
    const res = await fetch('/api/recommendations', { headers: { 'Accept': 'application/json' } });
    const data = await res.json();
    const container = document.getElementById('recommendations');
    if (!data.length) { container.innerHTML = '<p>No recommendations yet.</p>'; return; }
    container.innerHTML = data.map(a => `<div class='p-3 bg-white rounded mb-2'>${a.title || a.name || 'Activity'}</div>`).join('');
}
load();
</script>
@endsection
