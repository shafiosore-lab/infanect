<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Home page
Route::get('/', function () {
    return view('welcome');
});

// Auth routes
require __DIR__.'/auth.php';

// Registration routes
Route::get('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'register']);

// Provider registration
Route::get('/provider/register', [\App\Http\Controllers\Auth\ProviderRegisterController::class, 'show'])->name('provider.register');
Route::post('/provider/register', [\App\Http\Controllers\Auth\ProviderRegisterController::class, 'register'])->name('provider.register.post');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');

    // User bookings
    Route::get('bookings', [\App\Http\Controllers\UserBookingController::class, 'index'])->name('user.bookings.index');
    Route::get('bookings/{id}', [\App\Http\Controllers\UserBookingController::class, 'show'])->name('user.bookings.show');

    // Start Learning
    Route::get('/start-learning', [\App\Http\Controllers\StartLearningController::class, 'index'])->name('start-learning.index');
    Route::post('/start-learning/play', [\App\Http\Controllers\StartLearningController::class, 'play'])->name('start-learning.play');
    Route::get('/start-learning/play/{externalId?}', [\App\Http\Controllers\StartLearningController::class, 'playDirect'])->name('start-learning.play.direct');

    // Reflections
    Route::post('/reflections', [\App\Http\Controllers\ReflectionController::class, 'store'])->name('reflections.store');

    // Mood submission
    Route::post('/mood/submit', [\App\Http\Controllers\MoodController::class, 'submit'])->name('mood.submit');

    // Saved lessons
    Route::post('/saved-lessons/save', [\App\Http\Controllers\SavedLessonController::class, 'save'])->name('saved-lessons.save');
    Route::post('/saved-lessons/delete', [\App\Http\Controllers\SavedLessonController::class, 'delete'])->name('saved-lessons.delete');
    Route::get('/saved-lessons/list', [\App\Http\Controllers\SavedLessonController::class, 'list'])->name('saved-lessons.list');

    // Training Routes
    Route::get('/training', [\App\Http\Controllers\TrainingController::class, 'index'])->name('training.index');
    Route::get('/training/module/{moduleId}', [\App\Http\Controllers\TrainingController::class, 'module'])->name('training.module');
});

/*
|--------------------------------------------------------------------------
| Role-specific Dashboard Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:super-admin'])->get('/dashboard/super-admin', [\App\Http\Controllers\SuperAdminDashboardController::class, 'index'])->name('dashboard.super-admin');
Route::middleware(['auth', 'role:provider-professional'])->get('/dashboard/provider-professional', [\App\Http\Controllers\ProfessionalProviderDashboardController::class, 'index'])->name('dashboard.provider-professional');
Route::middleware(['auth', 'role:provider-bonding'])->get('/dashboard/provider-bonding', [\App\Http\Controllers\BondingProviderDashboardController::class, 'index'])->name('dashboard.provider-bonding');
Route::middleware(['auth', 'role:client'])->get('/dashboard/client', [\App\Http\Controllers\ClientDashboardController::class, 'index'])->name('dashboard.client');
Route::middleware(['auth','role:super-admin'])->get('/dashboard/stats/super', [\AppHttp\Controllers\DashboardStatsController::class, 'superAdmin'])->name('dashboard.stats.super');

/*
|--------------------------------------------------------------------------
| Module Routes
|--------------------------------------------------------------------------
*/
// Training modules
Route::get('training-modules', [\App\Http\Controllers\TrainingModuleController::class, 'index'])->name('training-modules.index');
Route::get('training-modules/{id}', [\App\Http\Controllers\TrainingModuleController::class, 'show'])->name('training-modules.show');

// Parenting modules
Route::get('parenting-modules', [\App\Http\Controllers\ParentingModuleController::class, 'index'])->name('parenting-modules.index');
Route::get('parenting-modules/{id}', [\App\Http\Controllers\ParentingModuleController::class, 'show'])->name('parenting-modules.show');

