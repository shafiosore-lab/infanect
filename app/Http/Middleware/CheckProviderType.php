<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckProviderType
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $type): Response
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Check if user is a provider
        if ($user->role !== 'provider') {
            abort(403, 'Access denied. Provider role required.');
        }

        // Check provider type
        $userProviderType = $user->provider_type ?? $user->type ?? '';

        // Handle different type formats
        if ($type === 'professional') {
            $allowedTypes = ['professional', 'provider-professional'];
        } elseif ($type === 'bonding') {
            $allowedTypes = ['bonding', 'provider-bonding'];
        } else {
            $allowedTypes = [$type];
        }

        if (!in_array($userProviderType, $allowedTypes)) {
            abort(403, "Access denied. {$type} provider type required.");
        }

        return $next($request);
    }
}
