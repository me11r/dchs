<?php

namespace App;

use App\Dictionary\FireLevel;
use Illuminate\Database\Eloquent\Model;

class OperationalCard extends Model
{
    protected $fillable = [
        'fire_department_id',
        'fire_level_id',
        'oc_number',
        'object_name',
        'location',
        'note',
    ];

    public function fire_department()
    {
        return $this->belongsTo(FireDepartment::class, 'fire_department_id');
    }

    public function fire_level()
    {
        return $this->belongsTo(FireLevel::class, 'fire_level_id');
    }

    public function scopeLocation($q, $address)
    {
        return $q->where('location', 'like', "%$address%");
    }
}
