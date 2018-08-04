<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class River extends Model
{
    public $table = 'rivers';

    public $timestamps = false;

    public $fillable = ['name'];

    public function gaugingStations()
    {
        return $this->hasMany(GaugingStation::class, 'river_id', 'id');
    }
}
