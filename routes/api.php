<?php

use App\Http\Controllers\SpaceController;
use App\Http\Controllers\UserController;
use App\Models\Link;
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


Route::post('/users', [UserController::class, 'login']);



Route::middleware('auth:sanctum')->group(function(){
    Route::get('/logout', [UserController::class, 'logout']);

    Route::put('/users', [UserController::class, 'update']);

    Route::delete('/users', [UserController::class, 'destroy']);

    Route::get('/users/{nickname}', [UserController::class, 'show']);

    // Route::get('/photo/{name}', [UserController::class, 'photo']);
});

Route::apiResource('/spaces', SpaceController::class);

Route::apiResource('/links', Link::class);
