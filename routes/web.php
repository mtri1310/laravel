<?php

use App\Http\Controllers\Api\LoginGoogleController;
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

// Route::get('/', [ProductController::class, 'index']);

Route::get('/', function () {
    return view('admin');
});
// Route::get('/movies', function () {  
Route::get('/dashboard', function () {
    return view('admin');
});
// Route::get('/film', [FilmController::class, 'index'])->name('film');
// Route::get('/film/add', [FilmController::class, 'create'])->name('film.add');

// Route::get('/', function () {
//     return view('index');
// });
// Route::get('/movies', function () {
//     return view('welcome');
// });
Route::get('/movies', [ImdbController::class, 'index']);

Route::get('/films', [FilmController::class, 'index'])->name('films.index');

Route::resource('users', UserController::class);
Route::resource('products', ProductController::class);
Route::resource('films', FilmController::class);
Route::resource('seats', SeatController::class);
Route::resource('rooms', RoomController::class);
Route::resource('showtimes', ShowtimeController::class);

//payment
Route::get('/checkout', [StripeController::class, 'createCheckoutSession']);
Route::get('/homepayment', [StripeController::class, 'index']);
Route::get('payment/success', [StripeController::class, 'success'])->name('payment.success');
Route::get('payment/cancel', [StripeController::class, 'cancel'])->name('payment.cancel');

// Route::get('/movies', [ImdbController::class, 'index']);

// Route::resource('products', ProductController::class);

//Google
Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {

    return view('dashboard');

})->name('dashboard');
Route::controller(LoginGoogleController::class)->group(function(){
    Route::get('auth/google', 'redirectToGoogle')->name('auth.google');
    Route::get('auth/google/callback', 'handleGoogleCallback');
});