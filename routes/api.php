<?php

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

Route::post('/auth/login', [App\Http\Controllers\Api\AuthController::class, 'login']);
Route::post('/auth/register', [App\Http\Controllers\Api\AuthController::class, 'register']);

Route::group([
    'middleware' => ['auth:sanctum']
], function () {

    Route::post('/auth/logout', [App\Http\Controllers\Api\AuthController::class, 'logout']);
});
