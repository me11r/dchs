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
 * @package App
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
