<?php
namespace App;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Right
 *
 * @property int $id
 * @property int $right_group_id
 * @property string $title
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Rights\Group $group
 * @method static \Illuminate\Database\Query\Builder|\App\Right whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Right whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Right whereRightGroupId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Right whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Right whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Right extends Model
{
    const CAN_LOGIN = 1;

    const CAN_SEE_REQUEST = 2;
    const CAN_ASSIGN_REQUEST = 3;
    const CAN_CREATE_REQUEST = 4;
    const CAN_EDIT_REQUEST = 5;
    const CAN_DELETE_REQUEST = 6;

    const CAN_MANAGE_USERS = 7;
    const CAN_SEE_TRIP_PLAN = 8;
    const CAN_EDIT_DICTIONARIES = 9;
    const CAN_EDIT_HYDRANT_LOCATIONS = 10;

    protected $table = 'rights';
    protected $fillable = ['title', 'right_group_id'];

    public function group()
    {
        return $this->belongsTo(\App\Rights\Group::class, 'right_group_id', 'id');
    }
}