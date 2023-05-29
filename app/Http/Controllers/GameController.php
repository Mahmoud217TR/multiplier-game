<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Http\Requests\{
    StoreGameRequest,
    UpdateGameRequest
};
use App\Http\Resources\UserResource;
use App\Models\GamePlayer;
use App\Models\Guess;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;
use Pusher\Pusher;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGameRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Game $game)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Game $game)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGameRequest $request, Game $game)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Game $game)
    {
        //
    }

    /**
     * Join the game lobby.
     */
    public function join(Game $game)
    {
        $gamePlayer = GamePlayer::firstOrCreate([
            'user_id' => auth()->user()->id,
            'game_id' => $game->id,
        ]);

        $pusher = new Pusher(env('PUSHER_APP_KEY'), env('PUSHER_APP_SECRET'), env('PUSHER_APP_ID'), [
            'cluster' => env('PUSHER_APP_CLUSTER'),
            'encrypted' => true,
        ]);

        $pusher->trigger('lobby', 'player-joined', $gamePlayer);

        $gamePlayers = $game->players;
        return response()->json([
            'players' => UserResource::collection($gamePlayers),
        ]);

    }

    /**
     * Leave the game lobby.
     */
    public function leave(Game $game)
    {
        $gamePlayer = $game->players()->where('user_id', auth()->user()->id)->first();
        $gamePlayer->delete();

        $pusher = $this->getPusherInstance();

        $pusher->trigger('lobby', 'player-left', $gamePlayer);

        return response()->json([
            'players' => new UserResource($gamePlayer),
        ]);
    }

    /**
     * Start the game lobby.
     */
    public function guess(Game $game, Request $request)
    {
        $request->validate([
            'multiplayer' => 'numeric',
            'points' => 'integer|min:1',
        ]);

        $round = $game->getCurrentRound();
        $player = auth()->user();

        if ($round->guesses()->where('user_id', $player->id)->exists()) {
            abort(403, "You already Guessed this round!!");
        }

        $guess = Guess::create([
            'user_id' => $player->id,
            'round_id' => $round->id,
            'multiplyer' => $request->multiplayer,
            'points' => $request->points,
        ]);

        $player = $guess->user;
        $player->points -= $guess->points;

        if ($round->guesses()->count() == $game->players()->count()) {
            return $this->start($game);
        }

        return response()->json([
            'message' => "Guess saved, waiting other players to guess.",
        ]);
    }

    private function start(Game $game): void
    {
        $round = $game->getCurrentRound();
        $multiplyer = fake()->numberBetween(100,1000)/100;

        $round->update([
            'multiplyer' => $multiplyer,
        ]);

        $round->refresh();

        foreach ($round->guesses as $guess) {
            if ($guess->won) {
                $player = $guess->user;
                $player->points += $guess->points *$guess->multiplyer;
                $player->save();
            }
        }

        $data = [
            'multiplyer' => $multiplyer,
            'guesses' => $round->guesses,
            'players' => $game->players,
        ];

        $pusher = $this->getPusherInstance();
        $pusher->trigger('lobby', 'game-started', $data);
    }

    private function getPusherInstance(): Pusher
    {
        return new Pusher(env('PUSHER_APP_KEY'), env('PUSHER_APP_SECRET'), env('PUSHER_APP_ID'), [
            'cluster' => env('PUSHER_APP_CLUSTER'),
            'encrypted' => true,
        ]);
    }
}
