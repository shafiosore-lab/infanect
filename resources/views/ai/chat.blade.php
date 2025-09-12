@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm" style="height: 70vh;">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">
                                <i class="fas fa-robot me-2"></i>AI Chat Assistant
                            </h5>
                            <small>Ask me anything about parenting and family wellness</small>
                        </div>
                        <a href="{{ route('ai-chat.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>Back
                        </a>
                    </div>
                </div>

                <div class="card-body d-flex flex-column p-0">
                    <!-- Chat Messages -->
                    <div id="chatMessages" class="flex-grow-1 p-3 overflow-auto" style="max-height: 50vh;">
                        <div class="message ai-message mb-3">
                            <div class="d-flex">
                                <div class="avatar bg-primary text-white rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                    <i class="fas fa-robot"></i>
                                </div>
                                <div class="message-content bg-light rounded p-2">
                                    <p class="mb-0">Hello! I'm your AI assistant. How can I help you today with parenting, activities, or family wellness?</p>
                                    <small class="text-muted">{{ now()->format('g:i A') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Chat Input -->
                    <div class="border-top p-3">
                        <form id="chatForm" class="d-flex gap-2">
                            @csrf
                            <input type="text" id="messageInput" name="message"
                                   class="form-control"
                                   placeholder="Type your message here..."
                                   required>
                            <button type="submit" class="btn btn-primary" id="sendBtn">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatForm = document.getElementById('chatForm');
    const messageInput = document.getElementById('messageInput');
    const chatMessages = document.getElementById('chatMessages');
    const sendBtn = document.getElementById('sendBtn');

    chatForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const message = messageInput.value.trim();
        if (!message) return;

        // Add user message to chat
        addMessage(message, 'user');

        // Clear input and disable button
        messageInput.value = '';
        sendBtn.disabled = true;
        sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

        // Send to AI
        fetch('{{ route("ai.send-message") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ message: message })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                addMessage(data.response, 'ai');
            } else {
                addMessage('Sorry, I encountered an error. Please try again.', 'ai');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            addMessage('Sorry, I encountered an error. Please try again.', 'ai');
        })
        .finally(() => {
            sendBtn.disabled = false;
            sendBtn.innerHTML = '<i class="fas fa-paper-plane"></i>';
            messageInput.focus();
        });
    });

    function addMessage(text, sender) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${sender}-message mb-3`;

        const time = new Date().toLocaleTimeString('en-US', {
            hour: 'numeric',
            minute: '2-digit',
            hour12: true
        });

        if (sender === 'user') {
            messageDiv.innerHTML = `
                <div class="d-flex justify-content-end">
                    <div class="message-content bg-primary text-white rounded p-2" style="max-width: 70%;">
                        <p class="mb-0">${text}</p>
                        <small class="text-light">${time}</small>
                    </div>
                    <div class="avatar bg-secondary text-white rounded-circle ms-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                        <i class="fas fa-user"></i>
                    </div>
                </div>
            `;
        } else {
            messageDiv.innerHTML = `
                <div class="d-flex">
                    <div class="avatar bg-primary text-white rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                        <i class="fas fa-robot"></i>
                    </div>
                    <div class="message-content bg-light rounded p-2" style="max-width: 70%;">
                        <p class="mb-0">${text}</p>
                        <small class="text-muted">${time}</small>
                    </div>
                </div>
            `;
        }

        chatMessages.appendChild(messageDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    // Focus on input
    messageInput.focus();
});
</script>
@endsection
