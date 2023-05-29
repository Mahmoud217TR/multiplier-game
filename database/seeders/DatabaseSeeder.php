<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Game;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'email' => 'player1@game.com'
        ]);
        User::factory()->create([
            'email' => 'player2@game.com'
        ]);
        User::factory()->create([
            'email' => 'player3@game.com'
        ]);
        User::factory()->create([
            'email' => 'player4@game.com'
        ]);
        Game::create();
    }
}
