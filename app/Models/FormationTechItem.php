<?php

namespace App\Models;

use App\FormationTechReport;
use App\VehicleStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FormationTechItem
 *
 * @property int $id
 * @property int $vehicle_id
 * @property int $formation_tech_report_id
 * @property int|null $department
 * @property string $status
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\FormationTechReport $formation_tech_report
 * @property-read \App\Models\Vehicle $vehicle
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FormationTechItem status($status)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FormationTechItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FormationTechItem whereDepartment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FormationTechItem whereFormationTechReportId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FormationTechItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FormationTechItem whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FormationTechItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FormationTechItem whereVehicleId($value)
 * @mixin \Eloquent
 * @property string|null $reserve
 * @property string|null $date_to
 * @property string|null $date_from
 * @property string|null $comment
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FormationTechItem available($department = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FormationTechItem getStat($vehicle_id, $date_begin, $date_end, $status = 'action')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FormationTechItem whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FormationTechItem whereDateFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FormationTechItem whereDateTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FormationTechItem whereReserve($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FormationTechItem reserved($department = null)
 */
class FormationTechItem extends Model
{
    protected $fillable = [
        'vehicle_id',
        'vehicle_status_id',
        'formation_tech_report_id',
        'department',
        'status',
        'reserve',
        'comment',
        'date_from',
        'date_to',
        'dvr',
    ];

    protected $appends = [
        'vehicle_name_status',
        'date_from_formatted',
        'date_to_formatted',
    ];

    public $statuses = [
        'vacation' => 'Отпуск',
        'business_trip' => 'Командировка',
        'maternity' => 'Декрет',
        'sick_leave' => 'Больничный',
        'other' => 'Другие причины',
        'action' => 'в расчете',
        'reserve' => 'в резерве',
        'repair' => 'в ремонте',
    ];

    public function scopeStatus($q, $status)
    {
        return $q->where('status', $status);
    }

    public function scopeDvr($q, $search = true)
    {
        return $q->where('dvr', $search);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id')->withTrashed();
    }

    public function vehicle_status()
    {
        return $this->belongsTo(VehicleStatus::class, 'vehicle_status_id');
    }

    public function formation_tech_report()
    {
        return $this->belongsTo(FormationTechReport::class, 'formation_tech_report_id');
    }

    public function scopeGetStat($q, $vehicle_id, $date_begin, $date_end, $status = 'action')
    {
        return $q->where('vehicle_id', $vehicle_id)
            ->where('status', $status)
            ->whereBetween('updated_at',[$date_begin, $date_end]);
    }

    public function getDateFromFormattedAttribute()
    {
        return $this->date_from ? Carbon::parse($this->date_from)->format('d-m-Y') : $this->date_from;
    }

    public function getDateToFormattedAttribute()
    {
        return $this->date_to ? Carbon::parse($this->date_to)->format('d-m-Y') : $this->date_to;
    }

    public function getVehicleNameStatusAttribute()
    {
        $vehicle = $this->vehicle->name ?? null;
        $status = $this->vehicle_status->name ?? null;
        $type = $this->vehicle->vehicle_type_id ?? null;
        $result = $vehicle . ' '.($status ? "($status)" : '');
        $result .= $type === 3 ? ' (вс.т)' : ''; //вспомогательная техника
        return $result;
    }

    public function scopeAvailable($q, $department = null)
    {
        if($department){

            $result = $q->where('status', 'action')
                ->where('department', $department);
        }
        else{
            $result = $q->where('status', 'action');
        }

        return $result;

    }

    public function scopeReserved($q, $department = null)
    {
        if($department){

            $result = $q->where('status', 'reserve')
                ->where('department', $department);
        }
        else{
            $result = $q->where('status', 'reserve');
        }

        return $result;

    }

    //attr:status_title
    public function getStatusTitleAttribute()
    {
        return $this->statuses[$this->status] ?? null;
    }
}
