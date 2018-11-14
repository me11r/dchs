<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\EventInfo
 *
 * @property int $id
 * @property string $name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EventInfo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EventInfo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EventInfo whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EventInfo whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class EventInfo extends Model
{
    protected $fillable = [
        'name',
    ];
}
