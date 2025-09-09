<?php

namespace App\Providers;

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

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
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
    }
}
