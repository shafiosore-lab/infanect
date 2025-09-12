@extends('layouts.app')

@section('title', 'Book Appointment - ' . $provider->name)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50">
    <!-- Hero Header -->
    <section class="relative bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-700 text-white py-4 overflow-hidden">
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent"></div>

        <!-- Floating Elements -->
        <div class="absolute top-5 left-3 w-8 h-8 bg-white/10 rounded-full blur-lg animate-pulse"></div>
        <div class="absolute bottom-3 right-5 w-12 h-12 bg-purple-200/10 rounded-full blur-xl animate-bounce"></div>

        <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <!-- Back Button -->
            <div class="flex justify-start mb-2">
                <a href="{{ route('providers.show', $provider->id) }}" class="inline-flex items-center px-3 py-1.5 bg-white/20 backdrop-blur-sm text-white rounded-full hover:bg-white/30 transition-all duration-300 border border-white/20 text-xs">
                    <i class="fas fa-arrow-left mr-1.5"></i>
                    <span class="font-medium">View Profile</span>
                </a>
            </div>

            <div class="flex flex-col items-center">
                <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center mb-2 backdrop-blur-sm border border-white/30">
                    <i class="fas fa-calendar-alt text-sm text-white"></i>
                </div>
                <h1 class="text-lg md:text-xl font-bold mb-1 bg-gradient-to-r from-white to-blue-100 bg-clip-text text-transparent">
                    Book Your Session
                </h1>
                <p class="text-xs text-blue-100 max-w-xl">
                    Schedule a personalized appointment with {{ $provider->name }}
                </p>
            </div>
        </div>
    </section>

    <!-- Main Booking Section -->
    <section class="py-4 relative">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-3 gap-3">

                <!-- Left Sidebar - Provider Info -->
                <div class="lg:col-span-1 space-y-2">
                    <!-- Provider Card -->
                    <div class="bg-white rounded-xl shadow-md p-3 border border-gray-100 hover:shadow-lg transition-all duration-300">
                        <div class="text-center">
                            <div class="w-12 h-12 mx-auto mb-2 rounded-full overflow-hidden ring-1 ring-blue-100">
                                <img src="https://images.unsplash.com/photo-{{
                                    collect([
                                        '1559839734-2b71ea197ec2?ixlib=rb-4.0.3&auto=format&fit=crop&w=48&h=48&q=85',
                                        '1594824388511-923247e6e01e?ixlib=rb-4.0.3&auto=format&fit=crop&w=48&h=48&q=85',
                                        '1612349317150-e3d4ac1d0e35?ixlib=rb-4.0.3&auto=format&fit=crop&w=48&h=48&q=85',
                                        '1582750433449-648ed127bb54?ixlib=rb-4.0.3&auto=format&fit=crop&w=48&h=48&q=85'
                                    ])[($provider->id - 1) % 4]
                                }}" alt="{{ $provider->name }}" class="w-full h-full object-cover">
                            </div>
                            <h3 class="text-sm font-bold text-gray-900 mb-1">{{ $provider->name }}</h3>
                            <p class="text-blue-600 font-medium text-xs mb-2">{{ $provider->title }}</p>

                            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-2 mb-2">
                                <div class="flex items-center justify-center mb-0.5">
                                    <i class="fas fa-dollar-sign text-blue-600 mr-1 text-xs"></i>
                                    <span class="text-lg font-bold text-blue-600">${{ $provider->price }}</span>
                                </div>
                                <p class="text-gray-600 text-xs">per session</p>
                            </div>
                        </div>

                        <!-- Features -->
                        <div class="space-y-1">
                            <div class="flex items-center text-xs text-gray-600">
                                <i class="fas fa-shield-check text-green-500 mr-1.5 w-3 text-xs"></i>
                                <span>Verified</span>
                            </div>
                            <div class="flex items-center text-xs text-gray-600">
                                <i class="fas fa-video text-blue-500 mr-1.5 w-3 text-xs"></i>
                                <span>Online & In-Person</span>
                            </div>
                            <div class="flex items-center text-xs text-gray-600">
                                <i class="fas fa-globe text-purple-500 mr-1.5 w-3 text-xs"></i>
                                <span>Multi-Language</span>
                            </div>
                            <div class="flex items-center text-xs text-gray-600">
                                <i class="fas fa-clock text-orange-500 mr-1.5 w-3 text-xs"></i>
                                <span>Flexible</span>
                            </div>
                        </div>
                    </div>

                    <!-- Trust Badge -->
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-3 border border-green-100">
                        <div class="flex items-center justify-center mb-1">
                            <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center">
                                <i class="fas fa-heart text-white text-xs"></i>
                            </div>
                        </div>
                        <h4 class="font-semibold text-center text-gray-900 mb-0.5 text-xs">Safe & Secure</h4>
                        <p class="text-xs text-gray-600 text-center leading-relaxed">
                            Encrypted & protected. Cancel anytime.
                        </p>
                    </div>
                </div>

                <!-- Right Content - Booking Form -->
                <div class="lg:col-span-2">
                    <form action="{{ route('providers.book.store', $provider->id) }}" method="POST" class="space-y-2">
                        @csrf

                        <!-- Step 1: Date Selection -->
                        <div class="bg-white rounded-xl shadow-md p-3 border border-gray-100">
                            <div class="flex items-center mb-2">
                                <div class="w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center mr-2 font-bold text-xs">1</div>
                                <h3 class="text-sm font-bold text-gray-900">Select Date</h3>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Preferred Date</label>
                                    <input type="date" name="date" class="w-full px-2 py-2 border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 text-xs" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Time Zone</label>
                                    <select name="timezone" class="w-full px-2 py-2 border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 text-xs">
                                        <option value="Africa/Nairobi" selected>UTC+3 (EAT) - Nairobi</option>
                                        <option value="Africa/Cairo">UTC+2 (EET) - Cairo</option>
                                        <option value="Africa/Lagos">UTC+1 (WAT) - Lagos</option>
                                        <option value="Africa/Johannesburg">UTC+2 (SAST) - Johannesburg</option>
                                        <option value="Africa/Casablanca">UTC+1 (WET) - Casablanca</option>
                                        <option value="Africa/Addis_Ababa">UTC+3 (EAT) - Addis Ababa</option>
                                        <option value="Africa/Accra">UTC+0 (GMT) - Accra</option>
                                        <option value="Africa/Kampala">UTC+3 (EAT) - Kampala</option>
                                        <option value="Africa/Dar_es_Salaam">UTC+3 (EAT) - Dar es Salaam</option>
                                        <option value="Africa/Kigali">UTC+2 (CAT) - Kigali</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Time Selection -->
                        <div class="bg-white rounded-xl shadow-md p-3 border border-gray-100">
                            <div class="flex items-center mb-2">
                                <div class="w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center mr-2 font-bold text-xs">2</div>
                                <h3 class="text-sm font-bold text-gray-900">Choose Time</h3>
                            </div>

                            <div class="grid grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-1">
                                @foreach(['09:00' => '9:00 AM', '10:00' => '10:00 AM', '11:00' => '11:00 AM', '14:00' => '2:00 PM', '15:00' => '3:00 PM', '16:00' => '4:00 PM', '17:00' => '5:00 PM', '18:00' => '6:00 PM'] as $value => $label)
                                    <button type="button" class="time-slot px-1 py-2 border border-gray-200 rounded-lg hover:border-blue-400 hover:bg-blue-50 transition-all duration-300 text-xs font-medium group" data-time="{{ $value }}">
                                        <div class="flex flex-col items-center">
                                            <i class="fas fa-clock text-gray-400 group-hover:text-blue-500 mb-0.5 text-xs"></i>
                                            <span class="text-xs">{{ $label }}</span>
                                        </div>
                                    </button>
                                @endforeach
                            </div>
                            <input type="hidden" name="time" id="selected-time" required>
                        </div>

                        <!-- Step 3: Session Details -->
                        <div class="bg-white rounded-lg shadow-sm p-2 border border-gray-100">
                            <div class="flex items-center mb-1">
                                <div class="w-4 h-4 bg-blue-600 text-white rounded-full flex items-center justify-center mr-1.5 font-bold text-xs">3</div>
                                <h3 class="text-xs font-bold text-gray-900">Session Details</h3>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-1 mb-1">
                                <!-- Session Type -->
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-0.5">Session Type</label>
                                    <div class="space-y-0.5">
                                        <div class="relative">
                                            <input type="radio" name="session_type" value="individual" id="individual" class="sr-only">
                                            <label for="individual" class="flex items-center p-1.5 border border-gray-200 rounded-md cursor-pointer hover:border-blue-400 transition-all duration-300 session-option">
                                                <i class="fas fa-user text-blue-500 mr-1.5 text-xs"></i>
                                                <div>
                                                    <div class="font-semibold text-gray-900 text-xs">Individual</div>
                                                    <div class="text-xs text-gray-600">One-on-one</div>
                                                </div>
                                            </label>
                                        </div>
                                        <div class="relative">
                                            <input type="radio" name="session_type" value="family" id="family" class="sr-only">
                                            <label for="family" class="flex items-center p-1.5 border border-gray-200 rounded-md cursor-pointer hover:border-blue-400 transition-all duration-300 session-option">
                                                <i class="fas fa-users text-green-500 mr-1.5 text-xs"></i>
                                                <div>
                                                    <div class="font-semibold text-gray-900 text-xs">Family</div>
                                                    <div class="text-xs text-gray-600">Group therapy</div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Session Format -->
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-0.5">Session Format</label>
                                    <div class="space-y-0.5">
                                        <div class="relative">
                                            <input type="radio" name="session_format" value="online" id="online" class="sr-only" checked>
                                            <label for="online" class="flex items-center p-1.5 border border-blue-500 bg-blue-50 rounded-md cursor-pointer transition-all duration-300 session-option">
                                                <i class="fas fa-video text-blue-500 mr-1.5 text-xs"></i>
                                                <div>
                                                    <div class="font-semibold text-gray-900 text-xs">Online</div>
                                                    <div class="text-xs text-gray-600">Video call</div>
                                                </div>
                                            </label>
                                        </div>
                                        <div class="relative">
                                            <input type="radio" name="session_format" value="in-person" id="in-person" class="sr-only">
                                            <label for="in-person" class="flex items-center p-1.5 border border-gray-200 rounded-md cursor-pointer hover:border-blue-400 transition-all duration-300 session-option">
                                                <i class="fas fa-map-marker-alt text-purple-500 mr-1.5 text-xs"></i>
                                                <div>
                                                    <div class="font-semibold text-gray-900 text-xs">In-Person</div>
                                                    <div class="text-xs text-gray-600">At office</div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Notes -->
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-0.5">Notes (Optional)</label>
                                <textarea name="notes" rows="1" class="w-full px-1.5 py-1.5 border border-gray-200 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 resize-none text-xs" placeholder="Any concerns..."></textarea>
                            </div>
                        </div>

                        <!-- Step 4: Contact Information -->
                        <div class="bg-white rounded-xl shadow-md p-3 border border-gray-100">
                            <div class="flex items-center mb-2">
                                <div class="w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center mr-2 font-bold text-xs">4</div>
                                <h3 class="text-sm font-bold text-gray-900">Contact Information</h3>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Email Address</label>
                                    <input type="email" name="email" class="w-full px-2 py-2 border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 text-xs" placeholder="your@email.com" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Phone Number</label>
                                    <input type="tel" name="phone" class="w-full px-2 py-2 border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 text-xs" placeholder="+1 (555) 123-4567" required>
                                </div>
                            </div>
                        </div>

                        <!-- Booking Summary & Submit -->
                        <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl shadow-lg p-3 text-white">
                            <h3 class="text-sm font-bold mb-2 text-center">Ready to Book?</h3>

                            <!-- Summary -->
                            <div class="bg-white/10 backdrop-blur-sm rounded-lg p-2 mb-2 border border-white/20">
                                <div class="grid grid-cols-2 gap-2 text-xs">
                                    <div>
                                        <span class="text-blue-100">Provider:</span>
                                        <div class="font-semibold">{{ $provider->name }}</div>
                                    </div>
                                    <div>
                                        <span class="text-blue-100">Session Rate:</span>
                                        <div class="font-semibold">${{ $provider->price }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-center">
                                <button type="submit" class="px-6 py-2 bg-white text-blue-600 rounded-full hover:bg-blue-50 transition-all duration-300 font-bold text-xs shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center">
                                    <i class="fas fa-calendar-check mr-1.5"></i>
                                    Confirm Booking
                                </button>
                            </div>

                            <p class="text-center text-blue-100 text-xs mt-2">
                                You'll receive a confirmation email within 5 minutes
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
.session-option {
    transition: all 0.3s ease;
}

.session-option:has(input:checked) {
    border-color: #2563eb;
    background-color: #eff6ff;
    transform: scale(1.02);
}

.time-slot.selected {
    border-color: #2563eb;
    background-color: #2563eb;
    color: white;
    transform: scale(1.05);
}

.time-slot.selected i {
    color: white !important;
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
}

.animate-float {
    animation: float 3s ease-in-out infinite;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Time slot selection
    const timeSlots = document.querySelectorAll('.time-slot');
    const selectedTimeInput = document.getElementById('selected-time');

    timeSlots.forEach(slot => {
        slot.addEventListener('click', function() {
            timeSlots.forEach(s => s.classList.remove('selected'));
            this.classList.add('selected');
            selectedTimeInput.value = this.dataset.time;
        });
    });

    // Radio button styling
    const sessionOptions = document.querySelectorAll('input[type="radio"]');
    sessionOptions.forEach(option => {
        option.addEventListener('change', function() {
            const name = this.name;
            document.querySelectorAll(`input[name="${name}"]`).forEach(input => {
                const label = input.closest('label') || document.querySelector(`label[for="${input.id}"]`);
                if (label) {
                    label.classList.remove('border-blue-500', 'bg-blue-50');
                    label.classList.add('border-gray-200');
                }
            });

            const label = this.closest('label') || document.querySelector(`label[for="${this.id}"]`);
            if (label) {
                label.classList.remove('border-gray-200');
                label.classList.add('border-blue-500', 'bg-blue-50');
            }
        });
    });
});
</script>
@endsection
