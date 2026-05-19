<?php

use App\Http\Controllers\AdminController;
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

    Route::get('/form/{survey}', [SurveyController::class, 'show'])->name('form-detail');
    Route::post('/form/{survey}/vote', [SurveyController::class, 'vote'])->middleware(['auth', 'throttle:10,1'])->name('form.vote');
});

// Protected Cyber Dashboard Routes (auth required)
Route::middleware(['auth', 'verified'])->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::middleware('admin')->group(function () {
        Route::get('/admin', [AdminController::class, 'index'])->name('admin');
        Route::post('/admin/integrations', [AdminController::class, 'syncCategories'])->name('admin.integrations.sync');
        Route::delete('/admin/survey/{survey}', [SurveyController::class, 'destroy'])->name('admin.survey.destroy');
        Route::post('/admin/rewards', [AdminController::class, 'storeReward'])->name('admin.rewards.store');
        Route::patch('/admin/rewards/{reward}', [AdminController::class, 'updateReward'])->name('admin.rewards.update');
        Route::delete('/admin/rewards/{reward}', [AdminController::class, 'destroyReward'])->name('admin.rewards.destroy');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
});

// Explore page
Route::get('/explore', function () {
    // TODO: Link correct view from blades
    return view('explore');
})->name('explore');

Route::middleware('auth')->group(function () {
    Route::get('/profile-edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile-edit', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/password-update', [ProfileController::class, 'updatePassword'])->name('password.update');
    Route::delete('/profile-edit', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/survey/create', [SurveyController::class, 'createView'])->name('survey.create');
    Route::post('/survey', [SurveyController::class, 'store'])->name('survey.store');
    Route::get('/survey/edit/{survey}', [SurveyController::class, 'editView'])->name('survey.edit');
    Route::patch('/survey/{survey}', [SurveyController::class, 'update'])->name('survey.update');
    Route::get('/user/{user}/profile', [ProfileController::class, 'show'])->name('user.profile');
    Route::get('/user/{user}/edit', [ProfileController::class, 'editUser'])->name('user.edit');
    Route::patch('/user/{user}', [ProfileController::class, 'updateUser'])->name('user.update');
    Route::put('/user/{user}/password', [ProfileController::class, 'updateUserPassword'])->name('user.password.update');
    Route::delete('/user/{user}', [ProfileController::class, 'destroyUser'])->name('user.destroy');
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

// Demo Endpoint for Category Integration Test
Route::get('/api/demo/categories', [\App\Http\Controllers\API\ApiDemoController::class, 'categories']);

require __DIR__.'/auth.php';
