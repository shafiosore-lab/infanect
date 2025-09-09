{{-- resources/views/provider/activities/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Create Activity')

@section('content')
<div class="container mx-auto p-6 max-w-4xl">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Create New Activity</h1>
        <p class="mt-2 text-gray-600">Fill in the details to create a new activity for your provider.</p>
    </div>

    <!-- Form -->
    <div class="bg-white shadow rounded-lg p-6">
        <form action="{{ route('provider.activities.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Title -->
                <div class="md:col-span-2">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Activity Title</label>
                    <input type="text" name="title" id="title" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                </div>

                <!-- Category -->
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                    <select name="category" id="category" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="">Select Category</option>
                        <option value="bonding_activity">Bonding Activity</option>
                        <option value="counseling">Counseling</option>
                        <option value="training">Training</option>
                        <option value="event">Event</option>
                        <option value="workshop">Workshop</option>
                        <option value="seminar">Seminar</option>
                    </select>
                </div>

                <!-- Price -->
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Price ($)</label>
                    <input type="number" name="price" id="price" step="0.01" min="0" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                </div>

                <!-- Date & Time -->
                <div>
                    <label for="datetime" class="block text-sm font-medium text-gray-700 mb-2">Date & Time</label>
                    <input type="datetime-local" name="datetime" id="datetime" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                </div>

                <!-- Slots -->
                <div>
                    <label for="slots" class="block text-sm font-medium text-gray-700 mb-2">Available Slots</label>
                    <input type="number" name="slots" id="slots" min="1" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                </div>

                <!-- Venue -->
                <div>
                    <label for="venue" class="block text-sm font-medium text-gray-700 mb-2">Venue</label>
                    <input type="text" name="venue" id="venue" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                </div>

                <!-- Country -->
                <div>
                    <label for="country" class="block text-sm font-medium text-gray-700 mb-2">Country</label>
                    <input type="text" name="country" id="country" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                </div>

                <!-- Duration -->
                <div>
                    <label for="duration" class="block text-sm font-medium text-gray-700 mb-2">Duration</label>
                    <input type="text" name="duration" id="duration" placeholder="e.g., 2 hours" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Difficulty Level -->
                <div>
                    <label for="difficulty_level" class="block text-sm font-medium text-gray-700 mb-2">Difficulty Level</label>
                    <select name="difficulty_level" id="difficulty_level" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Level</option>
                        <option value="beginner">Beginner</option>
                        <option value="intermediate">Intermediate</option>
                        <option value="advanced">Advanced</option>
                    </select>
                </div>

                <!-- Target Audience -->
                <div>
                    <label for="target_audience" class="block text-sm font-medium text-gray-700 mb-2">Target Audience</label>
                    <input type="text" name="target_audience" id="target_audience" placeholder="e.g., Parents, Children, Families" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" id="description" rows="4" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required></textarea>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="mt-8 flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0 sm:space-x-4">
                <!-- Left side - Additional actions -->
                <div class="flex space-x-3">
                    <button type="button" onclick="saveDraft()" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                        Save Draft
                    </button>
                    <a href="{{ route('provider.activities.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Cancel
                    </a>
                </div>

                <!-- Right side - Primary actions -->
                <div class="flex space-x-3">
                    <button type="submit" name="action" value="preview" class="inline-flex items-center px-4 py-2 border border-indigo-300 rounded-md shadow-sm text-sm font-medium text-indigo-700 bg-white hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Preview
                    </button>
                    <button type="submit" name="action" value="submit" class="inline-flex items-center px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                        Submit for Approval
                    </button>
                </div>
            </div>

            <!-- Form Status -->
            <div class="mt-4 text-sm text-gray-600">
                <p><strong>Note:</strong> Your activity will be submitted for admin approval before it becomes visible to users.</p>
            </div>
        </form>
    </div>
</div>
@endsection

<script>
function saveDraft() {
    // Get form data
    const formData = new FormData(document.querySelector('form'));
    formData.append('action', 'draft');

    // Show loading state
    const saveBtn = event.target;
    const originalText = saveBtn.innerHTML;
    saveBtn.innerHTML = '<svg class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>Saving...';
    saveBtn.disabled = true;

    // Send AJAX request to save draft
    fetch(window.location.href, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            showNotification('Draft saved successfully!', 'success');
            saveBtn.innerHTML = originalText;
            saveBtn.disabled = false;
        } else {
            throw new Error(data.message || 'Failed to save draft');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Failed to save draft. Please try again.', 'error');
        saveBtn.innerHTML = originalText;
        saveBtn.disabled = false;
    });
}

function showNotification(message, type) {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-4 py-2 rounded-md text-white text-sm font-medium z-50 ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    }`;
    notification.textContent = message;

    // Add to page
    document.body.appendChild(notification);

    // Remove after 3 seconds
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Auto-save draft every 30 seconds
let autoSaveInterval = setInterval(() => {
    const form = document.querySelector('form');
    if (form && form.querySelector('input[name="title"]').value) {
        saveDraft();
    }
}, 30000);

// Clear auto-save when form is submitted
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function() {
            clearInterval(autoSaveInterval);
        });
    }
});
</script>
