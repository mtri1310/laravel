<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FilmController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImdbController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\SeatController;
use App\Http\Controllers\ShowtimeController;

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

// Route::get('/', [ProductController::class, 'index']);

Route::get('/', DashboardController::class)->middleware('auth'); 

Route::get('/login', [AuthController::class, 'show'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');

Route::get('/movies', action: [ImdbController::class, 'index']);

Route::resource('products', ProductController::class);
Route::resource('films', FilmController::class);
Route::resource('seats', SeatController::class);
Route::resource('rooms', RoomController::class);
Route::resource('showtimes', ShowtimeController::class);

