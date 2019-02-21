<?php

namespace App;

use App\Dictionary\CityArea;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\DistrictManager
 *
 * @property int $id
 * @property int|null $city_area_id
 * @property string $name
 * @property string|null $rank
 * @property string|null $nickname
 * @property string|null $position
 * @property string|null $deleted_at
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Dictionary\CityArea|null $city_area
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\DistrictManagerPhone[] $phones
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\DistrictManager onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DistrictManager whereCityAreaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DistrictManager whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DistrictManager whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DistrictManager whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DistrictManager whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DistrictManager whereNickname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DistrictManager wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DistrictManager whereRank($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DistrictManager whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\DistrictManager withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\DistrictManager withoutTrashed()
 * @mixin \Eloquent
 */
class DistrictManager extends Model
{
    use SoftDeletes;

    protected $with = [
        'phones'
    ];

    protected $fillable = [
        'name',
        'rank',
        'nickname',
        'position',
        'city_area_id',
    ];

    public function phones()
    {
        return $this->hasMany(DistrictManagerPhone::class, 'district_manager_id');
    }

    public function city_area()
    {
        return $this->belongsTo(CityArea::class, 'city_area_id');
    }

    public function daily_items()
    {
        return $this->hasMany(FormationDistrictManagerItem::class, 'manager_id');
    }

    public function scopeGetDailyPerson($q, $cityAreaId, $date)
    {
        return $q->whereHas('daily_items', function ($di) use($cityAreaId, $date) {
            $di->where('city_area_id',$cityAreaId)
            ;
        })->whereHas('daily_items.report', function ($dr) use($cityAreaId, $date) {
            $dr->where('date',$date)
            ;
        })->first();
    }
}
