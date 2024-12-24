<?php

use App\Http\Controllers\Api\MovieDetailController;
use App\Http\Controllers\Api\SelectSeatController;
use App\Http\Controllers\Api\ListFilmsController;
use App\Http\Controllers\Api\PasswordResetController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SeatStatusController;
use App\Http\Controllers\Api\ShowtimeController;
use App\Http\Controllers\Api\MyTicketController;
use App\Http\Controllers\Api\TicketHistoryController;
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
Route::get('/payment/{bookingId}', [PaymentController::class, 'PaymentDetails']);
// Route::middleware('auth:api')->get('/login', [LoginController::class, 'getUserInfo']);
// Route::post('auth/google',  [LoginController::class, 'loginWithGoogle']);
// Route::post('auth/google/callback', 'handleGoogleCallback',  [LoginController::class, 'loginWithGoogle']);
Route::get('/ticket/{bookingId}', [MyTicketController::class, 'getTicketDetails']);
Route::get('/select_seat', [SelectSeatController::class, 'getSelectSeat']);
Route::get('/movie_detail', [MovieDetailController::class, 'getMovieDetails']);
Route::get('/ticket_history/{userId}', [TicketHistoryController::class, 'getTicketHistory']);

// Đăng ký
Route::post('/register', [AuthController::class, 'register']);

// Đăng nhập bằng email và password
Route::post('/login', [AuthController::class, 'login']);

// Đăng nhập bằng Google
Route::post('/login/google', [AuthController::class, 'loginOrRegisterWithGoogle']);

Route::prefix('password')->group(function () {
    Route::post('/request-reset-code', [PasswordResetController::class, 'requestResetCode']);
    Route::post('/verify-reset-code', [PasswordResetController::class, 'verifyResetCode']);
    Route::post('/reset-password', [PasswordResetController::class, 'resetPassword']);
});
Route::middleware(['auth:api'])->group(function () {
    Route::get('/payment', [PaymentController::class, 'payment']);
    Route::get('/ticket', [MyTicketController::class, 'getTicketDetails']);
    Route::post('/select_seat', [SelectSeatController::class, 'getSelectSeat']);
    Route::get('/userprofile', [AuthController::class, 'getUser']);
    Route::put('/userprofile', [AuthController::class, 'update']);
    Route::post('/changepassword', [AuthController::class, 'changePassword']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/showtimes/seats', [SeatStatusController::class, 'getSeatsByTimeAndDay']);
});
Route::get('/showtimes/film', [ShowtimeController::class, 'getShowtimesByFilm']);