// Mental Health modules (renamed from training)
Route::get('mentalhealth-modules', [\App\Http\Controllers\TrainingModuleController::class, 'index'])->name('mentalhealth-modules.index');
Route::get('mentalhealth-modules/{id}', [\App\Http\Controllers\TrainingModuleController::class, 'show'])->name('mentalhealth-modules.show');
Route::get('mentalhealth', [\App\Http\Controllers\TrainingModuleController::class, 'index'])->name('mentalhealth.index');
Route::get('mentalhealth/{id}', [\App\Http\Controllers\TrainingModuleController::class, 'show'])->name('mentalhealth.show');

// Short aliases
Route::get('parenting', [\App\Http\Controllers\ParentingModuleController::class, 'index'])->name('parenting.index');
Route::get('trainings', [\App\Http\Controllers\TrainingModuleController::class, 'index'])->name('trainings.index');
Route::get('trainings/{id}', [\App\Http\Controllers\TrainingModuleController::class, 'show'])->name('trainings.show');

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
// Activities
Route::get('activities/search', [\App\Http\Controllers\ActivityController::class, 'search'])->name('activities.search');
Route::get('activities', [\App\Http\Controllers\ActivityController::class, 'index'])->name('activities.index');
Route::get('activities/{id}', [\App\Http\Controllers\ActivityController::class, 'show'])->name('activities.show');

// Provider routes
Route::get('/providers', [App\Http\Controllers\UserProviderController::class, 'index'])->name('providers.index');
Route::get('/providers/{id}', [App\Http\Controllers\UserProviderController::class, 'show'])->name('providers.show');
Route::get('/providers/{id}/book', [App\Http\Controllers\UserProviderController::class, 'book'])->name('providers.book');
Route::post('/providers/{id}/book', [App\Http\Controllers\UserProviderController::class, 'storeBooking'])->name('providers.book.store');
Route::get('/providers/{id}/payment', [App\Http\Controllers\UserProviderController::class, 'payment'])->name('providers.payment');
Route::post('/providers/{id}/payment', [App\Http\Controllers\UserProviderController::class, 'processPayment'])->name('providers.payment.process');
Route::get('/booking/receipt/{bookingId}', [App\Http\Controllers\UserProviderController::class, 'downloadReceipt'])->name('booking.receipt');

// Activities routes
Route::get('/activities', [App\Http\Controllers\ActivityController::class, 'index'])->name('activities.index');
Route::get('/activities/{id}', [App\Http\Controllers\ActivityController::class, 'show'])->name('activities.show');
Route::get('/activities/category/{category}', [App\Http\Controllers\ActivityController::class, 'category'])->name('activities.category');

// Activity booking routes
Route::middleware('auth')->group(function () {
    Route::get('/activities/{id}/book', [AppHttp\Controllers\ActivityController::class, 'book'])->name('activities.book');
    Route::post('/activities/{id}/book', [AppHttp\Controllers\ActivityController::class, 'storeBooking'])->name('activities.book.store');
    Route::get('/activities/{id}/payment', [AppHttp\Controllers\ActivityController::class, 'payment'])->name('activities.payment');
    Route::post('/activities/{id}/payment', [AppHttp\Controllers\ActivityController::class, 'processPayment'])->name('activities.payment.process');
    Route::get('/activities/{id}/success/{reference}', [AppHttp\Controllers\ActivityController::class, 'bookingSuccess'])->name('activities.booking.success');

    // My bookings management
    Route::get('/my-bookings/activities', [AppHttp\Controllers\ActivityController::class, 'myBookings'])->name('activities.my-bookings');
    Route::get('/my-bookings/activities/{reference}', [AppHttp\Controllers\ActivityController::class, 'showBooking'])->name('activities.booking.details');
});

