<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\ActivityController as ApiActivity;
use App\Http\Controllers\Api\ProviderController as ApiProvider;
use App\Http\Controllers\Api\ModuleController as ApiModule;
use App\Http\Controllers\Api\ServiceController as ApiService;
use App\Http\Controllers\Api\BookingController as ApiBooking;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\RecommendationController;
use App\Http\Controllers\Api\NotificationWebhookController;
use App\Http\Controllers\Api\WebhookController;

/*
|--------------------------------------------------------------------------
| API Routes - AJAX & Mobile App
|--------------------------------------------------------------------------
*/

// Authenticated API (Laravel Sanctum)
Route::middleware('auth:sanctum')->prefix('api')->name('api.')->group(function () {
    // Search endpoints
    Route::get('activities/search', [ApiActivity::class, 'search'])->name('activities.search');
    Route::get('providers/search', [ApiProvider::class, 'search'])->name('providers.search');
    Route::get('services/search', [ApiService::class, 'search'])->name('services.search');

    // Booking management
    Route::post('bookings/{booking}/cancel', [ApiBooking::class, 'cancel'])->name('bookings.cancel');
    Route::post('bookings/{booking}/reschedule', [ApiBooking::class, 'reschedule'])->name('bookings.reschedule');

    // Notifications
    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');

    // Personalized recommendations
    Route::get('recommendations', [RecommendationController::class, 'index'])->name('recommendations.index');
});

/*
|--------------------------------------------------------------------------
| Public API v1 (No Auth Required)
|--------------------------------------------------------------------------
*/
Route::prefix('v1')->group(function () {
    // Activities
    Route::get('activities', [ApiActivity::class, 'index']);
    Route::get('activities/{activity}', [ApiActivity::class, 'show']);

    // Providers
    Route::get('providers', [ApiProvider::class, 'index']);
    Route::get('providers/{provider}', [ApiProvider::class, 'show']);

    // Modules
    Route::get('modules', [ApiModule::class, 'index']);
    Route::get('modules/{module}', [ApiModule::class, 'show']);

    // Bookings (authenticated with Sanctum)
    Route::post('bookings', [ApiBooking::class, 'store'])->middleware('auth:sanctum');

    // Payment webhook (Stripe)
    Route::post('webhook/stripe', [WebhookController::class, 'stripe']);
});

/*
|--------------------------------------------------------------------------
| Webhook endpoints (External services)
|--------------------------------------------------------------------------
*/
Route::post('/webhooks/notifications', [NotificationWebhookController::class, 'handle']);

/*
|--------------------------------------------------------------------------
| Auth & Default Redirect
|--------------------------------------------------------------------------
*/
require __DIR__.'/admin.php';

Auth::routes(); // If using default Laravel auth scaffolding

Route::get('/', fn () => redirect()->route('admin.analytics.index'));
