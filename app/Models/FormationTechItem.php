<?php

namespace App\Models;

use App\FormationTechReport;
use Illuminate\Database\Eloquent\Model;

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
