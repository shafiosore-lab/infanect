@extends('layouts.app')
@section('content')
<div class="max-w-md mx-auto p-4">
    <h2 class="text-xl font-bold mb-3">How are you feeling?</h2>
    <form id="moodForm">
        <div class="mb-3">
            <label class="block mb-1">Select mood</label>
            <select name="mood" class="w-full border p-2">
                <option value="happy">Happy</option>
                <option value="tired">Tired</option>
                <option value="stressed">Stressed</option>
                <option value="adventurous">Adventurous</option>
                <option value="bored">Bored</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="block mb-1">Choose days</label>
            <label class="inline-block mr-2"><input type="checkbox" name="availability[]" value="sat"> Sat</label>
            <label class="inline-block mr-2"><input type="checkbox" name="availability[]" value="sun"> Sun</label>
        </div>
        <button class="px-4 py-2 bg-indigo-600 text-white">Plan my weekend</button>
    </form>

    <div id="result" class="mt-4"></div>
</div>
<script>
document.getElementById('moodForm').addEventListener('submit', async (e)=>{
  e.preventDefault();
  const form = new FormData(e.target);
  const payload = { mood: form.get('mood'), availability: form.getAll('availability'), timezone: Intl.DateTimeFormat().resolvedOptions().timeZone };
  const res = await fetch('/api/mood/submit', { method:'POST', headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}' }, body: JSON.stringify(payload) });
  const json = await res.json();
  document.getElementById('result').innerText = 'Queued — we will notify you shortly.';
});
</script>
@endsection
