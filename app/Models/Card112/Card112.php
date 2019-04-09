<?php

namespace App\Models\Card112;

use App\AvalancheType;
use App\Dictionary\CityArea;
use App\Dictionary\Street;
use App\DiseaseType;
use App\ElevatorEmergencyType;
use App\EmergencyName;
use App\EmergencyType;
use App\FloodingPlace;
use App\FloodingReason;
use App\Models\BaseModel;
use App\Models\IncidentType;
use App\Models\Notification\Notification;
use App\Models\Notification\NotificationGroup;
use App\Ticket101ServicePlan;
use Carbon\Carbon;
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
 * @property bool $notifications_sent
 * @property string $notification_message
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $custom_created_at
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
 * @property int $injured_hard
 * @property int $poisoned
 * @property int $saved
 * @property int $saved_animals
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card112\Card112 getStat($date_begin, $date_end, $reason_id = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card112\Card112 whereInjuredHard($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card112\Card112 wherePoisoned($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card112\Card112 whereSaved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card112\Card112 whereSavedAnimals($value)
 * @property string|null $incident_place
 * @property string|null $additional_incident_place
 * @property string|null $reason
 * @property string|null $chronology_start_time
 * @property string|null $chronology_end_time
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Ticket101ServicePlan[] $service_plans
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card112\Card112 filterByIncidentType($filter)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card112\Card112 filterByServiceType($filter)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card112\Card112 whereAdditionalIncidentPlace($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card112\Card112 whereChronologyEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card112\Card112 whereChronologyStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card112\Card112 whereIncidentPlace($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card112\Card112 whereReason($value)
 */
class Card112 extends BaseModel
{
    protected $searchByDate = 'custom_created_at';
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
        'detailed_address',
        'injured_hard',
        'poisoned',
        'saved',
        'saved_animals',
        'incident_place',
        'additional_incident_place',
        'reason',
        'chronology_start_time',
        'chronology_end_time',
        'emergency_feature',
        'emergency_type_id',
        'emergency_name_id',
        'incident_type_text',
        'kui',
        'custom_created_at', //клон created_at, можно править в карточке
        'flooding_place_id',
        'flooding_reason_id',
        'living_count',
        'avalanche_volume',
        'avalanche_note',
        'avalanche_type_id',
        'elevator_emergency_type_id',
        'disease_type_id',
        'name_disease',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function street()
    {
        return $this->hasOne(Street::class, 'id', 'street_id');
    }

    public function disease_type()
    {
        return $this->belongsTo(DiseaseType::class, 'disease_type_id');
    }

    public function avalanche_type()
    {
        return $this->belongsTo(AvalancheType::class, 'avalanche_type_id');
    }

    public function elevator_emergency_type()
    {
        return $this->belongsTo(ElevatorEmergencyType::class, 'elevator_emergency_type_id');
    }

    public function flooding_place()
    {
        return $this->belongsTo(FloodingPlace::class, 'flooding_place_id');
    }

    public function flooding_reason()
    {
        return $this->belongsTo(FloodingReason::class, 'flooding_reason_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function emergency_name()
    {
        return $this->hasOne(EmergencyName::class, 'id', 'emergency_name_id');
    }

    public function emergency_type()
    {
        return $this->belongsTo(EmergencyType::class, 'emergency_type_id');
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

    public function service_plans()
    {
        return $this->hasMany(Ticket101ServicePlan::class, 'card112_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function cityArea()
    {
        return $this->hasOne(CityArea::class, 'id', 'city_area_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function popupNotifications()
    {
        return $this->belongsToMany(
            Notification::class,
            'card112_popup_notifications',
            'card_112_id'
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function notificationGroups()
    {
        return $this->belongsToMany(
            NotificationGroup::class,
            'card112_notification_groups',
            'card_112_id'
        );
    }

    public function scopeGetStat($q, $date_begin, $date_end, $reason_id = null)
    {
        $baseQuery = $q->whereBetween('custom_created_at',[$date_begin, $date_end]);

        if($reason_id){
            $baseQuery = $q->whereBetween('custom_created_at',[$date_begin, $date_end])
                ->where('additional_incident_type_id', $reason_id);
        }

        $result['total'] = $baseQuery->count();
        $types = [
            'injured',
            'dead',
            'evacuated',
            'hospitalized',
            'injured_hard',
            'poisoned',
            'saved',
            'saved_animals'
        ];
        foreach ($types as $type) {
            $result[$type] = $baseQuery->sum($type);
        }

        $result['hurt'] = $baseQuery->sum('injured')
            + $baseQuery->sum('hospitalized')
            + $baseQuery->sum('injured_hard')
            + $baseQuery->sum('poisoned');

        return $result;
    }

    public function scopeGetDetailedStat($q, $date_begin, $date_end, $reason_id = null, $cityAreaId = null, $emergencyNameId = null)
    {
        $result = [];
        if(!$cityAreaId) {
            $areas = CityArea::all();
        }
        else {
            $areas = CityArea::where('id', $cityAreaId)->get();
        }

        $date_begin = $date_begin ? $date_begin: now()->subYear();
        $date_end = $date_end ? $date_end : now();

        if($reason_id){
            $reasons = IncidentType::where('id', $reason_id)->get();
        }
        else{
            $reasons = IncidentType::orderBy('name')->get();
        }

        foreach ($reasons as $reason) {
            foreach ($areas as $area) {

                $baseQuery = $q->whereBetween('custom_created_at',[$date_begin, $date_end])
                    ->where('additional_incident_type_id', $reason->id)
                    ->where('city_area_id', $area->id);

                if($emergencyNameId) {
                    $baseQuery = $baseQuery->where('emergency_name_id', $emergencyNameId);
                }

                $result[$reason->name][$area->name]['total'] = $baseQuery->count();
                $types = [
                    'injured',
                    'dead',
                    'evacuated',
                    'hospitalized',
                    'injured_hard',
                    'poisoned',
                    'saved',
                    'saved_animals'
                ];

                foreach ($types as $type) {
                    $result[$reason->name][$area->name][$type] = $baseQuery->sum($type);
                }

                $result[$reason->name][$area->name]['hurt'] = $baseQuery->sum('injured')
                    + $baseQuery->sum('hospitalized')
                    + $baseQuery->sum('injured_hard')
                    + $baseQuery->sum('poisoned');

            }
        }

        return $result;
    }

    public function scopeFilterByServiceType($q, $filter)
    {
        return $q->whereHas('service_plans.service_type', function ($service_type) use ($filter){
            $service_type->where('name', $filter);
        });
    }

    public function scopeFilterByIncidentType($q, $filter)
    {
        return $q->whereHas('incident', function ($service_type) use ($filter){
            $service_type->where('name', $filter);
        });
    }

    public function scopeDailyRecords($q, $from = null, $to = null)
    {
        $from = $from ? $from : today()->addDay(-1)->addHours(7)->format('Y-m-d H:i:s');
        $to = $to ? $to : today()->addHours(7)->format('Y-m-d H:i:s');

        return $q->whereBetween('custom_created_at', [$from, $to]);
    }

    public function setDetailedAddressAttribute($value)
    {
        if(!$value) {
            $this->attributes['detailed_address'] = $this->attributes['location'];
        }
        else {
            $this->attributes['detailed_address'] = $value;
        }
    }

    public function getDateAttribute()
    {
        $format = 'd.m.Y';
        if ($this->custom_created_at) {
            return Carbon::parse($this->custom_created_at)->format($format);
        }
        if ($this->created_at) {
            return $this->created_at->format($format);
        }

        return null;
    }

    public function setCustomCreatedAtAttribute($value)
    {
        if(!$value) {
            $value = now();
        }
        $this->attributes['custom_created_at'] = $value;
    }
}
