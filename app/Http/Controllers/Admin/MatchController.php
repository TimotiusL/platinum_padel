<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\MatchTournament;
use App\Models\Team;
use App\Services\TournamentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class MatchController extends Controller
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

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->integer('category_id'));
        }

        if ($request->filled('status')) {
            $query->where('status', (string) $request->input('status'));
        }

        if ($request->filled('round')) {
            $query->where('round', (string) $request->input('round'));
        }

        $matches = $query
            ->orderBy('category_id')
            ->orderByRaw("FIELD(round, 'group', 'r16', 'qf', 'sf', 'final')")
            ->orderBy('bracket_order')
            ->orderBy('match_date')
            ->paginate(30)
            ->withQueryString();

        $categories = Category::with('tournament')
            ->orderBy('tournament_id')
            ->orderBy('name')
            ->get();

        return view('matches.index', compact('matches', 'categories'));
    }

    public function create(Request $request)
    {
        $categories = Category::with(['tournament', 'teams' => fn ($query) => $query->orderBy('team_code')])
            ->orderBy('tournament_id')
            ->orderBy('name')
            ->get();

        $selectedCategoryId = $request->integer('category_id') ?: optional($categories->first())->id;

        return view('matches.create', compact('categories', 'selectedCategoryId'));
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);

        $data['score_team_a'] = 0;
        $data['score_team_b'] = 0;
        MatchTournament::create($data);

        return redirect()
            ->route('matches.index', ['category_id' => $data['category_id']])
            ->with('success', 'Match berhasil dibuat.');
    }

    public function edit(MatchTournament $match)
    {
        $match->load(['teamA', 'teamB']);

        $categories = Category::with(['tournament', 'teams' => fn ($query) => $query->orderBy('team_code')])
            ->orderBy('tournament_id')
            ->orderBy('name')
            ->get();

        return view('matches.edit', compact('match', 'categories'));
    }

    public function update(Request $request, MatchTournament $match)
    {
        $data = $this->validatedData($request);
        $oldCategoryId = $match->category_id;

        $match->update($data);

        $this->tournamentService->syncTeamStats($oldCategoryId);
        if ($oldCategoryId !== (int) $data['category_id']) {
            $this->tournamentService->syncTeamStats((int) $data['category_id']);
        }

        return redirect()
            ->route('matches.index', ['category_id' => $data['category_id']])
            ->with('success', 'Match berhasil diperbarui.');
    }

    public function destroy(MatchTournament $match)
    {
        $categoryId = $match->category_id;
        $match->delete();
        $this->tournamentService->syncTeamStats($categoryId);

        return back()->with('success', 'Match berhasil dihapus.');
    }

    public function generate(Category $category)
    {
        $teams = Team::where('category_id', $category->id)
            ->where('status', 'approved')
            ->orderBy('group_code')
            ->orderBy('team_code')
            ->get();

        if ($teams->count() < 2) {
            return back()->with('error', 'Minimal harus ada 2 tim.');
        }

        $groups = $teams->groupBy(fn (Team $team) => $team->group_code ?: 'UNGROUPED');
        $created = 0;
        $order = 1;

        DB::transaction(function () use ($category, $groups, &$created, &$order) {
            MatchTournament::where('category_id', $category->id)
                ->whereIn('round', ['group', 'R1', 'r1'])
                ->delete();

            foreach ($groups as $groupTeams) {
                $groupTeams = $groupTeams->values();

                for ($i = 0; $i < $groupTeams->count(); $i++) {
                    for ($j = $i + 1; $j < $groupTeams->count(); $j++) {
                        MatchTournament::create([
                            'category_id' => $category->id,
                            'team_a_id' => $groupTeams[$i]->id,
                            'team_b_id' => $groupTeams[$j]->id,
                            'winner_team_id' => null,
                            'round' => 'group',
                            'bracket_order' => $order,
                            'court' => 'Court ' . (($order - 1) % 4 + 1),
                            'match_date' => null,
                            'status' => 'scheduled',
                            'score_team_a' => 0,
                            'score_team_b' => 0,
                        ]);

                        $created++;
                        $order++;
                    }
                }
            }
        });

        $this->tournamentService->syncTeamStats($category->id);

        return redirect()
            ->route('matches.index', ['category_id' => $category->id])
            ->with('success', "{$created} match group berhasil dibuat.");
    }

    public function generateBracket(Category $category)
    {
        $result = $this->tournamentService->generateKnockout($category->id);

        return redirect()
            ->route('matches.index', ['category_id' => $category->id])
            ->with('success', "{$result['matches']} match {$result['round']} berhasil dibuat.");
    }

    private function validatedData(Request $request): array
    {
        $request->merge([
            'round' => $this->tournamentService->normalizeRound($request->input('round')),
        ]);

        $data = $request->validate([
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'team_a_id' => [
                'required',
                'integer',
                'different:team_b_id',
                Rule::exists('teams', 'id')->where(fn ($query) => $query->where('category_id', $request->integer('category_id'))),
            ],
            'team_b_id' => [
                'required',
                'integer',
                'different:team_a_id',
                Rule::exists('teams', 'id')->where(fn ($query) => $query->where('category_id', $request->integer('category_id'))),
            ],
            'round' => ['required', Rule::in(['group', 'r16', 'qf', 'sf', 'final'])],
            'bracket_order' => ['nullable', 'integer', 'min:1'],
            'court' => ['nullable', 'string', 'max:255'],
            'match_date' => ['nullable', 'date'],
            'status' => ['required', Rule::in(['scheduled', 'ongoing', 'finished'])],
        ]);

        $data['bracket_order'] = $data['bracket_order']
            ?: ((int) MatchTournament::where('category_id', $data['category_id'])
                ->where('round', $data['round'])
                ->max('bracket_order') + 1);

        return $data;
    }
}
