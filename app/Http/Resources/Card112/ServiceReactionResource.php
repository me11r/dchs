<?php

namespace App\Http\Resources\Card112;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceReactionResource extends JsonResource
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
            'card112_id' => (int)$this->card112_id,
            'service_type_id' => (int)$this->service_type_id,
            'name' => (string)$this->name,
            'message_time' => Carbon::parse($this->message_time)->format('Y-m-d H:i:s'),
            'departure_time' => Carbon::parse($this->departure_time)->format('Y-m-d H:i:s'),
            'arrival_time' => Carbon::parse($this->arrival_time)->format('Y-m-d H:i:s')
        ];
    }
}
