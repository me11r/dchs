<?php

namespace App\Models;

use App\FormationTechReport;
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
 */
class FormationTechItem extends Model
{
    protected $fillable = [
        'vehicle_id',
        'formation_tech_report_id',
        'department',
        'status',
        'reserve',
    ];

    public function scopeStatus($q, $status)
    {
        return $q->where('status', $status);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    public function formation_tech_report()
    {
        return $this->belongsTo(FormationTechReport::class, 'formation_tech_report_id');
    }
}
