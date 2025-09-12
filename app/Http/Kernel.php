<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $middleware = [
        // ...existing middleware ...
    ];

    protected $middlewareGroups = [
        'web' => [
            // ...existing middleware ...
            \App\Http\Middleware\AuditLogger::class,
            \App\Http\Middleware\SetLocale::class,
        ],

        'api' => [
            // ...existing middleware ...
        ],
    ];

    protected $routeMiddleware = [
        // ...existing middleware ...
        'role' => \App\Http\Middleware\RoleMiddleware::class,
        'ensure.provider.approved' => \App\Http\Middleware\EnsureProviderIsApproved::class,
    ];
}
