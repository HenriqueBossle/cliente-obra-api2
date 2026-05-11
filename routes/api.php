<?php

use App\Http\Controllers\Api\ConstructionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

Route::apiResource('constructions', ConstructionController::class)->except(['create', 'edit']);