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
use App\Http\Controllers\Api\TicketHistoryController;
use App\Http\Controllers\Api\AuthController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
// Route::middleware('auth:sanctum')->get('/student', [StudentController::class, 'index']);
Route::get('/movies', [ImdbController::class, 'index']);
Route::get('/listfilms', [ListFilmsController::class, 'listfilms']);
Route::get('/payment', [PaymentController::class, 'payment']);
// Route::middleware('auth:api')->get('/login', [LoginController::class, 'getUserInfo']);
// Route::post('auth/google',  [LoginController::class, 'loginWithGoogle']);
// Route::post('auth/google/callback', 'handleGoogleCallback',  [LoginController::class, 'loginWithGoogle']);
Route::get('/ticket/{bookingId}', [MyTicketController::class, 'getTicketDetails']);
Route::get('/select_seat', [SelectSeatController::class, 'getSelectSeat']);
Route::get('/movie_detail', [MovieDetailController::class, 'getMovieDetails']);
Route::get('/ticket_history/{userId}', [TicketHistoryController::class, 'getTicketHistory']);

// Đăng nhập bằng email và password
Route::post('/login', [AuthController::class, 'login']);

// Đăng nhập bằng Google
Route::post('/login/google', [AuthController::class, 'loginOrRegisterWithGoogle']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');

// Lấy thông tin người dùng hiện tại (yêu cầu xác thực)
Route::get('/userprofile', [AuthController::class, 'getUser'])->middleware('auth:api');
