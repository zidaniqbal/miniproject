<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// API routes untuk User
Route::middleware(['auth:sanctum', 'checkRole:1'])->group(function () {
    // Tambahkan route API untuk user di sini
});

// API routes untuk Admin
Route::middleware(['auth:sanctum', 'checkRole:2'])->group(function () {
    // Route::get('/news', [AdminController::class, 'getNews']);
});
