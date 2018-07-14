<?php
/**
 * Created by PhpStorm.
 * User: gorbunov
 * Date: 14.07.2018
 * Time: 13:43
 */

namespace App\Dictionary;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Dictionary\FireObject
 *
 * @property int $id
 * @property string $name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Dictionary\FireObject whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Dictionary\FireObject whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Dictionary\FireObject whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Dictionary\FireObject whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FireObject extends Model
{
    protected $table = 'dict_fire_object';
    protected $guarded = ['id'];
}