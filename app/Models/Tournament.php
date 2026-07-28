<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    protected $fillable = [
        'name',
        'badge',
        'start_date',
        'end_date',
        'venue',
        'venue_sub',
        'location',
        'prize',
        'status',
        'poster',
        'tags'
    ];

    protected $casts = [
        'tags' => 'array',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

   public function categories()
{
    return $this->hasMany(Category::class);
}
}