<?php

namespace App;

use App\Dictionary\BurntObject;
use App\Dictionary\FireLevel;
use App\Dictionary\LiquidationMethod;
use App\Dictionary\TripResult;
use App\Dictionary\WaterSupplySource;
use Illuminate\Database\Eloquent\Model;

class Ticket101InfoFromFd extends Model
{
    protected $table = 'ticket101_info_from_fds';

    protected $fillable = [
        'detailed_address',
        'burn_object_id',
        'living_sector_type_id',
        'trip_result_id',
        'liquidation_method_id',
        'result_fire_level_id',
        'max_square',
        'vu_found',
        'animal_death',
        'car_crash',
        'rescued_count',
        'evac_count',
        'co2_poisoned_count',
        'ch4_poisoned_count',
        'gpt_burns_count',
        'people_death_count',
        'children_death_count',
        'hospitalized_count',
        'ticket_result',
        'special_tech',
        'more_info',
        'water_supply_source_id',
        'distance',
        'owner',
        'ticket_id',
        'fire_department_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function fire_level()
    {
        return $this->hasOne(FireLevel::class, 'id', 'result_fire_level_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function burn_object()
    {
        return $this->hasOne(BurntObject::class, 'id', 'burn_object_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function trip_result()
    {
        return $this->hasOne(TripResult::class, 'id', 'trip_result_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function fire_department()
    {
        return $this->belongsTo(FireDepartment::class,'fire_department_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function living_sector_type()
    {
        return $this->belongsTo(LivingSectorType::class, 'living_sector_type_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function liquidation_method()
    {
        return $this->hasOne(LiquidationMethod::class, 'id', 'liquidation_method_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function water_supply_source()
    {
        return $this->belongsTo(WaterSupplySource::class, 'water_supply_source_id');
    }
}
