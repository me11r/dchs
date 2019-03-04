<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class EmergencySituationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => (int)$this->id,
            'date' => Carbon::parse($this->date)->format('d.m.Y'),
            'time' => Carbon::parse($this->time)->format('H:i'),
            'readable_time' => Carbon::parse($this->time)->format('H') . ' час ' . Carbon::parse($this->time)->format('i') . ' мин.',
            'location' => $this->location,
            'died' => (int)$this->died,
            'injured' => (int)$this->injured,
            'hospitalized' => (int)$this->hospitalized,
            'evacuated' => (int)$this->evacuated,
            'saved' => (int)$this->saved,
            'lost' => (int)$this->lost,
            'influence' => $this->influence,
            'description' => $this->description,
            'wounded' => $this->wounded,
            'poisoned' => $this->poisoned,
            'saved_animals' => $this->saved_animals,
            'user_name' => $this->user ? $this->user->name : ''
        ];
    }
}

