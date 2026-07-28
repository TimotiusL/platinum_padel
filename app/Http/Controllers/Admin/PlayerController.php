<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Player;

class PlayerController extends Controller
{
    public function index()
    {
        $players = Player::with('user')->get();

        return view('players.index', compact('players'));
    }

    public function create()
{
    return view('players.create');
}
    public function store(Request $request)
{
    $user = User::create([
        'name' => $request->name,
        'email' => uniqid().'@player.com',
        'password' => bcrypt('password')
    ]);

    Player::create([
        'user_id' => $user->id,
        'phone' => $request->phone,
        'birth_date' => $request->birth_date,
        'gender' => $request->gender,
        'city' => $request->city,
        'ranking_point' => 0
    ]);

    return redirect()->route('players.index');
}
    public function show() {}
    public function edit(Player $player)
{
    return view('players.edit', compact('player'));
}
    public function update(Request $request, Player $player)
{
    $player->user->update([
        'name' => $request->name
    ]);

    $player->update([
        'phone' => $request->phone,
        'birth_date' => $request->birth_date,
        'gender' => $request->gender,
        'city' => $request->city
    ]);

    return redirect()->route('players.index');
}
    public function destroy(Player $player)
{
    $player->user()->delete();

    $player->delete();

    return redirect()->route('players.index');
}
}