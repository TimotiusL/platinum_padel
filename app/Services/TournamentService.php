<?php

namespace App\Services;

use App\Models\MatchTournament;
use App\Models\Team;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;
use App\Models\MatchSet;

class TournamentService
{
    public const KNOCKOUT_ROUNDS = ['r16', 'qf', 'sf', 'final'];

    public function teamPayload(?Team $team): ?array
    {
        if (!$team) {
            return null;
        }

        $members = $team->relationLoaded('members')
            ? $team->members
            : $team->members()->with('player.user')->get();

        $players = $members
            ->sortByDesc('is_captain')
            ->map(function ($member) {
                return [
                    'id' => $member->player?->id,
                    'name' => $member->player?->user?->name ?? '?',
                ];
            })
            ->values();

        return [
            'id' => $team->id,
            'code' => $team->team_code,
            'team_code' => $team->team_code,
            'name' => $team->team_name ?: $team->team_code,
            'group_code' => $team->group_code,
            'players' => $players->pluck('name')->all(),
            'player1' => $players->get(0),
            'player2' => $players->get(1),
        ];
    }

    public function matchPayload(MatchTournament $match): array
    {
        $teamA = $this->teamPayload($match->teamA);
        $teamB = $this->teamPayload($match->teamB);
        $sets = MatchSet::where('match_id', $match->id)
            ->orderBy('set_number')
            ->get();
        return [
            'id' => $match->id,
            'category_id' => $match->category_id,
            'tournament_id' => $match->category?->tournament_id,
            'team1_id' => $match->team_a_id,
            'team2_id' => $match->team_b_id,
            'winner_id' => $match->winner_team_id,
            'team1' => $teamA,
            'team2' => $teamB,
            'score1' => $sets->sum('score_team_a'),
            'score2' => $sets->sum('score_team_b'),

            'sets' => $sets->map(function ($set) {
                return [
                    'set' => $set->set_number,
                    'score1' => $set->score_team_a,
                    'score2' => $set->score_team_b,
                ];
            })->values(),
            'round' => $this->normalizeRound($match->round),
            'stage' => $this->normalizeRound($match->round),
            'bracket_order' => (int) ($match->bracket_order ?? 0),
            'court' => $match->court,
            'scheduled_at' => optional($match->match_date)->toIso8601String(),
            'played_at' => $match->status === 'finished'
                ? optional($match->updated_at)->toIso8601String()
                : null,
            'status' => $match->status,
            'group_code' => $match->teamA?->group_code,
        ];
    }

    public function normalizeRound(?string $round): string
    {
        $value = strtolower(trim((string) $round));

        return match ($value) {
            'r1', 'group', 'group stage', 'group-stage' => 'group',
            'round of 16', 'round16', 'round-of-16', 'r16' => 'r16',
            'quarter final', 'quarter-final', 'quarterfinal', 'qf' => 'qf',
            'semi final', 'semi-final', 'semifinal', 'sf' => 'sf',
            'grand final', 'final' => 'final',
            default => $value ?: 'group',
        };
    }

