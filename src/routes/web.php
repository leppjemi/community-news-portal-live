<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EditorController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsPostController;
use App\Http\Controllers\SocialShareController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

// Health check endpoint (no database required)
Route::get('/health', function () {
    return response()->json(['status' => 'ok', 'service' => 'community-news-portal'], 200);
})->name('health');

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/news/{slug}', [HomeController::class, 'show'])->name('news.show');

// API route for tracking social shares (public, but with CSRF protection)
Route::post('/api/social-share/track', [SocialShareController::class, 'track'])->name('api.social-share.track');

// API route for checking email availability (public, for registration form)
Route::post('/api/check-email', [RegisterController::class, 'checkEmail'])->name('api.check-email');
// API route for checking password (authenticated users - both regular users and editors)
Route::post('/api/check-password', function (Illuminate\Http\Request $request) {
    $user = Auth::user();

    $request->validate([
        'password' => 'required|string',
    ]);

    if (Hash::check($request->password, $user->password)) {
        return response()->json(['valid' => true]);
    }

    return response()->json(['valid' => false]);
})->name('api.check-password')->middleware('auth');

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::post('/logout', [LogoutController::class, 'logout'])->middleware('auth')->name('logout');

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// User Dashboard Routes (role='user' only)
Route::middleware(['auth', 'role:user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\UserDashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::put('/profile', [App\Http\Controllers\UserDashboardController::class, 'updateProfile'])->name('profile.update');

    // Settings
    Route::get('/settings', [App\Http\Controllers\UserDashboardController::class, 'settings'])->name('settings');
    Route::put('/settings/password', [App\Http\Controllers\UserDashboardController::class, 'updatePassword'])->name('settings.password');

    // Submissions
    Route::get('/submissions', [App\Http\Controllers\UserDashboardController::class, 'submissions'])->name('submissions');
    Route::get('/submit-news', [App\Http\Controllers\UserDashboardController::class, 'submitNews'])->name('submit-news');
    Route::get('/edit-news/{id}', [App\Http\Controllers\UserDashboardController::class, 'editNews'])->name('edit-news');
});

// Editor routes (role:editor only, NOT admin)
Route::middleware(['auth', 'role:editor'])->prefix('editor')->name('editor.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\EditorDashboardController::class, 'index'])->name('dashboard');
    Route::get('/submissions', [App\Http\Controllers\EditorDashboardController::class, 'submissions'])->name('submissions');
    Route::get('/submit-news', [App\Http\Controllers\EditorDashboardController::class, 'submitNews'])->name('submit-news');
    Route::get('/edit-news/{id}', [App\Http\Controllers\EditorDashboardController::class, 'editNews'])->name('edit-news');
    Route::get('/edit-approved-article/{id}', [App\Http\Controllers\EditorDashboardController::class, 'editApprovedArticle'])->name('edit-approved-article');
    Route::get('/settings', [App\Http\Controllers\EditorDashboardController::class, 'settings'])->name('settings');
    Route::put('/profile', [App\Http\Controllers\EditorDashboardController::class, 'updateProfile'])->name('profile.update');
    Route::put('/settings/password', [App\Http\Controllers\EditorDashboardController::class, 'updatePassword'])->name('settings.password');
    Route::get('/review-queue', [EditorController::class, 'reviewQueue'])->name('review-queue');
    Route::get('/approved-articles', [App\Http\Controllers\EditorDashboardController::class, 'approvedArticles'])->name('approved-articles');
    Route::get('/rejected-articles', [App\Http\Controllers\EditorDashboardController::class, 'rejectedArticles'])->name('rejected-articles');
    Route::get('/preview/{id}', [NewsPostController::class, 'preview'])->name('preview');
});

// Admin routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('categories', CategoryController::class);
    Route::resource('users', UserController::class);
    Route::get('/analytics', [SocialShareController::class, 'analytics'])->name('analytics');
});
