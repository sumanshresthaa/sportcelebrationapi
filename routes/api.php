<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthOtpController;
use App\Http\Controllers\FastApiController;
use App\Http\Controllers\PointController;
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

Route::post('/register', [AuthOtpController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);


//For logged in users so these runs only with sessions
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/points', [PointController::class, 'store']);
    Route::get('/points', [PointController::class, 'index']);
    Route::get('/global-rank', [PointController::class, 'globalRank']);

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::delete('/user', [AuthController::class, 'deleteUser']);

    //Logged in user information
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

Route::post('/verify-otp', [AuthOtpController::class, 'verifyOtp']);
Route::post('/resend-otp', [AuthOtpController::class, 'resendOtp']);
