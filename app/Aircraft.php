<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Aircraft
 *
 * @property int $id
 * @property string $name
 * @property string|null $number
 * @property string|null $type
 * @property int $aircraft_type_id
 * @property string|null $deleted_at
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\AircraftType $aircraft_type
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Aircraft name($search)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Aircraft number($search)
 * @method static \Illuminate\Database\Query\Builder|\App\Aircraft onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Aircraft type($search)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Aircraft whereAircraftTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Aircraft whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Aircraft whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Aircraft whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Aircraft whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Aircraft whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Aircraft whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Aircraft whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Aircraft withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Aircraft withoutTrashed()
 * @mixin \Eloquent
 */
class Aircraft extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'number',
        'type', // airplane|helicopter
        'aircraft_type_id', // airplane|helicopter
    ];

    public function scopeName($q, $search)
    {
        return $q->where('name', $search);
    }

    public function scopeType($q, $search)
    {
        return $q->where('type', $search);
    }

    public function scopeNumber($q, $search)
    {
        return $q->where('number', $search);
    }

    public function aircraft_type()
    {
        return $this->belongsTo(AircraftType::class, 'aircraft_type_id');
    }

    public function getFullNameAttribute()
    {
        return "{$this->name} {$this->number}";
    }
}
