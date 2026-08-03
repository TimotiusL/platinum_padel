<?php

namespace App\Http\Controllers\Admin;

use App\Models\MatchSet;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\MatchTournament;
use App\Services\TournamentService;
use Illuminate\Http\Request;

class ScoreboardController extends Controller
{
    public function __construct(private readonly TournamentService $tournamentService)
    {
    }

    public function index(Request $request)
    {
        $query = MatchTournament::with([
            'category.tournament',
            'teamA.members.player.user',
            'teamB.members.player.user',
            'winner',
        ]);

        if ($request->filled('match')) {
            $query->whereKey($request->integer('match'));
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->integer('category_id'));
        }

        $matches = $query
            ->orderByRaw("FIELD(status, 'ongoing', 'scheduled', 'finished')")
            ->orderBy('match_date')
            ->orderBy('court')
            ->get();

        $categories = Category::with('tournament')
            ->orderBy('tournament_id')
            ->orderBy('name')
            ->get();

        return view('admin.scoreboard.index', compact('matches', 'categories'));
    }

    public function update(Request $request, MatchTournament $match)
    {
        $data = $request->validate([
            'score_team_a' => ['required', 'integer', 'min:0', 'max:255'],
            'score_team_b' => ['required', 'integer', 'min:0', 'max:255'],
        ]);

        $scoreA = (int) $data['score_team_a'];
        $scoreB = (int) $data['score_team_b'];

        $currentSet = MatchSet::where('match_id', $match->id)
            ->count() + 1;

        $winner = null;

        if ($scoreA > $scoreB) {
            $winner = $match->team_a_id;
        } elseif ($scoreB > $scoreA) {
            $winner = $match->team_b_id;
        }

        MatchSet::create([
            'match_id' => $match->id,
            'set_number' => $currentSet,
            'score_team_a' => $scoreA,
            'score_team_b' => $scoreB,
            'winner_team_id' => $winner,
        ]);

        $match->update([
            'score_team_a' => 0,
            'score_team_b' => 0,
            'status' => 'ongoing',
        ]);

        $teamASet = MatchSet::where('match_id', $match->id)
            ->where('winner_team_id', $match->team_a_id)
            ->count();

        $teamBSet = MatchSet::where('match_id', $match->id)
            ->where('winner_team_id', $match->team_b_id)
            ->count();

        if ($teamASet == 2 || $teamBSet == 2) {

            $finalScoreA = MatchSet::where('match_id', $match->id)
                ->sum('score_team_a');

            $finalScoreB = MatchSet::where('match_id', $match->id)
                ->sum('score_team_b');

            $this->tournamentService->finishMatch(
                $match,
                $finalScoreA,
                $finalScoreB
            );

            $message = 'Match selesai.';
        } else {

            $message = 'Set berhasil disimpan.';
        }

        return redirect()
            ->route('scoreboard.index', [
                'match' => $request->integer('stay_on_match') ?: $match->id,
            ])
            ->with('success', $message);
    }
}
