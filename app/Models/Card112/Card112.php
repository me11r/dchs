<?php

namespace App\Models\Card112;

use App\Dictionary\Street;
use App\Models\IncidentType;
use Illuminate\Database\Eloquent\Model;

class Card112 extends Model
{
    public $table = 'card_112';

    public $fillable = [
        'address_id',
        'crossroad_1_id',
        'crossroad_2_id',
        'incident_type_id',
        'description',
        'caller_phone',
        'caller_name',
        'call_time',
        'additional_address_id',
        'additional_incident_type_id',
        'measures',
        'resources',
        'injured',
        'dead',
        'evacuated',
        'hospitalized'
    ];

    public function address()
    {
        return $this->hasOne(Street::class, 'id', 'address_id');
    }

    public function crossroad1()
    {
        return $this->hasOne(Street::class, 'id', 'crossroad_1_id');
    }

    public function crossroad2()
    {
        return $this->hasOne(Street::class, 'id', 'crossroad_2_id');
    }

    public function additionalAddress()
    {
        return $this->hasOne(Street::class, 'id', 'additional_address_id');
    }

    public function incident()
    {
        return $this->hasOne(IncidentType::class, 'id', 'incident_type_id');
    }

    public function additionalIncident()
    {
        return $this->hasOne(IncidentType::class, 'id', 'additional_incident_type_id');
    }

    public function serviceReactions()
    {
        return $this->hasMany(Card112ServiceReaction::class, 'card112_id', 'id');
    }

    public function chronology()
    {
        return $this->hasMany(Card112Chronology::class, 'card112_id', 'id');
    }
}