    public function leaderboard(int $categoryId, bool $includeLiveScores = true): Collection
    {
        $teams = Team::with('members.player.user')
            ->where('category_id', $categoryId)
            ->orderBy('team_code')
            ->get();

        $stats = [];

        foreach ($teams as $team) {
            $stats[$team->id] = [
                'id' => $team->id,
                'category_id' => $team->category_id,
                'team_code' => $team->team_code,
                'team_name' => $team->team_name ?: $team->team_code,
                'group_code' => $team->group_code,
                'players' => $team->members
                    ->sortByDesc('is_captain')
                    ->map(fn($member) => $member->player?->user?->name ?? '?')
                    ->values()
                    ->all(),
                'played' => 0,
                'win' => 0,
                'lose' => 0,
                'points' => 0,
                'total_score' => 0,
                'score_against' => 0,
                'score_difference' => 0,
            ];
        }

        $matches = MatchTournament::where('category_id', $categoryId)
            ->whereIn('status', $includeLiveScores ? ['ongoing', 'finished'] : ['finished'])
            ->get();

        foreach ($matches as $match) {
            if (!isset($stats[$match->team_a_id], $stats[$match->team_b_id])) {
                continue;
            }

            $scoreA = (int) $match->score_team_a;
            $scoreB = (int) $match->score_team_b;

            $stats[$match->team_a_id]['total_score'] += $scoreA;
            $stats[$match->team_a_id]['score_against'] += $scoreB;
            $stats[$match->team_b_id]['total_score'] += $scoreB;
            $stats[$match->team_b_id]['score_against'] += $scoreA;

            if ($match->status !== 'finished') {
                continue;
            }

            $stats[$match->team_a_id]['played']++;
            $stats[$match->team_b_id]['played']++;

            if ($scoreA > $scoreB) {
                $stats[$match->team_a_id]['win']++;
                $stats[$match->team_a_id]['points'] += 3;
                $stats[$match->team_b_id]['lose']++;
            } elseif ($scoreB > $scoreA) {
                $stats[$match->team_b_id]['win']++;
                $stats[$match->team_b_id]['points'] += 3;
                $stats[$match->team_a_id]['lose']++;
            }
        }

        $rows = collect(array_values($stats))
            ->map(function (array $row) {
                $row['score_difference'] = $row['total_score'] - $row['score_against'];
                return $row;
            })
            ->sort(function (array $a, array $b) {
                return ($b['total_score'] <=> $a['total_score'])
                    ?: ($b['points'] <=> $a['points'])
                    ?: ($b['win'] <=> $a['win'])
                    ?: ($b['score_difference'] <=> $a['score_difference'])
                    ?: strcmp((string) $a['team_code'], (string) $b['team_code']);
            })
            ->values()
            ->map(function (array $row, int $index) {
                $row['rank'] = $index + 1;
                return $row;
            });

        return $rows;
    }

    public function standings(int $categoryId): Collection
    {
        return $this->leaderboard($categoryId, false)
            ->sort(function (array $a, array $b) {
                return ($b['points'] <=> $a['points'])
                    ?: ($b['win'] <=> $a['win'])
                    ?: ($b['score_difference'] <=> $a['score_difference'])
                    ?: ($b['total_score'] <=> $a['total_score'])
                    ?: strcmp((string) $a['team_code'], (string) $b['team_code']);
            })
            ->values();
    }

    public function syncTeamStats(int $categoryId): void
    {
        $rows = $this->leaderboard($categoryId, false)->keyBy('id');

        $columns = [
            'played' => Schema::hasColumn('teams', 'played'),
            'win' => Schema::hasColumn('teams', 'win'),
            'lose' => Schema::hasColumn('teams', 'lose'),
            'points' => Schema::hasColumn('teams', 'points'),
            'score_for' => Schema::hasColumn('teams', 'score_for'),
            'score_against' => Schema::hasColumn('teams', 'score_against'),
        ];

        foreach (Team::where('category_id', $categoryId)->get() as $team) {
            $row = $rows->get($team->id);
            if (!$row) {
                continue;
            }

            $update = [];
            foreach (['played', 'win', 'lose', 'points'] as $column) {
                if ($columns[$column]) {
                    $update[$column] = $row[$column];
                }
            }
            if ($columns['score_for']) {
                $update['score_for'] = $row['total_score'];
            }
            if ($columns['score_against']) {
                $update['score_against'] = $row['score_against'];
            }

            if ($update) {
                $team->forceFill($update)->save();
            }
        }
    }

    public function finishMatch(MatchTournament $match, int $scoreA, int $scoreB): MatchTournament
    {
        if ($scoreA === $scoreB) {
            throw ValidationException::withMessages([
                'score_team_a' => 'Pertandingan tidak dapat diselesaikan dengan skor seri.',
            ]);
        }

        return DB::transaction(function () use ($match, $scoreA, $scoreB) {
            $match->score_team_a = $scoreA;
            $match->score_team_b = $scoreB;
            $match->winner_team_id = $scoreA > $scoreB
                ? $match->team_a_id
                : $match->team_b_id;
            $match->status = 'finished';
            $match->save();

            $this->syncTeamStats($match->category_id);
            $this->advanceWinner($match->fresh());

            return $match->fresh();
        });
    }

