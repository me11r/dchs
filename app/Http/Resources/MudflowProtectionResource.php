<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MudflowProtectionResource extends JsonResource
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
            'gauging_station_id' => (int)$this->gauging_station_id,
            'gaugingStation' => $this->resource->relationLoaded('gaugingStation') ? (new GaugingStationResource($this->gaugingStation)) : null,
            'information' => (string)$this->information,
            'name' => (string)$this->name,
            'water_flow_rate' => (float)$this->water_flow_rate,
            'critical_water_flow_rate' => (float)$this->critical_water_flow_rate,
            'turbidity_of_water' => (float)$this->turbidity_of_water,
            'max_turbidity_of_water' => (float)$this->max_turbidity_of_water,
            'air_temperature' => (float)$this->air_temperature,
            'water_temperature' => (float)$this->water_temperature,
            'precipitation' => (float)$this->precipitation,
            'height_of_snow' => (float)$this->height_of_snow,
            'weather' => (string) $this->weather,
            'comment' => (string) $this->comment
        ];
    }
}
