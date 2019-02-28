<?php

namespace App\Http\Resources\Card112;

use App\Http\Resources\CityAreaResource;
use App\Http\Resources\IncidentTypeResource;
use App\Http\Resources\StreetResource;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\Resource;

class Card112Resource extends JsonResource
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
            'location' => (string)$this->location,
            'detailed_address' => (string)$this->detailed_address,
            'street' => $this->resource->relationLoaded('street') ? (new StreetResource($this->street)) : null,
            'crossroad_1_id' => (int)$this->crossroad_1_id,
            'crossroad1' => $this->resource->relationLoaded('crossroad1') ? (new StreetResource($this->crossroad1)) : null,
            'crossroad_2_id' => (int)$this->crossroad_2_id,
            'crossroad2' => $this->resource->relationLoaded('crossroad2') ? (new StreetResource($this->crossroad2)) : null,
            'incident_type_id' => (int)$this->incident_type_id,
            'incident' => $this->resource->relationLoaded('incident') ? (new IncidentTypeResource($this->incident)) : null,
            'description' => (string)$this->description,
            'caller_phone' => (string)$this->caller_phone,
            'caller_name' => (string)$this->caller_name,
            'call_time' => Carbon::parse($this->call_time)->format('Y-m-d H:i:s'),
            'additional_street_id' => (int)$this->additional_street_id,
            'additional_street' => $this->resource->relationLoaded('additional_street') ? (new StreetResource($this->additional_street)) : null,
            'additional_incident_type_id' => (int)$this->additional_incident_type_id,
            'additional_incident' => $this->resource->relationLoaded('additionalIncident') ? (new IncidentTypeResource($this->additionalIncident)) : null,
            'measures' => (string)$this->measures,
            'resources' => (string)$this->resources,
            'injured' => (int)$this->injured,
            'dead' => (int)$this->dead,
            'evacuated' => (int)$this->evacuated,
            'hospitalized' => (int)$this->hospitalized,
            'service_reactions' => $this->resource->relationLoaded('serviceReactions') ? ServiceReactionResource::collection($this->serviceReactions) : [],
            'chronology' => $this->resource->relationLoaded('chronology') ? ChronologyResource::collection($this->chronology) : [],
            'additional_comment' => (string) $this->additional_comment,
            'city_area_id' => (int) $this->city_area_id,
            'city_area' => $this->resource->relationLoaded('cityArea') ? (new CityAreaResource($this->cityArea)) : null,
            'injured_hard' => (int) $this->injured_hard,
            'poisoned' => (int) $this->poisoned,
            'saved' => (int) $this->saved,
            'saved_animals' => (int) $this->saved_animals,
            'incident_place' => (string)$this->incident_place,
            'additional_incident_place' => (string)$this->additional_incident_place,
            'reason' => (string)$this->reason,
            'chronology_start_time' => Carbon::parse($this->chronology_start_time)->format('Y-m-d H:i:s'),
            'chronology_end_time' => Carbon::parse($this->chronology_end_time)->format('Y-m-d H:i:s'),
            'popup_notifications' => $this->resource->relationLoaded('popupNotifications') ? Resource::collection($this->popupNotifications) : [],
            'notifications_sent' => (bool) $this->notifications_sent,
            'notification_message' => (string) $this->notification_message,
            'emergency_type_id' => (int) $this->emergency_type_id,
            'emergency_feature' => (string) $this->emergency_feature,
            'service_plans' => $this->service_plans,
            'emergency_name_id' => $this->emergency_name_id,
            'incident_type_text' => $this->incident_type_text,
            'custom_created_at' => $this->custom_created_at,
            'created_at' => $this->created_at,
            'kui' => $this->kui ? $this->kui : $this->id,
            'flooding_place_id' => $this->flooding_place_id,
            'flooding_reason_id' => $this->flooding_reason_id,
            'living_count' => $this->living_count,
            'avalanche_type_id' => $this->avalanche_type_id,
            'avalanche_volume' => $this->avalanche_volume,
            'elevator_emergency_type_id' => $this->elevator_emergency_type_id,
            'disease_type_id' => $this->disease_type_id,
            'name_disease' => $this->name_disease,
        ];
    }
}
