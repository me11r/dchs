<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DistrictManagerPhone extends Model
{
    protected $fillable = [
        'phone',
        'district_manager_id',
    ];

    public function manager()
    {
        return $this->belongsTo(DistrictManager::class, 'district_manager_id');
    }
}
