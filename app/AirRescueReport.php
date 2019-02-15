<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * App\AirRescueReport
 *
 * @property int $id
 * @property int|null $jet_fuel_reserved
 * @property int|null $jet_fuel_action
 * @property int|null $radio_stations
 * @property int|null $personal_respiratory_protection
 * @property int|null $personal_protection
 * @property int|null $other_protection
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\AirRescueReportPersonsItem[] $persons
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\AirRescueReportTechItem[] $tech
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AirRescueReport whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AirRescueReport whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AirRescueReport whereJetFuelAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AirRescueReport whereJetFuelReserved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AirRescueReport whereOtherProtection($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AirRescueReport wherePersonalProtection($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AirRescueReport wherePersonalRespiratoryProtection($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AirRescueReport whereRadioStations($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AirRescueReport whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int|null $staff_duty_shift
 * @property int|null $staff_action
 * @property int|null $staff_total
 * @property int|null $staff_head
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AirRescueReport whereStaffAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AirRescueReport whereStaffDutyShift($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AirRescueReport whereStaffHead($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AirRescueReport whereStaffTotal($value)
 */
class AirRescueReport extends Model
{
    protected $fillable = [
        'jet_fuel_action',
        'jet_fuel_reserved',
        'radio_stations',
        'personal_respiratory_protection',
        'personal_protection',
        'other_protection',

        'staff_head',
        'staff_head_count',
        'staff_head_phone',
        'staff_total',
        'staff_action',
        'staff_duty_shift',
        'senior_shift_name',
        'staff_duty_shift_8hours',

        'date',
    ];

    public function tech()
    {
        return $this->hasMany(AirRescueReportTechItem::class, 'report_id');
    }

    public function getTechString($type = 'action')
    {
        if($result = $this->tech()->where('status', $type)->count()) {
            $result .= ': ';
            foreach ($this->tech()->where('status', $type)->get() as $item) {
                $result .= $item->aircraft->full_name."; ";
            }

            return $result;
        }

        return 0;
    }

    public function scopeDailyRecords($q, $from = null, $to = null)
    {
        $from = $from ? $from : today()->addDay(-1)->addHours(7)->format('Y-m-d H:i:s');
        $to = $to ? $to : today()->addHours(7)->format('Y-m-d H:i:s');

        return $q->whereBetween('date', [$from, $to]);
    }

    public function scopeByDate($q, $search)
    {
        return $this->date ? $q->where('date', $search) : $q->whereDate('created_at', $search);
    }

    public function getDateHumanFormatAttribute()
    {
        if($this->date) {
            return Carbon::parse($this->date)->format('d.m.Y');
        }
        else{
            return Carbon::parse($this->created_at)->format('d.m.Y');
        }
    }
}
