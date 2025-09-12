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

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array<string, class-string|string>
     */
    protected $routeMiddleware = [
        // ...existing middleware...
        'role' => \App\Http\Middleware\CheckRole::class,
    ];

    protected $middlewareAliases = [
        // ...existing middleware...
        'role' => \App\Http\Middleware\RoleMiddleware::class,
    ];
}
