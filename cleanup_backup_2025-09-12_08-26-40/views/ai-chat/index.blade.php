{{-- resources/views/ai-chat/index.blade.php --}}
@extends('layouts.app')

@section('title', 'AI Mental Health Assistant')

@section('content')
<div class="container mx-auto p-6 max-w-6xl">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">AI Mental Health Assistant</h1>
                <p class="mt-2 text-gray-600">Get personalized parenting and mental health guidance from our AI assistant</p>
            </div>
            <div class="flex items-center space-x-4">
                <button onclick="newSession()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    New Chat
                </button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Sidebar - Chat Sessions -->
        <div class="lg:col-span-1">
            <div class="bg-white shadow rounded-lg p-4">
                <h3 class="text-lg font-semibold mb-4">Chat Sessions</h3>
                <div id="sessions-list" class="space-y-2 max-h-96 overflow-y-auto">
                    @if($sessions->count() > 0)
                        @foreach($sessions as $sessionId)
                            <div class="session-item p-3 rounded-lg cursor-pointer transition-colors {{ $currentSession == $sessionId ? 'bg-blue-100 border-blue-300' : 'hover:bg-gray-50' }}"
                                 onclick="switchSession('{{ $sessionId }}')">
                                <div class="text-sm font-medium text-gray-900 truncate">
                                    Session {{ substr($sessionId, -8) }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ \Carbon\Carbon::parse(\App\Models\AiChatConversation::where('session_id', $sessionId)->latest()->first()?->created_at)->format('M j, g:i A') ?? 'New' }}
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <p class="mt-2 text-sm">No chat sessions yet</p>
                            <p class="text-xs">Start a conversation to begin</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Main Chat Area -->
        <div class="lg:col-span-3">
            <div class="bg-white shadow rounded-lg h-[600px] flex flex-col">
                <!-- Chat Header -->
                <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-gray-900">Mental Health Assistant</h3>
                                <p class="text-xs text-gray-500">Powered by approved parenting resources</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Online
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Chat Messages -->
                <div id="chat-messages" class="flex-1 overflow-y-auto p-4 space-y-4">
                    @if($conversation->count() > 0)
                        @foreach($conversation as $message)
                            <div class="flex {{ $message->isUserMessage() ? 'justify-end' : 'justify-start' }}">
                                <div class="max-w-xs lg:max-w-md xl:max-w-lg">
                                    <div class="flex items-start space-x-2">
                                        @if(!$message->isUserMessage())
                                            <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full flex items-center justify-center flex-shrink-0">
                                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                        @endif

                                        <div class="bg-{{ $message->isUserMessage() ? 'blue' : 'gray' }}-100 rounded-lg p-3">
                                            <div class="text-sm {{ $message->isUserMessage() ? 'text-blue-900' : 'text-gray-900' }} whitespace-pre-wrap">
                                                {!! nl2br(e($message->message)) !!}
                                            </div>

                                            @if($message->metadata && isset($message->metadata['sources']) && count($message->metadata['sources']) > 0)
                                                <div class="mt-3 pt-3 border-t border-gray-200">
                                                    <div class="text-xs text-gray-600 mb-2">📚 Related Resources:</div>
                                                    <div class="space-y-1">
                                                        @foreach(array_slice($message->metadata['sources'], 0, 2) as $source)
                                                            <a href="{{ $source['url'] }}" target="_blank"
                                                               class="text-xs text-blue-600 hover:text-blue-800 underline block">
                                                                {{ $source['type'] === 'module' ? '📖' : '📄' }} {{ $source['title'] }}
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif

                                            <div class="text-xs text-gray-500 mt-2 flex items-center justify-between">
                                                <span>{{ $message->created_at->format('g:i A') }}</span>
                                                @if($message->isAssistantMessage())
                                                    <div class="flex items-center space-x-2">
                                                        @if($message->hasAudio())
                                                            <button onclick="playAudio('{{ $message->audio_url }}')"
                                                                    class="text-blue-600 hover:text-blue-800">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                </svg>
                                                            </button>
                                                        @else
                                                            <button onclick="generateAudio({{ $message->id }})"
                                                                    class="text-gray-400 hover:text-blue-600">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path>
                                                                </svg>
                                                            </button>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        @if($message->isUserMessage())
                                            <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <!-- Welcome Message -->
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Welcome to your Mental Health Assistant!</h3>
                            <p class="text-gray-600 mb-6">I'm here to help you with parenting questions and mental health guidance based on our approved resources.</p>
                            <div class="bg-blue-50 rounded-lg p-4 text-left max-w-md mx-auto">
                                <h4 class="font-medium text-blue-900 mb-2">💡 Try asking about:</h4>
                                <ul class="text-sm text-blue-800 space-y-1">
                                    <li>• Baby development milestones</li>
                                    <li>• Managing toddler tantrums</li>
                                    <li>• Positive discipline techniques</li>
                                    <li>• Mental health and parenting</li>
                                    <li>• Safe sleep practices</li>
                                </ul>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Chat Input -->
                <div class="p-4 border-t border-gray-200 bg-gray-50">
                    <form id="chat-form" class="flex items-end space-x-4">
                        <div class="flex-1">
                            <textarea id="message-input"
                                      name="message"
                                      rows="1"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                                      placeholder="Ask me anything about parenting and mental health..."
                                      maxlength="1000"></textarea>
                            <div class="text-xs text-gray-500 mt-1">
                                <span id="char-count">0</span>/1000 characters
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <label class="flex items-center">
                                <input type="checkbox" id="generate-audio" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-600">Audio</span>
                            </label>
                            <button type="submit"
                                    id="send-button"
                                    class="inline-flex items-center px-4 py-3 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let currentSessionId = '{{ $currentSession }}';
