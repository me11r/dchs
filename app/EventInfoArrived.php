<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\EventInfoArrived
 *
 * @property int $id
 * @property string $name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EventInfoArrived whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EventInfoArrived whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EventInfoArrived whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EventInfoArrived whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class EventInfoArrived extends Model
{
    protected $fillable = [
        'name',
    ];
}
