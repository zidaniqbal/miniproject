<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LandingController;
use Illuminate\Support\Facades\Artisan;

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
Route::get('/landing/news', [LandingController::class, 'news'])->name('landing.news');
Route::get('/landing/get-news', [LandingController::class, 'getNews'])->name('landing.get-news');
Route::get('/landing/news/{category}/{title}', [LandingController::class, 'showNews'])->name('landing.news.show');
Route::get('/test-visitor', function() {
    $todayCount = \App\Models\Visitor::whereDate('created_at', \Carbon\Carbon::today())->count();
    $yesterdayCount = \App\Models\Visitor::whereDate('created_at', \Carbon\Carbon::yesterday())->count();
    $totalCount = \App\Models\Visitor::count();
    
    return response()->json([
        'today' => $todayCount,
        'yesterday' => $yesterdayCount,
        'total' => $totalCount,
        'latest_visitors' => \App\Models\Visitor::latest()->take(5)->get()
    ]);
});

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
        Route::post('/settings/update-maintenance', [AdminController::class, 'updateMaintenanceMode'])
            ->name('admin.settings.updateMaintenance');

            // Goals routes
        Route::get('/goals', [AdminController::class, 'goals'])->name('admin.goals');
        Route::get('/goals/{goal}', [AdminController::class, 'getGoal'])->name('admin.goals.get');
        Route::post('/goals', [AdminController::class, 'storeGoal'])->name('admin.goals.store');
        Route::post('/goals/{goal}/progress', [AdminController::class, 'updateGoalProgress'])->name('admin.goals.updateProgress');
        Route::post('/goals/{goal}/description', [AdminController::class, 'updateGoalDescription'])->name('admin.goals.updateDescription');
        Route::delete('/goals/{goal}', [AdminController::class, 'deleteGoal'])->name('admin.goals.delete');
        Route::get('/dashboard/goals-data', [AdminController::class, 'getDashboardGoals'])->name('admin.dashboard.goals-data');
        
        // Hanya routes news yang diperlukan
        Route::get('/news', [AdminController::class, 'news'])->name('admin.news');
        Route::get('/get-news', [AdminController::class, 'getNews'])->name('admin.get-news');
        Route::get('/gallery', [AdminController::class, 'gallery'])->name('admin.gallery');
        Route::get('/gallery/search', [AdminController::class, 'searchImages'])->name('admin.gallery.search');

        // Photobooth routes
        Route::get('/photobooth', [AdminController::class, 'photobooth'])->name('admin.photobooth');
        Route::post('/photobooth/save', [AdminController::class, 'savePhotobooth'])->name('admin.photobooth.save');
        Route::get('/photobooth-gallery', [AdminController::class, 'photoboothGallery'])->name('admin.photobooth.gallery');
        Route::get('/photo/{photo}/download', [AdminController::class, 'downloadPhoto'])->name('admin.photo.download');
        Route::delete('/photo/{photo}', [AdminController::class, 'deletePhoto'])->name('admin.photo.delete');
    });

    // User Routes
    Route::middleware(['auth', 'role:1'])->group(function () {
        Route::get('/dashboard', [UserController::class, 'index'])->name('user.dashboard');
        Route::get('/settings', [UserController::class, 'settings'])->name('user.settings');
        Route::post('/settings/update-profile', [UserController::class, 'updateProfile'])->name('user.settings.updateProfile');
        Route::post('/settings/update-password', [UserController::class, 'updatePassword'])->name('user.settings.updatePassword');
        
        // Add these new routes for user news
        Route::get('/news', [UserController::class, 'news'])->name('user.news');
        Route::get('/get-news', [UserController::class, 'getNews'])->name('user.get-news');
        
        // Goals routes
        Route::get('/goals', [UserController::class, 'goals'])->name('user.goals');
        Route::get('/goals/{goal}', [UserController::class, 'getGoal'])->name('user.goals.get');
        Route::post('/goals', [UserController::class, 'storeGoal'])->name('user.goals.store');
        Route::post('/goals/{goal}/progress', [UserController::class, 'updateGoalProgress'])->name('user.goals.updateProgress');
        Route::post('/goals/{goal}/description', [UserController::class, 'updateGoalDescription'])->name('user.goals.updateDescription');
        Route::delete('/goals/{goal}', [UserController::class, 'deleteGoal'])->name('user.goals.delete');
        Route::get('/dashboard/goals-data', [UserController::class, 'getDashboardGoals'])->name('user.dashboard.goals-data');

        Route::get('/user/gallery', [UserController::class, 'gallery'])->name('user.gallery');
        Route::get('/user/gallery/search', [UserController::class, 'searchImages'])->name('user.gallery.search');
    });

    // Admin & User news routes
    Route::prefix('admin')->group(function () {
        Route::get('/news', [AdminController::class, 'news'])->name('admin.news');
        Route::get('/get-news', [AdminController::class, 'getNews'])->name('admin.get-news');
    });
    
    Route::prefix('user')->group(function () {
        Route::get('/news', [UserController::class, 'news'])->name('user.news');
        Route::get('/get-news', [UserController::class, 'getNews'])->name('user.get-news');
    });
});
