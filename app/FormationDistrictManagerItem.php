<?php

namespace App;

use App\Dictionary\CityArea;
use Illuminate\Database\Eloquent\Model;

/**
 * App\FormationDistrictManagerItem
 *
 * @property int $id
 * @property int $report_id
 * @property int $manager_id
 * @property int $city_area_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Dictionary\CityArea $city_area
 * @property-read \App\DistrictManager $manager
 * @property-read \App\FormationDistrictManager $report
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationDistrictManagerItem whereCityAreaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationDistrictManagerItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationDistrictManagerItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationDistrictManagerItem whereManagerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationDistrictManagerItem whereReportId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationDistrictManagerItem whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FormationDistrictManagerItem extends Model
{
    protected $fillable = [
        'report_id',
        'manager_id',
        'city_area_id',
        'inactive_type',
        'date_from',
        'date_to',
        'comment',
    ];

    public $inactiveTypes = [
        'vacation' => 'Отпуск',
        'business_trip' => 'Командировка',
        'maternity' => 'Декрет',
        'sick_leave' => 'Больничный',
    ];

    public function report()
    {
        return $this->belongsTo(FormationDistrictManager::class, 'report_id');
    }

    public function manager()
    {
        return $this->belongsTo(DistrictManager::class, 'manager_id');
    }

    public function city_area()
    {
        return $this->belongsTo(CityArea::class, 'city_area_id');
    }
}
