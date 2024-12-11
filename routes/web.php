<?php

use App\Http\Controllers\FilmController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImdbController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SeatController;
use App\Http\Controllers\StripeController;

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

Route::get('/', function () {
    return view('welcome');
});
// Route::get('/movies', function () {  
//     return view('welcome');
// });
Route::get('/movies', [ImdbController::class, 'index']);

Route::resource('products', ProductController::class);
Route::resource('films', FilmController::class);
Route::resource('seats', SeatController::class);

Route::get('/checkout', [StripeController::class, 'createCheckoutSession']);
Route::get('/homepayment', [StripeController::class, 'index']);
// Route::post('/checkout', [StripeController::class, 'session']);
// Route::get('/success', [StripeController::class, 'success']);
Route::get('payment/success', [StripeController::class, 'success'])->name('payment.success');
Route::get('payment/cancel', [StripeController::class, 'cancel'])->name('payment.cancel');