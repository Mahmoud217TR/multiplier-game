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
        'multiplier',
        'points',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function round(): BelongsTo
    {
        return $this->belongsTo(Round::class);
    }

    public function hasWon(): bool
    {
        return $this->multiplier <= $this->round->multiplier;
    }

    public function getRewardPointsAmount(): int
    {
        return $this->hasWon()?$this->points * $this->multiplier:0;
    }
}
