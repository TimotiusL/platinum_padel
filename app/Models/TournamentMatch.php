<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TournamentMatch extends Model
{
    protected $fillable = [
        'category_id',
        'team_a_id',
        'team_b_id',
        'winner_team_id',
        'round',
        'court',
        'match_date',
        'status',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
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

    public function scores()
    {
        return $this->hasMany(MatchScore::class);
    }
}
