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

// Đăng nhập bằng email và password
Route::get('/login', [AuthController::class, 'show'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');

// Đăng nhập bằng Google
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// Đăng xuất
Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');

// Xử lý Controller
Route::resource('users', UserController::class)->except(['show'])->middleware('auth');
Route::resource('films', FilmController::class)->except(['show'])->middleware('auth');
Route::resource('seats', SeatController::class)->middleware('auth');
Route::resource('rooms', RoomController::class)->except(['show'])->middleware('auth');
Route::resource('showtimes', ShowtimeController::class)->except(['show'])->middleware('auth');
Route::resource('bookings', BookingController::class)->except(['show'])->middleware('auth');

Route::get('/movies', action: [ImdbController::class, 'index']);

//payment
Route::get('/checkout', [StripeController::class, 'createCheckoutSession']);
Route::get('/homepayment', [StripeController::class, 'index']);
Route::get('payment/success', [StripeController::class, 'success'])->name('payment.success');
Route::get('payment/cancel', [StripeController::class, 'cancel'])->name('payment.cancel');
