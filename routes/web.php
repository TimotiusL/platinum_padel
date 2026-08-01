<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\MatchController as AdminMatchController;
use App\Http\Controllers\Admin\PlayerController as AdminPlayerController;
use App\Http\Controllers\Admin\ScoreboardController;
use App\Http\Controllers\Admin\TournamentController as AdminTournamentController;

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminController::class, 'login'])->name('admin.login');
    Route::post('/login', [AdminController::class, 'authenticate']);
    Route::get('/logout', [AdminController::class, 'logout'])->name('admin.logout');

    Route::middleware('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])
            ->name('admin.dashboard');

        Route::resource('players', AdminPlayerController::class);
        Route::resource('tournaments', AdminTournamentController::class);

        Route::get('/categories', [CategoryController::class, 'index'])
            ->name('categories.index');

        Route::post('/categories/{category}/generate-group-matches', [AdminMatchController::class, 'generate'])
            ->name('matches.generate');

        Route::post('/categories/{category}/generate-bracket', [AdminMatchController::class, 'generateBracket'])
            ->name('matches.generate-bracket');

        Route::resource('matches', AdminMatchController::class)
            ->except(['show']);

        Route::get('/scoreboard', [ScoreboardController::class, 'index'])
            ->name('scoreboard.index');

        Route::post('/scoreboard/{match}', [ScoreboardController::class, 'update'])
            ->name('scoreboard.update');
    });
});

/*
|--------------------------------------------------------------------------
| SPA ROUTE (MUST BE LAST)
|--------------------------------------------------------------------------
*/

Route::view('/{any}', 'app')->where('any', '^(?!api|admin).*$');
