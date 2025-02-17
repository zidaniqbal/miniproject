<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LandingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public routes
Route::get('/', [LandingController::class, 'index'])->name('home');
Route::get('/news', [LandingController::class, 'news'])->name('news');
Route::get('/get-news', [LandingController::class, 'getNews'])->name('get-news');

// Authentication Routes
Route::middleware('guest')->group(function () {
    // Login Routes
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    
    // Registration Routes
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// Logout Route
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware('auth')->group(function () {
    // Admin Routes
    Route::middleware('role:2')->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
        Route::get('/users/data', [AdminController::class, 'getUsersData'])->name('admin.users.data');
        Route::post('/users/store', [AdminController::class, 'store'])->name('admin.users.store');
        Route::get('/users/{user}', [AdminController::class, 'show'])->name('admin.users.show');
        Route::put('/users/{user}', [AdminController::class, 'update'])->name('admin.users.update');
        Route::delete('/users/{user}', [AdminController::class, 'destroy'])->name('admin.users.destroy');
        Route::get('/settings', [AdminController::class, 'settings'])->name('admin.settings');
        Route::post('/settings/update-profile', [AdminController::class, 'updateProfile'])->name('admin.settings.updateProfile');
        Route::post('/settings/update-password', [AdminController::class, 'updatePassword'])->name('admin.settings.updatePassword');
        
        // Add these new routes for admin news
        Route::get('/news', [AdminController::class, 'news'])->name('admin.news');
        Route::get('/get-news', [AdminController::class, 'getNews'])->name('admin.get-news');
    });

    // User Routes
    Route::middleware('role:1')->prefix('user')->group(function () {
        Route::get('/dashboard', [UserController::class, 'index'])->name('user.dashboard');
        Route::get('/settings', [UserController::class, 'settings'])->name('user.settings');
        Route::post('/settings/update-profile', [UserController::class, 'updateProfile'])->name('user.settings.updateProfile');
        Route::post('/settings/update-password', [UserController::class, 'updatePassword'])->name('user.settings.updatePassword');
        
        // Add these new routes for user news
        Route::get('/news', [UserController::class, 'news'])->name('user.news');
        Route::get('/get-news', [UserController::class, 'getNews'])->name('user.get-news');
    });
});
