<?php
namespace App\Dictionary;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Dictionary\TripResult
 *
 * @property int $id
 * @property string $name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Dictionary\TripResult whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Dictionary\TripResult whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Dictionary\TripResult whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Dictionary\TripResult whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TripResult extends Model
{
    protected $table = 'dict_trip_result';
    protected $guarded = ['id'];
}