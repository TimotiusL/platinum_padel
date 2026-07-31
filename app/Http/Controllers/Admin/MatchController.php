<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Team;
use App\Models\MatchTournament;

class MatchController extends Controller
{
    public function generate(Category $category)
    {
        $teams = Team::where('category_id', $category->id)
            ->inRandomOrder()
            ->get();

        if ($teams->count() < 2) {
            return back()->with('error', 'Minimal harus ada 2 team.');
        }

        MatchTournament::where('category_id', $category->id)->delete();

        for ($i = 0; $i < $teams->count(); $i += 2) {

            if (!isset($teams[$i + 1])) {
                break;
            }

            MatchTournament::create([
                'category_id' => $category->id,
                'team_a_id' => $teams[$i]->id,
                'team_b_id' => $teams[$i + 1]->id,
                'round' => 'R1',
                'court' => floor($i / 2) + 1,
                'status' => 'scheduled',
                'score_team_a' => 0,
                'score_team_b' => 0,
            ]);
        }

        return back()->with('success', 'Match berhasil dibuat.');
    }

    public function index(Category $category)
    {
        $matches = MatchTournament::with([
            'teamA',
            'teamB'
        ])
            ->where('category_id', $category->id)
            ->orderBy('court')
            ->get();

        return view('matches.index', compact('matches', 'category'));
    }
}