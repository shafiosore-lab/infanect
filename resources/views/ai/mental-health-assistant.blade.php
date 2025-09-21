@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white border-b border-gray-200 px-6 py-4">
        <div class="max-w-7xl mx-auto">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Mental Health Research Assistant</h1>
                    <p class="text-sm text-gray-600 mt-1">Evidence-based information from peer-reviewed research</p>
                </div>
                <div class="flex items-center space-x-4">
                    @if($dashboardData['is_verified_provider'])
                        <button onclick="openUploadModal()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-upload mr-2"></i>
                            Upload Research
                        </button>
                    @endif
                    <button onclick="viewDocuments()" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors">
                        <i class="fas fa-book mr-2"></i>
                        Research Library
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto p-6">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Research Stats Sidebar -->
            <div class="lg:col-span-1">
                <div class="space-y-6">
                    <!-- Research Overview -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Research Database</h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Total Papers</span>
                                <span class="text-lg font-bold text-blue-600">{{ $dashboardData['total_research_papers'] }}</span>
                            </div>
                            @if($dashboardData['user_role'] === 'provider')
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Your Uploads</span>
                                    <span class="text-lg font-bold text-green-600" id="user-uploads">-</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Top Research Topics -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Popular Topics</h3>
                        <div class="space-y-3">
                            @foreach($dashboardData['top_topics']->take(5) as $topic => $count)
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-700 capitalize">{{ str_replace('_', ' ', $topic) }}</span>
                                    <span class="text-sm font-medium text-gray-500">{{ $count }} papers</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Quick Topics -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Questions</h3>
                        <div class="space-y-2">
                            <button onclick="askQuestion('What are evidence-based treatments for anxiety?')"
                                    class="w-full text-left text-sm text-blue-600 hover:text-blue-800 py-1 px-2 rounded hover:bg-blue-50">
                                Evidence-based anxiety treatments
                            </button>
                            <button onclick="askQuestion('How effective is CBT for depression?')"
                                    class="w-full text-left text-sm text-blue-600 hover:text-blue-800 py-1 px-2 rounded hover:bg-blue-50">
                                CBT effectiveness for depression
                            </button>
                            <button onclick="askQuestion('What research supports parent-child bonding interventions?')"
                                    class="w-full text-left text-sm text-blue-600 hover:text-blue-800 py-1 px-2 rounded hover:bg-blue-50">
                                Parent-child bonding research
                            </button>
                            <button onclick="askQuestion('What are best practices for trauma-informed care?')"
                                    class="w-full text-left text-sm text-blue-600 hover:text-blue-800 py-1 px-2 rounded hover:bg-blue-50">
                                Trauma-informed care guidelines
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chat Interface -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 h-[700px] flex flex-col">
                    <!-- Chat Header -->
                    <div class="border-b border-gray-200 p-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-brain text-white"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">Research Assistant</h3>
                                <p class="text-sm text-gray-500">Powered by peer-reviewed mental health research</p>
                            </div>
                        </div>
                    </div>

                    <!-- Chat Messages -->
                    <div id="chat-messages" class="flex-1 overflow-y-auto p-4 space-y-4">
                        <div class="flex items-start">
                            <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                <i class="fas fa-brain text-white text-sm"></i>
                            </div>
                            <div class="bg-gray-100 rounded-lg p-3 max-w-md">
                                <p class="text-gray-800">Hello! I'm your mental health research assistant. I can help you find evidence-based information from our database of peer-reviewed research papers. What would you like to know?</p>
                            </div>
                        </div>
                    </div>

                    <!-- Chat Input -->
                    <div class="border-t border-gray-200 p-4">
                        <form id="chat-form" class="flex space-x-3">
                            @csrf
                            <input type="text"
                                   id="chat-input"
                                   placeholder="Ask about mental health research, treatments, or interventions..."
                                   class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   maxlength="1000">
                            <button type="submit"
                                    id="send-button"
                                    class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </form>
                        <p class="text-xs text-gray-500 mt-2">
                            <i class="fas fa-info-circle mr-1"></i>
                            This information is for educational purposes only and is not a substitute for professional medical advice.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Research Upload Modal -->
@if($dashboardData['is_verified_provider'])
<div id="upload-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20">
        <div class="bg-white rounded-lg max-w-2xl w-full max-h-screen overflow-y-auto">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Upload Research Document</h3>
                <p class="text-sm text-gray-500">Share peer-reviewed research to enhance our knowledge base</p>
            </div>

            <form id="upload-form" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Research Paper (PDF)</label>
                    <input type="file" name="document" accept=".pdf" required class="w-full border border-gray-300 rounded-md px-3 py-2">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                        <input type="text" name="title" required class="w-full border border-gray-300 rounded-md px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Authors *</label>
                        <input type="text" name="authors" required class="w-full border border-gray-300 rounded-md px-3 py-2">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Publication Year *</label>
                        <input type="number" name="publication_year" min="1900" max="{{ date('Y') + 1 }}" required class="w-full border border-gray-300 rounded-md px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Journal</label>
                        <input type="text" name="journal" class="w-full border border-gray-300 rounded-md px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">DOI</label>
                        <input type="text" name="doi" class="w-full border border-gray-300 rounded-md px-3 py-2">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Document Type *</label>
                    <select name="document_type" required class="w-full border border-gray-300 rounded-md px-3 py-2">
                        <option value="systematic_review">Systematic Review</option>
                        <option value="rct">Randomized Controlled Trial</option>
                        <option value="meta_analysis">Meta-Analysis</option>
                        <option value="clinical_guideline">Clinical Guideline</option>
                        <option value="case_study">Case Study</option>
                        <option value="theoretical_paper">Theoretical Paper</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Topics * (Select all that apply)</label>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2 max-h-40 overflow-y-auto border border-gray-200 rounded p-3">
                        @php
                        $topics = [
                            'anxiety', 'depression', 'trauma', 'addiction', 'family_therapy', 'child_psychology',
                            'adolescent_mental_health', 'cognitive_behavioral_therapy', 'mindfulness', 'parent_child_bonding',
                            'crisis_intervention', 'grief_counseling', 'bipolar_disorder', 'eating_disorders', 'adhd',
                            'autism_spectrum', 'substance_abuse', 'ptsd', 'ocd', 'personality_disorders'
                        ];
                        @endphp
                        @foreach($topics as $topic)
                        <label class="flex items-center text-sm">
                            <input type="checkbox" name="topics[]" value="{{ $topic }}" class="mr-2">
                            {{ ucwords(str_replace('_', ' ', $topic)) }}
                        </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Summary</label>
                    <textarea name="summary" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2" placeholder="Brief summary of key findings..."></textarea>
                </div>

                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" onclick="closeUploadModal()" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Upload for Review
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<script>
let conversationId = null;

