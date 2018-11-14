<?php

namespace App;

use App\Models\Staff;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Ticket101Other
 *
 * @property int $id
 * @property int $ticket_101_id
 * @property int $fire_department_id
 * @property int|null $department
 * @property string|null $time_begin
 * @property int $ride_type_id
 * @property string|null $object_name
 * @property int $staff_id
 * @property string|null $time_end
 * @property string|null $note
 * @property string|null $direction
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\FireDepartment $fire_department
 * @property-read \App\RideType $ride_type
 * @property-read \App\Models\Staff $staff
 * @property-read \App\Ticket101 $ticket101
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101Other whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101Other whereDepartment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101Other whereDirection($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101Other whereFireDepartmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101Other whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101Other whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101Other whereObjectName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101Other whereRideTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101Other whereStaffId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101Other whereTicket101Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101Other whereTimeBegin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101Other whereTimeEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101Other whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Ticket101Other extends Model
{
    protected $fillable = [
        'ride_type_id',
        'ticket_101_id',
        'fire_department_id',
        'department',
        'time_begin',
        'time_end',
        'object_name',
        'staff_id',
        'note',
        'direction',
    ];

    public function ride_type()
    {
        return $this->belongsTo(RideType::class,'ride_type_id');
    }

    public function ticket101()
    {
        return $this->belongsTo(Ticket101::class,'ticket_101_id');
    }

    public function fire_department()
    {
        return $this->belongsTo(FireDepartment::class,'fire_department_id');
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class,'staff_id');
    }
}
