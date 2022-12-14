<?php

namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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
 * @property string|null $name
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Right whereName($value)
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
    public const CAN_EDIT_DICTIONARIES = 8;
    public const CAN_EDIT_HYDRANT_LOCATIONS = 10;

    public const CAN_SEE_DAILY_REPORT = 11;

    public const CAN_ACCESS_FORMATION_REPORT_101 = 12;
    public const CAN_ACCESS_FORMATION_REPORT_ROSO = 13;
    public const CAN_ACCESS_FORMATION_REPORT_CMK = 14;
    public const CAN_ACCESS_FORMATION_REPORT_MUDFLOW_PROTECTION = 15;
    public const CAN_ACCESS_FORMATION_REPORT_AIR_RESCUE = 16;
    public const CAN_ACCESS_FORMATION_REPORT_ORTSERT = 17;
    public const CAN_ACCESS_FORMATION_DCHS_ALMATY = 18;
    public const CAN_ACCESS_FORMATION_EMERGENCY_ALMATY = 19;

    public const CAN_APPROVE_FORMATION_REPORT_101 = 20;

    public const CAN_ACCESS_INFO = 21;
    public const CAN_ACCESS_OPER_INFO = 22;
    public const CAN_ACCESS_PERORT_PERSONS = 23;
    public const CAN_ACCESS_PERORT_TECH = 24;
    public const CAN_ACCESS_MANUAL_INPUT_CHRONO = 25;
    public const CAN_ACCESS_HYDRANT = 26;
    public const CAN_ACCESS_TECH = 27;
    public const CAN_ACCESS_PERSONS = 28;
    public const CAN_ACCESS_FIRE_DEPTS = 29;
    public const CAN_ACCESS_FIRE_LAKES = 30;
    public const CAN_READ_ONLY_FORMATION = 31;

    public const CAN_RECEIVE_SERVICE_PLAN = 32;

    public const CAN_VIEW_112_CARD = 33;
    public const CAN_ASSIGN_112_CARD = 34;
    public const CAN_CREATE_112_CARD = 35;
    public const CAN_EDIT_112_CARD = 36;
    public const CAN_DELETE_112_CARD = 37;

    public const CAN_SEE_ALL_EMERGENCY_SITUATIONS = 38;

    public const DEFAULT_FIRE_DEPARTMENT_RIGHTS = [
        self::CAN_LOGIN,
        self::CAN_SEE_REQUEST,
        self::CAN_EDIT_DICTIONARIES,
        self::CAN_SEE_DAILY_REPORT
    ];

    public const DEFAULT_RIGHTS_BY_ROLE_ID = [
        Role::FIRE_DEPARTMENT_ROLE_ID => self::DEFAULT_FIRE_DEPARTMENT_RIGHTS,
    ];

    public const ONLY_LOGIN_RIGHT = [
        self::CAN_LOGIN
    ];

    protected $table = 'rights';
    protected $fillable = [
        'title',
        'right_group_id',
        'name',
    ];

    public function group()
    {
        return $this->belongsTo(\App\Rights\Group::class, 'right_group_id', 'id');
    }

    public static function userFireDepartmentMatch($fireDepartmentId)
    {
        $user = Auth::user();
        if ($user->isAdmin() || $fireDepartmentId === null || $user->fire_department_id === null) {
            return true;
        }

        return (int)$fireDepartmentId === (int)$user->fire_department_id;
    }
}
