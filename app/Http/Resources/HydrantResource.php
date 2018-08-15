<?php

namespace App\Http\Resources;

use App\Http\Resources\CityAreaResource;
use App\Http\Resources\IncidentTypeResource;
use App\Http\Resources\StreetResource;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\Resource;

class HydrantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $department = $this->resource->relationLoaded('fireDepartment') &&  $this->fireDepartment ? (new Resource($this->fireDepartment)) : null;
        $description =
            'Адрес: '. $this->address.
            "<br>". 'Спецификация: ' .$this->specification .
            ($department? "<br> Депаратамент: " . $department->title:'') .
            '<br> Идентификатор: ' . $this->id;
        return [
            'id' => (int)$this->id,
            'address' => (string)$this->address,
            'specification' => (string)$this->specification,
            'fire_department_id' => (int)$this->fire_department_id,
            'lat' => (float)$this->lat,
            'long' => (float)$this->long,
            'outputDescription' => $description,
            'active' => (int)$this->active
        ];
    }
}
