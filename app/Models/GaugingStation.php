<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GaugingStation extends Model
{
    public $table = 'gauging_stations';

    public $timestamps = false;

    public $fillable = ['river_id', 'name'];

    public function mudflowProtection()
    {
        return $this->belongsTo(MudflowProtection::class, 'id', 'gauging_station_id');
    }
}
