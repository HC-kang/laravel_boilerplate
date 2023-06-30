<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\UploadedImageController;
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

Route::group(['prefix' => 'admin'], function () {
Route::post('users/login', [UserController::class, 'loginUser']);
Route::middleware(['auth:api', 'scope:admin,super-admin'])->group(function () {
        Route::post('users', [UserController::class, 'createUser'])->middleware('scope:super-admin');
        Route::get('/users', [UserController::class, 'index']);
        Route::patch('/users/{userId}', [UserController::class, 'update'])->middleware('scope:super-admin');
        Route::delete('/users/{userId}', [UserController::class, 'destroy'])->middleware('scope:super-admin');
        Route::get('/users/me', [UserController::class, 'me']);
    
        Route::group(['prefix' => 'images'], function () {
            Route::get('/', [UploadedImageController::class, 'index']);
            Route::post('/', [UploadedImageController::class, 'store']);
            Route::delete('/{imageId}', [UploadedImageController::class, 'destroy']);
        });
    });
});
