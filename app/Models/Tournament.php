<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Team;
use App\Models\MatchTournament;


class Tournament extends Model
{
    protected $fillable = [
        'name', 'badge', 'start_date', 'end_date', 'venue',
        'venue_sub', 'location', 'prize', 'status', 'poster', 'tags'
    ];

    protected $casts = [
        'tags' => 'array',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function teams()
    {
        return $this->hasMany(Team::class);
    }

    public function matches()
    {
        return $this->hasMany(MatchTournament::class);
    }
}