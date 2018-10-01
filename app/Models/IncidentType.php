<?php

namespace App\Models;

use App\IncidentTypeCategory;
use App\Ticket101;
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

    public $fillable = [
        'name',
        'category_id'
    ];

    public function cards101()
    {
        return $this->hasMany(Ticket101::class, 'pre_information_id');
    }

    public function category()
    {
        return $this->belongsTo(IncidentTypeCategory::class, 'category_id');
    }
}
