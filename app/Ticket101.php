<?php


namespace App;


use App\Dictionary\BurntObject;
use App\Dictionary\CityArea;
use App\Dictionary\FireLevel;
use App\Dictionary\FireObject;
use App\Dictionary\LiquidationMethod;
use App\Dictionary\Street;
use App\Dictionary\TripResult;
use App\Dictionary\WaterSupplySource;
use App\Models\FireDepartmentResult;
use App\Models\NotificationService;
use App\Models\OperationalPlan;
use App\Models\Ticket101\Ticket101Notification;
use App\Models\Ticket101\Ticket101OtherRecord;
use App\Models\WallMaterial;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Ticket101
 *
 * @package App
 * @property int $id
 * @property string|null $liqv_time
 * @property string|null $loc_time
 * @property string|null $kui
 * @property string|null $building_square
 * @property string|null $year_of_development
 * @property int|null $wall_material_id
 * @property int|null $water_supply_source_id
 * @property string|null $location
 * @property int|null $city_area_id
 * @property int|null $crossroad_1_id
 * @property int|null $crossroad_2_id
 * @property int|null $fire_object_id
 * @property int|null $storey_count
 * @property int|null $floor
 * @property string|null $building_description
 * @property int $people_in_danger
 * @property int|null $fire_level_id
 * @property string|null $caller_phone
 * @property string|null $caller_name
 * @property string|null $call_time
 * @property string|null $notify_100_time
 * @property string|null $notify_101_time
 * @property string|null $notify_102_time
 * @property string|null $notify_103_time
 * @property string|null $notify_104_time
 * @property string|null $notify_b01_time
 * @property string|null $notify_b04_time
 * @property string|null $next_notify_time
 * @property string|null $call_112_time
 * @property string|null $name_112_recv
 * @property string|null $arrival_112
 * @property string|null $call_102_time
 * @property string|null $name_102_recv
 * @property string|null $arrival_102
 * @property string|null $call_103_time
 * @property string|null $name_103_recv
 * @property string|null $arrival_103
 * @property string|null $call_104_time
 * @property string|null $name_104_recv
 * @property string|null $arrival_104
 * @property string|null $call_electro_time
 * @property string|null $name_electro_recv
 * @property string|null $arrival_electro
 * @property string|null $call_water_time
 * @property string|null $name_water_recv
 * @property string|null $arrival_water
 * @property string|null $call_smk_time
 * @property string|null $name_smk_recv
 * @property string|null $arrival_ao_ort_recv
 * @property string|null $arrival_kaz_aviaserice_recv
 * @property string|null $arrival_roso_recv
 * @property string|null $arrival_gu_kaz_recv
 * @property string|null $call_ao_ort_recv
 * @property string|null $call_kaz_aviaserice_recv
 * @property string|null $call_roso_recv
 * @property string|null $call_gu_kaz_recv
 * @property string|null $name_ao_ort_recv
 * @property string|null $name_kaz_aviaserice_recv
 * @property string|null $name_roso_recv
 * @property string|null $name_gu_kaz_recv
 * @property string|null $arrival_smk
 * @property string|null $add_info
 * @property string|null $deleted_at
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null $detailed_address
 * @property int|null $burn_object_id
 * @property int|null $trip_result_id
 * @property int|null $liquidation_method_id
 * @property int|null $result_fire_level_id
 * @property int $vu_found
 * @property int $animal_death
 * @property int $car_crash
 * @property int|null $rescued_count
 * @property int|null $evac_count
 * @property int|null $co2_poisoned_count
 * @property int|null $gpt_burns_count
 * @property int|null $people_death_count
 * @property int|null $children_death_count
 * @property int|null $hospitalized_count
 * @property string|null $more_info
 * @property int $hydrant_found
 * @property float|null $distance
 * @property string|null $owner
 * @property string|null $explanation
 * @property int|null $ch4_poisoned_count
 * @property string|null $register_time
 * @property string|null $object_name
 * @property int $operational_plan_id
 * @property int|null $operational_card_id
 * @property int $fire_department_id
 * @property int $pre_information_id
 * @property string|null $fireplace
 * @property string|null $additional_description
 * @property-read \App\Dictionary\CityArea $city_area
 * @property-read \App\Dictionary\Street $crossroad_1
 * @property-read \App\Dictionary\Street $crossroad_2
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\FireDepartment[] $departments
 * @property-read \App\FireDepartment $fire_department
 * @property-read \App\Dictionary\FireLevel $fire_level
 * @property-read \App\Dictionary\BurntObject $fire_object
 * @property-read \App\Dictionary\LiquidationMethod $liquidation_method
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Ticket101\Ticket101Notification[] $notifications
 * @property-read \App\OperationalCard|null $operational_card
 * @property-read \App\Models\OperationalPlan $operational_plan
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Ticket101\Ticket101OtherRecord[] $other_records
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\FireDepartmentResult[] $results
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\RoadtripPlan[] $road_trip_plans
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Ticket101ServicePlan[] $service_plans
 * @property-read \App\Dictionary\TripResult $trip_result
 * @property-read \App\Models\WallMaterial|null $wall_material
 * @property-read \App\Dictionary\WaterSupplySource|null $water_supply_source
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 canEditTicket()
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 getStat($date_begin, $date_end, $reason_id = null)
 * @method static \Illuminate\Database\Query\Builder|\App\Ticket101 onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereAddInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereAnimalDeath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereArrival102($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereArrival103($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereArrival104($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereArrival112($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereArrivalAoOrtRecv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereArrivalElectro($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereArrivalGuKazRecv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereArrivalKazAviasericeRecv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereArrivalRosoRecv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereArrivalSmk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereArrivalWater($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereBuildingDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereBuildingSquare($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereBurnObjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereCall102Time($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereCall103Time($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereCall104Time($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereCall112Time($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereCallAoOrtRecv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereCallElectroTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereCallGuKazRecv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereCallKazAviasericeRecv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereCallRosoRecv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereCallSmkTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereCallTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereCallWaterTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereCallerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereCallerPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereCarCrash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereCh4PoisonedCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereChildrenDeathCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereCityAreaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereCo2PoisonedCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereCrossroad1Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereCrossroad2Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereDetailedAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereDistance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereEvacCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereExplanation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereFireDepartmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereFireLevelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereFireObjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereFloor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereGptBurnsCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereHospitalizedCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereHydrantFound($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereKui($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereLiquidationMethodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereLiqvTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereLocTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereMoreInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereName102Recv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereName103Recv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereName104Recv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereName112Recv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereNameAoOrtRecv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereNameElectroRecv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereNameGuKazRecv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereNameKazAviasericeRecv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereNameRosoRecv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereNameSmkRecv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereNameWaterRecv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereNextNotifyTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereNotify100Time($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereNotify101Time($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereNotify102Time($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereNotify103Time($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereNotify104Time($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereNotifyB01Time($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereNotifyB04Time($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereObjectName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereOperationalCardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereOperationalPlanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereOwner($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePeopleDeathCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePeopleInDanger($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePreInformationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereRegisterTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereRescuedCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereResultFireLevelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereStoreyCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereTripResultId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereVuFound($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereWallMaterialId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereWaterSupplySourceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereYearOfDevelopment($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Ticket101 withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Ticket101 withoutTrashed()
 * @mixin \Eloquent
 */
class Ticket101 extends Model
{
    use SoftDeletes;
    protected $table = 'ticket101';
    protected $fillable = [];
    protected $guarded = ['id'];

    public function crossroad_1()
    {
        return $this->hasOne(Street::class, 'id', 'crossroad_1_id');
    }

    public function crossroad_2()
    {
        return $this->hasOne(Street::class, 'id', 'crossroad_2_id');
    }

    public function city_area()
    {
        return $this->hasOne(CityArea::class, 'id', 'city_area_id');
    }

    public function fire_level(){
        return $this->hasOne(FireLevel::class, 'id', 'fire_level_id');
    }

    public function fire_object()
    {
        return $this->hasOne(BurntObject::class, 'id', 'fire_object_id');
    }

    public function trip_result()
    {
        return $this->hasOne(TripResult::class, 'id', 'trip_result_id');
    }

    public function liquidation_method()
    {
        return $this->hasOne(LiquidationMethod::class, 'id', 'liquidation_method_id');
    }

    public function departments()
    {
        return $this->belongsToMany(FireDepartment::class, 'roadtrip_plan', 'card_id', 'department_id');
    }

    public function road_trip_plans()
    {
        return $this->hasMany(RoadtripPlan::class, 'card_id');
    }

    public function operational_plan()
    {
        return $this->hasOne(OperationalPlan::class, 'id', 'operational_plan_id');
    }

    public function fire_department()
    {
        return $this->belongsTo(FireDepartment::class,'fire_department_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function other_records()
    {
        return $this->hasMany(Ticket101OtherRecord::class, 'ticket101_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function notifications()
    {
        return $this->hasMany(Ticket101Notification::class, 'ticket101_id', 'id');
    }

    public function results()
    {
        return $this->hasMany(FireDepartmentResult::class, 'ticket101_id');
    }

    public function water_supply_source()
    {
        return $this->belongsTo(WaterSupplySource::class, 'water_supply_source_id');
    }

    public function scopeCanEditTicket($q)
    {
        $created_date = $this->created_at;
        if($created_date == null){
            return true;
        }
        $is_yesterday = $created_date->isYesterday();
        $is_today = $created_date->isToday();
        $pass_after_9 = now()->format('H') > 9;

        if(!$is_yesterday && !$is_today){
            return false;
        }
        elseif($is_today && !$pass_after_9){
            return true;
        }
        elseif($is_today){
            return true;
        }
        elseif($is_yesterday && !$pass_after_9){
            return false;
        }

        return false;
    }

    public function getServicePlan($ticket, $service)
    {
        return Ticket101ServicePlan::where('card_id', $ticket)
            ->where('department', $service)->first();
    }


    public function wall_material()
    {
        return $this->belongsTo(WallMaterial::class, 'wall_material_id');
    }

    public function operational_card()
    {
        return $this->belongsTo(OperationalCard::class, 'operational_card_id');
    }

    public function service_plans()
    {
        return $this->hasMany(Ticket101ServicePlan::class, 'card_id');
    }

    public function scopeReal($q, $search = true)
    {
        return $q->where('is_real', $search);
    }

    public function scopeGetStat($q, $date_begin, $date_end, $reason_id = null)
    {
        $baseQuery = $q->whereBetween('created_at',[$date_begin, $date_end]);

        if($reason_id){
            $baseQuery = $q->whereBetween('created_at',[$date_begin, $date_end])
                ->where('trip_result_id', $reason_id);
        }

        $result['total'] = $baseQuery->count();
        $result['rescued_count'] = $baseQuery->sum('rescued_count');
        $result['evac_count'] = $baseQuery->sum('evac_count');
        $result['co2_poisoned_count'] = $baseQuery->sum('co2_poisoned_count');
        $result['ch4_poisoned_count'] = $baseQuery->sum('ch4_poisoned_count');
        $result['gpt_burns_count'] = $baseQuery->sum('gpt_burns_count');
        $result['people_death_count'] = $baseQuery->sum('people_death_count');
        $result['children_death_count'] = $baseQuery->sum('children_death_count');
        $result['hospitalized_count'] = $baseQuery->sum('hospitalized_count');

        $result['hurt'] = $baseQuery->sum('co2_poisoned_count')
            + $baseQuery->sum('ch4_poisoned_count')
            + $baseQuery->sum('gpt_burns_count')
            + $baseQuery->sum('hospitalized_count');

        return $result;
    }


}
