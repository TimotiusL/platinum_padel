<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Player;
use App\Models\Tournament;
use App\Models\MatchTournament;

class Team extends Model
{
    protected $fillable = [
        'code', 'player1_id', 'player2_id', 'tournament_id',
        'group_code', 'points'
    ];

    public function player1()
    {
        return $this->belongsTo(Player::class, 'player1_id');
    }

    public function player2()
    {
        return $this->belongsTo(Player::class, 'player2_id');
    }

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    public function matchesAsTeam1()
    {
        return $this->hasMany(MatchTournament::class, 'team1_id');
    }

    public function matchesAsTeam2()
    {
        return $this->hasMany(MatchTournament::class, 'team2_id');
    }

    public function getPlayersAttribute()
    {
        return [$this->player1->name, $this->player2->name];
    }
}