// Dashboard feature routes
Route::middleware('auth')->group(function () {
    // Financial Insights
    Route::get('/financial/insights', [App\Http\Controllers\FinancialController::class, 'insights'])->name('financial.insights');
    Route::get('/financial/reports', [App\Http\Controllers\FinancialController::class, 'reports'])->name('financial.reports');

    // Messages
    Route::get('/messages', [AppHttp\Controllers\MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{id}', [AppHttp\Controllers\MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages', [AppHttp\Controllers\MessageController::class, 'store'])->name('messages.store');

    // Community Engagements
    Route::get('/engagements', [AppHttp\Controllers\EngagementController::class, 'index'])->name('engagements.index');
    Route::get('/engagements/{id}', [AppHttp\Controllers\EngagementController::class, 'show'])->name('engagements.show');
    Route::post('/engagements/{id}/join', [AppHttp\Controllers\EngagementController::class, 'join'])->name('engagements.join');

    // Family Management
    Route::get('/family', [AppHttp\Controllers\FamilyController::class, 'index'])->name('family.index');
    Route::post('/family/members', [AppHttp\Controllers\FamilyController::class, 'addMember'])->name('family.add-member');
    Route::delete('/family/members/{id}', [AppHttp\Controllers\FamilyController::class, 'removeMember'])->name('family.remove-member');

    // Rewards & Points
    Route::get('/rewards', [AppHttp\Controllers\RewardController::class, 'index'])->name('rewards.index');
    Route::post('/rewards/{id}/redeem', [AppHttp\Controllers\RewardController::class, 'redeem'])->name('rewards.redeem');
    Route::get('/rewards/history', [AppHttp\Controllers\RewardController::class, 'history'])->name('rewards.history');
});// Public services
Route::get('services/{service}', [\AppHttp\Controllers\ServicePublicController::class, 'show'])->name('services.show');
Route::get('services/{service}/slots', [\AppHttp\Controllers\ServicePublicController::class, 'slots'])->name('services.slots');
Route::post('services/{service}/book', [\AppHttp\Controllers\ServiceBookingController::class, 'store'])->middleware('auth')->name('services.book');

// AI Chat
Route::get('ai/chat', [\App\Http\Controllers\AiChatController::class, 'index'])->name('ai.chat');
Route::post('ai/chat/upload', [\App\Http\Controllers\AiChatController::class, 'upload'])->name('ai.upload');
Route::post('ai/chat/message', [\App\Http\Controllers\AiChatController::class, 'message'])->name('ai.message');
Route::get('ai/documents', [\App\Http\Controllers\AiChatController::class, 'documents'])->name('ai.documents');

// Payments
Route::get('payments/{booking}/checkout/{gateway?}', [\App\Http\Controllers\PaymentController::class, 'checkout'])->name('payments.checkout');
Route::post('payments/webhook', [\App\Http\Controllers\PaymentController::class, 'webhook'])->name('payments.webhook');
Route::get('payments/{payment}/receipt', [\AppHttp\Controllers\PaymentController::class, 'receipt'])->name('payments.receipt');

// Locale switcher
Route::get('/locale/{locale}', [\App\Http\Controllers\LocaleController::class, 'set'])->name('locale.set');

// Calm Space
Route::post('/calmspace/track', [\App\Http\Controllers\CalmSpaceController::class, 'track'])->name('calmspace.track');
Route::get('/resources/mental-health', [\App\Http\Controllers\CalmSpaceController::class, 'resources'])->name('resources.mental-health');

/*
|--------------------------------------------------------------------------
| Provider Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:provider-professional,provider-bonding'])->prefix('provider')->name('provider.')->group(function () {
    Route::get('dashboard', [\App\Http\Controllers\Provider\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('services', \App\Http\Controllers\Provider\ServiceController::class);
    Route::resource('bookings', \AppHttp\Controllers\Provider\BookingController::class)->only(['index', 'show', 'update']);
    Route::resource('reviews', \AppHttp\Controllers\Provider\ReviewController::class)->only(['index']);

    // Availability management
    Route::get('services/{service}/availability', [\App\Http\Controllers\Provider\AvailabilityController::class, 'edit'])->name('services.availability.edit');
    Route::post('services/{service}/availability', [\App\Http\Controllers\Provider\AvailabilityController::class, 'update'])->name('services.availability.update');
    Route::get('services/{service}/slots', [\App\Http\Controllers\Provider\AvailabilityController::class, 'slots'])->name('services.availability.slots');

    // Metrics
    Route::get('metrics', [\App\Http\Controllers\Provider\MetricsController::class, 'index'])->name('provider.metrics');

    // Payments
    Route::post('payments/process', [\App\Http\Controllers\PaymentController::class, 'process'])->name('payments.process');
    Route::post('payments/mpesa/callback', [\App\Http\Controllers\PaymentController::class, 'mpesaCallback'])->name('payments.mpesa.callback');
});

// Activity Provider Routes - specific to bonding/activity providers
Route::middleware(['auth', 'role:provider-bonding'])->prefix('provider')->name('provider.')->group(function () {
    // Activity management for activity providers
    Route::resource('activities', \App\Http\Controllers\Admin\ActivityController::class)->except(['index', 'show']);
    Route::get('my-activities', [\App\Http\Controllers\Admin\ActivityController::class, 'index'])->name('activities.index');
    Route::get('activities/{activity}', [\App\Http\Controllers\Admin\ActivityController::class, 'show'])->name('activities.show');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Activities management - accessible by super-admins, admins, and activity providers
    Route::middleware(['role:super-admin,admin,provider-bonding'])->group(function () {
        Route::resource('activities', \App\Http\Controllers\Admin\ActivityController::class);
        Route::post('activities/{activity}/approve', [\App\Http\Controllers\Admin\ActivityController::class, 'approve'])->name('activities.approve');
    });

    // Super admin only routes
    Route::middleware(['role:super-admin'])->group(function () {
    // User management
    Route::get('users', [\App\Http\Controllers\Admin\UserManagementController::class, 'index'])->name('users.index');
    Route::post('users/{id}/update-role', [\App\Http\Controllers\Admin\UserManagementController::class, 'updateRole'])->name('users.update-role');

    // Documents
    Route::get('documents', [\App\Http\Controllers\DocumentAdminController::class, 'index'])->name('documents.index');
    Route::delete('documents/{id}', [\App\Http\Controllers\DocumentAdminController::class, 'destroy'])->name('documents.destroy');

    // Providers
    Route::resource('providers', \App\Http\Controllers\ProviderController::class);

    // Approvals
    Route::resource('approvals', \App\Http\Controllers\ApprovalController::class);
    Route::post('approvals/bulk-action', [\App\Http\Controllers\ApprovalController::class, 'bulkAction'])->name('approvals.bulk-action');

    // Modules
    Route::resource('modules', \App\Http\Controllers\ModuleController::class);

    // Roles
    Route::resource('roles', \App\Http\Controllers\RoleController::class);

    // Services
    Route::resource('services', \App\Http\Controllers\ServiceController::class);

    // Categories
    Route::resource('categories', \App\Http\Controllers\CategoryController::class);

    // Clients
    Route::resource('clients', \App\Http\Controllers\ProviderClientController::class);

    // Bookings
    Route::resource('bookings', \App\Http\Controllers\Admin\AdminBookingController::class);

    // Analytics
    Route::get('analytics', [\AppHttp\Controllers\Admin\AnalyticsController::class, 'index'])->name('analytics');
    Route::get('analytics/performance', [\AppHttp\Controllers\Admin\AnalyticsController::class, 'performance'])->name('analytics.performance');
    Route::get('analytics/growth-trends', [\AppHttp\Controllers\Admin\AnalyticsController::class, 'growthTrends'])->name('analytics.growth-trends');
    Route::get('analytics/retention', [\AppHttp\Controllers\Admin\AnalyticsController::class, 'retention'])->name('analytics.retention');
    Route::get('analytics/engagement-heatmaps', [\AppHttp\Controllers\Admin\AnalyticsController::class, 'engagementHeatmaps'])->name('analytics.engagement-heatmaps');
    }); // Close super admin only routes

}); // Close admin routes

// Optional diagnostics include
if (file_exists(__DIR__.'/diagnose_missing_routes.php')) {
    require __DIR__.'/diagnose_missing_routes.php';
}

// Fallback route
Route::fallback(function(){
    return response()->view('errors.404', [], 404);
});

