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
        'ranking_point',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'ranking_point' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function teamMembers()
    {
        return $this->hasMany(TeamMember::class);
    }

    public function teams()
    {
        return $this->belongsToMany(
            Team::class,
            'team_members',
            'player_id',
            'team_id'
        );
    }
}
