<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SimulateController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/simulate/register-meal', [SimulateController::class, 'registerMeal']);

Route::get('/simulate/daily-summary', [SimulateController::class, 'dailySummary']);

Route::get('/simulate/stats', [SimulateController::class, 'weeklyStats']);


