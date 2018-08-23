<?php

namespace App\Models;

use App\Dictionary\CityArea;
use App\User;
use Illuminate\Database\Eloquent\Model;

class EmergencySituation extends Model
{
    /**
     * @var string
     */
    public $table = 'emergency_situations';

    /**
     * @var array
     */
    public $guarded = ['id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function cityArea()
    {
        return $this->hasOne(CityArea::class, 'id', 'city_area_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
