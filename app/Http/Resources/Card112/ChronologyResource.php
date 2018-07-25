<?php

namespace App\Http\Resources\Card112;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ChronologyResource extends JsonResource
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
            'time' => Carbon::parse($this->time)->format('Y-m-d H:i:s'),
            'comment' => (string)$this->comment,
            'additional_comment' => (string)$this->additional_comment,
        ];
    }
}
