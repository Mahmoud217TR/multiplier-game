<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Round extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_id',
        'multiplier',
    ];

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }
    
    public function guesses(): HasMany
    {
        return $this->hasMany(Guess::class);
    }
    
    
    public function scopeNotFinished(Builder $query): void
    {
        $query->whereNull('multiplier');
    }

    public function hasPlayerGuessed(User $player): bool
    {
        return $this->guesses()
            ->where('user_id', $player->id)
            ->exists();
    }

    public function canStart(): bool
    {
        return $this->guesses()->count() == $this->game->players()->count();
    }
}
