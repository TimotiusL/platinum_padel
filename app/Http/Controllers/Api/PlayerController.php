<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Player;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
   public function index()
{
    $players = Player::with('user')
        ->orderByDesc('ranking_point')
        ->get()
        ->map(function ($p) {
            return [
                'id' => $p->id,
                'name' => $p->user->name,
                'location' => $p->city,
                'rank' => $p->ranking_point,
                'titles' => 0,
                'photo' => $p->photo,
            ];
        });

    return response()->json([
        'players' => $players
    ]);
}

    public function show($id)
    {
        $player = Player::with(['teams'])->findOrFail($id);
        return response()->json([
            'player' => $player,
            'profile' => $player->profile_data ?? $this->getDefaultProfile()
        ]);
    }

    public function leaders()
    {
        return response()->json([
            'players' => Player::where('titles', '>', 0)
                ->orderBy('titles', 'desc')
                ->limit(10)
                ->get()
        ]);
    }

    private function getDefaultProfile()
    {
        return [
            'name' => 'Player',
            'main' => 0,
            'menang' => 0,
            'winrate' => 0,
            'juara' => 0,
            'years' => [],
            'history' => []
        ];
    }
}
