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
        'note',
        'notified_two_min',
        'updated_by'
    ];

    protected $casts = [
        'died_at' => 'datetime',
        'spawn_at' => 'datetime',
        'notified_two_min' => 'boolean',
    ];

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
