<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClientDashboardController;
use App\Http\Controllers\ProviderDashboardController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Provider\ProviderController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\AIController;
use App\Http\Controllers\MoodController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\MentalHealthController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\MeditationController;
use App\Http\Controllers\NutritionController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\SleepController;
use App\Http\Controllers\StressManagementController;
use App\Http\Controllers\SubstanceAbuseController;
use App\Http\Controllers\SelfCareController;
use App\Http\Controllers\TherapyController;
use App\Http\Controllers\MindfulnessController;
use App\Http\Controllers\YogaController;
use App\Http\Controllers\ArtTherapyController;
use App\Http\Controllers\MusicTherapyController;
use App\Http\Controllers\DanceTherapyController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\ProviderNotificationController;

Route::middleware(['auth'])->prefix('provider')->group(function () {
    Route::get('/notifications', [ProviderNotificationController::class, 'index'])
        ->name('provider.notifications');
});

use App\Http\Controllers\ProviderFinancialsController;

Route::middleware(['auth'])->prefix('provider')->group(function () {
    Route::get('/financials', [ProviderFinancialsController::class, 'index'])
        ->name('provider.financials');
});


Route::get('/', fn() => view('welcome'));

// -------------------- Authentication --------------------
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Dashboard (auto-redirects based on role)
    Route::get('/dashboard', function () {
        $user = auth()->user();
        $role = $user->roles->first()?->slug ?? 'client';

        return match ($role) {
            'provider-professional' => redirect()->route('dashboard.provider.professional'),
            'provider-bonding'      => redirect()->route('dashboard.provider.bonding'),
            'provider'              => redirect()->route('dashboard.provider'),
            'super-admin'           => redirect()->route('admin.dashboard'),
            'client'                => redirect()->route('dashboard.client'),
            default                 => redirect()->route('dashboard.client'),
        };
    })->name('dashboard');

    // User dashboard alias (redirects to main dashboard)
    Route::get('/user/dashboard', function () {
        return redirect()->route('dashboard');
    })->name('user.dashboard');

    // -------------------- Dashboards --------------------
    Route::get('/dashboard/client', [ClientDashboardController::class, 'index'])->name('dashboard.client');

    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('/provider', [ProviderDashboardController::class, 'index'])->name('provider');
        Route::get('/provider/professional', [ProviderDashboardController::class, 'professional'])->name('provider.professional');
        Route::get('/provider/bonding', [ProviderDashboardController::class, 'bonding'])->name('provider.bonding');
    });

    // -------------------- Activities --------------------
    Route::resource('activities', ActivityController::class);
    Route::post('/activities/{id}/book', [ActivityController::class, 'storeBooking'])->name('activities.book');
    Route::get('/activities/{id}/checkout', [ActivityController::class, 'checkout'])->name('activities.checkout');
    Route::post('/activities/{id}/payment', [ActivityController::class, 'processPayment'])->name('activities.payment');
    Route::get('/activities/public', [ActivityController::class, 'publicIndex'])->name('activities.public');

    // -------------------- Services --------------------
    Route::resource('services', ServiceController::class);
    Route::get('/services/public', [ServiceController::class, 'publicIndex'])->name('services.public');

    // -------------------- Bookings --------------------
    Route::resource('bookings', BookingController::class);

    // -------------------- Other Modules --------------------
    Route::get('/start-learning', [ActivityController::class, 'index'])->name('start-learning.index');
    Route::post('/mood/submit', [MoodController::class, 'submit'])->name('mood.submit');

    // Training
    Route::get('training', [TrainingController::class, 'index'])->name('training.index');
    Route::get('training/{module}', [TrainingController::class, 'show'])->name('training.show');
    Route::post('training/{module}/complete', [TrainingController::class, 'complete'])->name('training.complete');

    // AI Assistant
    Route::get('ai/chat', [AIController::class, 'chat'])->name('ai.chat');
    Route::post('ai/chat', [AIController::class, 'sendMessage'])->name('ai.send-message');
    Route::get('ai-chat', [AIController::class, 'index'])->name('ai-chat.index');

    // Messages
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');

    // Mental Health & Wellness
    Route::get('/mental-health', [MentalHealthController::class, 'index'])->name('mentalhealth.index');
    Route::get('/forum', [ForumController::class, 'index'])->name('forum.index');
    Route::get('/forum/{id}', [ForumController::class, 'show'])->name('forum.show');
    Route::post('/forum', [ForumController::class, 'store'])->name('forum.store');

    Route::get('/meditation', [MeditationController::class, 'index'])->name('meditation.index');
    Route::post('/meditation', [MeditationController::class, 'store'])->name('meditation.store');

    Route::get('/nutrition', [NutritionController::class, 'index'])->name('nutrition.index');
    Route::post('/nutrition', [NutritionController::class, 'store'])->name('nutrition.store');

    Route::get('/exercise', [ExerciseController::class, 'index'])->name('exercise.index');
    Route::post('/exercise', [ExerciseController::class, 'store'])->name('exercise.store');

    Route::get('/sleep', [SleepController::class, 'index'])->name('sleep.index');
    Route::post('/sleep', [SleepController::class, 'store'])->name('sleep.store');

    Route::get('/stress-management', [StressManagementController::class, 'index'])->name('stressmanagement.index');
    Route::post('/stress-management', [StressManagementController::class, 'store'])->name('stressmanagement.store');

    Route::get('/substance-abuse', [SubstanceAbuseController::class, 'index'])->name('substanceabuse.index');
    Route::post('/substance-abuse', [SubstanceAbuseController::class, 'store'])->name('substanceabuse.store');

    Route::get('/self-care', [SelfCareController::class, 'index'])->name('selfcare.index');
    Route::post('/self-care', [SelfCareController::class, 'store'])->name('selfcare.store');

    Route::get('/therapy', [TherapyController::class, 'index'])->name('therapy.index');
    Route::post('/therapy', [TherapyController::class, 'store'])->name('therapy.store');

    Route::get('/mindfulness', [MindfulnessController::class, 'index'])->name('mindfulness.index');
    Route::post('/mindfulness', [MindfulnessController::class, 'store'])->name('mindfulness.store');

    Route::get('/yoga', [YogaController::class, 'index'])->name('yoga.index');
    Route::post('/yoga', [YogaController::class, 'store'])->name('yoga.store');

    Route::get('/art-therapy', [ArtTherapyController::class, 'index'])->name('arttherapy.index');
    Route::post('/art-therapy', [ArtTherapyController::class, 'store'])->name('arttherapy.store');

    Route::get('/music-therapy', [MusicTherapyController::class, 'index'])->name('musictherapy.index');
    Route::post('/music-therapy', [MusicTherapyController::class, 'store'])->name('musictherapy.store');

    Route::get('/dance-therapy', [DanceTherapyController::class, 'index'])->name('dancetherapy.index');
    Route::post('/dance-therapy', [DanceTherapyController::class, 'store'])->name('dancetherapy.store');

    // Provider registration
    Route::get('/provider/register', [ProviderController::class, 'register'])->name('provider.register');
    Route::post('/provider/register', [ProviderController::class, 'store'])->name('provider.register.store');
});

// -------------------- Admin --------------------
Route::middleware(['auth', 'role:admin,super-admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', \App\Http\Controllers\Admin\UserManagementController::class)->only(['index']);
    Route::post('users/{id}/update-role', [\App\Http\Controllers\Admin\UserManagementController::class, 'updateRole'])->name('users.update-role');

    Route::resource('activities', \App\Http\Controllers\Admin\ActivityController::class);
    Route::resource('services', \App\Http\Controllers\ServiceController::class);
    Route::resource('modules', \App\Http\Controllers\ModuleController::class);
    Route::resource('categories', \App\Http\Controllers\CategoryController::class);
    Route::resource('roles', \App\Http\Controllers\RoleController::class);
    Route::resource('bookings', \App\Http\Controllers\Admin\AdminBookingController::class);

    Route::get('analytics', [\App\Http\Controllers\Admin\AnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('engagement/insights', [\App\Http\Controllers\Admin\AnalyticsController::class, 'engagement'])->name('engagement.insights');
    Route::get('reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/export/{type}', [\App\Http\Controllers\Admin\ReportController::class, 'export'])->name('reports.export');
});

// -------------------- Fallback --------------------
Route::fallback(fn() => response()->view('errors.404', [], 404));
