<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $table = 'teams';

    protected $fillable = [
        'category_id',
        'group_code',
        'team_code',
        'captain_player_id',
        'team_name',
        'payment_status',
        'status',
        'approved_at',
        'played',
        'win',
        'lose',
        'points',
        'score_for',
        'score_against',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'played' => 'integer',
        'win' => 'integer',
        'lose' => 'integer',
        'points' => 'integer',
        'score_for' => 'integer',
        'score_against' => 'integer',
    ];

    public function members()
    {
        return $this->hasMany(TeamMember::class, 'team_id');
    }

    public function captain()
    {
        return $this->belongsTo(Player::class, 'captain_player_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function matchesAsTeamA()
    {
        return $this->hasMany(MatchTournament::class, 'team_a_id');
    }

    public function matchesAsTeamB()
    {
        return $this->hasMany(MatchTournament::class, 'team_b_id');
    }
}
