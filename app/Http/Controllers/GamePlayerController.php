<?php

namespace App\Http\Controllers;

use App\Models\GamePlayer;
use App\Http\Requests\StoreGamePlayerRequest;
use App\Http\Requests\UpdateGamePlayerRequest;

class GamePlayerController extends Controller
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
    public function store(StoreGamePlayerRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(GamePlayer $gamePlayer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GamePlayer $gamePlayer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGamePlayerRequest $request, GamePlayer $gamePlayer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GamePlayer $gamePlayer)
    {
        //
    }
}
