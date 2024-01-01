<?php

use App\Http\Controllers\LinkController;
use App\Http\Controllers\SpaceController;
use App\Http\Controllers\UserController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/login', [UserController::class, 'login']);

Route::post('/logout', [UserController::class, 'logout'])->middleware(['auth:sanctum']);

Route::get('/notifications', [UserController::class, 'notifications'])->middleware(['auth:sanctum']);

Route::delete('/notifications', [UserController::class, 'notificationsDelete'])->middleware(['auth:sanctum']);

Route::post('/users', [UserController::class, 'store']);



Route::prefix('v1')->group(function(){ 
    
    Route::middleware('auth:sanctum')->group(function(){

        Route::put('/users', [UserController::class, 'update']);

        Route::delete('/users', [UserController::class, 'destroy']);

        Route::get('/users/me', [UserController::class, 'show']);

        Route::apiResource('/spaces', SpaceController::class);

        Route::apiResource('/links', LinkController::class)->except(['index', 'show']);
    });

    Route::get('/links/{short}', [LinkController::class, 'show']);

    Route::get('/photo/{name}', [UserController::class, 'photo']);
});

