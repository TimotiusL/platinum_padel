<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MatchTournament;
use Illuminate\Http\Request;
use App\Models\Team;
class ScoreboardController extends Controller
{
    public function index()
    {
        $matches = MatchTournament::with([
            'teamA.members.player.user',
            'teamB.members.player.user',
        ])
            ->orderBy('court')
            ->get();

        return view('admin.scoreboard.index', compact('matches'));
    }

    public function update(Request $request, MatchTournament $match)
    {
        $request->validate([
            'score_team_a' => 'required|integer|min:0',
            'score_team_b' => 'required|integer|min:0',
        ]);

        $match->update([
            'score_team_a' => $request->score_team_a,
            'score_team_b' => $request->score_team_b,
        ]);

        if ($request->has('finish') && $match->status != 'finished') {
            $teamA = Team::find($match->team_a_id);
            $teamB = Team::find($match->team_b_id);

            $teamA->increment('played');
            $teamB->increment('played');

            if ($request->score_team_a > $request->score_team_b) {

                $teamA->increment('win');
                $teamA->increment('points', 3);

                $teamB->increment('lose');

                $match->winner_team_id = $teamA->id;

            } elseif ($request->score_team_b > $request->score_team_a) {

                $teamB->increment('win');
                $teamB->increment('points', 3);

                $teamA->increment('lose');

                $match->winner_team_id = $teamB->id;
            }

            $match->status = 'finished';
            $match->save();
        }

        return redirect()
            ->route('scoreboard.index')
            ->with('success', 'Score berhasil diperbarui.');
    }
}