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
        $tournament = Tournament::with(['matches' => function($q) {
            $q->with(['team1.player1', 'team1.player2', 'team2.player1', 'team2.player2', 'winner']);
        }, 'teams.player1', 'teams.player2'])->findOrFail($id);

        // Group matches by stage
        $stages = ['group_stage', 'r16', 'qf', 'sf', 'final'];
        $groupedMatches = [];
        foreach ($stages as $stage) {
            $groupedMatches[$stage] = $tournament->matches
                ->where('stage', $stage)
                ->values();
        }

        // Group teams by group_code
        $groupedTeams = $tournament->teams->groupBy('group_code');

        return response()->json([
            'tournament' => $tournament,
            'matches' => $groupedMatches,
            'teams' => $groupedTeams,
            'categories' => $this->getCategories()
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