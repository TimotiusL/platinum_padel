<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MatchTournament;
use Illuminate\Http\Request;

class MatchController extends Controller
{
    public function index(Request $request)
    {
        $query = MatchTournament::with(['team1.player1', 'team1.player2', 'team2.player1', 'team2.player2', 'winner']);

        if ($request->has('tournament_id')) {
            $query->where('tournament_id', $request->tournament_id);
        }

        if ($request->has('stage')) {
            $query->where('stage', $request->stage);
        }

        return response()->json([
            'matches' => $query->orderBy('scheduled_at')->get()
        ]);
    }

    public function show($id)
    {
        $match = MatchTournament::with([
            'team1.player1', 'team1.player2',
            'team2.player1', 'team2.player2',
            'winner',
            'tournament'
        ])->findOrFail($id);

        return response()->json([
            'match' => $match
        ]);
    }

    public function bracket($tournamentId)
    {
        $stages = ['r16', 'qf', 'sf', 'final'];
        $bracket = [];

        foreach ($stages as $stage) {
            $bracket[$stage] = MatchTournament::with([
                'team1.player1', 'team1.player2',
                'team2.player1', 'team2.player2',
                'winner'
            ])
            ->where('tournament_id', $tournamentId)
            ->where('stage', $stage)
            ->orderBy('scheduled_at')
            ->get();
        }

        return response()->json($bracket);
    }
}