<?php

namespace App\Models;

use App\Dictionary\CityArea;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\EmergencySituation
 *
 * @property int $id
 * @property string|null $incident_type
 * @property string $date
 * @property string $time
 * @property string $place
 * @property int|null $city_area_id
 * @property string|null $location
 * @property string|null $object_name
 * @property string|null $size
 * @property int|null $died
 * @property int|null $died_children
 * @property int|null $injured
 * @property int|null $injured_children
 * @property int|null $hospitalized
 * @property int|null $hospitalized_children
 * @property int|null $evacuated
 * @property int|null $evacuated_children
 * @property int|null $saved
 * @property int|null $saved_children
 * @property int|null $lost
 * @property int|null $lost_children
 * @property string|null $influence
 * @property int $can_fix_themselves
 * @property string|null $involved
 * @property string|null $involved_services
 * @property string|null $involved_people
 * @property string|null $involved_tech
 * @property int $user_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Dictionary\CityArea $cityArea
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmergencySituation whereCanFixThemselves($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmergencySituation whereCityAreaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmergencySituation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmergencySituation whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmergencySituation whereDied($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmergencySituation whereDiedChildren($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmergencySituation whereEvacuated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmergencySituation whereEvacuatedChildren($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmergencySituation whereHospitalized($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmergencySituation whereHospitalizedChildren($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmergencySituation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmergencySituation whereIncidentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmergencySituation whereInfluence($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmergencySituation whereInjured($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmergencySituation whereInjuredChildren($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmergencySituation whereInvolved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmergencySituation whereInvolvedPeople($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmergencySituation whereInvolvedServices($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmergencySituation whereInvolvedTech($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmergencySituation whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmergencySituation whereLost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmergencySituation whereLostChildren($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmergencySituation whereObjectName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmergencySituation wherePlace($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmergencySituation whereSaved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmergencySituation whereSavedChildren($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmergencySituation whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmergencySituation whereTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmergencySituation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmergencySituation whereUserId($value)
 * @mixin \Eloquent
 */
class EmergencySituation extends Model
{
    /**
     * @var string
     */
    public $table = 'emergency_situations';

    /**
     * @var array
     */
    public $guarded = ['id'];

    protected $appends = [
        'date',
        'time',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function getDateAttribute()
    {
        if($this->date_time) {
            return Carbon::parse($this->date_time)->format('Y-m-d');
        }

        return null;
    }

    public function getTimeAttribute()
    {
        if($this->date_time) {
            return Carbon::parse($this->date_time)->format('H:i:s');
        }

        return null;
    }

    public function getTimeHumanFormatAttribute()
    {
        if($this->time) {
            return Carbon::parse($this->time)->format('H') . ' час ' . Carbon::parse($this->time)->format('i') . ' мин.';
        }
        return null;
    }

    public function getDateHumanFormatAttribute()
    {
        if($this->date) {
            return Carbon::parse($this->date)->format('d.m.Y');
        }
        return null;
    }

    public function scopeDailyRecords($q, $from = null, $to = null)
    {
        $from = $from ? $from : today()->addDay(-1)->addHours(7)->format('Y-m-d H:i:s');
        $to = $to ? $to : today()->addHours(7)->format('Y-m-d H:i:s');

        return $q->whereBetween('date_time', [$from, $to]);
    }
}
