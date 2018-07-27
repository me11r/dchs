<?php

namespace App\Models\Card112;

use App\Dictionary\CityArea;
use App\Dictionary\Street;
use App\Models\IncidentType;
use Illuminate\Database\Eloquent\Model;

class Card112 extends Model
{
    /**
     * @var string
     */
    public $table = 'card_112';

    /**
     * @var array
     */
    public $fillable = [
        'street_id',
        'crossroad_1_id',
        'crossroad_2_id',
        'incident_type_id',
        'description',
        'caller_phone',
        'caller_name',
        'call_time',
        'additional_street_id',
        'additional_incident_type_id',
        'measures',
        'resources',
        'injured',
        'dead',
        'evacuated',
        'hospitalized',
        'additional_comment',
        'city_area_id'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function street()
    {
        return $this->hasOne(Street::class, 'id', 'street_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function crossroad1()
    {
        return $this->hasOne(Street::class, 'id', 'crossroad_1_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function crossroad2()
    {
        return $this->hasOne(Street::class, 'id', 'crossroad_2_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function additionalAddress()
    {
        return $this->hasOne(Street::class, 'id', 'additional_street_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function incident()
    {
        return $this->hasOne(IncidentType::class, 'id', 'incident_type_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function additionalIncident()
    {
        return $this->hasOne(IncidentType::class, 'id', 'additional_incident_type_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function serviceReactions()
    {
        return $this->hasMany(Card112ServiceReaction::class, 'card112_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function chronology()
    {
        return $this->hasMany(Card112Chronology::class, 'card112_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function cityArea()
    {
        return $this->hasOne(CityArea::class, 'id', 'city_area_id');
    }
}
