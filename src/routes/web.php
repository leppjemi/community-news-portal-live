<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EditorController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SocialShareController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/news/{slug}', [HomeController::class, 'show'])->name('news.show');

// API route for tracking social shares (public, but with CSRF protection)
Route::post('/api/social-share/track', [SocialShareController::class, 'track'])->name('api.social-share.track');

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

    // User routes
    Route::get('/my-submissions', function () {
        return view('my-submissions');
    })->name('my-submissions');

    Route::get('/submit-news', function () {
        return view('submit-news');
    })->name('submit-news');

    Route::get('/edit-news/{id}', function ($id) {
        return view('submit-news', ['postId' => $id]);
    })->name('edit-news');
});

// Editor routes
Route::middleware(['auth', 'role:editor,admin'])->prefix('editor')->name('editor.')->group(function () {
    Route::get('/review-queue', [EditorController::class, 'reviewQueue'])->name('review-queue');
});

// Admin routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('categories', CategoryController::class);
    Route::resource('users', UserController::class);
    Route::get('/analytics', [SocialShareController::class, 'analytics'])->name('analytics');
});
