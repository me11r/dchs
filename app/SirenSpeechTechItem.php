<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SirenSpeechTechItem extends Model
{
    protected $fillable = [
        'tech_id',
        'text',
        'type', //demounted | broken | inactive
    ];

    public function tech()
    {
        return $this->belongsTo(SirenSpeechTech::class, 'tech_id');
    }

    public function scopeType($q, $search)
    {
        return $q->where('type', $search);
    }
}
