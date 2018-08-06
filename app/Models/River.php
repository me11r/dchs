<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\River
 *
 * @property int $id
 * @property string $name
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\GaugingStation[] $gaugingStations
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\River whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\River whereName($value)
 * @mixin \Eloquent
 */
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
