<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserGroupController;
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

// public
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::get('posts', [PostController::class, 'index']);
Route::get('posts/{id}', [PostController::class, 'show']);

// protect
Route::group(['middleware' => 'auth:sanctum'], function(){ // 無登入使用者不可使用以下api
    Route::post('setGroup', [UserGroupController::class, 'setGroup'])->middleware(['ability:admin']);

    Route::post('posts', [PostController::class, 'store'])->middleware(['ability:admin,manager']);
    Route::put('posts/{id}', [PostController::class, 'update'])->middleware(['ability:admin,manager']);
    Route::delete('posts/{id}', [PostController::class, 'destroy'])->middleware(['ability:admin,manager']);
    Route::post('logout', [AuthController::class, 'logout']);
});

