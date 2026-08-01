<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\TournamentService;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    public function __construct(private readonly TournamentService $tournamentService)
    {
    }

    public function index(Request $request)
    {
        $categoryId = $request->integer('category_id');

        if (!$categoryId && $request->filled('tournament_id')) {
            $categoryId = Category::where('tournament_id', $request->integer('tournament_id'))
                ->orderBy('id')
                ->value('id');
        }

        if (!$categoryId) {
            $categoryId = Category::orderBy('id')->value('id');
        }

        if (!$categoryId) {
            return response()->json([
                'category_id' => null,
                'leaderboard' => [],
            ]);
        }

        $category = Category::findOrFail($categoryId);

        return response()->json([
            'category_id' => $category->id,
            'category' => $category->name,
            'leaderboard' => $this->tournamentService
                ->leaderboard($category->id, true)
                ->values(),
        ]);
    }
}
