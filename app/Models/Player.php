<?php

namespace App\Models;
use App\Models\Team;
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
    public function show($id)
    {
        $player = Player::findOrFail($id);

        return response()->json([
            'player' => $player,
            'profile' => $player->profile_data ?? $this->getDefaultProfile()
        ]);
    }
}