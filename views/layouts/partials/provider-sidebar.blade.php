{{-- Provider sidebar links --}}
<div class="mt-4">
    <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">Provider</span>
    <ul class="ml-2 mt-2 space-y-1">
        <li><a href="{{ route('provider.dashboard') }}" class="text-primary hover:underline">Overview</a></li>
        <li><a href="{{ route('provider.notifications') }}" class="text-primary hover:underline">Notifications</a></li>
        <li><a href="{{ route('messages.index') }}" class="text-primary hover:underline">Messages</a></li>
        <li><a href="{{ route('provider.financials') }}" class="text-primary hover:underline">Financials</a></li>
        <li><a href="{{ route('mentalhealth.index') }}" class="text-primary hover:underline">Mental Health Modules</a></li>
        @php
            $providerTotalSpent = 0;
            if (Schema::hasTable('transactions') && Schema::hasTable('providers') && auth()->check()) {
                $prov = DB::table('providers')->where('user_id', auth()->id())->first();
                if ($prov) {
                    $providerTotalSpent = DB::table('transactions')->where('provider_id', $prov->id)->sum('amount');
                }
            }
        @endphp
        <li class="text-xs text-gray-400">Total handled: {{ number_format($providerTotalSpent,2) }}</li>
    </ul>
</div>
