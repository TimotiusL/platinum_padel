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
        'name' => $request->name,
        'badge' => $request->badge,
        'start_date' => $request->start_date,
        'end_date' => $request->end_date,
        'venue' => $request->venue,
        'venue_sub' => $request->venue_sub,
        'location' => $request->location,
        'prize' => $request->prize,
        'status' => $request->status,
        'poster' => $request->poster,
        'tags' => explode(',', $request->tags)
    ]);

    return redirect()->route('tournaments.index');
}

    public function edit(Tournament $tournament)
    {
        return view('tournaments.edit', compact('tournament'));
    }

    public function update(Request $request, Tournament $tournament)
    {
        $tournament->update($request->all());

        return redirect()->route('tournaments.index');
    }

    public function destroy(Tournament $tournament)
    {
        $tournament->delete();

        return redirect()->route('tournaments.index');
    }
}