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
use App\Http\Controllers\api\StripeWebhookController;
use App\Http\Controllers\Api\TicketHistoryController;
use App\Http\Controllers\Api\FilmSearchController;
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


Route::get('/listfilms', [ListFilmsController::class, 'listfilms']);
// Route::get('/payment/{bookingId}', [PaymentController::class, 'PaymentDetails']);
// Route::middleware('auth:api')->get('/login', [LoginController::class, 'getUserInfo']);
// Route::post('auth/google',  [LoginController::class, 'loginWithGoogle']);
// Route::post('auth/google/callback', 'handleGoogleCallback',  [LoginController::class, 'loginWithGoogle']);
Route::get('/select_seat', [SelectSeatController::class, 'getSelectSeat']);
Route::get('/movie_detail', [MovieDetailController::class, 'getMovieDetails']);
Route::get('/films/search', [FilmSearchController::class, 'searchByName']);

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
    Route::get('/userprofile', [AuthController::class, 'getUser']);
    Route::put('/userprofile', [AuthController::class, 'update']);
    Route::post('/changepassword', [AuthController::class, 'changePassword']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/showtimes/film', [ShowtimeController::class, 'getShowtimesByFilm']);
    Route::post('/showtimes/seats', [SeatStatusController::class, 'getSeatsByTimeAndDay']);
    Route::get('/payment', [PaymentController::class, 'payment']);
    Route::post('/purchase', [SelectSeatController::class, 'getSelectSeat']);
    Route::post('/purchase/cancel', [SelectSeatController::class, 'cancelPurchase']);
    Route::post('/confirm_payment', [PaymentController::class, 'confirmPayment']);
    Route::post('/cancel_payment', [PaymentController::class, 'cancelPayment']);
    Route::get('/ticket', [MyTicketController::class, 'getTicketDetails']);
    Route::get('/ticket_history', [TicketHistoryController::class, 'getTicketHistory']);
});
Route::post('/stripe/webhook', [StripeWebhookController::class, 'handleWebhook']);
