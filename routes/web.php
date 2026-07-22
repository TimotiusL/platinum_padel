<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Compatibility URLs: the reference interface uses hash-based navigation.
Route::get('/tournaments', [HomeController::class, 'tournaments'])->name('tournaments.index');
Route::get('/tournaments/{id}', [HomeController::class, 'tournament'])->name('tournaments.show');
Route::get('/players', [HomeController::class, 'players'])->name('players.index');
Route::get('/players/{id}', [HomeController::class, 'player'])->name('players.show');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
