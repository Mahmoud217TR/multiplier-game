<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GuessResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'player_name' => $this->user->name,
            'won' => $this->hasWon(),
            'points' => $this->points,
            'multiplier' => $this->multiplier,
            'reward' => $this->getRewardPointsAmount(),
        ];
    }
}
