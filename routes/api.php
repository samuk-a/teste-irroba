<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\ClassesController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\UserTypeController;
use App\Http\Controllers\API\PermissionController;
use App\Http\Controllers\API\RequestController;
use App\Http\Controllers\API\NotificationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [RegisterController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::resource('classes', ClassesController::class);
    Route::resource('users', UserController::class);
    Route::resource('userTypes', UserTypeController::class);
    Route::resource('permissions', PermissionController::class);
    Route::resource('requests', RequestController::class);
    Route::post('notify', [NotificationController::class, 'sendRequestNotification']);
    Route::get('notifications', [NotificationController::class, 'index']);
});

Route::fallback(function(){
    return response()->json([
        'message' => 'Page Not Found. If error persists, contact suport@irrobaschool.com'], 404);
});