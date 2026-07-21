<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/', function () {
    return view('home.index');
})->name('home');

Route::get('/tournaments', function () {
    return view('tournaments.index');
})->name('tournaments.index');

Route::get('/tournaments/{id}', function ($id) {
    return view('tournaments.show');
})->name('tournaments.show');

Route::get('/players', function () {
    return view('players.index');
})->name('players.index');

Route::get('/players/{id}', function ($id) {
    return view('players.show');
})->name('players.show');

Route::get('/registrations/create', function () {
    return view('registrations.create');
})->name('registrations.create');

