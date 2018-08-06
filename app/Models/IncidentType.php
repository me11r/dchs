<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\IncidentType
 *
 * @property int $id
 * @property string $name
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IncidentType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IncidentType whereName($value)
 * @mixin \Eloquent
 */
class IncidentType extends Model
{
    public $table = 'incident_types';

    public $timestamps = false;

    public $fillable = ['name'];
}
