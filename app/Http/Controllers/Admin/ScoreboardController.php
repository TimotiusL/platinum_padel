<?php

namespace App\Http\Controllers\Admin;

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

        if ($request->has('finish')) {
            $this->tournamentService->finishMatch($match, $scoreA, $scoreB);
            $message = 'Pertandingan selesai, winner dan bracket sudah diperbarui.';
        } else {
            $match->update([
                'score_team_a' => $scoreA,
                'score_team_b' => $scoreB,
                'status' => $match->status === 'scheduled' ? 'ongoing' : $match->status,
            ]);

            $this->tournamentService->syncTeamStats($match->category_id);
            $message = 'Score live berhasil diperbarui.';
        }

        return redirect()
            ->route('scoreboard.index', [
                'match' => $request->integer('stay_on_match') ?: $match->id,
            ])
            ->with('success', $message);
    }
}
