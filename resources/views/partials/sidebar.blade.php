<div class="h-screen flex flex-col bg-gradient-to-b from-slate-900 via-indigo-900 to-slate-800 text-white shadow-lg">

    <!-- Brand -->
    <div class="px-4 py-3 border-b bg-gray-900 flex items-center gap-2">
        <h2 class="text-lg font-semibold text-gray-100">Infanect</h2>
    </div>

    <!-- Sidebar Content -->
    <nav class="h-full px-2 py-4 text-sm text-gray-200 bg-gray-800" x-data="{ openMenu: null }>

        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-md transition-all duration-200
                  {{ request()->routeIs('dashboard') ? 'bg-gray-700' : 'text-gray-200 hover:bg-gray-700' }}">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12h18M12 3v18"/></svg>
            <span>Dashboard Overview</span>
        </a>

        @if(auth()->user()->isSuperAdmin())

        <!-- ACTIVITIES & MODULES -->
        <div>
            <button @click="openMenu === 1 ? openMenu = null : openMenu = 1"
                    class="flex justify-between items-center w-full px-4 py-3 text-gray-200 hover:bg-gray-700 rounded-md">
                <span>Activities & Providers</span>
                <svg :class="openMenu === 1 ? 'rotate-90' : ''" class="h-4 w-4 transform transition-transform"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            <div x-show="openMenu === 1" class="pl-8 space-y-2 py-2">
                <a href="{{ route('admin.activities.index') }}" class="block py-2 text-gray-200 hover:text-indigo-400">All Activities</a>
                <a href="{{ route('admin.providers.index') }}" class="block py-2 text-gray-200 hover:text-indigo-400">All Providers</a>
                <a href="{{ route('admin.approvals.index') }}" class="block py-2 text-gray-200 hover:text-indigo-400">Pending Approvals</a>
                <a href="{{ route('admin.modules.index') }}" class="block py-2 text-gray-200 hover:text-indigo-400">Modules & Categories</a>
                <a href="{{ route('admin.ai.recommendations') }}" class="block py-2 text-gray-200 hover:text-indigo-400">AI Recommendations</a>
            </div>
        </div>

        <!-- USER & ACCESS -->
        <div>
            <button @click="openMenu === 2 ? openMenu = null : openMenu = 2"
                    class="flex justify-between items-center w-full px-4 py-3 text-gray-200 hover:bg-gray-700 rounded-md">
                <span>User & Access</span>
                <svg :class="openMenu === 2 ? 'rotate-90' : ''" class="h-4 w-4 transform transition-transform"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            <div x-show="openMenu === 2" class="pl-8 space-y-2 py-2">
                <a href="{{ route('admin.users.index') }}" class="block py-2 text-gray-200 hover:text-indigo-400">Users Management</a>
                <a href="{{ route('admin.roles') }}" class="block py-2 text-gray-200 hover:text-indigo-400">Roles & Permissions</a>
                <a href="{{ route('admin.settings') }}" class="block py-2 text-gray-200 hover:text-indigo-400">Platform Settings</a>
            </div>
        </div>

        <!-- SERVICES -->
        <div>
            <button @click="openMenu === 3 ? openMenu = null : openMenu = 3"
                    class="flex justify-between items-center w-full px-4 py-3 text-gray-200 hover:bg-gray-700 rounded-md">
                <span>Service Providers</span>
                <svg :class="openMenu === 3 ? 'rotate-90' : ''" class="h-4 w-4 transform transition-transform"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            <div x-show="openMenu === 3" class="pl-8 space-y-2 py-2">
                <a href="{{ route('admin.services.index') }}" class="block py-2 text-gray-200 hover:text-indigo-400">Services</a>
                <a href="{{ route('admin.categories.index') }}" class="block py-2 text-gray-200 hover:text-indigo-400">Service Categories</a>
                <a href="{{ route('admin.service.insights') }}" class="block py-2 text-gray-200 hover:text-indigo-400">Service Insights</a>
            </div>
        </div>

        <!-- CLIENTS / USERS -->
        <div>
            <button @click="openMenu === 4 ? openMenu = null : openMenu = 4"
                    class="flex justify-between items-center w-full px-4 py-3 text-gray-200 hover:bg-gray-700 rounded-md">
                <span>Clients / Users</span>
                <svg :class="openMenu === 4 ? 'rotate-90' : ''" class="h-4 w-4 transform transition-transform"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            <div x-show="openMenu === 4" class="pl-8 space-y-2 py-2">
                <a href="{{ route('admin.clients.index') }}" class="block py-2 text-gray-200 hover:text-indigo-400">Clients List</a>
                <a href="{{ route('admin.bookings.index') }}" class="block py-2 text-gray-200 hover:text-indigo-400">Bookings</a>
                <a href="{{ route('admin.modules.index') }}" class="block py-2 text-gray-200 hover:text-indigo-400">Bonding / Parenting Modules</a>
                <a href="{{ route('admin.client.insights') }}" class="block py-2 text-gray-200 hover:text-indigo-400">Client Insights</a>
                <a href="{{ route('admin.feedback') }}" class="block py-2 text-gray-200 hover:text-indigo-400">Feedback & Reviews</a>
                <a href="{{ route('admin.ai.chat') }}" class="block py-2 text-gray-200 hover:text-indigo-400">AI Chat Support</a>
            </div>
        </div>

        <!-- FINANCIALS -->
        <div>
            <button @click="openMenu === 5 ? openMenu = null : openMenu = 5"
                    class="flex justify-between items-center w-full px-4 py-3 text-gray-200 hover:bg-gray-700 rounded-md">
                <span>Financials</span>
                <svg :class="openMenu === 5 ? 'rotate-90' : ''" class="h-4 w-4 transform transition-transform"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="ml-2 bg-red-500 px-2 rounded-full text-xs">{{ $pendingTransactions ?? 0 }}</span>
            </button>
            <div x-show="openMenu === 5" class="pl-8 space-y-2 py-2">
                <a href="{{ route('admin.finance.insights') }}" class="block py-2 text-gray-200 hover:text-indigo-400">Financial Insights</a>
                <a href="{{ route('admin.earnings') }}" class="block py-2 text-gray-200 hover:text-indigo-400">Earnings & Payouts</a>
                <a href="{{ route('admin.invoices') }}" class="block py-2 text-gray-200 hover:text-indigo-400">Invoices & Billing</a>
                <a href="{{ route('admin.subscriptions') }}" class="block py-2 text-gray-200 hover:text-indigo-400">Subscription Plans</a>
            </div>
        </div>

        <!-- OPERATIONS -->
        <div>
            <button @click="openMenu === 6 ? openMenu = null : openMenu = 6"
                    class="flex justify-between items-center w-full px-4 py-3 text-gray-200 hover:bg-gray-700 rounded-md">
                <span>Operations</span>
                <svg :class="openMenu === 6 ? 'rotate-90' : ''" class="h-4 w-4 transform transition-transform"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            <div x-show="openMenu === 6" class="pl-8 space-y-2 py-2">
                <a href="{{ route('admin.tasks') }}" class="block py-2 text-gray-200 hover:text-indigo-400">Tasks</a>
                <a href="{{ route('admin.team') }}" class="block py-2 text-gray-200 hover:text-indigo-400">Team</a>
                <a href="{{ route('admin.reports') }}" class="block py-2 text-gray-200 hover:text-indigo-400">Reports</a>
                <a href="{{ route('admin.notifications') }}" class="block py-2 text-gray-200 hover:text-indigo-400">Notifications</a>
                <a href="{{ route('admin.support') }}" class="block py-2 text-gray-200 hover:text-indigo-400">Support Tickets</a>
            </div>
        </div>

        @elseif(auth()->user()->isServiceProvider())
            <!-- Service Provider Menu -->
            <a href="{{ route('services.index') }}" class="flex items-center px-4 py-3 rounded-lg text-slate-300 hover:bg-indigo-700 hover:text-white">
                🛠️ My Services
            </a>
            <a href="{{ route('bookings.index') }}" class="flex items-center px-4 py-3 rounded-lg text-slate-300 hover:bg-indigo-700 hover:text-white">
                📋 Bookings
            </a>
            <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-3 rounded-lg text-slate-300 hover:bg-indigo-700 hover:text-white">
                👤 Profile
            </a>

        @elseif(auth()->user()->isActivityProvider())
            <!-- Activity Provider Menu -->
            <a href="{{ route('activities.index') }}" class="flex items-center px-4 py-3 rounded-lg text-slate-300 hover:bg-indigo-700 hover:text-white">
                🎯 My Activities
            </a>
            <a href="{{ route('bookings.index') }}" class="flex items-center px-4 py-3 rounded-lg text-slate-300 hover:bg-indigo-700 hover:text-white">
                📋 Registrations
            </a>
            <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-3 rounded-lg text-slate-300 hover:bg-indigo-700 hover:text-white">
                👤 Profile
            </a>

        @elseif(auth()->user()->isClient() || auth()->user()->isUser())
            <!-- Client / User Menu -->
            <a href="{{ route('user.modules.index') }}" class="flex items-center px-4 py-3 rounded-lg text-slate-300 hover:bg-indigo-700 hover:text-white">
                📚 Modules & Activities
            </a>
            <a href="{{ route('user.bookings.index') }}" class="flex items-center px-4 py-3 rounded-lg text-slate-300 hover:bg-indigo-700 hover:text-white">
                📋 My Bookings
            </a>
            <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-3 rounded-lg text-slate-300 hover:bg-indigo-700 hover:text-white">
                👤 Profile
            </a>
        @endif

    </nav>
