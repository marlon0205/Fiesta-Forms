<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\API\ApiSurveyController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Explore page
Route::get('/explore', function () {
    // TODO: Link correct view from blades
    return view('explore');
})->name('explore');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Saves a new survey (POST)
Route::post('/surveys', [SurveyController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('surveys.store');

// Shows a specific survey (GET) and its relations in a html view
// TODO: 16.01.2026 - Marked for removal. Wait until we can open and see Forms.
Route::get('/testing/surveys/{survey}', [SurveyController::class, 'showView'])
    ->middleware(['auth']) // Optional: Nur für eingeloggte User
    ->name('surveys.show');



/*
 *
 * REST-API Endpoints
 *
 */

//Rest-API Endpoint for Surveys
Route::get('/api/surveys/{survey}', [ApiSurveyController::class, 'show']);



require __DIR__.'/auth.php';
