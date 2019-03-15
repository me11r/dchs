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
use App\Models\BaseModel;
use App\Models\FireDepartmentResult;
use App\Models\Notification\Notification;
use App\Models\Notification\NotificationGroup;
use App\Models\NotificationService;
use App\Models\OperationalPlan;
use App\Models\Schedule;
use App\Models\Ticket101\Ticket101Notification;
use App\Models\Ticket101\Ticket101OtherRecord;
use App\Models\UploadedFile;
use App\Models\WallMaterial;
use Carbon\Carbon;
use function foo\func;
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
 * @property boolean $notifications_sent
 * @property string $notification_message
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
 * @property int|null $closed
 * @property int $is_real
 * @property string|null $pre_information
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Chronology101[] $chronologies
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Notification\NotificationGroup[] $notification_groups
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Notification\Notification[] $popup_notifications
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 closed($search = true)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 real($search = true)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereAdditionalDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereClosed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereFireplace($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereIsReal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereNotificationMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereNotificationsSent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 wherePreInformation($value)
 * @property string|null $drill_type
 * @property-read \App\Ticket101Other $other_ride
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 checkDrill($search)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101 whereDrillType($value)
 */
class Ticket101 extends BaseModel
{
    use SoftDeletes;
    protected $table = 'ticket101';
    protected $fillable = [];
    protected $guarded = ['id'];
    protected $searchByDate = 'custom_created_at';

    protected $appends = [
        'loc_time_total',
        'liqv_time_total',
        'on_way_category',
        'liqv_category',
        'gdzs_count',
        'gdzs_count_type',
        'gdzs_count_time',
    ];

    public function getTrucks()
    {
        return $this->chronologies ? $this->chronologies()
            ->whereDoesntHave('event_info_arrived', function ($q) {
                $q->where('name', 'ГДЗС');
            })->get() : [];
    }

    public function getGdzs()
    {
        return $this->chronologies ? $this->chronologies()
            ->whereHas('event_info_arrived', function ($q) {
                $q->where('name', 'ГДЗС');
            })
            ->with('event_info_arrived')
            ->get() : [];
    }

    public function getGdzsCountTypeAttribute()
    {
        $count = $this->gdzs_count;

        if($count === 1) {
            return 'one';
        }
        elseif ($count > 1) {
            return 'many';
        }

        return null;
    }

    public function getGdzsCountTimeAttribute()
    {
        return $this->chronologies ? $this->chronologies()
            ->whereHas('event_info_arrived', function ($q) {
                $q->where('name', 'ГДЗС');
            })
            ->with('event_info_arrived')
            ->sum('working_time') : 0;
    }

    //время следования
    public function getOnWayCategoryAttribute()
    {
        if($first_arrived = $this->first_department_arrived()) {

            $minutes = $first_arrived->on_way_time;

            if($minutes !== null && $minutes !== '0') {
                if($minutes < 5) {
                    return 'less_5';
                }
                elseif($minutes > 5 && $minutes < 10) {
                    return 'less_10';
                }
                elseif($minutes > 10) {
                    return 'more_10';
                }
            }
        }

        return null;
    }

    //время ликвидации
    public function getLiqvCategoryAttribute()
    {
        if($this->liqv_time_total !== '00:00:00' && $this->liqv_time_total !== null) {
            $time = Carbon::parse($this->liqv_time_total)->diffInMinutes('00:00:00');
            if($time < 15) {
                return 'less_15';
            }
            elseif($time > 15 && $time < 30) {
                return 'less_30';
            }
            elseif($time > 30 && $time < 60){
                return 'less_60';
            }
            elseif($time > 60 && $time < 120) {
                return 'less_120';
            }
            elseif($time > 120) {
                return 'more_120';
            }
        }

        return null;
    }

    //время ликвидации
    public function getGdzsCountAttribute()
    {
        if($this->chronologies) {
            $count = $this->chronologies()
                ->whereDoesntHave('event_info_arrived', function ($q) {
                    $q->where('name', 'ГДЗС');
                })->get();

            $count2 = $this->chronologies()
                ->whereDoesntHave('event_info_arrived', function ($q) {
                    $q->where('name', 'ГДЗС');
                })->sum('quantity');

            if($count->count() == 1 || $count2 == 1) {

                return 1;
            }
            elseif($count->count() > 1 || $count2 > 1) {
                return max($count->count(), $count2);
            }
        }

        return 0;
    }

    public function emergency_type()
    {
        return $this->belongsTo(EmergencyType::class, 'emergency_type_id');
    }

    public function object_classification()
    {
        return $this->belongsTo(ObjectClassification::class, 'object_classification_id');
    }

    public function chronologies()
    {
        return $this->hasMany(Chronology101::class, 'ticket101_id');
    }

    public function chronologies_arrived()
    {
        return $this->hasMany(Chronology101::class, 'ticket101_id')->whereNotNull('event_info_arrived_id');
    }

    public function chronologies_trucks()
    {
        return $this->hasMany(Chronology101::class, 'ticket101_id')
            ->whereDoesntHave('event_info_arrived', function ($q){
                $q->where('name', 'ГДЗС');
            });
    }

    public function chronologiesFromFd()
    {
        return $this->hasMany(Chronology101FromFd::class, 'ticket101_id');
    }

    public function crossroad_1()
    {
        return $this->hasOne(Street::class, 'id', 'crossroad_1_id');
    }

    public function district_manager()
    {
        return $this->belongsTo(DistrictManager::class, 'district_manager_id');
    }

    public function analytics()
    {
        return $this->hasOne(Analytics101Item::class, 'ticket101_id');
    }

    public function crossroad_2()
    {
        return $this->hasOne(Street::class, 'id', 'crossroad_2_id');
    }

    public function city_area()
    {
        return $this->hasOne(CityArea::class, 'id', 'city_area_id');
    }

    public function cityArea()
    {
        return $this->hasOne(CityArea::class, 'id', 'city_area_id');
    }

    public function fire_level()
    {
        return $this->hasOne(FireLevel::class, 'id', 'fire_level_id');
    }

    public function fire_object()
    {
        return $this->hasOne(BurntObject::class, 'id', 'fire_object_id');
    }

    public function burn_object()
    {
        return $this->hasOne(BurntObject::class, 'id', 'burn_object_id');
    }

    public function trip_result()
    {
        return $this->hasOne(TripResult::class, 'id', 'trip_result_id');
    }

    public function hqRides()
    {
        return $this->hasMany(Ticket101HqRide::class, 'ticket101_id');
    }

    public function logs()
    {
        return $this->hasMany(Ticket101Log::class, 'ticket101_id');
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

    public function living_sector_type()
    {
        return $this->belongsTo(LivingSectorType::class, 'living_sector_type_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function other_records()
    {
        return $this->hasMany(Ticket101OtherRecord::class, 'ticket101_id', 'id');
    }

    public function first_department_arrived()
    {
        $first_arrived = $this->results()
            ->whereNotNull('arrive_time')
            ->selectRaw('arrive_time, id, out_time, out_time - arrive_time as on_way_time')
            ->groupBy('id')
            ->havingRaw('min(arrive_time)')
            ->first();

        if($first_arrived) {
            try {
                $arrive_time = Carbon::parse($first_arrived->arrive_time);
                $first_arrived->on_way_time = $arrive_time->diffInMinutes($first_arrived->out_time);
            }
            catch (\Exception $e) {

            }

        }

        return $first_arrived;
    }

    public function departments_arrived()
    {
        return $this->results()
            ->whereNotNull('arrive_time')
            ->orderBy('arrive_time')
            ->get();
    }

    public function departments_arrived_hq()
    {
        return $this->hqRides()
            ->whereNotNull('arrive_time')
            ->where('dispatched', true)
            ->orderBy('arrive_time')
            ->get();
    }

    public function getLocTimeTotalAttribute()
    {
        try{
            //loc_time - arrived_time
            if($this->loc_time){
                $first_arrived = $this->first_department_arrived();
                if($first_arrived && $first_arrived->arrive_time){
                    $time1 = Carbon::parse($this->loc_time);
                    $time2 = Carbon::parse($first_arrived->arrive_time);

                    return $time1->diff($time2)->format('%H:%I:%S');
                }
            }
            return null;
        }
        catch (\Exception $e){
            return null;
        }

    }

    //liqv_time_total
    public function getLiqvTimeTotalAttribute()
    {
        try {
            if ($this->liqv_time) {
                $first_arrived = $this->first_department_arrived();
                if ($first_arrived && $first_arrived->arrive_time) {

                    $time1 = Carbon::parse($this->liqv_time);
                    $time2 = Carbon::parse($first_arrived->arrive_time);

                    return $time1->diff($time2)->format('%H:%I:%S');
                }
            }
            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    //liqv_time_total_minutes
    public function getLiqvTimeTotalMinutesAttribute()
    {
        try {
            if ($this->liqv_time) {
                $first_arrived = $this->first_department_arrived();
                if ($first_arrived && $first_arrived->arrive_time) {

                    $time1 = Carbon::parse($this->liqv_time);
                    $time2 = Carbon::parse($first_arrived->arrive_time);

                    return $time1->diffInMinutes($time2);
                }
            }
            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function popup_notifications()
    {
        return $this->belongsToMany(
            Notification::class,
            'ticket101_popup_notifications',
            'ticket101_id'
        );
    }

    public function notification_groups()
    {
        return $this->belongsToMany(
            NotificationGroup::class,
            'ticket101_notification_groups',
            'ticket101_id'
        );
    }

    public function notifications()
    {
        return $this
                ->hasMany(Ticket101Notification::class, 'ticket101_id', 'id');
                //->join('service_types', 'service_types.id', '=', 'ticket101_notifications.notification_service_id')
                //->orderBy('service_types.priority', 'DESC');
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

        if($this->closed){
            return false;
        }
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

    public function drill_type()
    {
        return $this->belongsTo(DrillType::class,'drill_type_id');
    }

    public function scopeReal($q, $search = null)
    {
        return $q->where('drill_type_id', $search);
    }

    public function scopeDrill($q)
    {
        return $q->whereNotNull('drill_type_id');
    }

    public function scopeClosed($q, $search = true)
    {
        return $q->where('closed', $search);
    }

    public function scopeGetStat($q, $date_begin, $date_end, $reason_id = null)
    {
        $baseQuery = $q->whereBetween('custom_created_at',[$date_begin, $date_end]);

        if($reason_id){
            $baseQuery = $q->whereBetween('custom_created_at',[$date_begin, $date_end])
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

    public function scopeGetDetailedStat($q, $date_begin, $date_end, $result_id = null, $burnt_id = null, $city_area_id = null)
    {
        $date_begin = $date_begin ? $date_begin: now()->subDays(3);
        $date_end = $date_end ? $date_end : now();

        $tickets = $q->with([
            'crossroad_1',
            'burn_object',
            'trip_result',
            'city_area',
            'crossroad_2',
            'other_records',
            'chronologies',
            'chronologies_trucks',
            'chronologies.event_info',
            'chronologies.event_info_arrived',
            'chronologies.fire_department_result.tech',
            'chronologies.fire_department_result.department',
            'results',
            'results.tech',
            'results.tech.formation_tech_report',
            'operational_card',
            'operational_plan.special_plans'
        ])
            ->whereBetween('custom_created_at',[$date_begin, $date_end]);

        if($result_id){
            $tickets = $tickets->where('trip_result_id', $result_id);
        }

        if ($burnt_id) {
            $tickets = $tickets->where('burn_object_id', $burnt_id);
        }

        if ($city_area_id) {
            $tickets = $tickets->where('city_area_id', $city_area_id);
        }

        $result = $tickets->orderBy('id', 'desc')
            ->get()
        ;

        foreach ($result as $key => $ticket) {
            $first_arrived = $ticket->first_department_arrived();

            if($first_arrived) {

                $result[$key]->first_arrived_time = $first_arrived->arrive_time;
                $result[$key]->on_way_time = $first_arrived->on_way_time;

                $result[$key]->detailed_staff_count = $ticket->getDetailedStaffCount();
                $result[$key]->gdzs_items = $ticket->getGdzs();

            }
        }

        return $result;
    }

    public function getRecommendations()
    {
        $schedule = Schedule::where('fire_department_main_id', $this->fire_department_id)
            ->where('dict_fire_level_id', $this->fire_level_id)
            ->get();

        return $schedule;
    }

    /*для разделения вьюшек 101 карточки: реальных и учебных*/
    public function scopeCheckDrill($q, $search)
    {
        return $q;
        /*if($search){
            return $q->whereNotNull('drill_type');
        }
        else{
            return $q->whereNull('drill_type');
        }*/
    }

    public function file_1() {
        return $this->hasOne(UploadedFile::class, 'id', 'file_1_id');
    }

    public function file_2() {
        return $this->hasOne(UploadedFile::class, 'id', 'file_2_id');
    }

    public function file_3() {
        return $this->hasOne(UploadedFile::class, 'id', 'file_3_id');
    }

    public function file_4() {
        return $this->hasOne(UploadedFile::class, 'id', 'file_4_id');
    }

    public function scopeDailyRecords($q, $from = null, $to = null)
    {
        $from = $from ? $from : today()->addDay(-1)->addHours(7)->format('Y-m-d H:i:s');
        $to = $to ? $to : today()->addHours(7)->format('Y-m-d H:i:s');

        return $q->whereBetween('custom_created_at', [$from, $to])
            ->with('city_area', 'departments', 'trip_result', 'liquidation_method');
    }

    public function fireDepartmentsInfo()
    {
        return $this->hasMany(Ticket101InfoFromFd::class, 'ticket_id');
    }

    public function formation_report()
    {
        return $this->belongsTo(FormationReport::class, 'formation_report_id');
    }

    public function getDetailedStaffCount()
    {
        if($this->results->count()) {
            $depts_out = $this->results()->whereNotNull('dispatch_time')->get();
            $deptsArr = array_unique($depts_out->pluck('fire_department_id')->toArray());

            foreach (FireDepartment::whereIn('id', $deptsArr)->get() as $item) {

                $deptsNumbers = $depts_out->filter(function ($q) use ($item){
                    return $q->fire_department_id === $item->id;
                })->pluck('tech.department')->toArray();

                $deptsStaffCounts = $depts_out->filter(function ($q) use ($item){
                    return $q->fire_department_id === $item->id;
                })->pluck('staff_count')->toArray();

                $res = [];
                foreach ($deptsNumbers as $key => $deptsNumber) {
                    $res[] = "{$deptsNumber}:{$deptsStaffCounts[$key]} чел.";
                }

                $deptsNumbers = implode(',', $res);

                $this->detailed_staff_count .= "{$item->title}($deptsNumbers), ";

            }
        }
        return $this->detailed_staff_count;
    }

    public function setTotalStaffCountAttribute($value)
    {
        if(!$value) {
            $results = $this->results;

            if($results && $results->count()) {
                $value = $this->results()->sum('staff_count');
            }
        }

        $this->attributes['total_staff_count'] = $value;
    }

    public function getTotalStaffCountAttribute($value)
    {
        if(!$value) {
            $results = $this->results;

            if($results && $results->count()) {
                $value = $this->results()->sum('staff_count');
            }
        }

        return $value;
    }

//    public function setCustomCreatedAtAttribute($value)
//    {
//        if(!$value) {
//            $value = now();
//        }
//        $this->attributes['custom_created_at'] = $value;
//    }
//
//    public function getCreatedAtAttribute()
//    {
//        return $this->custom_created_at ? Carbon::parse($this->custom_created_at) : $this->created_at;
//    }
}