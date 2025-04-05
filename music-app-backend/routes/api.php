<?php

use App\Http\Controllers\AlbumController;
use App\Http\Controllers\VoteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::apiResource('albums', AlbumController::class)->only(['index', 'destroy']);
    Route::post('/albums/{album}/vote', [VoteController::class, 'vote']);
});

