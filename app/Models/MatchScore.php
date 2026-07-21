<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatchScore extends Model
{
    protected $fillable = [
        'match_id',
        'set_number',
        'team_a_score',
        'team_b_score',
    ];

    public function match()
    {
        return $this->belongsTo(TournamentMatch::class, 'match_id');
    }
}