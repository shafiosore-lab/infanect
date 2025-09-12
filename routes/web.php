<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClientDashboardController;
use App\Http\Controllers\ProfessionalProviderDashboardController;
use App\Http\Controllers\BondingProviderDashboardController;
use App\Http\Controllers\SuperAdminDashboardController;
use App\Http\Controllers\MoodController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\AIController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserBookingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Main dashboard route - intelligent routing
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Role-specific dashboards
    Route::get('/dashboard/client', [ClientDashboardController::class, 'index'])->name('dashboard.client');
    Route::get('/dashboard/provider-professional', [ProfessionalProviderDashboardController::class, 'index'])->name('dashboard.provider-professional');
    Route::get('/dashboard/provider-bonding', [BondingProviderDashboardController::class, 'index'])->name('dashboard.provider-bonding');
    Route::get('/dashboard/super-admin', [SuperAdminDashboardController::class, 'index'])->name('dashboard.super-admin');

    // Dashboard API endpoints
    Route::prefix('dashboard/api')->name('dashboard.')->group(function () {
        Route::get('wellness', [DashboardController::class, 'wellness'])->name('wellness');
        Route::get('weekly-engagement', [DashboardController::class, 'weeklyEngagement'])->name('weekly-engagement');
        Route::get('learning-progress', [DashboardController::class, 'learningProgress'])->name('learning-progress');
        Route::get('stats', [DashboardController::class, 'stats'])->name('stats');
        Route::get('tab/{tab}', [DashboardController::class, 'tabContent'])->name('tab');
        Route::get('search', [DashboardController::class, 'search'])->name('search');
    });
});

/*
|--------------------------------------------------------------------------
| Mood Tracking Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::post('/mood/submit', [MoodController::class, 'submit'])->name('mood.submit');
});

/*
|--------------------------------------------------------------------------
| Activity Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/activities', [ActivityController::class, 'index'])->name('activities.index');
    Route::get('/activities/{activity}', [ActivityController::class, 'show'])->name('activities.show');
    Route::post('/activities/{activity}/book', [ActivityController::class, 'book'])->name('activities.book');
});

/*
|--------------------------------------------------------------------------
| Service Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
    Route::get('/services/create', [ServiceController::class, 'create'])->name('services.create');
    Route::get('/services/{service}', [ServiceController::class, 'show'])->name('services.show');
    Route::post('/services', [ServiceController::class, 'store'])->name('services.store');
    Route::post('/services/{service}/book', [ServiceController::class, 'book'])->name('services.book');

    // Bookings
    Route::get('/bookings', [UserBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [UserBookingController::class, 'show'])->name('bookings.show');
});

/*
|--------------------------------------------------------------------------
| Provider Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/provider/register', [ProviderController::class, 'register'])->name('provider.register');
    Route::post('/provider/register', [ProviderController::class, 'store'])->name('provider.store');
    Route::get('/providers', [ProviderController::class, 'index'])->name('providers.index');
    Route::get('/providers/{provider}', [ProviderController::class, 'show'])->name('providers.show');
});

/*
|--------------------------------------------------------------------------
| Training & AI Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('training', [TrainingController::class, 'index'])->name('training.index');
    Route::get('training/{module}', [TrainingController::class, 'show'])->name('training.show');
    Route::post('training/{module}/complete', [TrainingController::class, 'complete'])->name('training.complete');

    // AI Assistant Routes - Fixed naming
    Route::get('ai/chat', [AIController::class, 'chat'])->name('ai.chat');
    Route::post('ai/chat', [AIController::class, 'sendMessage'])->name('ai.send-message');
    Route::get('ai-chat', [AIController::class, 'index'])->name('ai-chat.index'); // Added missing route
});

/*
|--------------------------------------------------------------------------
| User Profile Management
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Fallback Route (handles 404s)
|--------------------------------------------------------------------------
*/
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});

/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin,super-admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('users', [\App\Http\Controllers\Admin\UserManagementController::class, 'index'])->name('users.index');
    Route::post('users/{id}/update-role', [\App\Http\Controllers\Admin\UserManagementController::class, 'updateRole'])->name('users.update-role');

    Route::resource('activities', \App\Http\Controllers\Admin\ActivityController::class);
    Route::resource('services', \App\Http\Controllers\ServiceController::class);
    Route::resource('modules', \App\Http\Controllers\ModuleController::class);
    Route::resource('categories', \App\Http\Controllers\CategoryController::class);
    Route::resource('roles', \App\Http\Controllers\RoleController::class);
    Route::resource('bookings', \App\Http\Controllers\Admin\AdminBookingController::class);

    Route::get('analytics', [\App\Http\Controllers\Admin\AnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/export/{type}', [\App\Http\Controllers\Admin\ReportController::class, 'export'])->name('reports.export');
});

/*
|--------------------------------------------------------------------------
| Legacy Modules
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('training-modules', [\App\Http\Controllers\TrainingModuleController::class, 'index'])->name('training-modules.index');
    Route::get('training-modules/{id}', [\App\Http\Controllers\TrainingModuleController::class, 'show'])->name('training-modules.show');

    Route::get('parenting-modules', [\App\Http\Controllers\ParentingModuleController::class, 'index'])->name('parenting-modules.index');
    Route::get('parenting-modules/{id}', [\App\Http\Controllers\ParentingModuleController::class, 'show'])->name('parenting-modules.show');
});

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('api')->name('api.')->group(function () {
    Route::get('activities/search', [\App\Http\Controllers\API\ActivityController::class, 'search'])->name('activities.search');
    Route::get('providers/search', [\App\Http\Controllers\API\ProviderController::class, 'search'])->name('providers.search');
    Route::get('services/search', [\App\Http\Controllers\API\ServiceController::class, 'search'])->name('services.search');

    Route::post('bookings/{booking}/cancel', [\App\Http\Controllers\API\BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::post('bookings/{booking}/reschedule', [\App\Http\Controllers\API\BookingController::class, 'reschedule'])->name('bookings.reschedule');

    Route::get('notifications', [\App\Http\Controllers\API\NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('notifications/{notification}/read', [\App\Http\Controllers\API\NotificationController::class, 'markAsRead'])->name('notifications.read');
});

/*
|--------------------------------------------------------------------------
| Fallback
|--------------------------------------------------------------------------
*/
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
