@php
/**
 * Dynamic Role-Based Engagement Sidebar
 * Supports: admin, super-admin, provider-professional, provider-bonding
 */
$user = auth()->user();

// Get user role safely
$userRole = '';
if ($user) {
    if (method_exists($user, 'hasRole')) {
        // Using a role system with hasRole method
        foreach (['super-admin', 'admin', 'provider-professional', 'provider-bonding'] as $role) {
            if ($user->hasRole($role)) {
                $userRole = $role;
                break;
            }
        }
    } else {
        // Using role_id or role property
        $userRole = $user->role ?? '';
        if (is_numeric($user->role_id ?? null)) {
            $roleMap = [
                7 => 'admin',
                8 => 'super-admin',
                4 => 'provider-professional',
                5 => 'provider-bonding',
                3 => 'provider',
            ];
            $userRole = $roleMap[$user->role_id] ?? '';
        }
    }
}

// Helper function to safely check routes
$routeExists = function($name) {
    return app('router')->has($name);
};

// Helper function to check if route is active
$isRouteActive = function($route) {
    if (!app('router')->has($route)) return false;
    $currentRoute = request()->route()->getName();
    return $currentRoute === $route ||
           str_starts_with($currentRoute, $route . '.') ||
           (stripos($currentRoute, $route) !== false && in_array($route, ['messages', 'financials', 'activities']));
};

// Define role-based link configurations
$sidebarLinks = [
    'super-admin' => [
        [
            'title' => 'Messages',
            'icon' => 'fas fa-envelope',
            'dropdown' => [
                ['title' => 'Inbox', 'route' => 'messages.inbox'],
                ['title' => 'Sent', 'route' => 'messages.sent'],
                ['title' => 'Compose', 'route' => 'messages.create'],
                ['title' => 'Logs & Reports', 'route' => 'messages.logs'],
            ]
        ],
        [
            'title' => 'Financials',
            'icon' => 'fas fa-dollar-sign',
            'dropdown' => [
                ['title' => 'Financial Dashboard', 'route' => 'financials.dashboard'],
                ['title' => 'Transactions', 'route' => 'transactions.index'],
                ['title' => 'Expenses', 'route' => 'expenses.index'],
            ]
        ],
        ['title' => 'Engagement Insights', 'route' => 'admin.engagement.insights', 'icon' => 'fas fa-chart-bar'],
        ['title' => 'Mental Health Modules', 'route' => 'mentalhealth.index', 'icon' => 'fas fa-brain'],
        ['title' => 'User Analytics', 'route' => 'admin.analytics', 'icon' => 'fas fa-chart-line'],
    ],
    'admin' => [
        [
            'title' => 'Messages',
            'icon' => 'fas fa-envelope',
            'dropdown' => [
                ['title' => 'Inbox', 'route' => 'messages.inbox'],
                ['title' => 'Sent', 'route' => 'messages.sent'],
                ['title' => 'Compose', 'route' => 'messages.create'],
                ['title' => 'Logs & Reports', 'route' => 'messages.logs'],
            ]
        ],
        [
            'title' => 'Financials',
            'icon' => 'fas fa-dollar-sign',
            'dropdown' => [
                ['title' => 'Financial Dashboard', 'route' => 'financials.dashboard'],
                ['title' => 'Transactions', 'route' => 'transactions.index'],
                ['title' => 'Expenses', 'route' => 'expenses.index'],
            ]
        ],
        ['title' => 'Engagement Insights', 'route' => 'admin.engagement.insights', 'icon' => 'fas fa-chart-bar'],
        ['title' => 'Mental Health Modules', 'route' => 'mentalhealth.index', 'icon' => 'fas fa-brain'],
    ],
    'provider-bonding' => [
        ['title' => 'My Notifications', 'route' => 'provider.notifications', 'icon' => 'fas fa-bell'],
        ['title' => 'Messages', 'route' => 'messages.index', 'icon' => 'fas fa-envelope'],
        ['title' => 'Manage Activities', 'route' => 'activities.index', 'icon' => 'fas fa-calendar-alt'],
        ['title' => 'Activity Calendar', 'route' => 'activities.calendar', 'icon' => 'fas fa-calendar-week'],
        ['title' => 'Engagement Insights', 'route' => 'community.analytics', 'icon' => 'fas fa-chart-bar'],
        ['title' => 'Family Communications', 'route' => 'provider.communications', 'icon' => 'fas fa-comment-alt'],
    ],
    'provider-professional' => [
        ['title' => 'My Notifications', 'route' => 'provider.notifications', 'icon' => 'fas fa-bell'],
        ['title' => 'Messages', 'route' => 'messages.index', 'icon' => 'fas fa-envelope'],
        [
            'title' => 'Financials',
            'icon' => 'fas fa-dollar-sign',
            'dropdown' => [
                ['title' => 'Payouts', 'route' => 'provider.financials'],
                ['title' => 'Transactions', 'route' => 'transactions.index'],
                ['title' => 'Invoices', 'route' => 'invoices.index'],
            ]
        ],
        ['title' => 'My Clients', 'route' => 'clients.index', 'icon' => 'fas fa-users'],
        ['title' => 'Mental Health Resources', 'route' => 'mentalhealth.index', 'icon' => 'fas fa-brain'],
        ['title' => 'Professional Tools', 'route' => 'provider.tools', 'icon' => 'fas fa-toolbox'],
    ],
];
@endphp

