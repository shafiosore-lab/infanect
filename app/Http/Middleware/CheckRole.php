<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed ...$roles
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // For development/testing, allow admin users to access any dashboard
        $userRole = $user->role_id ?? ($user->role->slug ?? 'client');

        if (in_array($userRole, ['super-admin', 'admin'])) {
            return $next($request);
        }

        // Check if user has any of the required roles
        if (empty($roles) || in_array($userRole, $roles)) {
            return $next($request);
        }

        // If user doesn't have required role, redirect with a message
        return redirect()->route('dashboard')->with('warning', 'You don\'t have permission to access that area. Redirected to your dashboard.');
    }
        // If user has multiple roles, check intersection
        $userRoles = is_array($user->role) ? $user->role : [$user->role->slug];

        if (count(array_intersect($mappedRoles, $userRoles)) > 0) {
            return $next($request);
        }

        abort(403, 'Unauthorized access.');
    }
}
}