    public function advanceWinner(MatchTournament $match): void
    {
        $round = $this->normalizeRound($match->round);
        $nextRound = [
            'r16' => 'qf',
            'qf' => 'sf',
            'sf' => 'final',
        ][$round] ?? null;

        if (!$nextRound || !$match->winner_team_id) {
            return;
        }

        $currentOrder = (int) ($match->bracket_order ?: 0);

        if ($currentOrder < 1) {
            $ids = MatchTournament::where('category_id', $match->category_id)
                ->where('round', $round)
                ->orderBy('id')
                ->pluck('id')
                ->values();

            $position = $ids->search($match->id);
            $currentOrder = $position === false ? 1 : $position + 1;
            $match->bracket_order = $currentOrder;
            $match->save();
        }

        $nextOrder = (int) ceil($currentOrder / 2);
        $firstOrder = (($nextOrder - 1) * 2) + 1;

        $feeders = MatchTournament::with('winner')
            ->where('category_id', $match->category_id)
            ->where('round', $round)
            ->whereIn('bracket_order', [$firstOrder, $firstOrder + 1])
            ->where('status', 'finished')
            ->orderBy('bracket_order')
            ->get();

        if ($feeders->count() !== 2 || !$feeders[0]->winner_team_id || !$feeders[1]->winner_team_id) {
            return;
        }

        $next = MatchTournament::firstOrNew([
            'category_id' => $match->category_id,
            'round' => $nextRound,
            'bracket_order' => $nextOrder,
        ]);

        $newTeamA = $feeders[0]->winner_team_id;
        $newTeamB = $feeders[1]->winner_team_id;
        $participantsChanged = $next->exists
            && ($next->team_a_id !== $newTeamA || $next->team_b_id !== $newTeamB);

        $next->team_a_id = $newTeamA;
        $next->team_b_id = $newTeamB;
        $next->court = $next->court ?: 'Bracket ' . $nextOrder;
        $next->match_date = $next->match_date ?: null;

        if (!$next->exists || $participantsChanged) {
            $next->winner_team_id = null;
            $next->score_team_a = 0;
            $next->score_team_b = 0;
            $next->status = 'scheduled';
        }

        $next->save();

        if ($participantsChanged) {
            $this->syncTeamStats($match->category_id);
        }
    }

    public function generateKnockout(int $categoryId): array
    {
        $standings = $this->standings($categoryId);
        $teamCount = $standings->count();

        $size = match (true) {
            $teamCount >= 16 => 16,
            $teamCount >= 8 => 8,
            $teamCount >= 4 => 4,
            $teamCount >= 2 => 2,
            default => 0,
        };

        if ($size < 2) {
            throw ValidationException::withMessages([
                'category' => 'Minimal harus ada 2 tim untuk membuat bracket.',
            ]);
        }

        $round = [
            16 => 'r16',
            8 => 'qf',
            4 => 'sf',
            2 => 'final',
        ][$size];

        $seeded = $standings->take($size)->values();

        DB::transaction(function () use ($categoryId, $round, $seeded, $size) {
            MatchTournament::where('category_id', $categoryId)
                ->whereIn('round', self::KNOCKOUT_ROUNDS)
                ->delete();

            for ($i = 0; $i < $size / 2; $i++) {
                $teamA = $seeded[$i];
                $teamB = $seeded[$size - 1 - $i];

                MatchTournament::create([
                    'category_id' => $categoryId,
                    'team_a_id' => $teamA['id'],
                    'team_b_id' => $teamB['id'],
                    'winner_team_id' => null,
                    'round' => $round,
                    'bracket_order' => $i + 1,
                    'court' => 'Bracket ' . ($i + 1),
                    'match_date' => null,
                    'status' => 'scheduled',
                    'score_team_a' => 0,
                    'score_team_b' => 0,
                ]);
            }
        });

        return ['round' => $round, 'matches' => (int) ($size / 2)];
    }
}
