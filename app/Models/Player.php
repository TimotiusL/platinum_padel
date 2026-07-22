<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Team;

class Player extends Model
{
    protected $fillable = ['name', 'location', 'titles', 'rank', 'photo_url', 'profile_data'];

    protected $casts = [
        'profile_data' => 'array',
    ];

    public function teams()
    {
        return $this->hasMany(Team::class, 'player1_id')
            ->orWhere('player2_id', $this->id);
    }

    public function fullName()
    {
        return $this->name;
    }

    public function initials()
    {
        $parts = explode(' ', $this->name);
        return strtoupper(substr($parts[0], 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : ''));
    }
}