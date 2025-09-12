<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ActivityController as ApiActivity;
use App\Http\Controllers\Api\ProviderController as ApiProvider;
use App\Http\Controllers\Api\ModuleController as ApiModule;
use App\Http\Controllers\Api\BookingController as ApiBooking;
use App\Http\Controllers\Api\RecommendationController;
use App\Http\Controllers\Api\NotificationWebhookController;

Route::prefix('v1')->group(function () {
    Route::get('activities', [ApiActivity::class,'index']);
    Route::get('activities/{activity}', [ApiActivity::class,'show']);

    Route::get('providers', [ApiProvider::class,'index']);
    Route::get('providers/{provider}', [ApiProvider::class,'show']);

    Route::get('modules', [ApiModule::class,'index']);
    Route::get('modules/{module}', [ApiModule::class,'show']);

    Route::post('bookings', [ApiBooking::class,'store'])->middleware('auth:sanctum');
    Route::post('webhook/stripe', [\App\Http\Controllers\Api\WebhookController::class,'stripe']); // payments
});


Route::middleware('auth:sanctum')->get('/recommendations', [RecommendationController::class, 'index']);

// Webhook endpoint for external notification providers (Twilio mock)
Route::post('/webhooks/notifications', [NotificationWebhookController::class, 'handle']);


require __DIR__.'/admin.php';
Auth::routes(); // if using default auth
Route::get('/', fn() => redirect()->route('admin.dashboard'));
