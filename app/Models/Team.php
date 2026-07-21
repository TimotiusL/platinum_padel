<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = [
        'category_id',
        'captain_player_id',
        'team_name',
        'payment_status',
        'status',
        'approved_at',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function captain()
    {
        return $this->belongsTo(Player::class, 'captain_player_id');
    }

    public function members()
    {
        return $this->hasMany(TeamMember::class);
    }

    public function homeMatches()
    {
        return $this->hasMany(TournamentMatch::class, 'team_a_id');
    }

    public function awayMatches()
    {
        return $this->hasMany(TournamentMatch::class, 'team_b_id');
    }

    public function wonMatches()
    {
        return $this->hasMany(TournamentMatch::class, 'winner_team_id');
    }
}
