<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ConstructionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// Públicas
Route::middleware('throttle:8,1')->group(function () {

    Route::post('/register', [AuthController::class, 'register']);

    Route::post('/login', [AuthController::class, 'login']);
});

// Privadas
Route::middleware('auth:sanctum')->group(function () {

    Route::middleware('throttle:60,1')->group(function () {

        Route::get('/constructions', [ConstructionController::class, 'index']);

        Route::get('/constructions/{construction}', [ConstructionController::class, 'show']);

    });


    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::apiResource('constructions', ConstructionController::class)
        ->except(['index', 'show']);


});