let isTyping = false;

// Auto-resize textarea
document.getElementById('message-input').addEventListener('input', function() {
    this.style.height = 'auto';
    this.style.height = Math.min(this.scrollHeight, 120) + 'px';

    // Update character count
    document.getElementById('char-count').textContent = this.value.length;
});

// Handle form submission
document.getElementById('chat-form').addEventListener('submit', function(e) {
    e.preventDefault();

    const messageInput = document.getElementById('message-input');
    const sendButton = document.getElementById('send-button');
    const generateAudio = document.getElementById('generate-audio').checked;

    if (!messageInput.value.trim()) return;

    // Disable input while sending
    messageInput.disabled = true;
    sendButton.disabled = true;

    // Add user message to chat
    addMessageToChat(messageInput.value, 'user');

    // Send message to server
    fetch('/ai-chat/message', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            message: messageInput.value,
            session_id: currentSessionId,
            generate_audio: generateAudio
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Add assistant message to chat
            addMessageToChat(data.message.message, 'assistant', data.message, data.sources);

            // Update sessions list if this is a new session
            updateSessionsList();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        addMessageToChat('Sorry, I encountered an error. Please try again.', 'assistant');
    })
    .finally(() => {
        // Re-enable input
        messageInput.disabled = false;
        sendButton.disabled = false;
        messageInput.value = '';
        messageInput.style.height = 'auto';
        document.getElementById('char-count').textContent = '0';
        document.getElementById('generate-audio').checked = false;

        // Scroll to bottom
        scrollToBottom();
    });
});

