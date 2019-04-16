<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\AircraftType
 *
 * @property int $id
 * @property string $name
 * @property string|null $deleted_at
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Aircraft[] $aircrafts
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AircraftType name($search)
 * @method static \Illuminate\Database\Query\Builder|\App\AircraftType onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AircraftType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AircraftType whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AircraftType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AircraftType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AircraftType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AircraftType withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\AircraftType withoutTrashed()
 * @mixin \Eloquent
 */
class AircraftType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name'
    ];

    public $attributeNames = [
        'name' => 'Наименование',
    ];

    public function scopeName($q, $search)
    {
        return $q->where('name', $search);
    }

    public function aircrafts()
    {
        return $this->hasMany(Aircraft::class, 'aircraft_type_id');
    }
}
