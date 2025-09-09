<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
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
            'admin' => 'super-admin',
            'manager' => 'super-admin',
            'provider' => 'service-provider', // This might need to be more specific
        ];

        $mappedRoles = array_map(function($role) use ($roleMappings) {
            return $roleMappings[$role] ?? $role;
        }, $roles);

        if (in_array($user->role->slug, $mappedRoles)) {
            return $next($request);
        }

        abort(403, 'Unauthorized access.');
    }
}
