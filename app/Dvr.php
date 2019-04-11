<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Dvr extends Model
{
    protected $fillable = [
        'formation_tech_report_id',
        'date_from',
        'date_to',
        'status',
        'note',
    ];

    public function scopeStatus($q, $status)
    {
        return $q->where('status', $status);
    }

    public function formation_tech_report()
    {
        return $this->belongsTo(FormationTechReport::class, 'formation_tech_report_id');
    }

    public function getDateFromFormattedAttribute()
    {
        return $this->date_from ? Carbon::parse($this->date_from)->format('d-m-Y') : $this->date_from;
    }

    public function getDateToFormattedAttribute()
    {
        return $this->date_to ? Carbon::parse($this->date_to)->format('d-m-Y') : $this->date_to;
    }
}
