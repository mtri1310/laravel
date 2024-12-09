<?php

use App\Http\Controllers\FilmController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImdbController;
use App\Http\Controllers\ProductController;

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
Route::get('/film', [FilmController::class, 'index'])->name('film');
Route::get('/film/add', [FilmController::class, 'create'])->name('film.add');

// Route::get('/movies', [ImdbController::class, 'index']);

// Route::resource('products', ProductController::class);

