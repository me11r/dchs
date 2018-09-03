<?php

namespace App\Models;

use App\Dictionary\CityArea;
use Illuminate\Database\Eloquent\Model;

class CityMicroArea extends Model
{
    protected $fillable = [
        'name',
        'city_area_id',
    ];

    public function city_area()
    {
        return $this->belongsTo(CityArea::class, 'city_area_id');
    }

    public function scopeName($q, $title)
    {
        return $q->where('name', $title);
    }
}
