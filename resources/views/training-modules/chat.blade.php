{{-- resources/views/training-modules/chat.blade.php --}}
@extends('layouts.app')

@section('title', 'AI Chat - ' . $module->title)

@section('content')
<div class="container mx-auto p-6">
    <!-- Header -->
    <div class="bg-white shadow rounded-lg p-6 mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">AI Chat Assistant</h1>
                <p class="text-gray-600 mt-1">Ask questions about: <strong>{{ $module->title }}</strong></p>
            </div>
            <a href="{{ route('training-modules.show', $module) }}"
               class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Module
            </a>
        </div>
    </div>

    <!-- Chat Interface -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <!-- Chat Messages -->
        <div id="chat-messages" class="h-96 overflow-y-auto p-6 space-y-4">
            <!-- Welcome Message -->
            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex-1">
                    <div class="bg-purple-50 rounded-lg p-4">
                        <p class="text-sm text-gray-900">
                            Hello! I'm your AI assistant for this training module. I can help you understand the content,
                            answer questions about "{{ $module->title }}", and provide insights based on the document.
                            What would you like to know?
                        </p>
                    </div>
                </div>
            </div>

            <!-- Previous Messages -->
            @foreach($conversations as $conversation)
                <!-- User Message -->
                <div class="flex items-start space-x-3 justify-end">
                    <div class="flex-1 max-w-2xl">
                        <div class="bg-blue-50 rounded-lg p-4">
                            <p class="text-sm text-gray-900">{{ $conversation->user_message }}</p>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">{{ $conversation->created_at->format('M j, H:i') }}</p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- AI Response -->
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1 max-w-2xl">
                        <div class="bg-purple-50 rounded-lg p-4">
                            <p class="text-sm text-gray-900 whitespace-pre-line">{{ $conversation->ai_response }}</p>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">{{ $conversation->created_at->format('M j, H:i') }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Chat Input -->
        <div class="border-t border-gray-200 p-4">
            <form id="chat-form" class="flex space-x-4">
                <div class="flex-1">
                    <textarea
                        id="message-input"
                        name="message"
                        rows="2"
                        class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500 resize-none"
                        placeholder="Ask a question about this training module..."
                        maxlength="1000"
                    ></textarea>
                    <p class="text-xs text-gray-500 mt-1">
                        <span id="char-count">0</span>/1000 characters
                    </p>
                </div>
                <div class="flex-shrink-0">
                    <button type="submit"
                            id="send-button"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                        Send
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Quick Questions -->
    <div class="bg-white shadow rounded-lg p-6 mt-8">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Questions</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <button onclick="askQuestion('What are the main topics covered in this module?')"
                    class="text-left p-3 border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                <p class="text-sm font-medium text-gray-900">Main topics covered</p>
                <p class="text-xs text-gray-500">Get an overview of key concepts</p>
            </button>

            <button onclick="askQuestion('Can you summarize the key points?')"
                    class="text-left p-3 border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                <p class="text-sm font-medium text-gray-900">Key points summary</p>
                <p class="text-xs text-gray-500">Quick summary of important information</p>
            </button>

            <button onclick="askQuestion('What are the most important takeaways?')"
                    class="text-left p-3 border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                <p class="text-sm font-medium text-gray-900">Important takeaways</p>
                <p class="text-xs text-gray-500">What you should remember</p>
            </button>

            <button onclick="askQuestion('Are there any practical applications mentioned?')"
                    class="text-left p-3 border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                <p class="text-sm font-medium text-gray-900">Practical applications</p>
                <p class="text-xs text-gray-500">How to apply this knowledge</p>
            </button>

            <button onclick="askQuestion('What are the main challenges or issues discussed?')"
                    class="text-left p-3 border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                <p class="text-sm font-medium text-gray-900">Challenges discussed</p>
                <p class="text-xs text-gray-500">Key problems and solutions</p>
            </button>

            <button onclick="askQuestion('Can you explain the most complex concepts?')"
                    class="text-left p-3 border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                <p class="text-sm font-medium text-gray-900">Explain complex concepts</p>
                <p class="text-xs text-gray-500">Break down difficult ideas</p>
            </button>
        </div>
    </div>
</div>

<script>
let isLoading = false;

function askQuestion(question) {
    document.getElementById('message-input').value = question;
    updateCharCount();
    document.getElementById('chat-form').dispatchEvent(new Event('submit'));
}

function updateCharCount() {
    const input = document.getElementById('message-input');
    const count = document.getElementById('char-count');
    count.textContent = input.value.length;
}

document.getElementById('message-input').addEventListener('input', updateCharCount);

document.getElementById('chat-form').addEventListener('submit', function(e) {
    e.preventDefault();

    if (isLoading) return;

    const message = document.getElementById('message-input').value.trim();
    if (!message) return;

    const sendButton = document.getElementById('send-button');
    const originalText = sendButton.innerHTML;

    // Disable input and button
    document.getElementById('message-input').disabled = true;
    sendButton.disabled = true;
    sendButton.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
    isLoading = true;

    // Add user message to chat
    addMessageToChat(message, 'user');

    // Clear input
    document.getElementById('message-input').value = '';
    updateCharCount();

    // Send message to server
    fetch(`{{ route('training-modules.chat.message', $module) }}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            message: message
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            addMessageToChat(data.response, 'ai');
        } else {
            addMessageToChat('Sorry, I encountered an error. Please try again.', 'ai');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        addMessageToChat('Sorry, I encountered an error. Please try again.', 'ai');
    })
    .finally(() => {
        // Re-enable input and button
        document.getElementById('message-input').disabled = false;
        sendButton.disabled = false;
        sendButton.innerHTML = originalText;
        isLoading = false;
    });
});

function addMessageToChat(message, type) {
    const chatMessages = document.getElementById('chat-messages');
    const messageDiv = document.createElement('div');
    messageDiv.className = `flex items-start space-x-3 ${type === 'user' ? 'justify-end' : ''}`;

    if (type === 'user') {
        messageDiv.innerHTML = `
            <div class="flex-1 max-w-2xl">
                <div class="bg-blue-50 rounded-lg p-4">
                    <p class="text-sm text-gray-900">${message}</p>
                </div>
                <p class="text-xs text-gray-500 mt-1">Just now</p>
            </div>
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
        `;
    } else {
        messageDiv.innerHTML = `
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                    </svg>
                </div>
            </div>
            <div class="flex-1 max-w-2xl">
                <div class="bg-purple-50 rounded-lg p-4">
                    <p class="text-sm text-gray-900 whitespace-pre-line">${message}</p>
                </div>
                <p class="text-xs text-gray-500 mt-1">Just now</p>
            </div>
        `;
    }

    chatMessages.appendChild(messageDiv);
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

// Auto-resize textarea
document.getElementById('message-input').addEventListener('input', function() {
    this.style.height = 'auto';
    this.style.height = this.scrollHeight + 'px';
});
</script>
@endsection