<div class="sidebar-section {{ $sectionClass ?? 'mt-4' }}">
    <span class="text-xs font-semibold uppercase tracking-wider text-gray-400 px-4 py-2 block">Engagement</span>
    <ul class="ml-2 mt-2 space-y-1">
        @foreach($sidebarLinks[$userRole] ?? [] as $link)
            @if(isset($link['dropdown']))
                <li class="dropdown relative group">
                    <button type="button"
                            class="text-primary hover:underline flex items-center justify-between w-full px-4 py-2 rounded-lg {{ collect($link['dropdown'])->contains(function($item) use ($isRouteActive) { return $isRouteActive($item['route']); }) ? 'bg-gray-100 font-medium' : '' }}"
                            aria-haspopup="true"
                            onclick="toggleDropdown(this)">
                        <span>
                            @if(isset($link['icon']))<i class="{{ $link['icon'] }} mr-2"></i>@endif
                            {{ $link['title'] }}
                        </span>
                        <svg class="w-4 h-4 ml-2 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <ul class="dropdown-menu ml-4 mt-1 hidden md:group-hover:block py-1 shadow-sm bg-white border border-gray-100 rounded-lg z-10" aria-label="submenu">
                        @foreach($link['dropdown'] as $subLink)
                            @if($routeExists($subLink['route']))
                                <li>
                                    <a href="{{ route($subLink['route']) }}"
                                       class="text-primary block px-4 py-2 hover:bg-gray-100 rounded-lg {{ $isRouteActive($subLink['route']) ? 'bg-gray-50 font-medium' : '' }}">
                                        {{ $subLink['title'] }}
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </li>
            @else
                @if($routeExists($link['route']))
                    <li>
                        <a href="{{ route($link['route']) }}"
                           class="text-primary hover:bg-gray-100 block px-4 py-2 rounded-lg {{ $isRouteActive($link['route']) ? 'bg-gray-100 font-medium' : '' }}">
                            @if(isset($link['icon']))<i class="{{ $link['icon'] }} mr-2"></i>@endif
                            {{ $link['title'] }}
                        </a>
                    </li>
                @endif
            @endif
        @endforeach

        @if(empty($sidebarLinks[$userRole] ?? []))
            <li class="px-4 py-2 text-sm text-gray-500">No engagement options available for your role.</li>
        @endif
    </ul>
</div>

@once
@push('scripts')
<script>
// Toggle dropdown on mobile/desktop
function toggleDropdown(button) {
    // Get dropdown menu
    const dropdown = button.nextElementSibling;

    // Toggle active class on button
    button.classList.toggle('active');

    // Toggle dropdown visibility
    dropdown.classList.toggle('hidden');

    // Toggle icon rotation
    const icon = button.querySelector('svg');
    if (icon) {
        icon.classList.toggle('rotate-180');
    }
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(event) {
    if (!event.target.closest('.dropdown')) {
        // Hide all dropdown menus
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            menu.classList.add('hidden');
        });

        // Reset all dropdown buttons
        document.querySelectorAll('.dropdown button').forEach(button => {
            button.classList.remove('active');
            const icon = button.querySelector('svg');
            if (icon) icon.classList.remove('rotate-180');
        });
    }
});

// Handle mobile menu behavior
document.addEventListener('DOMContentLoaded', function() {
    // Only apply specific behavior for mobile
    const mobileDropdowns = document.querySelectorAll('.dropdown button');

    // Set initial state of dropdowns based on active routes
    mobileDropdowns.forEach(button => {
        // If button or any child is marked as active
        if (button.classList.contains('bg-gray-100') || button.nextElementSibling.querySelector('.bg-gray-50')) {
            // Auto-expand dropdown on mobile only
            if (window.innerWidth < 768) {
                toggleDropdown(button);
            }
        }
    });
});
</script>
@endpush
@endonce