function addMessageToChat(message, type, messageData = null, sources = []) {
    const chatMessages = document.getElementById('chat-messages');
    const messageDiv = document.createElement('div');
    messageDiv.className = `flex ${type === 'user' ? 'justify-end' : 'justify-start'}`;

    const messageContent = `
        <div class="max-w-xs lg:max-w-md xl:max-w-lg">
            <div class="flex items-start space-x-2">
                ${type === 'assistant' ? `
                    <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                ` : ''}
                <div class="bg-${type === 'user' ? 'blue' : 'gray'}-100 rounded-lg p-3">
                    <div class="text-sm ${type === 'user' ? 'text-blue-900' : 'text-gray-900'} whitespace-pre-wrap">
                        ${message.replace(/\n/g, '<br>')}
                    </div>
                    ${sources && sources.length > 0 ? `
                        <div class="mt-3 pt-3 border-t border-gray-200">
                            <div class="text-xs text-gray-600 mb-2">📚 Related Resources:</div>
                            <div class="space-y-1">
                                ${sources.slice(0, 2).map(source => `
                                    <a href="${source.url}" target="_blank" class="text-xs text-blue-600 hover:text-blue-800 underline block">
                                        ${source.type === 'module' ? '📖' : '📄'} ${source.title}
                                    </a>
                                `).join('')}
                            </div>
                        </div>
                    ` : ''}
                    <div class="text-xs text-gray-500 mt-2 flex items-center justify-between">
                        <span>${new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</span>
                        ${type === 'assistant' ? `
                            <div class="flex items-center space-x-2">
                                ${messageData && messageData.is_audio_generated ? `
                                    <button onclick="playAudio('${messageData.audio_url}')" class="text-blue-600 hover:text-blue-800">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </button>
                                ` : `
                                    <button onclick="generateAudio(${messageData ? messageData.id : 0})" class="text-gray-400 hover:text-blue-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path>
                                        </svg>
                                    </button>
                                `}
                            </div>
                        ` : ''}
                    </div>
                </div>
                ${type === 'user' ? `
                    <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                ` : ''}
            </div>
        </div>
    `;

    messageDiv.innerHTML = messageContent;
    chatMessages.appendChild(messageDiv);
    scrollToBottom();
}

function scrollToBottom() {
    const chatMessages = document.getElementById('chat-messages');
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

function switchSession(sessionId) {
    currentSessionId = sessionId;

    // Update active session in sidebar
    document.querySelectorAll('.session-item').forEach(item => {
        item.classList.remove('bg-blue-100', 'border-blue-300');
        item.classList.add('hover:bg-gray-50');
    });

    event.target.closest('.session-item').classList.add('bg-blue-100', 'border-blue-300');
    event.target.closest('.session-item').classList.remove('hover:bg-gray-50');

    // Load conversation for this session
    fetch(`/ai-chat/conversation/${sessionId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const chatMessages = document.getElementById('chat-messages');
                chatMessages.innerHTML = '';

                if (data.conversation.length > 0) {
                    data.conversation.forEach(message => {
                        addMessageToChat(message.message, message.message_type, message, message.metadata ? message.metadata.sources : []);
                    });
                } else {
                    // Show welcome message for empty session
                    chatMessages.innerHTML = `
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Welcome back!</h3>
                            <p class="text-gray-600">Continue your conversation or start a new topic.</p>
                        </div>
                    `;
                }
            }
        });
}

function newSession() {
    fetch('/ai-chat/new-session', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            currentSessionId = data.session_id;
            updateSessionsList();

            // Clear chat and show welcome message
            const chatMessages = document.getElementById('chat-messages');
            chatMessages.innerHTML = `
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">New conversation started!</h3>
                    <p class="text-gray-600">What parenting or mental health topic can I help you with today?</p>
                </div>
            `;
        }
    });
}

function updateSessionsList() {
    fetch('/ai-chat/conversation/' + currentSessionId)
        .then(response => response.json())
        .then(data => {
            // This would update the sessions list - simplified for demo
            location.reload(); // Simple refresh for now
        });
}

function generateAudio(messageId) {
    fetch(`/ai-chat/generate-audio/${messageId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update the message to show audio is available
            location.reload(); // Simple refresh to show audio button
        }
    });
}

function playAudio(audioUrl) {
    // In production, this would play the audio file
    // For demo purposes, we'll show an alert
    alert('Audio playback would start here. In production, this would play the generated audio file.');
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    scrollToBottom();
});
</script>
@endsection
