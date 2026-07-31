<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Team;

class LeaderboardController extends Controller
{
    public function index()
    {
        $teams = Team::with([
            'members.player.user'
        ])
            ->orderByDesc('points')
            ->orderByDesc('win')
            ->get();

        return response()->json([
            'leaderboard' => $teams->map(function ($team) {

                return [
                    'id' => $team->id,
                    'team_code' => $team->team_code,
                    'played' => $team->played,
                    'win' => $team->win,
                    'lose' => $team->lose,
                    'points' => $team->points,

                    'players' => $team->members->map(function ($member) {
                        return $member->player->user->name;
                    })->values()
                ];
            })
        ]);
    }
}