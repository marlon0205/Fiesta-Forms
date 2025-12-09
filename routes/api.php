<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SurveyController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Diese Zeile erstellt alle notwendigen REST-API-Routen für Surveys.
Route::apiResource('surveys', SurveyController::class);
