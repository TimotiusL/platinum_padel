<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $fillable = [
        'user_id',
        'phone',
        'birth_date',
        'gender',
        'city',
        'photo',
        'ranking_point'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function captainTeams()
    {
        return $this->hasMany(Team::class, 'captain_player_id');
    }

    public function teamMembers()
    {
        return $this->hasMany(TeamMember::class);
    }
}
