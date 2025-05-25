<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'level',
        'delay_minutes',
        'location',
        'died_at',
        'spawn_at',
        'note'
    ];

    protected $casts = [
        'died_at' => 'datetime',
        'spawn_at' => 'datetime',
    ];
}
