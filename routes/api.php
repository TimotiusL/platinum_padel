<?php

use App\Http\Controllers\Api\LeaderboardController;
use App\Http\Controllers\Api\MatchController;
use App\Http\Controllers\Api\PlayerController;
use App\Http\Controllers\Api\TournamentController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/players/leaders', [PlayerController::class, 'leaders']);
    Route::get('/players', [PlayerController::class, 'index']);
    Route::get('/players/{id}', [PlayerController::class, 'show'])->whereNumber('id');

    Route::get('/tournaments/history', [TournamentController::class, 'history']);
    Route::get('/tournaments', [TournamentController::class, 'index']);
    Route::get('/tournaments/{id}', [TournamentController::class, 'show'])->whereNumber('id');

    Route::get('/matches/bracket/{tournamentId}', [MatchController::class, 'bracket'])->whereNumber('tournamentId');
    Route::get('/matches', [MatchController::class, 'index']);
    Route::get('/matches/{id}', [MatchController::class, 'show'])->whereNumber('id');

    Route::get('/leaderboard', [LeaderboardController::class, 'index']);
});
