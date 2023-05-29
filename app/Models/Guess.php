<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Guess extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'round_id',
        'multiplyer',
        'points',
    ];

    protected $appends = [
        'player_name',
        'won',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function round(): BelongsTo
    {
        return $this->belongsTo(Round::class);
    }

    public function getPlayerNameAttribute(): string
    {
        return $this->user->name;
    }

    public function getWonAttribute(): bool
    {
        return $this->multiplyer <= $this->round->multiplyer;
    }
}
