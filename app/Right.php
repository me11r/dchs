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
    public const CAN_LOGIN = 1;

    public const CAN_SEE_REQUEST = 2;
    public const CAN_ASSIGN_REQUEST = 3;
    public const CAN_CREATE_REQUEST = 4;
    public const CAN_EDIT_REQUEST = 5;
    public const CAN_DELETE_REQUEST = 6;

    public const CAN_MANAGE_USERS = 7;
    public const CAN_SEE_TRIP_PLAN = 8;
    public const CAN_EDIT_DICTIONARIES = 9;
    public const CAN_EDIT_HYDRANT_LOCATIONS = 10;

    public const CAN_SEE_DAILY_REPORT = 11;

    public const CAN_ACCESS_FORMATION_REPORT_101 = 12;
    public const CAN_ACCESS_FORMATION_REPORT_ROSO = 13;
    public const CAN_ACCESS_FORMATION_REPORT_CMK = 14;
    public const CAN_ACCESS_FORMATION_REPORT_MUDFLOW_PROTECTION = 15;
    public const CAN_ACCESS_FORMATION_REPORT_AIR_RESCUE = 16;
    public const CAN_ACCESS_FORMATION_REPORT_ORTSERT = 17;
    public const CAN_ACCESS_FORMATION_DCHS_ALMATY = 18;

    protected $table = 'rights';
    protected $fillable = ['title', 'right_group_id'];

    public function group()
    {
        return $this->belongsTo(\App\Rights\Group::class, 'right_group_id', 'id');
    }
}
