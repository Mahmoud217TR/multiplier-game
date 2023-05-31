<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Http\Requests\{
    GuessRequest,
    StoreGameRequest,
    UpdateGameRequest
};
use App\Http\Resources\GuessResource;
use App\Http\Resources\UserResource;
use App\Models\GamePlayer;
use App\Models\Guess;
use App\Models\Round;
use App\Models\User;
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
        GamePlayer::firstOrCreate([
            'user_id' => auth()->user()->id,
            'game_id' => $game->id,
        ]);

        $pusher = $this->getPusherInstance();

        $data = [
            'players' => $game->players,
        ];

        $pusher->trigger('lobby', 'player-joined-'.$game->id, $data);

        return response()->json([
            'players' => UserResource::collection($game->players),
        ]);

    }

    /**
     * Leave the game lobby.
     */
    public function leave(Game $game)
    {
        $gamePlayer = GamePlayer::where('user_id', auth()->user()->id)
            ->where('game_id', $game->id)
            ->first();
        $gamePlayer->delete();

        $pusher = $this->getPusherInstance();

        $data = [
            'players' => $game->players,
        ];

        $pusher->trigger('lobby', 'player-left-'.$game->id, $data);

        return response()->json([
            'players' => UserResource::collection($game->players),
        ]);
    }

    /**
     * Start the game lobby.
     */
    public function guess(GuessRequest $request, Game $game)
    {
        $player = auth()->user();
        $round = $game->getCurrentRound();
        if ($round->hasPlayerGuessed($player)) {
            abort(403, "You already Guessed this round!!");
        }

        $guess = $this->makeGuess(
            $player,
            $round,
            $request->multiplier,
            $request->points
        );

        if ($round->canStart()) {
            return $this->start($game);
        }

        return response()->json([
            'message' => "Guess saved, waiting other players to guess.",
        ]);
    }

    private function start(Game $game): void
    {
        $round = $game->getCurrentRound();
        $multiplier = $this->generateRandomMultiplier();

        $round->update([
            'multiplier' => $multiplier,
        ]);

        $round->refresh();

        foreach ($round->guesses as $guess) {
            if ($guess->hasWon()) {
                $guess->user->increasePoints(
                    $guess->getRewardPointsAmount()
                );
            }
        }

        $data = [
            'multiplier' => $multiplier,
            'guesses' => GuessResource::collection($round->guesses),
            'players' => UserResource::collection($game->players),
        ];

        $pusher = $this->getPusherInstance();
        $pusher->trigger('lobby', 'game-started-'.$game->id, $data);
    }

    private function getPusherInstance(): Pusher
    {
        return app(Pusher::class);
    }

    private function makeGuess(
        User $player,
        Round $round,
        float $multiplier,
        int $points,
    ): Guess {
        $guess = Guess::create([
            'user_id' => $player->id,
            'round_id' => $round->id,
            'multiplier' => $multiplier,
            'points' => $points,
        ]);

        $player->decreasePoints($points);

        return $guess;
    }

    private function generateRandomMultiplier(): float
    {
        return fake()->numberBetween(100, 1000)/100;
    }
}
