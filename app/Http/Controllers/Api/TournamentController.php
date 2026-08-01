<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tournament;
use App\Services\TournamentService;
use Illuminate\Http\Request;

class TournamentController extends Controller
{
    public function __construct(private readonly TournamentService $tournamentService)
    {
    }

    public function index(Request $request)
    {
        $query = Tournament::query()->orderByDesc('start_date');

        if ($request->filled('status')) {
            $statuses = collect(explode(',', (string) $request->input('status')))
                ->map(fn ($status) => trim($status))
                ->filter()
                ->values();

            if ($statuses->isNotEmpty()) {
                $query->whereIn('status', $statuses);
            }
        }

        $limit = min(max($request->integer('limit', 20), 1), 100);

        return response()->json([
            'tournaments' => $query->limit($limit)->get()->map(
                fn (Tournament $tournament) => $this->tournamentPayload($tournament)
            ),
        ]);
    }

    public function show(int $id)
    {
        $tournament = Tournament::with([
            'categories.teams.members.player.user',
            'categories.matches.category',
            'categories.matches.teamA.members.player.user',
            'categories.matches.teamB.members.player.user',
            'categories.matches.winner.members.player.user',
        ])->findOrFail($id);

        $allTeams = [];
        $categoryData = [];

        foreach ($tournament->categories->sortBy('id') as $category) {
            $teamsByGroup = [];

            foreach ($category->teams->sortBy('team_code') as $team) {
                $group = $team->group_code ?: 'UNGROUPED';
                $payload = $this->tournamentService->teamPayload($team);

                $teamsByGroup[$group][] = $payload;
                $allTeams[$group][] = $payload;
            }

            $groupMatches = [];
            $knockout = [
                'r16' => [],
                'qf' => [],
                'sf' => [],
                'final' => [],
            ];

            foreach ($category->matches
                ->sortBy(fn ($match) => sprintf(
                    '%s-%05d-%05d',
                    $this->tournamentService->normalizeRound($match->round),
                    (int) ($match->bracket_order ?? 0),
                    $match->id
                )) as $match) {
                $round = $this->tournamentService->normalizeRound($match->round);
                $payload = $this->tournamentService->matchPayload($match);

                if ($round === 'group') {
                    $group = $match->teamA?->group_code ?: 'UNGROUPED';
                    $groupMatches[$group][] = $payload;
                } elseif (array_key_exists($round, $knockout)) {
                    $knockout[$round][] = $payload;
                }
            }

            $categoryData[(string) $category->id] = [
                'category' => [
                    'id' => $category->id,
                    'label' => $category->name,
                    'name' => $category->name,
                    'tier' => match ($category->gender) {
                        'male' => 'Men',
                        'female' => 'Women',
                        default => 'Mixed',
                    },
                    'gender' => $category->gender,
                ],
                'teams' => $teamsByGroup,
                'matches' => [
                    'group' => $groupMatches,
                    ...$knockout,
                ],
                'leaderboard' => $this->tournamentService
                    ->leaderboard($category->id, true)
                    ->values(),
            ];
        }

        $categories = $tournament->categories
            ->sortBy('id')
            ->map(fn ($category) => [
                'id' => (string) $category->id,
                'label' => $category->name,
                'name' => $category->name,
                'tier' => match ($category->gender) {
                    'male' => 'Men',
                    'female' => 'Women',
                    default => 'Mixed',
                },
                'gender' => $category->gender,
            ])
            ->values();

        $firstCategoryId = optional($tournament->categories->sortBy('id')->first())->id;
        $firstData = $firstCategoryId ? ($categoryData[(string) $firstCategoryId] ?? []) : [];

        return response()->json([
            'tournament' => $this->tournamentPayload($tournament),
            'categories' => $categories,
            'category_data' => $categoryData,
            'teams' => $allTeams,
            'matches' => $firstData['matches'] ?? [
                'group' => [],
                'r16' => [],
                'qf' => [],
                'sf' => [],
                'final' => [],
            ],
            'leaderboard' => $firstData['leaderboard'] ?? [],
        ]);
    }

    public function history(Request $request)
    {
        $limit = min(max($request->integer('limit', 10), 1), 50);

        return response()->json([
            'history' => Tournament::where('status', 'finished')
                ->orderByDesc('start_date')
                ->limit($limit)
                ->get()
                ->map(fn (Tournament $tournament) => $this->tournamentPayload($tournament)),
        ]);
    }

    private function tournamentPayload(Tournament $tournament): array
    {
        return [
            'id' => $tournament->id,
            'name' => $tournament->title,
            'title' => $tournament->title,
            'description' => $tournament->description,
            'badge' => 'PLATINUM PADEL',
            'poster' => $tournament->poster,
            'venue' => $tournament->venue,
            'venue_sub' => null,
            'location' => $tournament->location,
            'start_date' => $tournament->start_date,
            'end_date' => $tournament->end_date,
            'registration_deadline' => $tournament->registration_deadline,
            'status' => $tournament->status,
            'prize_pool' => $tournament->prize_pool,
            'tags' => $tournament->relationLoaded('categories')
                ? $tournament->categories->pluck('name')->take(3)->values()
                : [],
        ];
    }
}
