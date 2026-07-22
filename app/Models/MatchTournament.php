<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Tournament;
use App\Models\Team;

class MatchTournament extends Model
{
    protected $fillable = [
        'tournament_id', 'team1_id', 'team2_id', 'score1', 'score2',
        'winner_id', 'stage', 'round', 'scheduled_at', 'played_at',
        'score_history'
    ];

    protected $casts = [
        'score_history' => 'array',
        'scheduled_at' => 'datetime',
        'played_at' => 'datetime',
    ];

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    public function team1()
    {
        return $this->belongsTo(Team::class, 'team1_id');
    }

    public function team2()
    {
        return $this->belongsTo(Team::class, 'team2_id');
    }

    public function winner()
    {
        return $this->belongsTo(Team::class, 'winner_id');
    }

    public function getTeam1PlayersAttribute()
    {
        return $this->team1 ? [$this->team1->player1->name, $this->team1->player2->name] : [];
    }

    public function getTeam2PlayersAttribute()
    {
        return $this->team2 ? [$this->team2->player1->name, $this->team2->player2->name] : [];
    }
}