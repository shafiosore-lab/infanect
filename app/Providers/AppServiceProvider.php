<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register services for metric calculations
        $this->app->singleton(\App\Services\DashboardMetricsService::class);
        $this->app->singleton(\App\Services\EngagementService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force HTTPS in production
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // Fix MySQL string length issue for older versions
        Schema::defaultStringLength(191);

        // Register custom Blade directives
        $this->registerBladeDirectives();

        // Register custom Blade component paths
        Blade::componentNamespace('App\\View\\Components', 'infanect');

        // After Laravel 12, clear compiled views in development
        if ($this->app->environment('local')) {
            if (file_exists(storage_path('framework/views'))) {
                array_map('unlink', glob(storage_path('framework/views/*')));
            }
        }

        // Share global stats with specific views
        $this->shareGlobalStats();
    }

    /**
     * Register custom Blade directives
     */
    private function registerBladeDirectives(): void
    {
        // Safely convert any value to string
        Blade::directive('stringify', function ($expression) {
            return "<?php echo e(is_string($expression) ? $expression : (is_null($expression) ? '' : (method_exists($expression, '__toString') ? (string)$expression : json_encode($expression)))); ?>";
        });

        // Safe output that handles null/undefined/objects
        Blade::directive('safe', function ($expression) {
            return "<?php echo e($expression ?? ''); ?>";
        });
    }

    /**
     * Share global stats with views
     */
    private function shareGlobalStats(): void
    {
        // Only compose stats for layout and dashboard views to improve performance
        View::composer(['layouts.*', 'dashboards.*', 'admin.*', 'components.widgets.*'], function ($view) {
            // Cache stats for 5 minutes to reduce DB load
            $globalStats = Cache::remember('global_stats', 5 * 60, function () {
                // Base counts - using try/catch for schema resilience during migrations
                $stats = $this->getBaseCounts();

                // Add engagement data
                $stats = array_merge($stats, $this->getEngagementCounts());

                // Activity metrics
                $stats = array_merge($stats, $this->getActivityMetrics());

                // Add active engagements for sidebars/widgets
                $stats['active_engagements'] = $this->getActiveEngagements();

                // Add recent mood submissions
                $stats['recent_mood_submissions'] = $this->getRecentMoodSubmissions();

                return $stats;
            });

            $view->with('global_stats', $globalStats);
        });
    }

    /**
     * Get base entity counts with error handling
     */
    private function getBaseCounts(): array
    {
        $stats = [
            'users_count' => 0,
            'providers_count' => 0,
            'services_count' => 0,
            'bookings_count' => 0,
            'reviews_count' => 0,
        ];

        try {
            if (Schema::hasTable('users')) {
                $stats['users_count'] = DB::table('users')->count();
            }
        } catch (\Exception $e) {
            // Table might not exist during migrations
        }

        try {
            if (Schema::hasTable('providers')) {
                $stats['providers_count'] = DB::table('providers')
                    ->whereNull('deleted_at')
                    ->count();
            }
        } catch (\Exception $e) {
            // Table might not exist during migrations
        }

        try {
            if (Schema::hasTable('services')) {
                $stats['services_count'] = DB::table('services')->count();
            }
        } catch (\Exception $e) {
            // Table might not exist during migrations
        }

        try {
            if (Schema::hasTable('bookings')) {
                $stats['bookings_count'] = DB::table('bookings')
                    ->whereNull('deleted_at')
                    ->count();
            }
        } catch (\Exception $e) {
            // Table might not exist during migrations
        }

        try {
            if (Schema::hasTable('reviews')) {
                $stats['reviews_count'] = DB::table('reviews')->count();
            }
        } catch (\Exception $e) {
            // Table might not exist during migrations
        }

        return $stats;
    }

    /**
     * Get engagement-related counts
     */
    private function getEngagementCounts(): array
    {
        $stats = [
            'engagements_count' => 0,
            'provider_engagements_count' => 0,
            'active_engagements_count' => 0,
        ];

        try {
            if (Schema::hasTable('engagements')) {
                $stats['engagements_count'] = DB::table('engagements')->count();

                $stats['provider_engagements_count'] = DB::table('engagements')
                    ->whereNotNull('provider_id')
                    ->count();

                $stats['active_engagements_count'] = DB::table('engagements')
                    ->where('status', 'active')
                    ->count();
            }
        } catch (\Exception $e) {
            // Table might not exist yet
        }

        return $stats;
    }

    /**
     * Get activity metrics
     */
    private function getActivityMetrics(): array
    {
        $stats = [
            'total_activity_participants' => 0,
            'avg_attendance_rate' => 0,
        ];

        try {
            if (Schema::hasTable('activities') && Schema::hasTable('bookings')) {
                // Total participants in activities
                $stats['total_activity_participants'] = DB::table('bookings')
                    ->whereNotNull('activity_id')
                    ->count();

                // Calculate attendance rate
                $totalBookings = DB::table('bookings')->count() ?: 1; // Avoid division by zero
                $attendedBookings = DB::table('bookings')
                    ->where('status', 'completed')
                    ->count();

                $stats['avg_attendance_rate'] = round(($attendedBookings / $totalBookings) * 100, 1);
            }
        } catch (\Exception $e) {
            // Tables might not exist yet
        }

        return $stats;
    }

    /**
     * Get active engagements for sidebar widgets
     */
    private function getActiveEngagements(): array
    {
        $engagements = [];

        try {
            if (Schema::hasTable('engagements')) {
                $engagements = DB::table('engagements')
                    ->select([
                        'id', 'title', 'description', 'start_date', 'end_date',
                        'status', 'provider_id', 'type', 'image_url'
                    ])
                    ->where('status', 'active')
                    ->whereDate('end_date', '>=', now())
                    ->orderBy('start_date')
                    ->limit(5)
                    ->get()
                    ->toArray();
            }
        } catch (\Exception $e) {
            // Table might not exist yet
        }

        return $engagements;
    }

    /**
     * Get recent mood submissions for insights
     */
    private function getRecentMoodSubmissions(): array
    {
        $submissions = [];

        try {
            if (Schema::hasTable('mood_submissions')) {
                $submissions = DB::table('mood_submissions')
                    ->join('users', 'mood_submissions.user_id', '=', 'users.id')
                    ->select([
                        'mood_submissions.id',
                        'mood_submissions.mood',
                        'mood_submissions.mood_score',
                        'mood_submissions.created_at',
                        'users.name as user_name'
                    ])
                    ->orderBy('mood_submissions.created_at', 'desc')
                    ->limit(10)
                    ->get()
                    ->toArray();
            }
        } catch (\Exception $e) {
            // Table might not exist yet
        }

        return $submissions;
    }
}
