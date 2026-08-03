<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatchSet extends Model
{
    protected $fillable = [
        'match_id',
        'set_number',
        'score_team_a',
        'score_team_b',
        'winner_team_id',
    ];

    public function match()
    {
        return $this->belongsTo(MatchTournament::class, 'match_id');
    }
}