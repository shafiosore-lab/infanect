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
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        if (!$user->is_active) {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Your account is inactive.');
        }

        // Map legacy role names to new ones for backward compatibility
        $roleMappings = [
            'admin'    => 'super-admin',
            'manager'  => 'super-admin',
            'provider' => 'service-provider',
            'employee' => 'staff', // Example additional mapping
        ];

        // Map requested roles
        $mappedRoles = array_map(fn($role) => $roleMappings[$role] ?? $role, $roles);

        // If user has multiple roles, check intersection
        $userRoles = is_array($user->role) ? $user->role : [$user->role->slug];

        if (count(array_intersect($mappedRoles, $userRoles)) > 0) {
            return $next($request);
        }

        abort(403, 'Unauthorized access.');
    }
}
