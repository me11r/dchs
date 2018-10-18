<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AirRescueReport extends Model
{
    protected $fillable = [
        'head',
        'jet_fuel_action',
        'jet_fuel_reserved',
        'radio_stations',
        'personal_respiratory_protection',
        'personal_protection',
        'other_protection',
    ];

    public function persons()
    {
        return $this->hasMany(AirRescueReportPersonsItem::class, 'report_id');
    }

    public function tech()
    {
        return $this->hasMany(AirRescueReportTechItem::class, 'report_id');
    }
}
