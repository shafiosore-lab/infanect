<!-- FontAwesome Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="sidebar-modern vh-100 d-flex flex-column text-white position-fixed start-0 top-0 shadow-lg" style="width: 280px; background: linear-gradient(135deg, #1e293b 0%, #334155 50%, #0f172a 100%); z-index: 1030;">

    <!-- Brand -->
    <div class="brand-section px-4 py-4 border-bottom border-secondary d-flex align-items-center gap-3">
        <div class="brand-icon bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
            <i class="fas fa-heart text-white"></i>
        </div>
        <h2 class="h5 mb-0 fw-bold text-white">Infanect</h2>
    </div>

    <!-- Sidebar Content -->
    <div class="accordion" id="sidebarAccordion">
    <nav class="sidebar-nav flex-grow-1 px-3 py-4 overflow-auto" data-bs-spy="scroll">

        @auth
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}"
           class="nav-link d-flex align-items-center gap-3 px-3 py-3 rounded-3 mb-2 transition-all sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt"></i>
            <span>Dashboard Overview</span>
        </a>

        @if(auth()->user() && method_exists(auth()->user(), 'isSuperAdmin') && auth()->user()->isSuperAdmin())

        <!-- ACTIVITIES & MODULES -->
        <div class="accordion-item border-0 bg-transparent">
            <button class="accordion-button collapsed nav-link d-flex align-items-center gap-3 px-3 py-3 rounded-3 mb-2 transition-all sidebar-accordion-btn" type="button" data-bs-toggle="collapse" data-bs-target="#activitiesCollapse">
                <i class="fas fa-users"></i>
                <span>Activities & Providers</span>
            </button>
            <div id="activitiesCollapse" class="accordion-collapse collapse" data-bs-parent="#sidebarAccordion">
                <div class="accordion-body p-0">
                    <a href="{{ route('admin.activities.index') }}" class="nav-link d-flex align-items-center gap-3 px-4 py-2 ms-3 mb-1 rounded-2 transition-all sidebar-submenu-link">
                        <i class="fas fa-list"></i>
                        <span>All Activities</span>
                    </a>
                    <a href="{{ route('admin.providers.index') }}" class="nav-link d-flex align-items-center gap-3 px-4 py-2 ms-3 mb-1 rounded-2 transition-all sidebar-submenu-link">
                        <i class="fas fa-user-md"></i>
                        <span>All Providers</span>
                    </a>
                    <a href="{{ route('admin.approvals.index') }}" class="nav-link d-flex align-items-center gap-3 px-4 py-2 ms-3 mb-1 rounded-2 transition-all sidebar-submenu-link">
                        <i class="fas fa-check-circle"></i>
                        <span>Pending Approvals</span>
                    </a>
                    <a href="{{ route('admin.modules.index') }}" class="nav-link d-flex align-items-center gap-3 px-4 py-2 ms-3 mb-1 rounded-2 transition-all sidebar-submenu-link">
                        <i class="fas fa-cubes"></i>
                        <span>Modules & Categories</span>
                    </a>
                    <a href="{{ route('admin.ai.recommendations') }}" class="nav-link d-flex align-items-center gap-3 px-4 py-2 ms-3 mb-1 rounded-2 transition-all sidebar-submenu-link">
                        <i class="fas fa-robot"></i>
                        <span>AI Recommendations</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- USER & ACCESS -->
        <div class="accordion-item border-0 bg-transparent">
            <button class="accordion-button collapsed nav-link d-flex align-items-center gap-3 px-3 py-3 rounded-3 mb-2 transition-all sidebar-accordion-btn" type="button" data-bs-toggle="collapse" data-bs-target="#userAccessCollapse">
                <i class="fas fa-user-shield"></i>
                <span>User & Access</span>
            </button>
            <div id="userAccessCollapse" class="accordion-collapse collapse" data-bs-parent="#sidebarAccordion">
                <div class="accordion-body p-0">
                    <a href="{{ route('admin.users.index') }}" class="nav-link d-flex align-items-center gap-3 px-4 py-2 ms-3 mb-1 rounded-2 transition-all sidebar-submenu-link">
                        <i class="fas fa-users-cog"></i>
                        <span>Users Management</span>
                    </a>
                    <a href="{{ route('admin.roles') }}" class="nav-link d-flex align-items-center gap-3 px-4 py-2 ms-3 mb-1 rounded-2 transition-all sidebar-submenu-link">
                        <i class="fas fa-key"></i>
                        <span>Roles & Permissions</span>
                    </a>
                    <a href="{{ route('admin.settings') }}" class="nav-link d-flex align-items-center gap-3 px-4 py-2 ms-3 mb-1 rounded-2 transition-all sidebar-submenu-link">
                        <i class="fas fa-cogs"></i>
                        <span>Platform Settings</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- SERVICES -->
        <div class="accordion-item border-0 bg-transparent">
            <button class="accordion-button collapsed nav-link d-flex align-items-center gap-3 px-3 py-3 rounded-3 mb-2 transition-all sidebar-accordion-btn" type="button" data-bs-toggle="collapse" data-bs-target="#servicesCollapse">
                <i class="fas fa-concierge-bell"></i>
                <span>Service Providers</span>
            </button>
            <div id="servicesCollapse" class="accordion-collapse collapse" data-bs-parent="#sidebarAccordion">
                <div class="accordion-body p-0">
                    <a href="{{ route('admin.services.index') }}" class="nav-link d-flex align-items-center gap-3 px-4 py-2 ms-3 mb-1 rounded-2 transition-all sidebar-submenu-link">
                        <i class="fas fa-tools"></i>
                        <span>Services</span>
                    </a>
                    <a href="{{ route('admin.categories.index') }}" class="nav-link d-flex align-items-center gap-3 px-4 py-2 ms-3 mb-1 rounded-2 transition-all sidebar-submenu-link">
                        <i class="fas fa-tags"></i>
                        <span>Service Categories</span>
                    </a>
                    <a href="{{ route('admin.service.insights') }}" class="nav-link d-flex align-items-center gap-3 px-4 py-2 ms-3 mb-1 rounded-2 transition-all sidebar-submenu-link">
                        <i class="fas fa-chart-line"></i>
                        <span>Service Insights</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- CLIENTS / USERS -->
        <div class="accordion-item border-0 bg-transparent">
            <button class="accordion-button collapsed nav-link d-flex align-items-center gap-3 px-3 py-3 rounded-3 mb-2 transition-all sidebar-accordion-btn" type="button" data-bs-toggle="collapse" data-bs-target="#clientsCollapse">
                <i class="fas fa-users"></i>
                <span>Clients / Users</span>
            </button>
            <div id="clientsCollapse" class="accordion-collapse collapse" data-bs-parent="#sidebarAccordion">
                <div class="accordion-body p-0">
                    <a href="{{ route('admin.clients.index') }}" class="nav-link d-flex align-items-center gap-3 px-4 py-2 ms-3 mb-1 rounded-2 transition-all sidebar-submenu-link">
                        <i class="fas fa-address-book"></i>
                        <span>Clients List</span>
                    </a>
                    <a href="{{ route('admin.bookings.index') }}" class="nav-link d-flex align-items-center gap-3 px-4 py-2 ms-3 mb-1 rounded-2 transition-all sidebar-submenu-link">
                        <i class="fas fa-calendar-check"></i>
                        <span>Bookings</span>
                    </a>
                    <a href="{{ route('admin.modules.index') }}" class="nav-link d-flex align-items-center gap-3 px-4 py-2 ms-3 mb-1 rounded-2 transition-all sidebar-submenu-link">
                        <i class="fas fa-book"></i>
                        <span>Bonding / Parenting Modules</span>
                    </a>
                    <a href="{{ route('admin.client.insights') }}" class="nav-link d-flex align-items-center gap-3 px-4 py-2 ms-3 mb-1 rounded-2 transition-all sidebar-submenu-link">
                        <i class="fas fa-chart-bar"></i>
                        <span>Client Insights</span>
                    </a>
                    <a href="{{ route('admin.feedback') }}" class="nav-link d-flex align-items-center gap-3 px-4 py-2 ms-3 mb-1 rounded-2 transition-all sidebar-submenu-link">
                        <i class="fas fa-star"></i>
                        <span>Feedback & Reviews</span>
                    </a>
                    <a href="{{ route('admin.ai.chat') }}" class="nav-link d-flex align-items-center gap-3 px-4 py-2 ms-3 mb-1 rounded-2 transition-all sidebar-submenu-link">
                        <i class="fas fa-robot"></i>
                        <span>AI Chat Support</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- FINANCIALS -->
        <div class="accordion-item border-0 bg-transparent">
            <button class="accordion-button collapsed nav-link d-flex align-items-center gap-3 px-3 py-3 rounded-3 mb-2 transition-all sidebar-accordion-btn" type="button" data-bs-toggle="collapse" data-bs-target="#financialsCollapse">
                <i class="fas fa-dollar-sign"></i>
                <span>Financials</span>
                @if(isset($pendingTransactions) && $pendingTransactions > 0)
                    <span class="badge bg-danger rounded-pill ms-auto">{{ $pendingTransactions }}</span>
                @endif
            </button>
            <div id="financialsCollapse" class="accordion-collapse collapse" data-bs-parent="#sidebarAccordion">
                <div class="accordion-body p-0">
                    <a href="{{ route('admin.finance.insights') }}" class="nav-link d-flex align-items-center gap-3 px-4 py-2 ms-3 mb-1 rounded-2 transition-all sidebar-submenu-link">
                        <i class="fas fa-chart-pie"></i>
                        <span>Financial Insights</span>
                    </a>
                    <a href="{{ route('admin.earnings') }}" class="nav-link d-flex align-items-center gap-3 px-4 py-2 ms-3 mb-1 rounded-2 transition-all sidebar-submenu-link">
                        <i class="fas fa-money-bill-wave"></i>
                        <span>Earnings & Payouts</span>
                    </a>
                    <a href="{{ route('admin.invoices') }}" class="nav-link d-flex align-items-center gap-3 px-4 py-2 ms-3 mb-1 rounded-2 transition-all sidebar-submenu-link">
                        <i class="fas fa-file-invoice-dollar"></i>
                        <span>Invoices & Billing</span>
                    </a>
                    <a href="{{ route('admin.subscriptions') }}" class="nav-link d-flex align-items-center gap-3 px-4 py-2 ms-3 mb-1 rounded-2 transition-all sidebar-submenu-link">
                        <i class="fas fa-crown"></i>
                        <span>Subscription Plans</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- OPERATIONS -->
        <div class="accordion-item border-0 bg-transparent">
            <button class="accordion-button collapsed nav-link d-flex align-items-center gap-3 px-3 py-3 rounded-3 mb-2 transition-all sidebar-accordion-btn" type="button" data-bs-toggle="collapse" data-bs-target="#operationsCollapse">
                <i class="fas fa-cogs"></i>
                <span>Operations</span>
            </button>
            <div id="operationsCollapse" class="accordion-collapse collapse" data-bs-parent="#sidebarAccordion">
                <div class="accordion-body p-0">
                    <a href="{{ route('admin.tasks') }}" class="nav-link d-flex align-items-center gap-3 px-4 py-2 ms-3 mb-1 rounded-2 transition-all sidebar-submenu-link">
                        <i class="fas fa-tasks"></i>
                        <span>Tasks</span>
                    </a>
                    <a href="{{ route('admin.team') }}" class="nav-link d-flex align-items-center gap-3 px-4 py-2 ms-3 mb-1 rounded-2 transition-all sidebar-submenu-link">
                        <i class="fas fa-user-friends"></i>
                        <span>Team</span>
                    </a>
                    <a href="{{ route('admin.reports') }}" class="nav-link d-flex align-items-center gap-3 px-4 py-2 ms-3 mb-1 rounded-2 transition-all sidebar-submenu-link">
                        <i class="fas fa-file-alt"></i>
                        <span>Reports</span>
                    </a>
                    <a href="{{ route('admin.notifications') }}" class="nav-link d-flex align-items-center gap-3 px-4 py-2 ms-3 mb-1 rounded-2 transition-all sidebar-submenu-link">
                        <i class="fas fa-bell"></i>
                        <span>Notifications</span>
                    </a>
                    <a href="{{ route('admin.support') }}" class="nav-link d-flex align-items-center gap-3 px-4 py-2 ms-3 mb-1 rounded-2 transition-all sidebar-submenu-link">
                        <i class="fas fa-headset"></i>
                        <span>Support Tickets</span>
                    </a>
                </div>
            </div>
        </div>

        @elseif(auth()->user() && method_exists(auth()->user(), 'isServiceProvider') && auth()->user()->isServiceProvider())
            <!-- Service Provider Menu -->
            <a href="{{ route('services.index') }}" class="nav-link d-flex align-items-center gap-3 px-3 py-3 rounded-3 mb-2 transition-all sidebar-link">
                <i class="fas fa-tools"></i>
                <span>My Services</span>
            </a>
            <a href="{{ route('bookings.index') }}" class="nav-link d-flex align-items-center gap-3 px-3 py-3 rounded-3 mb-2 transition-all sidebar-link">
                <i class="fas fa-calendar-check"></i>
                <span>Bookings</span>
            </a>
            <a href="{{ route('profile.edit') }}" class="nav-link d-flex align-items-center gap-3 px-3 py-3 rounded-3 mb-2 transition-all sidebar-link">
                <i class="fas fa-user"></i>
                <span>Profile</span>
            </a>

        @elseif(auth()->user() && method_exists(auth()->user(), 'isActivityProvider') && auth()->user()->isActivityProvider())
            <!-- Activity Provider Menu -->
            <a href="{{ route('activities.index') }}" class="nav-link d-flex align-items-center gap-3 px-3 py-3 rounded-3 mb-2 transition-all sidebar-link">
                <i class="fas fa-bullseye"></i>
                <span>My Activities</span>
            </a>
            <a href="{{ route('bookings.index') }}" class="nav-link d-flex align-items-center gap-3 px-3 py-3 rounded-3 mb-2 transition-all sidebar-link">
                <i class="fas fa-users"></i>
                <span>Registrations</span>
            </a>
            <a href="{{ route('profile.edit') }}" class="nav-link d-flex align-items-center gap-3 px-3 py-3 rounded-3 mb-2 transition-all sidebar-link">
                <i class="fas fa-user"></i>
                <span>Profile</span>
            </a>

        @elseif(auth()->user() && ((method_exists(auth()->user(), 'isClient') && auth()->user()->isClient()) || (method_exists(auth()->user(), 'isUser') && auth()->user()->isUser())))
            <!-- Client / User Menu -->
            <a href="{{ route('user.modules.index') }}" class="nav-link d-flex align-items-center gap-3 px-3 py-3 rounded-3 mb-2 transition-all sidebar-link">
                <i class="fas fa-book"></i>
                <span>Modules & Activities</span>
            </a>
            <a href="{{ route('user.bookings.index') }}" class="nav-link d-flex align-items-center gap-3 px-3 py-3 rounded-3 mb-2 transition-all sidebar-link">
                <i class="fas fa-calendar-alt"></i>
                <span>My Bookings</span>
            </a>
            <a href="{{ route('profile.edit') }}" class="nav-link d-flex align-items-center gap-3 px-3 py-3 rounded-3 mb-2 transition-all sidebar-link">
                <i class="fas fa-user"></i>
                <span>Profile</span>
            </a>
        @endif

        @endauth

    </nav>
    </div>

    @auth
    <!-- User Profile Section -->
    <div class="user-profile px-3 py-3 border-top border-secondary mt-auto">
        <div class="d-flex align-items-center gap-3">
            <div class="user-avatar bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                <i class="fas fa-user text-white"></i>
            </div>
            <div class="flex-grow-1">
                <div class="fw-semibold text-white small">
                    @if(auth()->user())
                        {{ auth()->user()->name ?? 'User' }}
                    @else
                        User
                    @endif
                </div>
                <div class="text-muted small">
                    @if(auth()->user() && auth()->user()->role)
                        {{ auth()->user()->role->name ?? 'User' }}
                    @else
                        User
                    @endif
                </div>
            </div>
            <a href="{{ route('logout') }}" class="btn btn-sm btn-outline-light" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt"></i>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    </div>
    @endauth

    @guest
    <!-- Guest Section -->
    <div class="user-profile px-3 py-3 border-top border-secondary mt-auto">
        <div class="text-center">
            <a href="{{ route('login') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-sign-in-alt me-2"></i>Login
            </a>
        </div>
    </div>
    @endguest
