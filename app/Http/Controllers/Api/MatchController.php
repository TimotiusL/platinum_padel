<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MatchTournament;
use App\Services\TournamentService;
use Illuminate\Http\Request;

class MatchController extends Controller
{
    public function __construct(private readonly TournamentService $tournamentService)
    {
    }

    public function index(Request $request)
    {
        $query = MatchTournament::with([
            'category',
            'teamA.members.player.user',
            'teamB.members.player.user',
            'winner.members.player.user',
        ]);

        if ($request->filled('tournament_id')) {
            $query->whereHas('category', fn ($category) =>
                $category->where('tournament_id', $request->integer('tournament_id'))
            );
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->integer('category_id'));
        }

        if ($request->filled('round')) {
            $query->where('round', $this->tournamentService->normalizeRound((string) $request->input('round')));
        }

        if ($request->filled('status')) {
            $query->where('status', (string) $request->input('status'));
        }

        return response()->json([
            'matches' => $query
                ->orderBy('category_id')
                ->orderBy('bracket_order')
                ->orderBy('match_date')
                ->get()
                ->map(fn (MatchTournament $match) => $this->tournamentService->matchPayload($match)),
        ]);
    }

    public function show(int $id)
    {
        $match = MatchTournament::with([
            'category',
            'teamA.members.player.user',
            'teamB.members.player.user',
            'winner.members.player.user',
        ])->findOrFail($id);

        return response()->json([
            'match' => $this->tournamentService->matchPayload($match),
        ]);
    }

    public function bracket(int $tournamentId, Request $request)
    {
        $query = MatchTournament::with([
            'category',
            'teamA.members.player.user',
            'teamB.members.player.user',
            'winner.members.player.user',
        ])
            ->whereHas('category', fn ($category) =>
                $category->where('tournament_id', $tournamentId)
            )
            ->whereIn('round', TournamentService::KNOCKOUT_ROUNDS);

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->integer('category_id'));
        }

        $matches = $query
            ->orderBy('category_id')
            ->orderBy('bracket_order')
            ->get()
            ->groupBy('category_id')
            ->map(function ($categoryMatches) {
                return collect(TournamentService::KNOCKOUT_ROUNDS)
                    ->mapWithKeys(function ($round) use ($categoryMatches) {
                        return [
                            $round => $categoryMatches
                                ->filter(fn ($match) => $this->tournamentService->normalizeRound($match->round) === $round)
                                ->map(fn ($match) => $this->tournamentService->matchPayload($match))
                                ->values(),
                        ];
                    });
            });

        return response()->json([
            'bracket' => $matches,
        ]);
    }
}
