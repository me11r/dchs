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
            'incident_type' => $this->incident_type,
            'place' => $this->place,
            'area_name' => $this->cityArea ? $this->cityArea->name : '',
            'location' => $this->location,
            'object_name' => $this->object_name,
            'size' => $this->size,
            'died' => (int)$this->died,
            'died_children' => (int)$this->died_children,
            'injured' => (int)$this->injured,
            'injured_children' => (int)$this->injured_children,
            'hospitalized' => (int)$this->hospitalized,
            'hospitalized_children' => (int)$this->hospitalized_children,
            'evacuated' => (int)$this->evacuated,
            'evacuated_children' => (int)$this->evacuated_children,
            'saved' => (int)$this->saved,
            'saved_children' => (int)$this->saved_children,
            'lost' => (int)$this->lost,
            'lost_children' => (int)$this->lost_children,
            'influence' => $this->influence,
            'can_fix_themselves' => $this->can_fix_themselves,
            'can_fix_themselves_readable' => $this->can_fix_themselves ? 'Да' : 'Нет',
            'involved' => $this->involved,
            'involved_services' => $this->involved_services,
            'involved_people' => (int) $this->involved_people,
            'involved_tech' => (int) $this->involved_tech,

            'user_name' => $this->user ? $this->user->name : ''
        ];
    }
}