</div>

<style>
/* Modern Sidebar Styles */
.sidebar-modern {
    backdrop-filter: blur(10px);
    border-right: 1px solid rgba(255,255,255,0.1);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.sidebar-modern:hover {
    box-shadow: 4px 0 20px rgba(0,0,0,0.3);
}

.brand-section {
    background: linear-gradient(135deg, rgba(13, 110, 253, 0.1), rgba(102, 16, 242, 0.1));
    border-bottom: 1px solid rgba(255,255,255,0.1);
}

.brand-icon {
    animation: pulse 2s infinite;
}

.sidebar-nav {
    scrollbar-width: thin;
    scrollbar-color: rgba(255,255,255,0.3) transparent;
}

.sidebar-nav::-webkit-scrollbar {
    width: 6px;
}

.sidebar-nav::-webkit-scrollbar-track {
    background: transparent;
}

.sidebar-nav::-webkit-scrollbar-thumb {
    background: rgba(255,255,255,0.3);
    border-radius: 3px;
}

.sidebar-nav::-webkit-scrollbar-thumb:hover {
    background: rgba(255,255,255,0.5);
}

.sidebar-link {
    color: rgba(255,255,255,0.8) !important;
    border-radius: 8px;
    margin-bottom: 4px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.sidebar-link::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
    transition: left 0.5s;
}

.sidebar-link:hover::before {
    left: 100%;
}

.sidebar-link:hover {
    background: rgba(255,255,255,0.1) !important;
    color: white !important;
    transform: translateX(4px);
}

.sidebar-link.active {
    background: linear-gradient(135deg, #0d6efd, #6610f2) !important;
    color: white !important;
    box-shadow: 0 4px 15px rgba(13, 110, 253, 0.3);
}

.sidebar-accordion-btn {
    color: rgba(255,255,255,0.8) !important;
    background: transparent !important;
    border: none !important;
    padding: 0.75rem 1rem !important;
    border-radius: 8px !important;
    transition: all 0.3s ease !important;
}

.sidebar-accordion-btn:hover {
    background: rgba(255,255,255,0.1) !important;
    color: white !important;
}

.sidebar-accordion-btn:not(.collapsed) {
    background: rgba(255,255,255,0.1) !important;
    color: white !important;
}

.sidebar-submenu-link {
    color: rgba(255,255,255,0.7) !important;
    padding: 0.5rem 1rem !important;
    margin: 2px 0 !important;
    border-radius: 6px !important;
    transition: all 0.3s ease !important;
    font-size: 0.9rem !important;
}

.sidebar-submenu-link:hover {
    background: rgba(255,255,255,0.1) !important;
    color: white !important;
    transform: translateX(4px);
}

.user-profile {
    background: rgba(0,0,0,0.2);
    border-top: 1px solid rgba(255,255,255,0.1);
}

.user-avatar {
    animation: bounceIn 0.6s ease-out;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}

@keyframes bounceIn {
    0% { transform: scale(0.3); opacity: 0; }
    50% { transform: scale(1.05); }
    70% { transform: scale(0.9); }
    100% { transform: scale(1); opacity: 1; }
}

/* Accordion Animation */
.accordion-button::after {
    filter: invert(1);
    transition: transform 0.3s ease;
}

.accordion-button:not(.collapsed)::after {
    transform: rotate(90deg);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .sidebar-modern {
        width: 100% !important;
        position: relative !important;
    }
}
</style>

<script>
// Initialize Bootstrap accordion
document.addEventListener('DOMContentLoaded', function() {
    // Add smooth scrolling to sidebar navigation
    const sidebarLinks = document.querySelectorAll('.sidebar-nav .nav-link');
    sidebarLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            // Add ripple effect
            const ripple = document.createElement('span');
            ripple.style.position = 'absolute';
            ripple.style.borderRadius = '50%';
            ripple.style.background = 'rgba(255,255,255,0.3)';
            ripple.style.transform = 'scale(0)';
            ripple.style.animation = 'ripple 0.6s linear';
            ripple.style.left = '50%';
            ripple.style.top = '50%';
            ripple.style.width = '20px';
            ripple.style.height = '20px';
            ripple.style.marginLeft = '-10px';
            ripple.style.marginTop = '-10px';
            this.appendChild(ripple);

            setTimeout(() => ripple.remove(), 600);
        });
    });
});

// Add ripple animation
const style = document.createElement('style');
style.textContent = `
    @keyframes ripple {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);
</script>

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
