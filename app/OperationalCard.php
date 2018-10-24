<?php

namespace App;

use App\Dictionary\FireLevel;
use Illuminate\Database\Eloquent\Model;

/**
 * App\OperationalCard
 *
 * @property int $id
 * @property int $fire_department_id
 * @property int $fire_level_id
 * @property string $oc_number
 * @property string|null $object_name
 * @property string $location
 * @property string|null $note
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\FireDepartment $fire_department
 * @property-read \App\Dictionary\FireLevel $fire_level
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Ticket101[] $tickets101
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OperationalCard location($address)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OperationalCard whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OperationalCard whereFireDepartmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OperationalCard whereFireLevelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OperationalCard whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OperationalCard whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OperationalCard whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OperationalCard whereObjectName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OperationalCard whereOcNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OperationalCard whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OperationalCard extends Model
{
    protected $fillable = [
        'fire_department_id',
        'fire_level_id',
        'oc_number',
        'object_name',
        'location',
        'note',
    ];

    public function fire_department()
    {
        return $this->belongsTo(FireDepartment::class, 'fire_department_id');
    }

    public function fire_level()
    {
        return $this->belongsTo(FireLevel::class, 'fire_level_id');
    }

    public function tickets101()
    {
        return $this->hasMany(Ticket101::class, 'operational_card_id');
    }

    public function scopeLocation($q, $address)
    {
        return $q->where('location', 'like', "%$address%");
    }
}
