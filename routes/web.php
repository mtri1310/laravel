<?php

use App\Http\Controllers\Api\LoginGoogleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FilmController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImdbController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\SeatController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\ShowtimeController;
use App\Http\Controllers\UserController;

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

Route::get('/', DashboardController::class)->middleware('auth'); 

// Authentication Routes
Route::get('/login', [AuthController::class, 'show'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');

// Google OAuth Routes
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// Logout Route
Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');

// Resource Routes with Authentication Middleware
Route::resource('users', UserController::class)->except(['show'])->middleware('auth');
Route::resource('films', FilmController::class)->except(['show'])->middleware('auth');
Route::resource('rooms', RoomController::class)->except(['show'])->middleware('auth');
Route::resource('showtimes', ShowtimeController::class)->except(['show'])->middleware('auth');
Route::resource('bookings', BookingController::class)->except(['show'])->middleware('auth');

// Updated Seats Route to Include Showtime
Route::get('/rooms/{room}/seats', [SeatController::class, 'index'])->name('seats.index')->middleware('auth');

// External Routes
Route::get('/movies', [ImdbController::class, 'index']);

// Payment Routes
Route::get('/checkout', [StripeController::class, 'createCheckoutSession']);
Route::get('/homepayment', [StripeController::class, 'index']);
Route::get('payment/success', [StripeController::class, 'success'])->name('payment.success');
Route::get('payment/cancel', [StripeController::class, 'cancel'])->name('payment.cancel');
