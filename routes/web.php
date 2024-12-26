<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FilmController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\SeatController;
use App\Http\Controllers\ShowtimeController;
use App\Http\Controllers\UserController;



// Public Routes (Accessible without authentication)
Route::middleware('guest')->group(function () {
    // Authentication Routes
    Route::get('/login', [AuthController::class, 'show'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');

    // Google OAuth Routes
    Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
});

// Protected Routes (Require authentication)
Route::middleware('auth')->group(function () {
    // Dashboard Route
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Logout Route
    Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');

    // Resource Routes
    Route::resources([
        'users'     => UserController::class,
        'films'     => FilmController::class,
        'rooms'     => RoomController::class,
        'showtimes' => ShowtimeController::class,
        'bookings'  => BookingController::class,
        'invoices'  => InvoiceController::class,
    ], [
        'except' => ['show'],
    ]);
    // Seat Routes
    Route::get('/rooms/{room}/seats', [SeatController::class, 'index'])->name('seats.index');
});
