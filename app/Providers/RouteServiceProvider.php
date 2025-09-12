<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/dashboard';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            $this->mapApiRoutes();
            $this->mapWebRoutes();
            $this->mapClientRoutes();
            $this->mapProviderRoutes();
            $this->mapAdminRoutes();
        });
    }

    /**
     * Define the routes for the application.
     */
    public function routes($callback): void
    {
        $this->app->booted($callback);
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     */
    protected function mapWebRoutes(): void
    {
        Route::middleware('web')
            ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     */
    protected function mapApiRoutes(): void
    {
        Route::prefix('api')
            ->middleware('api')
            ->as('api.')
            ->group(base_path('routes/api.php'));
    }

    /**
     * Client-facing routes.
     */
    protected function mapClientRoutes(): void
    {
        if (file_exists(base_path('routes/client.php'))) {
            Route::prefix('client')
                ->middleware(['web', 'auth', 'role:client,employee'])
                ->as('client.')
                ->group(base_path('routes/client.php'));
        }
    }

    /**
     * Provider routes.
     */
    protected function mapProviderRoutes(): void
    {
        if (file_exists(base_path('routes/provider.php'))) {
            Route::prefix('provider')
                ->middleware(['web', 'auth', 'role:provider,provider-professional,provider-bonding'])
                ->as('provider.')
                ->group(base_path('routes/provider.php'));
        }
    }

    /**
     * Admin routes.
     */
    protected function mapAdminRoutes(): void
    {
        if (file_exists(base_path('routes/admin.php'))) {
            Route::prefix('admin')
                ->middleware(['web', 'auth', 'role:admin,super-admin'])
                ->as('admin.')
                ->group(base_path('routes/admin.php'));
        }
    }
}
