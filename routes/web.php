<?php

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
    Route::get('/admin', function () {
        return view('cyber.admin');
    })->name('admin');

    Route::get('/profile', function () {
        return view('cyber.profile');
    })->name('profile');
});

// Neue Route für die Anzeige einer einzelnen Umfrage
Route::get('/surveys/{survey}', [SurveyController::class, 'showView'])
    ->middleware(['auth']) // Optional: Nur für eingeloggte User
    ->name('surveys.show');

Route::middleware('auth')->group(function () {
    Route::get('/profile-edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile-edit', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile-edit', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
