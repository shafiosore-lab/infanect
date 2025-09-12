@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">AI Chat (Documents)</h1>

    @if(session('status'))
        <div class="p-3 bg-green-100 text-green-800 rounded mb-4">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('ai.upload') }}" enctype="multipart/form-data" class="mb-6">
        @csrf
        <label class="block">Upload PDF</label>
        <input type="file" name="document" accept="application/pdf" required />
        <button class="btn btn-primary mt-2">Upload</button>
    </form>

    <div>
        <label class="block">Message</label>
        <textarea id="ai_message" class="w-full border p-2" rows="4"></textarea>
        <button id="sendBtn" class="btn btn-primary mt-2">Send</button>
    </div>

    <pre id="ai_reply" class="mt-4 bg-gray-100 p-4"></pre>
</div>

<script>
document.getElementById('sendBtn').addEventListener('click', async function(){
    const msg = document.getElementById('ai_message').value;
    if(!msg) return alert('Enter a message');
    const resp = await fetch("{{ route('ai.message') }}", {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ message: msg })
    });
    const data = await resp.json();
    document.getElementById('ai_reply').textContent = data.reply;
});
</script>
@endsection
