<?php

use App\Http\Controllers\Api\MovieDetailController;
use App\Http\Controllers\Api\MyTicketController;
use App\Http\Controllers\Api\SelectSeatController;
use App\Http\Controllers\Api\UserProfileController;
use App\Http\Controllers\StudentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImdbController;
use Illuminate\Console\View\Components\Secret;

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
// Route::middleware('auth:sanctum')->get('/student', [StudentController::class, 'index']);
Route::get('/student', [StudentController::class, 'index']);
Route::get('/movies', [ImdbController::class, 'index']);
Route::get('/ticket', [MyTicketController::class, 'getTicketDetails']);
Route::get('/user_profile', [UserProfileController::class, 'getUserProfile']);
Route::get('/select_seat', [SelectSeatController::class, 'getSelectSeat']);
Route::get('/movie_detail', [MovieDetailController::class, 'getMovieDetails']);
