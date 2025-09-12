<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureProviderIsApproved
{
    public function handle(Request $request, Closure $next)
    {
        $provider = $request->user()?->provider;
        if (!$provider || $provider->status !== 'approved') {
            return redirect()->route('provider.onboarding')->with('warning', 'Your provider account is not approved yet.');
        }
        return $next($request);
    }
}
