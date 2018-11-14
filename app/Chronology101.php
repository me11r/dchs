<?php

namespace App;

use App\Models\FireDepartmentResult;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Chronology101
 *
 * @property int $id
 * @property int|null $ticket101_id
 * @property int|null $event_info_arrived_id
 * @property int|null $event_info_id
 * @property int $fire_department_result_id
 * @property int|null $quantity
 * @property int|null $working_time
 * @property string|null $information
 * @property string|null $time
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\EventInfo|null $event_info
 * @property-read \App\EventInfoArrived|null $event_info_arrived
 * @property-read \App\Models\FireDepartmentResult $fire_department_result
 * @property-read \App\Ticket101|null $ticket101
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Chronology101 whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Chronology101 whereEventInfoArrivedId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Chronology101 whereEventInfoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Chronology101 whereFireDepartmentResultId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Chronology101 whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Chronology101 whereInformation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Chronology101 whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Chronology101 whereTicket101Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Chronology101 whereTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Chronology101 whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Chronology101 whereWorkingTime($value)
 * @mixin \Eloquent
 */
class Chronology101 extends Model
{
    protected $fillable = [
        'ticket101_id',
        'event_info_id',
        'time',
        'information',
        'fire_department_result_id',

        'working_time',
        'event_info_arrived_id',
        'quantity',
    ];

    public function ticket101()
    {
        return $this->belongsTo(Ticket101::class, 'ticket101_id');
    }

    public function event_info()
    {
        return $this->belongsTo(EventInfo::class, 'event_info_id');
    }

    public function event_info_arrived()
    {
        return $this->belongsTo(EventInfoArrived::class, 'event_info_arrived_id');
    }

    public function fire_department_result()
    {
        return $this->belongsTo(FireDepartmentResult::class, 'fire_department_result_id');
    }
}
