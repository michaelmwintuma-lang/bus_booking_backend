<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BusController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\SeatController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application.
|
*/

// Public Auth Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth Routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Buses Routes
    Route::apiResource('buses', BusController::class);

    // Routes
    Route::apiResource('routes', RouteController::class);

    // Seats Routes
    Route::apiResource('seats', SeatController::class);

    // Bookings Routes
    Route::apiResource('bookings', BookingController::class);
    Route::get('/my-bookings', [BookingController::class, 'myBookings']);

    // Branch Routes
    Route::apiResource('admin/branches', BranchController::class, ['as' => 'branches']);

    // Admin Routes
    Route::get('/admin/bookings', [AdminController::class, 'allBookings']);
    Route::get('/admin/stats', [AdminController::class, 'stats']);
    Route::get('/admin/sub-admins', [AdminController::class, 'allSubAdmins']);
    Route::post('/admin/sub-admins', [AdminController::class, 'createSubAdmin']);
    Route::get('/branch/bookings', [AdminController::class, 'branchBookings']);
});