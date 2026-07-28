<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tournament;
use Illuminate\Http\Request;

class TournamentController extends Controller
{
    public function index()
    {
        $tournaments = Tournament::latest()->get();

        return view('tournaments.index', compact('tournaments'));
    }

    public function create()
    {
        return view('tournaments.create');
    }

    public function store(Request $request)
    {
        Tournament::create([
            'title' => $request->title,
            'description' => $request->description,
            'poster' => $request->poster,
            'venue' => $request->venue,
            'location' => $request->location,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'registration_deadline' => $request->registration_deadline,
            'status' => $request->status,
            'prize_pool' => $request->prize_pool,
            'created_by' => 9999
        ]);

        return redirect()->route('tournaments.index');
    }

    public function edit(Tournament $tournament)
    {
        return view('tournaments.edit', compact('tournament'));
    }

    public function update(Request $request, Tournament $tournament)
    {
        $tournament->update([
            'title' => $request->title,
            'description' => $request->description,
            'poster' => $request->poster,
            'venue' => $request->venue,
            'location' => $request->location,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'registration_deadline' => $request->registration_deadline,
            'status' => $request->status,
            'prize_pool' => $request->prize_pool,
        ]);

        return redirect()->route('tournaments.index');
    }

    public function destroy(Tournament $tournament)
    {
        $tournament->delete();

        return redirect()->route('tournaments.index');
    }
}