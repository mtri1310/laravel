<?php

use App\Http\Controllers\Api\LoginGoogleController;
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

Route::get('/', function () {
    return view('admin');
});
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


Route::resource('products', ProductController::class);
Route::resource('films', FilmController::class);
Route::resource('seats', SeatController::class);
Route::resource('rooms', RoomController::class);
Route::resource('showtimes', ShowtimeController::class);


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