<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DistrictManager extends Model
{
    use SoftDeletes;

    protected $with = [
        'phones'
    ];

    protected $fillable = [
        'name',
        'rank',
        'nickname',
        'position',
    ];

    public function phones()
    {
        return $this->hasMany(DistrictManagerPhone::class, 'district_manager_id');
    }
}
