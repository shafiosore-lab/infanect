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
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        $userRole = $user->role->slug ?? null;

        // Handle comma-separated roles in a single parameter
        $allowedRoles = [];
        foreach ($roles as $role) {
            if (str_contains($role, ',')) {
                $allowedRoles = array_merge($allowedRoles, explode(',', $role));
            } else {
                $allowedRoles[] = $role;
            }
        }

        \Log::info('RoleMiddleware check', [
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