</div>

<style>
/* Force sidebar appearance to black with white text/links */
aside, .sidebar, .side-nav, .main-sidebar, .partial-sidebar {
    background-color: #000 !important;
    color: #fff !important;
}

aside a, .sidebar a, .side-nav a, .main-sidebar a, .partial-sidebar a,
.sidebar .nav-link, .side-nav .nav-link {
    color: #fff !important;
}

/* Active/hover states */
.sidebar .active, .side-nav .active, .main-sidebar .active {
    background-color: #111 !important;
}
.sidebar a:hover, .side-nav a:hover, .main-sidebar a:hover {
    color: #fff !important;
    background-color: rgba(255,255,255,0.05) !important;
}

/* Hide scrollbars for sidebar nav */
.hide-scrollbar { scrollbar-width: none; -ms-overflow-style: none; }
.hide-scrollbar::-webkit-scrollbar { display: none; }
</style>

<!-- Mood Submission Modal -->
<div class="modal fade" id="moodModal" tabindex="-1" aria-labelledby="moodModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="moodModalLabel">
                    <i class="bi bi-heart text-danger me-2"></i>How are you feeling today?
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="moodSubmissionForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Select your mood:</label>
                        <div class="mood-selection d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-primary mood-btn" data-mood="very-low" data-score="1">
                                😢<br><small>Very Low</small>
                            </button>
                            <button type="button" class="btn btn-outline-primary mood-btn" data-mood="low" data-score="3">
                                😔<br><small>Low</small>
                            </button>
                            <button type="button" class="btn btn-outline-primary mood-btn" data-mood="neutral" data-score="5">
                                😐<br><small>Neutral</small>
                            </button>
                            <button type="button" class="btn btn-outline-primary mood-btn" data-mood="good" data-score="7">
                                😊<br><small>Good</small>
                            </button>
                            <button type="button" class="btn btn-outline-primary mood-btn" data-mood="excellent" data-score="10">
                                😄<br><small>Excellent</small>
                            </button>
                        </div>
                        <input type="hidden" name="mood" id="selectedMood">
                        <input type="hidden" name="mood_score" id="selectedScore">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">When are you available for learning?</label>
                        <div class="availability-selection">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="availability[]" value="morning" id="morning">
                                <label class="form-check-label" for="morning">Morning (6-12 PM)</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="availability[]" value="afternoon" id="afternoon">
                                <label class="form-check-label" for="afternoon">Afternoon (12-6 PM)</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="availability[]" value="evening" id="evening">
                                <label class="form-check-label" for="evening">Evening (6-10 PM)</label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="timezone" class="form-label">Timezone</label>
                        <select name="timezone" id="timezone" class="form-select">
                            <option value="UTC">UTC</option>
                            <option value="America/New_York">Eastern Time</option>
                            <option value="America/Los_Angeles">Pacific Time</option>
                            <option value="Europe/London">London</option>
                            <option value="Africa/Nairobi" selected>Nairobi</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="submitMood" disabled>
                    Submit & Get Recommendations
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function openMoodSubmission() {
    document.getElementById('moodModal').classList.add('show');
    document.getElementById('moodModal').style.display = 'block';
    document.body.classList.add('modal-open');
}

