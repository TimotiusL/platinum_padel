<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tournament;
use App\Models\Match;
use App\Models\Team;
use Illuminate\Http\Request;

class TournamentController extends Controller
{
    public function index()
    {
        return response()->json([
            'tournaments' => Tournament::orderBy('start_date', 'desc')->get()
        ]);
    }

   public function show($id)
{
    $tournament = Tournament::with([
        'categories.teams.members.player.user'
    ])->findOrFail($id);

    $teams = [];

    foreach ($tournament->categories as $category) {

        foreach ($category->teams as $team) {

            $players = $team->members
                ->sortByDesc('is_captain')
                ->map(function ($member) {
                    return $member->player?->user?->name ?? '?';
                })
                ->values()
                ->toArray();

            $teams[$team->group_code][] = [
                'code' => $team->team_code,
                'players' => $players
            ];
        }
    }

    return response()->json([
        'tournament' => $tournament,
        'teams' => $teams,
        'matches' => [
            'r16' => [],
            'qf' => [],
            'sf' => [],
            'final' => []
        ]
    ]);
}

    public function history()
    {
        return response()->json([
            'history' => Tournament::where('status', 'finished')
                ->orderBy('start_date', 'desc')
                ->limit(10)
                ->get()
        ]);
    }

    private function getCategories()
    {
        return [
            ['id' => 'rookie-men', 'label' => 'Rookie', 'tier' => 'Men'],
            ['id' => 'bronze-men', 'label' => 'Bronze', 'tier' => 'Men'],
            ['id' => 'upper-women', 'label' => 'Upper Beginner', 'tier' => 'Women'],
            ['id' => 'open-men', 'label' => 'Open', 'tier' => 'Men'],
            ['id' => 'open-women', 'label' => 'Open', 'tier' => 'Women'],
        ];
    }
}
