{{-- Engagement section: Admin & Provider links --}}
<div class="mt-4">
    <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">Engagement</span>
    <ul class="ml-2 mt-2 space-y-1">
        {{-- Admin links --}}
        @if(auth()->check() && (method_exists(auth()->user(), 'hasRole') ? auth()->user()->hasRole('super-admin') : auth()->user()->is_admin ?? false))
            <li class="relative group">
                <a href="#" class="text-primary hover:underline flex items-center justify-between">Messages
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </a>
                <ul class="ml-4 mt-1 hidden group-hover:block">
                    <li><a href="{{ route('messages.inbox') }}" class="text-primary hover:underline">Inbox</a></li>
                    <li><a href="{{ route('messages.sent') }}" class="text-primary hover:underline">Sent</a></li>
                    <li><a href="{{ route('messages.create') }}" class="text-primary hover:underline">Compose</a></li>
                    <li><a href="{{ route('messages.logs') }}" class="text-primary hover:underline">Logs & Reports</a></li>
                </ul>
            </li>

            <li class="relative group">
                <a href="#" class="text-primary hover:underline flex items-center justify-between">Financials
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </a>
                <ul class="ml-4 mt-1 hidden group-hover:block">
                    <li><a href="{{ route('financials.dashboard') }}" class="text-primary hover:underline">Financial Dashboard</a></li>
                    <li><a href="{{ route('transactions.index') }}" class="text-primary hover:underline">Transactions</a></li>
                    <li><a href="{{ route('expenses.index') }}" class="text-primary hover:underline">Expenses</a></li>
                </ul>
            </li>

            <li><a href="{{ route('admin.engagement.insights') }}" class="text-primary hover:underline">Engagement Insights</a></li>
            <li><a href="{{ route('mentalhealth.index') }}" class="text-primary hover:underline">Mental Health Modules</a></li>
        @endif

        {{-- Provider links --}}
        @if(auth()->check() && (method_exists(auth()->user(), 'hasRole') ? auth()->user()->hasRole('provider') : (auth()->user()->role ?? '') === 'provider'))
            <li><a href="{{ route('provider.notifications') }}" class="text-primary hover:underline">My Notifications</a></li>
            <li><a href="{{ route('messages.index') }}" class="text-primary hover:underline">Messages</a></li>
            <li class="relative group">
                <a href="#" class="text-primary hover:underline flex items-center justify-between">Financials
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </a>
                <ul class="ml-4 mt-1 hidden group-hover:block">
                    <li><a href="{{ route('provider.financials') }}" class="text-primary hover:underline">Payouts</a></li>
                    <li><a href="{{ route('transactions.index') }}" class="text-primary hover:underline">Transactions</a></li>
                </ul>
            </li>
            <li><a href="{{ route('admin.engagement.insights') }}" class="text-primary hover:underline">Engagement Insights</a></li>
            <li><a href="{{ route('mentalhealth.index') }}" class="text-primary hover:underline">Mental Health</a></li>
        @endif
    </ul>
</div>
