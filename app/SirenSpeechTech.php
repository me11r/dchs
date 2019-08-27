<?php

namespace App;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class SirenSpeechTech extends BaseModel
{
    protected $with = [
        'items'
    ];
    
    protected $fillable = [
        'sst',
        'motor',
        'demounted',
        'broken',
        'inactive',
        'created_at',
    ];

    protected $appends = [
        'total'
    ];

    public function items()
    {
        return $this->hasMany(SirenSpeechTechItem::class, 'tech_id');
    }

    public function getTotalAttribute()
    {
        return $this->sst + $this->motor;
    }

    public function scopeDailyRecords($q, $from = null, $to = null)
    {
        $from = $from ? $from : today()->addDay(-1)->addHours(7)->format('Y-m-d H:i:s');
        $to = $to ? $to : today()->addHours(7)->format('Y-m-d H:i:s');

        return $q->whereBetween('created_at', [$from, $to]);
    }
}
