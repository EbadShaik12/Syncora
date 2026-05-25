<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Startup\StartupDashboardController;
use App\Http\Controllers\Startup\StartupProfileController;
use App\Http\Controllers\Corporate\CorporateDashboardController;
use App\Http\Controllers\Corporate\CorporateProfileController;
use App\Http\Controllers\Corporate\ChallengeController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\SwipeController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileViewController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\MilestoneController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\LeaderboardController;
use Illuminate\Support\Facades\Route;

// Public
Route::get('/', [HomeController::class, 'index'])->name('home');

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRoleSelection'])->name('register');
    Route::get('/register/startup', [RegisterController::class, 'showStartupForm'])->name('register.startup');
    Route::post('/register/startup', [RegisterController::class, 'registerStartup']);
    Route::get('/register/corporate', [RegisterController::class, 'showCorporateForm'])->name('register.corporate');
    Route::post('/register/corporate', [RegisterController::class, 'registerCorporate']);

    // Admin auth routes (guest-only)
    Route::get('/admin/login', [LoginController::class, 'showAdminLoginForm'])->name('admin.login');
    Route::post('/admin/login', [LoginController::class, 'adminLogin'])->name('admin.login.post');
    Route::get('/admin/register', [RegisterController::class, 'showAdminForm'])->name('admin.register');
    Route::post('/admin/register', [RegisterController::class, 'registerAdmin'])->name('admin.register.post');
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Shared routes
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
    Route::get('/search', [SearchController::class, 'index'])->name('search');
    Route::get('/search/suggestions', [SearchController::class, 'suggestions'])->name('search.suggestions');
    Route::get('/profile/{user}', [ProfileViewController::class, 'show'])->name('profile.show');
    Route::get('/profile/{user}/pdf', [PdfController::class, 'startupReport'])->name('profile.pdf');
    Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard');

    // Chat
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{connection}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{connection}/message', [ChatController::class, 'sendMessage'])->name('chat.send');
    Route::get('/chat/{connection}/messages', [ChatController::class, 'fetchMessages'])->name('chat.fetch');
    Route::post('/chat/{connection}/typing', [ChatController::class, 'setTyping'])->name('chat.typing');
    Route::get('/chat/{connection}/typing', [ChatController::class, 'getTyping'])->name('chat.typing.get');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/count', [NotificationController::class, 'unreadCount'])->name('notifications.count');
    Route::get('/notifications/dropdown', [NotificationController::class, 'dropdown'])->name('notifications.dropdown');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.readAll');

    // Startup routes
    Route::prefix('startup')->name('startup.')->middleware('role:startup')->group(function () {
        Route::get('/dashboard', [StartupDashboardController::class, 'index'])->name('dashboard');
        Route::get('/profile/edit', [StartupProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [StartupProfileController::class, 'update'])->name('profile.update');
        Route::post('/milestones', [MilestoneController::class, 'store'])->name('milestones.store');
        Route::delete('/milestones/{milestone}', [MilestoneController::class, 'destroy'])->name('milestones.destroy');
        Route::get('/swipe', [SwipeController::class, 'index'])->name('swipe');
        Route::get('/challenges', [ApplicationController::class, 'availableChallenges'])->name('challenges');
        Route::get('/challenges/{challenge}/apply', [ApplicationController::class, 'create'])->name('challenges.apply');
        Route::post('/challenges/{challenge}/apply', [ApplicationController::class, 'store'])->name('challenges.apply.store');
        Route::get('/applications', [ApplicationController::class, 'myApplications'])->name('applications');
    });

    // Corporate routes
    Route::prefix('corporate')->name('corporate.')->middleware('role:corporate')->group(function () {
        Route::get('/dashboard', [CorporateDashboardController::class, 'index'])->name('dashboard');
        Route::get('/profile/edit', [CorporateProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [CorporateProfileController::class, 'update'])->name('profile.update');
        Route::get('/swipe', [SwipeController::class, 'index'])->name('swipe');
        Route::resource('challenges', ChallengeController::class);
        Route::get('/challenges/{challenge}/applications', [ChallengeController::class, 'applications'])->name('challenges.applications');
        Route::get('/challenges/{challenge}/kanban', [ChallengeController::class, 'kanban'])->name('challenges.kanban');
        Route::post('/applications/{application}/shortlist', [ChallengeController::class, 'shortlist'])->name('applications.shortlist');
        Route::post('/applications/{application}/reject', [ChallengeController::class, 'rejectApplication'])->name('applications.reject');
        Route::patch('/applications/{application}/stage', [ChallengeController::class, 'updateStage'])->name('applications.stage');
    });

    // Swipe API
    Route::post('/swipe', [SwipeController::class, 'swipe'])->name('swipe.store');
    Route::post('/swipe/reset', [SwipeController::class, 'reset'])->name('swipe.reset');

    // Connection Rating API
    Route::post('/connections/{connection}/rate', [ChatController::class, 'rateConnection'])->name('connections.rate');

    // Admin routes
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::post('/users/{user}/approve', [AdminUserController::class, 'approve'])->name('users.approve');
        Route::post('/users/{user}/reject', [AdminUserController::class, 'reject'])->name('users.reject');
        Route::post('/users/{user}/toggle-suspend', [AdminUserController::class, 'toggleSuspend'])->name('users.toggleSuspend');
        Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
        Route::get('/analytics', [AdminDashboardController::class, 'analytics'])->name('analytics');
    });
});
