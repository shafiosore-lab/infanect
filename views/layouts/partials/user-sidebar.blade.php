{{-- User sidebar links --}}
<div class="mt-4">
    <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">Account</span>
    <ul class="ml-2 mt-2 space-y-1">
        <li><a href="{{ route('user.dashboard') }}" class="text-primary hover:underline">Overview</a></li>
        <li><a href="{{ route('notifications.index') }}" class="text-primary hover:underline">Notifications</a></li>
        <li><a href="{{ route('mentalhealth.index') }}" class="text-primary hover:underline">Mental Health</a></li>
        @php
            $totalSpent = 0;
            if (Schema::hasTable('transactions') && Schema::hasTable('users') && auth()->check()) {
                $userId = auth()->id();
                // assume transactions linked via provider->user relationship; compute sum of bookings paid by user if available
                if (Schema::hasTable('bookings')) {
                    // sum of amounts for bookings where user_id matches (best-effort)
                    try {
                        $totalSpent = DB::table('transactions')
                            ->whereIn('provider_id', function($q) use ($userId) {
                                $q->select('id')->from('providers')->where('user_id', $userId);
                            })->sum('amount');
                    } catch (\Exception $e) {
                        $totalSpent = 0;
                    }
                }
            }
        @endphp
        <li class="text-xs text-gray-400">Total spent: {{ number_format($totalSpent,2) }}</li>
    </ul>
</div>
