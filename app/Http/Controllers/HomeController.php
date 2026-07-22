<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('home.index');
    }

    public function tournaments(): RedirectResponse
    {
        return redirect('/#/tournaments');
    }

    public function tournament(string $id): RedirectResponse
    {
        return redirect('/#/tournament/' . rawurlencode($id));
    }

    public function players(): RedirectResponse
    {
        return redirect('/#/players');
    }

    public function player(string $id): RedirectResponse
    {
        return redirect('/#/player/' . rawurlencode($id));
    }
}