document.addEventListener('DOMContentLoaded', function() {
    // Mood selection
    document.querySelectorAll('.mood-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove active class from all buttons
            document.querySelectorAll('.mood-btn').forEach(b => b.classList.remove('btn-primary'));
            document.querySelectorAll('.mood-btn').forEach(b => b.classList.add('btn-outline-primary'));

            // Add active class to selected button
            this.classList.remove('btn-outline-primary');
            this.classList.add('btn-primary');

            // Set form values
            document.getElementById('selectedMood').value = this.dataset.mood;
            document.getElementById('selectedScore').value = this.dataset.score;

            // Enable submit button
            document.getElementById('submitMood').disabled = false;
        });
    });

    // Submit mood
    document.getElementById('submitMood').addEventListener('click', function() {
        const formData = new FormData();
        formData.append('_token', document.querySelector('[name="_token"]').value);
        formData.append('mood', document.getElementById('selectedMood').value);
        formData.append('mood_score', document.getElementById('selectedScore').value);
        formData.append('timezone', document.getElementById('timezone').value);

        // Get availability
        const availability = [];
        document.querySelectorAll('input[name="availability[]"]:checked').forEach(input => {
            availability.push(input.value);
        });
        availability.forEach(item => formData.append('availability[]', item));

        // Submit to MoodController
        fetch('{{ route("mood.submit") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'queued') {
                // Close modal
                document.getElementById('moodModal').classList.remove('show');
                document.getElementById('moodModal').style.display = 'none';
                document.body.classList.remove('modal-open');

                // Show success message and redirect to training with personalized content
                alert('Thank you for sharing! Redirecting to personalized training recommendations...');
                window.location.href = '{{ route("training.index") }}?mood_id=' + data.mood_id;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('There was an error submitting your mood. Please try again.');
        });
    });
});
</script>
