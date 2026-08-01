<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Player;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    public function index(Request $request)
    {
        $limit = min(max($request->integer('limit', 100), 1), 500);

        $players = Player::with('user')
            ->orderByDesc('ranking_point')
            ->limit($limit)
            ->get()
            ->map(function (Player $player, int $index) {
                return [
                    'id' => $player->id,
                    'name' => $player->user?->name ?? 'Unknown',
                    'location' => $player->city,
                    'rank' => $index + 1,
                    'ranking_point' => (int) $player->ranking_point,
                    'titles' => 0,
                    'photo' => $player->photo,
                ];
            });

        return response()->json([
            'players' => $players,
        ]);
    }

    public function show(int $id)
    {
        $player = Player::with([
            'user',
            'teams.members.player.user',
        ])->findOrFail($id);

        return response()->json([
            'player' => [
                'id' => $player->id,
                'name' => $player->user?->name ?? 'Unknown',
                'location' => $player->city,
                'ranking_point' => (int) $player->ranking_point,
                'photo' => $player->photo,
                'teams' => $player->teams,
            ],
            'profile' => $this->getDefaultProfile($player),
        ]);
    }

    public function leaders(Request $request)
    {
        $limit = min(max($request->integer('limit', 6), 1), 50);

        $players = Player::with('user')
            ->orderByDesc('ranking_point')
            ->limit($limit)
            ->get()
            ->map(fn (Player $player) => [
                'id' => $player->id,
                'name' => $player->user?->name ?? 'Unknown',
                'rank' => (int) $player->ranking_point,
                'ranking_point' => (int) $player->ranking_point,
                'titles' => 0,
                'photo' => $player->photo,
            ]);

        return response()->json([
            'players' => $players,
        ]);
    }

    private function getDefaultProfile(Player $player): array
    {
        return [
            'name' => $player->user?->name ?? 'Player',
            'main' => 0,
            'menang' => 0,
            'winrate' => 0,
            'juara' => 0,
            'years' => [],
            'history' => [],
        ];
    }
}
