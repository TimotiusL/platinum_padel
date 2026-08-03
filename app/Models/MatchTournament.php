<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatchTournament extends Model
{
    protected $table = 'tournament_matches';

    protected $fillable = [
        'category_id',
        'team_a_id',
        'team_b_id',
        'winner_team_id',
        'round',
        'bracket_order',
        'court',
        'match_date',
        'status',
        'score_team_a',
        'score_team_b',
    ];

    protected $casts = [
        'match_date' => 'datetime',
        'bracket_order' => 'integer',
        'score_team_a' => 'integer',
        'score_team_b' => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function teamA()
    {
        return $this->belongsTo(Team::class, 'team_a_id');
    }

    public function teamB()
    {
        return $this->belongsTo(Team::class, 'team_b_id');
    }

    public function winner()
    {
        return $this->belongsTo(Team::class, 'winner_team_id');
    }
    public function sets()
    {
        return $this->hasMany(MatchSet::class, 'match_id')
            ->orderBy('set_number');
    }
}
