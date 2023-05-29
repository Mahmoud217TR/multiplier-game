<?php

namespace App\Http\Controllers;

use App\Models\Guess;
use App\Http\Requests\StoreGuessRequest;
use App\Http\Requests\UpdateGuessRequest;

class GuessController extends Controller
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
    public function store(StoreGuessRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Guess $guess)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Guess $guess)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGuessRequest $request, Guess $guess)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Guess $guess)
    {
        //
    }
}
