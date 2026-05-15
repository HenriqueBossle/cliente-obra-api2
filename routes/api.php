<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ConstructionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::apiResource('constructions', ConstructionController::class)->except(['create', 'show' ,'edit']);