document.addEventListener('DOMContentLoaded', function() {
    const chatForm = document.getElementById('chat-form');
    const chatInput = document.getElementById('chat-input');
    const sendButton = document.getElementById('send-button');
    const messagesContainer = document.getElementById('chat-messages');

    chatForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        const message = chatInput.value.trim();
        if (!message) return;

        // Add user message to chat
        addMessage(message, 'user');
        chatInput.value = '';
        sendButton.disabled = true;

        // Add typing indicator
        const typingId = addTypingIndicator();

        try {
            const response = await fetch('/ai/message', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    message: message,
                    conversation_id: conversationId
                })
            });

            const data = await response.json();

            // Remove typing indicator
            document.getElementById(typingId).remove();

            if (data.reply) {
                conversationId = data.conversation_id;
                addMessage(data.reply, 'assistant', data.sources);
            } else {
                addMessage('Sorry, I encountered an error processing your request.', 'assistant');
            }
        } catch (error) {
            document.getElementById(typingId).remove();
            addMessage('Sorry, I encountered a connection error.', 'assistant');
        } finally {
            sendButton.disabled = false;
            chatInput.focus();
        }
    });

    // Upload form handling
    @if($dashboardData['is_verified_provider'])
    const uploadForm = document.getElementById('upload-form');
    uploadForm.addEventListener('submit', async function(e) {
        e.preventDefault();

        const formData = new FormData(uploadForm);

        try {
            const response = await fetch('/ai/upload', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                alert('Research document uploaded successfully!');
                closeUploadModal();
                uploadForm.reset();
            } else {
                alert(data.error || 'Upload failed');
            }
        } catch (error) {
            alert('Upload error occurred');
        }
    });
    @endif
});

function addMessage(content, sender, sources = null) {
    const messagesContainer = document.getElementById('chat-messages');
    const messageDiv = document.createElement('div');

    if (sender === 'user') {
        messageDiv.innerHTML = `
            <div class="flex items-start justify-end">
                <div class="bg-blue-600 text-white rounded-lg p-3 max-w-md mr-3">
                    <p>${escapeHtml(content)}</p>
                </div>
                <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-user text-white text-sm"></i>
                </div>
            </div>
        `;
    } else {
        let sourcesHtml = '';
        if (sources && sources.length > 0) {
            sourcesHtml = `
                <div class="mt-3 pt-3 border-t border-gray-200">
                    <p class="text-xs font-medium text-gray-500 mb-2">Sources:</p>
                    ${sources.map(source => `
                        <div class="text-xs text-gray-600 mb-1">
                            <strong>${source.title}</strong> by ${source.authors} (${source.year})
                            ${source.journal ? `<br><em>${source.journal}</em>` : ''}
                            ${source.doi ? `<br>DOI: ${source.doi}` : ''}
                        </div>
                    `).join('')}
                </div>
            `;
        }

        messageDiv.innerHTML = `
            <div class="flex items-start">
                <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                    <i class="fas fa-brain text-white text-sm"></i>
                </div>
                <div class="bg-gray-100 rounded-lg p-3 max-w-2xl">
                    <p class="text-gray-800 whitespace-pre-wrap">${escapeHtml(content)}</p>
                    ${sourcesHtml}
                </div>
            </div>
        `;
    }

    messagesContainer.appendChild(messageDiv);
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
}

function addTypingIndicator() {
    const messagesContainer = document.getElementById('chat-messages');
    const typingDiv = document.createElement('div');
    const typingId = 'typing-' + Date.now();
    typingDiv.id = typingId;

    typingDiv.innerHTML = `
        <div class="flex items-start">
            <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                <i class="fas fa-brain text-white text-sm"></i>
            </div>
            <div class="bg-gray-100 rounded-lg p-3">
                <div class="flex space-x-1">
                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></div>
                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                </div>
            </div>
        </div>
    `;

    messagesContainer.appendChild(typingDiv);
    messagesContainer.scrollTop = messagesContainer.scrollHeight;

    return typingId;
}

function askQuestion(question) {
    document.getElementById('chat-input').value = question;
    document.getElementById('chat-form').dispatchEvent(new Event('submit'));
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

@if($dashboardData['is_verified_provider'])
function openUploadModal() {
    document.getElementById('upload-modal').classList.remove('hidden');
}

function closeUploadModal() {
    document.getElementById('upload-modal').classList.add('hidden');
}
@endif

function viewDocuments() {
    // Could open a modal or redirect to documents page
    window.open('/ai/documents', '_blank');
}
</script>
@endsection
