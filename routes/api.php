<?php

use App\Http\Controllers\Api\PlayerController;
use App\Http\Controllers\Api\MatchController;
use App\Http\Controllers\Api\TournamentController;
use Illuminate\Support\Facades\Route;

// Public API - No auth needed
Route::prefix('v1')->group(function () {
    // Players
    Route::get('/players', [PlayerController::class, 'index']);
    Route::get('/players/{id}', [PlayerController::class, 'show']);
    Route::get('/players/leaders', [PlayerController::class, 'leaders']);

    // Tournaments
    Route::get('/tournaments', [TournamentController::class, 'index']);
    Route::get('/tournaments/{id}', [TournamentController::class, 'show']);
    Route::get('/tournaments/history', [TournamentController::class, 'history']);

    // Matches
    Route::get('/matches', [MatchController::class, 'index']);
    Route::get('/matches/{id}', [MatchController::class, 'show']);
    Route::get('/matches/bracket/{tournamentId}', [MatchController::class, 'bracket']);
});