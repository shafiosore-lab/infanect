@extends('layouts.app')

@section('title', 'Book Activity - ' . $activity->title)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 via-blue-50 to-purple-50">
    <!-- Hero Header -->
    <section class="relative bg-gradient-to-r from-green-600 via-teal-600 to-blue-700 text-white py-8 overflow-hidden">
        <div class="absolute inset-0 bg-black/10"></div>

        <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Back Button -->
            <div class="flex justify-start mb-4">
                <a href="{{ route('activities.show', $activity->id) }}" class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur-sm text-white rounded-full hover:bg-white/30 transition-all duration-300 border border-white/20 text-sm">
                    <i class="fas fa-arrow-left mr-2"></i>
                    <span class="font-medium">Back to Activity</span>
                </a>
            </div>

            <div class="text-center">
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mb-4 backdrop-blur-sm border border-white/30 mx-auto">
                    <i class="fas fa-calendar-plus text-2xl text-white"></i>
                </div>
                <h1 class="text-3xl md:text-4xl font-bold mb-2 bg-gradient-to-r from-white to-green-100 bg-clip-text text-transparent">
                    Book Your Activity
                </h1>
                <p class="text-lg text-green-100 max-w-2xl mx-auto">
                    {{ $activity->title }}
                </p>
            </div>
        </div>
    </section>

    <!-- Main Booking Section -->
    <section class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-3 gap-8">

                <!-- Activity Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100 sticky top-8">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Activity Summary</h3>

                        <div class="space-y-4">
                            <div>
                                <h4 class="font-semibold text-gray-900">{{ $activity->title }}</h4>
                                <p class="text-sm text-gray-600">{{ $activity->location }}</p>
                            </div>

                            <div class="bg-gradient-to-r from-green-50 to-teal-50 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-gray-600 text-sm">Price per person</span>
                                    <span class="text-2xl font-bold text-green-600">${{ $activity->price }}</span>
                                </div>
                                <div class="text-xs text-gray-500">
                                    Duration: {{ $activity->duration }}
                                </div>
                            </div>

                            <div>
                                <h5 class="font-medium text-gray-900 mb-2">What's Included:</h5>
                                <ul class="text-sm text-gray-600 space-y-1">
                                    @foreach($activity->includes as $item)
                                        <li class="flex items-center">
                                            <i class="fas fa-check text-green-500 mr-2 text-xs"></i>
                                            {{ $item }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <div>
                                <h5 class="font-medium text-gray-900 mb-2">Requirements:</h5>
                                <ul class="text-sm text-gray-600 space-y-1">
                                    @foreach($activity->requirements as $requirement)
                                        <li class="flex items-center">
                                            <i class="fas fa-info-circle text-blue-500 mr-2 text-xs"></i>
                                            {{ $requirement }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Booking Form -->
                <div class="lg:col-span-2">
                    <form action="{{ route('activities.book.store', $activity->id) }}" method="POST" id="booking-form" class="space-y-6">
                        @csrf

                        <!-- Step 1: Date & Time -->
                        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
                            <div class="flex items-center mb-4">
                                <div class="w-8 h-8 bg-green-600 text-white rounded-full flex items-center justify-center mr-3 font-bold text-sm">1</div>
                                <h3 class="text-lg font-bold text-gray-900">Select Date & Time</h3>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Preferred Date</label>
                                    <input type="date" name="date" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-300" required min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Preferred Time</label>
                                    <select name="time" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" required>
                                        <option value="">Select time</option>
                                        <option value="09:00">9:00 AM</option>
                                        <option value="10:00">10:00 AM</option>
                                        <option value="11:00">11:00 AM</option>
                                        <option value="14:00">2:00 PM</option>
                                        <option value="15:00">3:00 PM</option>
                                        <option value="16:00">4:00 PM</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Participants -->
                        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
                            <div class="flex items-center mb-4">
                                <div class="w-8 h-8 bg-green-600 text-white rounded-full flex items-center justify-center mr-3 font-bold text-sm">2</div>
                                <h3 class="text-lg font-bold text-gray-900">Participant Information</h3>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Number of Participants</label>
                                <select name="participants" id="participants-count" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" required>
                                    <option value="">Select participants</option>
                                    @for($i = 1; $i <= $activity->max_participants; $i++)
                                        <option value="{{ $i }}">{{ $i }} {{ $i == 1 ? 'Person' : 'People' }}</option>
                                    @endfor
                                </select>
                            </div>

                            <div id="participant-details" class="space-y-4" style="display: none;">
                                <!-- Participant details will be dynamically added here -->
                            </div>
                        </div>

                        <!-- Step 3: Contact Information -->
                        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
                            <div class="flex items-center mb-4">
                                <div class="w-8 h-8 bg-green-600 text-white rounded-full flex items-center justify-center mr-3 font-bold text-sm">3</div>
                                <h3 class="text-lg font-bold text-gray-900">Contact Information</h3>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                                    <input type="email" name="email" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-300" placeholder="your@email.com" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Phone Number</label>
                                    <input type="tel" name="phone" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-300" placeholder="+254 123 456 789" required>
                                </div>
                            </div>

                            <div class="mt-4">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Special Requirements (Optional)</label>
                                <textarea name="special_requirements" rows="3" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-300 resize-none" placeholder="Any dietary restrictions, accessibility needs, or special requests..."></textarea>
                            </div>
                        </div>

                        <!-- Total & Submit -->
                        <div class="bg-gradient-to-r from-green-600 to-teal-600 rounded-xl shadow-lg p-6 text-white">
                            <div class="text-center mb-4">
                                <h3 class="text-lg font-bold mb-2">Ready to Book?</h3>
                                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 mb-4 border border-white/20">
                                    <div class="flex justify-between items-center">
                                        <span class="text-green-100">Total Amount:</span>
                                        <div class="text-right">
                                            <div id="total-amount" class="text-2xl font-bold">$0</div>
                                            <div class="text-xs text-green-100" id="breakdown"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="w-full px-6 py-4 bg-white text-green-600 rounded-lg hover:bg-green-50 transition-all duration-300 font-bold text-lg shadow-lg hover:shadow-xl transform hover:scale-105">
                                <i class="fas fa-credit-card mr-2"></i>
                                Proceed to Payment
                            </button>

                            <p class="text-center text-green-100 text-sm mt-3">
                                You'll complete payment on the next page
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const participantsSelect = document.getElementById('participants-count');
    const participantDetails = document.getElementById('participant-details');
    const totalAmount = document.getElementById('total-amount');
    const breakdown = document.getElementById('breakdown');
    const pricePerPerson = {{ $activity->price }};
    const ageGroups = {
        'toddler': 'Toddlers (2-4 years)',
        'child': 'Children (5-12 years)',
        'teen': 'Teenagers (13-17 years)',
        'adult': 'Adults (18+ years)'
    };

    participantsSelect.addEventListener('change', function() {
        const count = parseInt(this.value);
        updateParticipantForms(count);
        updateTotal(count);
    });

    function updateParticipantForms(count) {
        participantDetails.innerHTML = '';

        if (count > 0) {
            participantDetails.style.display = 'block';

            for (let i = 1; i <= count; i++) {
                const participantForm = `
                    <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                        <h4 class="font-semibold text-gray-900 mb-3">Participant ${i}</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                                <input type="text" name="participant_details[${i-1}][name]" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-green-500 focus:border-green-500" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Age</label>
                                <input type="number" name="participant_details[${i-1}][age]" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-green-500 focus:border-green-500" min="1" max="120" required>
                            </div>
                        </div>
                        <div class="mt-3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Age Group</label>
                            <select name="participant_details[${i-1}][age_group]" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-green-500 focus:border-green-500" required>
                                <option value="">Select age group</option>
                                ${Object.entries(ageGroups).map(([key, value]) => `<option value="${key}">${value}</option>`).join('')}
                            </select>
                        </div>
                    </div>
                `;
                participantDetails.insertAdjacentHTML('beforeend', participantForm);
            }
        } else {
            participantDetails.style.display = 'none';
        }
    }

    function updateTotal(count) {
        const total = count * pricePerPerson;
        totalAmount.textContent = `$${total}`;
        breakdown.textContent = count > 0 ? `${count} × $${pricePerPerson}` : '';
    }
});
</script>
@endsection
