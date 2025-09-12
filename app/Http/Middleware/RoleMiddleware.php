<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string[]  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Get user's role
        $userRole = $user->role_id ?? ($user->role->slug ?? 'client');

        // Check if user has any of the required roles
        if (in_array($userRole, $roles)) {
            return $next($request);
        }

        // If user doesn't have required role, redirect to their appropriate dashboard
        return redirect()->route('dashboard')->with('error', 'Access denied. You don\'t have permission to access that area.');
    }
}
            'user_id' => $user->id,
            'user_role' => $userRole,
            'required_roles' => $allowedRoles,
            'allowed' => $userRole && in_array($userRole, $allowedRoles)
        ]);

        if (!$userRole || !in_array($userRole, $allowedRoles)) {
            abort(403, 'Unauthorized access');
        }

        return $next($request);
    }
}
