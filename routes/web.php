<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    DashboardController,
    ProfileController,
    FeedbackController,
    FinanceInsightsController,
    AnalyticsController,
    ActivityController,
    ProviderController as UserProviderController,
    BookingController as UserBookingController,
    PaymentController,
    ParentingModuleController,
    TrainingModuleController,
    AiChatController,
    ServicesController
};
use App\Http\Controllers\Admin\{
    UserController,
    ProviderController,
    ActivityController as AdminActivityController,
    BookingController as AdminBookingController,
    ReportController,
    EarningsController,
    InvoicesController,
    SubscriptionsController,
    ServiceInsightController,
    ClientInsightController,
    FinancialInsightController,
    CategoryController,
    ServiceController,
    ServiceProviderController,
    ModuleController,
    DashboardController as AdminDashboardController,
    FeedbackController as AdminFeedbackController,
    RoleController,
    ApprovalController
};
use App\Http\Controllers\Provider\{
    ServiceController as ProviderServiceController,
    BookingController as ProviderBookingController,
    ClientController as ProviderClientController,
    PaymentController as ProviderPaymentController
};





use App\Http\Controllers\Provider\ProviderDashboardController;
use App\Http\Controllers\User\UserDashboardController;

Route::get('/', function () {
    return view('welcome');
});

// --- AUTH routes ---
Auth::routes();

// --- ADMIN routes ---
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');
    });

// --- PROVIDER routes ---
Route::middleware(['auth', 'role:provider'])
    ->prefix('provider')
    ->name('provider.')
    ->group(function () {
        Route::get('/dashboard', [ProviderDashboardController::class, 'index'])
            ->name('dashboard');
    });

// --- USER/CLIENT routes ---
Route::middleware(['auth', 'role:user'])
    ->prefix('user')
    ->name('user.')
    ->group(function () {
        Route::get('/dashboard', [UserDashboardController::class, 'index'])
            ->name('dashboard');
    });


    Route::prefix('dashboard')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [UserDashboardController::class, 'index'])->name('dashboard');

    // Chart endpoints
    Route::get('/weekly-engagement', [UserDashboardController::class, 'weeklyEngagement'])
        ->name('dashboard.weekly-engagement');
    Route::get('/learning-progress', [UserDashboardController::class, 'learningProgress'])
        ->name('dashboard.learning-progress');
});


// =====================
// Public Routes
// =====================
Route::get('/', fn() => view('welcome'));

// =====================
// User Bookings Routes
// =====================
Route::prefix('bookings')->name('bookings.')->group(function () {

    // List all bookings
    Route::get('/', [UserBookingController::class, 'index'])->name('index');

    // Show a single booking
    Route::get('/{booking}', [UserBookingController::class, 'show'])->name('show');

    // Create booking for an activity
    Route::get('/create/activity/{activity}', [UserBookingController::class, 'createForActivity'])
        ->name('create.activity')
        ->whereNumber('activity');

    // Create booking for a service
    Route::get('/create/service/{service}', [UserBookingController::class, 'createForService'])
        ->name('create.service')
        ->whereNumber('service');

    // Store booking (for both activity and service)
    Route::post('/', [UserBookingController::class, 'store'])->name('store');

    // Booking success
    Route::get('/{booking}/success', [UserBookingController::class, 'success'])->name('success');

    // Cancel a booking
    Route::post('/{booking}/cancel', [UserBookingController::class, 'cancel'])->name('cancel');

    // Payment for a booking
    Route::get('/{booking}/payment', [PaymentController::class, 'create'])->name('payment.create');
    Route::post('/{booking}/payment', [PaymentController::class, 'store'])->name('payment.store');

});

// =====================
// User Payments Routes
// =====================
Route::prefix('payments')->name('payments.')->group(function () {

    // Confirm payment
    Route::get('/{booking}/{transaction}/confirm', [PaymentController::class, 'confirm'])->name('confirm');
    Route::post('/{booking}/{transaction}/confirm', [PaymentController::class, 'confirm'])->name('confirm.post');

    // Payment history
    Route::get('/history', [PaymentController::class, 'history'])->name('history');

    // Process payment
    Route::post('/process', [PaymentController::class, 'processPayment'])->name('process');

    // Mpesa callback
    Route::post('/mpesa/callback', [PaymentController::class, 'mpesaCallback'])->name('mpesa.callback');

});


