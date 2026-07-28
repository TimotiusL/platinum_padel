<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\PlayerController as ApiPlayerController;
use App\Http\Controllers\Api\TournamentController;
use App\Http\Controllers\Api\MatchController;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\PlayerController as AdminPlayerController;
use App\Http\Controllers\Admin\TournamentController as AdminTournamentController;

/*
|--------------------------------------------------------------------------
| API ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('api/v1')->group(function () {

    Route::get('/players', [ApiPlayerController::class, 'index']);
    Route::get('/players/{id}', [ApiPlayerController::class, 'show']);
    Route::get('/players/leaders', [ApiPlayerController::class, 'leaders']);

    Route::get('/tournaments', [TournamentController::class, 'index']);
    Route::get('/tournaments/{id}', [TournamentController::class, 'show']);
    Route::get('/tournaments/history', [TournamentController::class, 'history']);

    Route::get('/matches', [MatchController::class, 'index']);
    Route::get('/matches/{id}', [MatchController::class, 'show']);
    Route::get('/matches/bracket/{tournamentId}', [MatchController::class, 'bracket']);

});


/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->group(function () {

    Route::get('/login', [AdminController::class, 'login'])->name('admin.login');
    Route::post('/login', [AdminController::class, 'authenticate']);
    Route::get('/logout', [AdminController::class, 'logout']);

    Route::middleware('admin')->group(function () {

        Route::get('/dashboard', [AdminController::class, 'dashboard'])
            ->name('admin.dashboard');

        Route::resource('players', AdminPlayerController::class);
        Route::resource('tournaments', AdminTournamentController::class);

    });

});


/*
|--------------------------------------------------------------------------
| SPA ROUTE (MUST BE LAST)
|--------------------------------------------------------------------------
*/

Route::view('/{any}', 'app')->where('any', '^(?!api|admin).*$');