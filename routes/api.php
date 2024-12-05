<?php

use App\Http\Controllers\StudentController;
use App\Http\Controllers\Api\ListFilmsController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImdbController;
use App\Models\Payment;

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
Route::get('/listfilms', [ListFilmsController::class, 'listfilms']);
Route::get('/payment', [PaymentController::class, 'payment']);
Route::get('/login', [LoginController::class, 'login']);