// =====================
// Authenticated Routes
// =====================
Route::middleware(['auth'])->group(function () {

    // ---------------------
    // Dashboard & Profile
    // ---------------------
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/wellness', [DashboardController::class, 'wellness'])->name('dashboard.wellness');
    Route::get('/dashboard/weekly-engagement', [DashboardController::class, 'weeklyEngagement'])->name('dashboard.weekly-engagement');
    Route::get('/dashboard/learning-progress', [DashboardController::class, 'learningProgress'])->name('dashboard.learning-progress');
    Route::get('/dashboard/stats', [DashboardController::class, 'stats'])->name('dashboard.stats');
    Route::get('/dashboard/tab-content/{tab}', [DashboardController::class, 'tabContent'])->name('dashboard.tab-content');
    Route::get('/dashboard/search', [DashboardController::class, 'search'])->name('dashboard.search');

    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });

    // ---------------------
    // Language Switch
    // ---------------------
    Route::get('locale/{lang}', function ($lang) {
        session(['locale' => $lang]);
        return redirect()->back();
    });

    // ---------------------
    // User Functionality
    // ---------------------

    // Activities
    Route::resource('activities', ActivityController::class)->only(['index', 'show']);
    Route::get('/search/activities', [ActivityController::class, 'search'])->name('activities.search');
    Route::get('/activities/family', [ActivityController::class, 'family'])->name('activities.family');
    Route::get('/activities/outdoor', [ActivityController::class, 'outdoor'])->name('activities.outdoor');
    Route::get('/activities/indoor', [ActivityController::class, 'indoor'])->name('activities.indoor');

    // Providers
    Route::resource('providers', UserProviderController::class)->only(['index', 'show']);
    Route::get('/providers/featured', [UserProviderController::class, 'featured'])->name('providers.featured');
    Route::get('/providers/top-rated', [UserProviderController::class, 'topRated'])->name('providers.top-rated');

    // Services
    Route::resource('services', ServicesController::class)->only(['index']);
    Route::get('/services/popular', [ServicesController::class, 'popular'])->name('services.popular');
    Route::get('/services/categories', [ServicesController::class, 'categories'])->name('services.categories');

    // Bookings
    Route::resource('bookings', UserBookingController::class)->only(['index', 'show', 'store']);
    Route::get('/bookings/create/{activity?}', [UserBookingController::class, 'create'])
        ->name('bookings.create')
        ->whereNumber('activity');
    Route::get('/bookings/create/service/{service}', [UserBookingController::class, 'createForService'])
        ->name('bookings.create.service')
        ->whereNumber('service');
    Route::get('/bookings/{booking}/success', [UserBookingController::class, 'success'])->name('bookings.success');
    Route::post('/bookings/{booking}/cancel', [UserBookingController::class, 'cancel'])->name('bookings.cancel');

    // Payments
    Route::get('/bookings/{booking}/payment', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('/bookings/{booking}/payment', [PaymentController::class, 'store'])->name('payments.store');
    Route::get('/payments/{booking}/{transaction}/confirm', [PaymentController::class, 'confirm'])->name('payments.confirm');
    Route::post('/payments/{booking}/{transaction}/confirm', [PaymentController::class, 'confirm'])->name('payments.confirm.post');
    Route::get('/payments/history', [PaymentController::class, 'history'])->name('payments.history');
    Route::post('/bookings/process-payment', [PaymentController::class, 'processPayment'])->name('bookings.processPayment');
    Route::post('/mpesa/callback', [PaymentController::class, 'mpesaCallback']);

    // Parenting Modules
    Route::prefix('parenting-modules')->name('parenting-modules.')->group(function () {
        Route::get('/', [ParentingModuleController::class, 'index'])->name('index');
        Route::get('/my-progress', [ParentingModuleController::class, 'myProgress'])->name('my-progress');
        Route::get('/favorites', [ParentingModuleController::class, 'favorites'])->name('favorites');
        Route::get('/recommendations', [ParentingModuleController::class, 'recommendations'])->name('recommendations');
        Route::get('/{module}', [ParentingModuleController::class, 'show'])->name('show');
        Route::get('/{module}/content/{content}', [ParentingModuleController::class, 'content'])->name('content');
        Route::post('/{module}/progress', [ParentingModuleController::class, 'updateProgress'])->name('update-progress');
        Route::post('/{module}/favorite', [ParentingModuleController::class, 'toggleFavorite'])->name('toggle-favorite');
        Route::post('/{module}/rate', [ParentingModuleController::class, 'rate'])->name('rate');
    });

    // Training Modules
    Route::prefix('training-modules')->name('training-modules.')->group(function () {
        Route::get('/', [TrainingModuleController::class, 'index'])->name('index');
        Route::get('/my-progress', [TrainingModuleController::class, 'myProgress'])->name('my-progress');
        Route::get('/favorites', [TrainingModuleController::class, 'favorites'])->name('favorites');
        Route::get('/{module}', [TrainingModuleController::class, 'show'])->name('show');
        Route::get('/{module}/chat', [TrainingModuleController::class, 'chat'])->name('chat');
        Route::post('/{module}/chat/message', [TrainingModuleController::class, 'sendMessage'])->name('chat.message');
        Route::post('/{module}/progress', [TrainingModuleController::class, 'updateProgress'])->name('update-progress');
        Route::post('/{module}/favorite', [TrainingModuleController::class, 'toggleFavorite'])->name('toggle-favorite');
        Route::post('/{module}/rate', [TrainingModuleController::class, 'rate'])->name('rate');

        // Admin routes for training modules
        Route::middleware(['role:admin'])->group(function () {
            Route::get('/create', [TrainingModuleController::class, 'create'])->name('create');
            Route::post('/', [TrainingModuleController::class, 'store'])->name('store');
            Route::get('/{module}/edit', [TrainingModuleController::class, 'edit'])->name('edit');
            Route::put('/{module}', [TrainingModuleController::class, 'update'])->name('update');
            Route::delete('/{module}', [TrainingModuleController::class, 'destroy'])->name('destroy');
        });
    });

    // AI Chat
    Route::prefix('ai-chat')->name('ai-chat.')->group(function () {
        Route::get('/', [AiChatController::class, 'index'])->name('index');
        Route::post('/message', [AiChatController::class, 'sendMessage'])->name('send-message');
        Route::post('/new-session', [AiChatController::class, 'newSession'])->name('new-session');
        Route::get('/conversation/{sessionId}', [AiChatController::class, 'getConversation'])->name('get-conversation');
        Route::post('/generate-audio/{messageId}', [AiChatController::class, 'generateAudio'])->name('generate-audio');
    });

    // =====================
    // Admin Routes
    // =====================
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Core Resources
        Route::resource('users', UserController::class);
        Route::resource('providers', ProviderController::class);
        Route::resource('activities', AdminActivityController::class);
        Route::resource('bookings', AdminBookingController::class);
        Route::resource('clients', ProviderClientController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('services', ServiceController::class);
        Route::resource('modules', ModuleController::class);

        // Insights & Analytics
        Route::prefix('analytics')->name('analytics.')->group(function () {
            Route::get('/', [AnalyticsController::class, 'index'])->name('index');
            Route::get('/performance', [AnalyticsController::class, 'performance'])->name('performance');
            Route::get('/growth-trends', [AnalyticsController::class, 'growth'])->name('growth');
            Route::get('/retention', [AnalyticsController::class, 'retention'])->name('retention');
            Route::get('/engagement-heatmaps', [AnalyticsController::class, 'engagement'])->name('engagement');
        });

        Route::get('/service-insights', [ServiceInsightController::class, 'index'])->name('service.insights');
        Route::get('/client-insights', [ClientInsightController::class, 'index'])->name('client.insights');
        Route::get('/financial-insights', [FinancialInsightController::class, 'index'])->name('financial.insights');

        // Finance & Earnings
        Route::get('/finance/insights', [FinanceInsightsController::class, 'index'])->name('finance.insights');
        Route::get('/earnings', [EarningsController::class, 'index'])->name('earnings');
        Route::get('/invoices', [InvoicesController::class, 'index'])->name('invoices');
        Route::get('/subscriptions', [SubscriptionsController::class, 'index'])->name('subscriptions');

        // Team, Tasks, Feedback, Support
        Route::view('/team', 'admin.team')->name('team');
        Route::resource('tasks', \App\Http\Controllers\Admin\TaskController::class);
        Route::post('/tasks/{task}/status', [\App\Http\Controllers\Admin\TaskController::class, 'updateStatus'])->name('tasks.update-status');
        Route::get('/feedback', [AdminFeedbackController::class, 'index'])->name('feedback');
        Route::view('/support', 'admin.support')->name('support');
        Route::view('/notifications', 'admin.notifications')->name('notifications');

        // Approvals
        Route::resource('approvals', ApprovalController::class)->only(['index', 'show']);
        Route::post('/approvals/{approval}/approve', [ApprovalController::class, 'approve'])->name('approvals.approve');
        Route::post('/approvals/{approval}/reject', [ApprovalController::class, 'reject'])->name('approvals.reject');
        Route::post('/approvals/bulk-action', [ApprovalController::class, 'bulkAction'])->name('approvals.bulk-action');

        // Reports & System Pages
        Route::get('/reports', [ReportController::class, 'index'])->name('reports');
        Route::view('/roles', 'admin.roles')->name('roles');
        Route::view('/settings', 'admin.settings')->name('settings');
    });

    // =====================
    // Provider Routes
    // =====================
    Route::middleware(['role:provider'])->prefix('provider')->name('provider.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Provider\ProviderController::class, 'dashboard'])->name('dashboard');
        Route::get('/register', [\App\Http\Controllers\Provider\ProviderController::class, 'register'])->name('register');
        Route::post('/register', [\App\Http\Controllers\Provider\ProviderController::class, 'register'])->name('register.store');

        Route::resource('services', ProviderServiceController::class);
        Route::resource('bookings', ProviderBookingController::class);
        Route::resource('clients', ProviderClientController::class);
        Route::get('/payments', [ProviderPaymentController::class, 'index'])->name('payments');

        Route::view('/support', 'provider.support')->name('support');
        Route::view('/service-insights', 'provider.service-insights')->name('service.insights');
        Route::view('/client-insights', 'provider.client-insights')->name('client.insights');
        Route::view('/financial-insights', 'provider.financial-insights')->name('financial.insights');

        // Activities Management
        Route::get('/activities', [\App\Http\Controllers\Provider\ProviderController::class, 'activities'])->name('activities.index');
        Route::get('/activities/create', [\App\Http\Controllers\Provider\ProviderController::class, 'createActivity'])->name('activities.create');
        Route::post('/activities', [\App\Http\Controllers\Provider\ProviderController::class, 'createActivity'])->name('activities.store');
        Route::get('/activities/{activity}/edit', [\App\Http\Controllers\Provider\ProviderController::class, 'editActivity'])->name('activities.edit');
        Route::put('/activities/{activity}', [\App\Http\Controllers\Provider\ProviderController::class, 'editActivity'])->name('activities.update');
        Route::delete('/activities/{activity}', [\App\Http\Controllers\Provider\ProviderController::class, 'deleteActivity'])->name('activities.destroy');

        // Employee Management
        Route::get('/employees', [\App\Http\Controllers\Provider\ProviderController::class, 'employees'])->name('employees.index');
        Route::get('/employees/create', [\App\Http\Controllers\Provider\ProviderController::class, 'createEmployee'])->name('employees.create');
        Route::post('/employees', [\App\Http\Controllers\Provider\ProviderController::class, 'createEmployee'])->name('employees.store');
    });

    // =====================
    // Manager Routes
    // =====================
    Route::middleware(['role:manager'])->prefix('manager')->name('manager.')->group(function () {
        Route::view('/team', 'manager.team')->name('team');
        Route::view('/reports', 'manager.reports')->name('reports');
    });

    // =====================
    // Employee Routes
    // =====================
    Route::middleware(['role:employee'])->prefix('employee')->name('employee.')->group(function () {
        Route::view('/profile', 'employee.profile')->name('profile');
        Route::view('/tasks', 'employee.tasks')->name('tasks');
    });
});

// Breeze auth
require __DIR__ . '/auth.php';
