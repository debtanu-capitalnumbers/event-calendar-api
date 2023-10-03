<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Event\EventController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Event\ActiveEventController;
use App\Http\Controllers\Api\Event\EventnoauthController;
use App\Http\Controllers\Api\Auth\ResetPasswordController;
use App\Http\Controllers\Api\Auth\ForgotPasswordController;

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

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('/events', EventController::class);
    Route::post('/events/{event}', [EventController::class, 'update']);
    Route::get('/events/calendar/events', [EventController::class, 'allCalendarEvents']);
    Route::patch('/events/{event}/active', ActiveEventController::class);
    Route::post('/events/export/file', [EventController::class, 'export']);
});
Route::prefix('auth')->group(function () {
    Route::post('/login', LoginController::class);
    Route::post('/forgotPassword', ForgotPasswordController::class);
    Route::post('/resetPassword', ResetPasswordController::class);
    Route::post('/logout', LogoutController::class)->middleware('auth:sanctum');
    Route::post('/register', RegisterController::class);
    Route::get('/user', function (Request $request) {
        return $request->user();
    })->middleware('auth:sanctum');
});

