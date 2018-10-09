<?php

namespace App\Models\Card112;

use App\Dictionary\CityArea;
use App\Dictionary\Street;
use App\Models\IncidentType;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Card112\Card112
 *
 * @property int $id
 * @property int $crossroad_1_id
 * @property int $crossroad_2_id
 * @property int $incident_type_id
 * @property string|null $description
 * @property string $caller_phone
 * @property string $caller_name
 * @property string $call_time
 * @property int $additional_street_id
 * @property int $additional_incident_type_id
 * @property string $measures
 * @property string $resources
 * @property int $injured
 * @property int|null $dead
 * @property int $evacuated
 * @property int $hospitalized
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null $additional_comment
 * @property int $city_area_id
 * @property-read \App\Dictionary\Street $additionalAddress
 * @property-read \App\Models\IncidentType $additionalIncident
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Card112\Card112Chronology[] $chronology
 * @property-read \App\Dictionary\CityArea $cityArea
 * @property-read \App\Dictionary\Street $crossroad1
 * @property-read \App\Dictionary\Street $crossroad2
 * @property-read \App\Models\IncidentType $incident
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Card112\Card112ServiceReaction[] $serviceReactions
 * @property-read \App\Dictionary\Street $street
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card112\Card112 whereAdditionalComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card112\Card112 whereAdditionalIncidentTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card112\Card112 whereAdditionalStreetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card112\Card112 whereCallTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card112\Card112 whereCallerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card112\Card112 whereCallerPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card112\Card112 whereCityAreaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card112\Card112 whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card112\Card112 whereCrossroad1Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card112\Card112 whereCrossroad2Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card112\Card112 whereDead($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card112\Card112 whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card112\Card112 whereEvacuated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card112\Card112 whereHospitalized($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card112\Card112 whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card112\Card112 whereIncidentTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card112\Card112 whereInjured($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card112\Card112 whereMeasures($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card112\Card112 whereResources($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card112\Card112 whereStreetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card112\Card112 whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $location
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card112\Card112 whereLocation($value)
 */
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
        'city_area_id',
        'location',
        'injured_hard',
        'poisoned',
        'saved',
        'saved_animals'
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
