<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\GaugingStation
 *
 * @property int $id
 * @property string $name
 * @property int $river_id
 * @property-read \App\Models\MudflowProtection $mudflowProtection
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GaugingStation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GaugingStation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GaugingStation whereRiverId($value)
 * @mixin \Eloquent
 */
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
