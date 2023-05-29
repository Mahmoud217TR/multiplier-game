<?php

use App\Http\Controllers\{
    GameController,
    UserController
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::prefix('user')->group(function () {
    Route::post('login', [UserController::class, 'login']);
    Route::post('register', [UserController::class, 'register']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('user/check',[UserController::class, 'check']);
    Route::post('logout',[UserController::class,'logout']);
    Route::prefix('game')
        ->group(function () {
            Route::post('{game}/join', [GameController::class, 'join']);
            Route::post('{game}/leave', [GameController::class, 'leave']);
            Route::post('{game}/guess', [GameController::class, 'guess']);
        });
});
