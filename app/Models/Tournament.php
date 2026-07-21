<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    protected $fillable = [

        'title',
        'description',
        'poster',
        'venue',
        'location',
        'start_date',
        'end_date',
        'registration_deadline',
        'status',
        'prize_pool',
        'created_by',

    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }
}
