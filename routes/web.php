<?php

use App\Http\Controllers\API\ApiSurveyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SurveyController;
use Illuminate\Support\Facades\Route;

// Redirect root to dashboard
Route::get('/', function () {
    return redirect()->route('dashboard.home');
});

// Public Cyber Dashboard Routes (no auth required)
Route::prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/', function () {
        return view('cyber.home');
    })->name('home');

    Route::get('/explore', function () {
        return view('cyber.explore');
    })->name('explore');

    Route::get('/form/{id}', function ($id) {
        return view('cyber.form-detail', ['id' => $id]);
    })->name('form-detail');
});

// Protected Cyber Dashboard Routes (auth required)
Route::middleware(['auth', 'verified'])->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/admin', [SurveyController::class, 'index'])->name('admin');
    Route::delete('/admin/survey/{survey}', [SurveyController::class, 'destroy'])->name('admin.survey.destroy');

    Route::get('/profile', function () {
        return view('cyber.profile');
    })->name('profile');
});

// Explore page
Route::get('/explore', function () {
    // TODO: Link correct view from blades
    return view('explore');
})->name('explore');

Route::middleware('auth')->group(function () {
    Route::get('/profile-edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile-edit', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile-edit', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/survey/create', [SurveyController::class, 'createView'])->name('survey.create');
    Route::post('/survey', [SurveyController::class, 'store'])->name('survey.store');
    Route::get('/survey/edit/{survey}', [SurveyController::class, 'editView'])->name('survey.edit');
    Route::patch('/survey/{survey}', [SurveyController::class, 'update'])->name('survey.update');
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

// Rest-API Endpoint for Surveys
Route::get('/api/surveys/{survey}', [ApiSurveyController::class, 'show']);

require __DIR__.'/auth.php';
