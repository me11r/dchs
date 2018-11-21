<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SirenSpeechTech extends Model
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
        return $this->sst + $this->motor + $this->demounted + $this->broken + $this->inactive;
    }
}
