<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = [
        'category_id',
        'group_code',
        'team_code',
        'captain_player_id',
        'team_name',
        'payment_status',
        'status',
        'approved_at'
    ];

    protected $table = 'teams';

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
}