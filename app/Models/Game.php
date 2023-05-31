<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{
    BelongsToMany,
    HasMany
};

class Game extends Model
{
    use HasFactory;

    public function players(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'game_players', 'game_id', 'user_id');
    }

    public function rounds(): HasMany
    {
        return $this->hasMany(Round::class);
    }

    public function getCurrentRound(): Round
    {
        $latestRound = $this->rounds()
            ->notFinished()
            ->latest()
            ->first();

        if (is_null($latestRound)) {
            return Round::create([
                'game_id' => $this->id,
            ]);
        } else {
            return $latestRound;
        }
    }
}
