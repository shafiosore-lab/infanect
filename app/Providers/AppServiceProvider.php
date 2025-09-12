<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use App\Models\User;
use App\Models\Provider;
use App\Models\Service;
use App\Models\Booking;
use App\Models\Review;
use BackedEnum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind common short controller names to their fully-qualified classes to support legacy route strings
        $this->app->bind('DashboardController', \App\Http\Controllers\DashboardController::class);
        $this->app->bind('ProviderDashboardController', \App\Http\Controllers\Provider\DashboardController::class);
        $this->app->bind('PaymentController', \App\Http\Controllers\PaymentController::class);
        $this->app->bind('AiChatController', \App\Http\Controllers\AiChatController::class);
        $this->app->bind('TrainingModuleController', \App\Http\Controllers\TrainingModuleController::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Fix "Specified key was too long" for older MySQL versions
        Schema::defaultStringLength(191);

        // Use Bootstrap pagination views (if using Bootstrap frontend)
        Paginator::useBootstrap();

        // Force HTTPS in production
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // Share global stats & settings with all views
        View::composer('*', function ($view) {
            try {
                $globalStats = cache()->remember('global_stats', 300, function () {
                    return [
                        'users_count'     => User::count(),
                        'providers_count' => Provider::count(),
                        'services_count'  => Service::count(),
                        'bookings_count'  => Booking::count(),
                        'reviews_count'   => Review::count(),
                    ];
                });

                $view->with('globalStats', $globalStats);
            } catch (\Throwable $e) {
                // In case DB is not ready during migrations/seeding
                $view->with('globalStats', [
                    'users_count'     => 0,
                    'providers_count' => 0,
                    'services_count'  => 0,
                    'bookings_count'  => 0,
                    'reviews_count'   => 0,
                ]);
            }
        });

        Blade::directive('stringify', function ($expression) {
            return "<?php echo is_array($expression) ? implode(' ', $expression) : $expression; ?>";
        });

        Blade::directive('safe', function ($expression) {
            return "<?php echo is_array($expression) ? e(implode(' ', $expression)) : e($expression ?? ''); ?>";
        });

        // Provide class_alias fallbacks for unqualified controller names
        if (!class_exists('DashboardController') && class_exists(\App\Http\Controllers\DashboardController::class)) {
            class_alias(\App\Http\Controllers\DashboardController::class, 'DashboardController');
        }
        if (!class_exists('ProviderDashboardController') && class_exists(\App\Http\Controllers\Provider\DashboardController::class)) {
            class_alias(\App\Http\Controllers\Provider\DashboardController::class, 'ProviderDashboardController');
        }
        if (!class_exists('AiChatController') && class_exists(\App\Http\Controllers\AiChatController::class)) {
            class_alias(\App\Http\Controllers\AiChatController::class, 'AiChatController');
        }
        if (!class_exists('TrainingModuleController') && class_exists(\App\Http\Controllers\TrainingModuleController::class)) {
            class_alias(\App\Http\Controllers\TrainingModuleController::class, 'TrainingModuleController');
        }

        // Global composer: sanitize arrays and simple values only. Skip objects (exceptions, models) to avoid breaking views that expect objects.
        View::composer('*', function ($view) {
            $data = $view->getData();
            $sanitized = [];

            foreach ($data as $key => $value) {
                // Skip sanitization for objects so exception and model objects remain intact for views
                if (is_object($value)) {
                    $sanitized[$key] = $value;
                    continue;
                }

                // Only sanitize arrays and scalar/primitive types
                $sanitized[$key] = $this->sanitizeValue($value);
            }

            if (!empty($sanitized)) {
                $view->with($sanitized);
            }
        });

        // Prevent legacy view route(...) calls from throwing by returning a safe '#'
        try {
            $url = $this->app['url'];
            $resolver = function ($name, $parameters, $absolute) {
                return '#';
            };

            $ref = new \ReflectionClass($url);
            if ($ref->hasProperty('missingNamedRouteResolver')) {
                $prop = $ref->getProperty('missingNamedRouteResolver');
                $prop->setAccessible(true);
                $prop->setValue($url, $resolver);
            } else {
                // best-effort fallback
                $url->missingNamedRouteResolver = $resolver;
            }
        } catch (\Throwable $e) {
            // ignore failures
        }
    }

    /**
     * Convert value to a safe scalar/string for Blade.
     * Only handles scalars and arrays; objects are returned unchanged.
     */
    protected function sanitizeValue(mixed $value): mixed
    {
        if (is_null($value)) return '';

        if ($value instanceof BackedEnum) return (string) $value->value;

        if (is_string($value) || is_numeric($value)) return $value;

        if (is_bool($value)) return $value ? '1' : '0';

        if (is_array($value)) {
            return implode(' ', array_map(fn($v) => $this->sanitizeValue($v), $value));
        }

        // For objects, return as-is to avoid breaking views that expect specific object types
        if (is_object($value)) {
            return $value;
        }

        return (string) $value;
    }
}
