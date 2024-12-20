<?php

use App\Http\Controllers\Api\MovieDetailController;
use App\Http\Controllers\Api\MyTicketController;
use App\Http\Controllers\Api\SelectSeatController;
use App\Http\Controllers\Api\UserProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\Api\ListFilmsController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\LoginGoogleController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\api\ShowtimeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImdbController;
use App\Models\Payment;
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


Route::get('/movies', [ImdbController::class, 'index']);
Route::get('/listfilms', [ListFilmsController::class, 'listfilms']);
Route::get('/payment', [PaymentController::class, 'payment']);
// Route::middleware('auth:api')->get('/login', [LoginController::class, 'getUserInfo']);
// Route::post('auth/google',  [LoginController::class, 'loginWithGoogle']);
// Route::post('auth/google/callback', 'handleGoogleCallback',  [LoginController::class, 'loginWithGoogle']);
Route::get('/ticket/{bookingId}', [MyTicketController::class, 'getTicketDetails']);
Route::get('/select_seat', [SelectSeatController::class, 'getSelectSeat']);
Route::get('/movie_detail', [MovieDetailController::class, 'getMovieDetails']);

// Đăng nhập bằng email và password
Route::post('/login', [AuthController::class, 'login']);

// Đăng nhập bằng Google
Route::post('/login/google', [AuthController::class, 'loginOrRegisterWithGoogle']);
// Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');

// Lấy thông tin người dùng hiện tại (yêu cầu xác thực)

Route::middleware(['auth:api'])->group(function () {
    Route::get('/payment', [PaymentController::class, 'payment']);
    Route::get('/ticket', [MyTicketController::class, 'getTicketDetails']);
    Route::post('/select_seat', [SelectSeatController::class, 'getSelectSeat']);
    Route::get('/userprofile', [AuthController::class, 'getUser']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
Route::get('/showtimes/film', [ShowtimeController::class, 'getShowtimesByFilm']);
