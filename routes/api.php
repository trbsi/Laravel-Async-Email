<?php

use App\Code\V1\Emails\Controllers\EmailController;
use App\Code\V1\Users\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/tokens/create', [UserController::class, 'createToken']);

Route::middleware('auth:sanctum')->prefix('v1')->group(function() {
    Route::get('user', [UserController::class, 'getUser']);
    Route::prefix('email')->group(function() {
        Route::post('send', [EmailController::class, 'send']);
        Route::get('list', [EmailController::class, 'list']);
    });
    Route::post('send', [EmailController::class, 'send']);
});