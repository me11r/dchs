<?php
/**
 * Created by PhpStorm.
 * User: gorbunov
 * Date: 19.04.2017
 * Time: 12:19
 */

namespace App\User;


use Illuminate\Database\Eloquent\Model;

/**
 * App\User\Right
 *
 * @property int $id
 * @property int $user_id
 * @property int $right_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\User\Right whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User\Right whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User\Right whereRightId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User\Right whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User\Right whereUserId($value)
 * @mixin \Eloquent
 */
class Right extends Model
{
    protected $table = 'user_rights';

}